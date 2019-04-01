<?php
include '../functions/html_functions.php';
include '../functions/php_functions.php';
include '../functions/mongo_functions.php';
require('../session/control-session.php');




new_cobra_header("../..");
new_cobra_body(isset($_SESSION['login'])? $_SESSION['login']:False,"Network","section_network","../..");
$db=mongoConnector();
$historyCollection = new Mongocollection($db, "history");
date_default_timezone_set('Europe/Paris');

if ((isset($_GET['search']) && $_GET['search']!='' && $_GET['search']!='NA')){

	// Save search to user history
    array_push($_SESSION['historic'],$_GET['search']);
    // Control input
	$search=control_post(htmlspecialchars($_GET['search']));

    ////////////////////////////////////
    //ASSIGN ALL COLLECTIONS and GRIDS//
    ////////////////////////////////////
    $grid = $db->getGridFS();
	$samplesCollection = new MongoCollection($db, "samples");
	$full_mappingsCollection = new Mongocollection($db, "full_mappings");
	$mappingsCollection = new Mongocollection($db, "mappings");
	$interactionsCollection = new Mongocollection($db, "interactions");
    $pv_interactionsCollection = new Mongocollection($db, "pv_interactions");
    $pp_interactionsCollection = new Mongocollection($db, "pp_interactions");


    ///////////////////////////////////////////////////////
    //SEARCH ENTRY IN FULL TABLE MAPPING WITH SAME ID//
    ///////////////////////////////////////////////////////  
    $cursor=$full_mappingsCollection->aggregate(array(
        array('$match' => array('type'=>'full_table')),  
        array('$project' => array('mapping_file'=>1,'_id'=>0)),
        array('$unwind'=>'$mapping_file'),
        array('$match' => array('$or'=> array(
            array('mapping_file.Uniprot ID'=>new MongoRegex("/^$search/xi")),
            array('mapping_file.Gene ID'=>new MongoRegex("/^$search/xi")),
            array('mapping_file.Gene ID'=>new MongoRegex("/$search$/xi")),
            // array('mapping_file.Gene ID'=> $search),
            array('mapping_file.Symbol'=>new MongoRegex("/^$search/xi")),
            array('mapping_file.Gene ID 2'=>new MongoRegex("/^$search/xi"))))),
        array('$project' => array("mapping_file"=>1,'_id'=>0))
    ),
        // array("allowDiskUse" => True),
        array('cursor' => array("batchSize" => 10)));
    
    
    ///////////////////////////////////
    //CREATE ALL ARRAYS and VARIABLES//
    ///////////////////////////////////
    
    $go_grid_plaza_id_list=array();
    $go_grid_id_list=array();
    $gene_alias=array();
    $gene_id=array();
    $gene_id_bis=array();
    $transcript_id=array();
    $gene_symbol=array();
    $descriptions=array();
    $uniprot_id=array();
    $protein_id=array();
    $est_id=array();
    $go_list=array();
    $percent_array=array();
    $score=0.0;

    //////////////////////////////////////////
    //PARSE RESULT AND FILL DEDICATED ARRAYS//
    //////////////////////////////////////////   
    if (count($cursor['cursor']['firstBatch'])>0){
        foreach ($cursor['cursor']['firstBatch'] as $result) {
            
            // presence of uniprot ID
            if (isset($result['mapping_file']['Uniprot ID']) && $result['mapping_file']['Uniprot ID']!='' && $result['mapping_file']['Uniprot ID']!='NA'){
                $uniprot_ids = preg_split("/[\s,]+/",$result['mapping_file']['Uniprot ID']);
                //print_r($uniprot_ids);
                foreach ($uniprot_ids as $id) {
                    if (in_array($id,$uniprot_id)==FALSE){
                        array_push($uniprot_id,$id);
                    }
                }
            }
            // presence of gene ID
         	if (isset($result['mapping_file']['Gene ID'])&& $result['mapping_file']['Gene ID']!='' && $result['mapping_file']['Gene ID']!='NA'){
                if (in_array($result['mapping_file']['Gene ID'],$gene_id)==FALSE){
                    array_push($gene_id,$result['mapping_file']['Gene ID']);
                }
            }
            // Save search historic
            if (isset($result['mapping_file']['Global_Score'])&& $result['mapping_file']['Global_Score']!='' && $result['mapping_file']['Global_Score']!='NA'){
                $score=(int)$result['mapping_file']['Global_Score'];
                $today = date("F j, Y, g:i a");
                $document = array("firstname" => $_SESSION['firstname'],
                      "lastname" => $_SESSION['lastname'],
                      "search id" => $_GET['search'],
                      "type" => "search",
                      "score" =>$score,
                      "date" => $today
                );
                $historyCollection->insert($document);
            }
            // Add second gene ID to list
            if (isset($result['mapping_file']['Gene ID 2'])&& $result['mapping_file']['Gene ID 2']!=''&& $result['mapping_file']['Gene ID 2']!="NA"){
                
                $gene_ids_bis = preg_split("/[\s,]+/",$result['mapping_file']['Gene ID 2']);
                //print_r($uniprot_ids);
                foreach ($gene_ids_bis as $id) {
                    if (in_array($id,$gene_id_bis)==FALSE){
                        array_push($gene_id_bis,$id);
                    }
                }
            }
            // Retrieve gene aliases
            if (isset($result['mapping_file']['Alias'])&& $result['mapping_file']['Alias']!='' && $result['mapping_file']['Alias']!='NA'){
                if (in_array($result['mapping_file']['Alias'],$gene_alias)==FALSE){
                    array_push($gene_alias,$result['mapping_file']['Alias']);
                }
            }
            // Retrieve gene name
            if (isset($result['mapping_file']['Gene Name'])&& $result['mapping_file']['Gene Name']!='' && $result['mapping_file']['Gene Name']!='NA'){
                if (in_array($result['mapping_file']['Gene Name'],$gene_alias)==FALSE){
                    array_push($gene_alias,$result['mapping_file']['Gene Name']);
                }
            }
            // Retrieve transcript ID
            if (isset($result['mapping_file']['Transcript ID'])&& $result['mapping_file']['Transcript ID']!='' && $result['mapping_file']['Transcript ID']!='NA'){
                
                $transcript_ids = preg_split("/[\s,]+/",$result['mapping_file']['Transcript ID']);
                foreach ($transcript_ids as $id) {
                    if (in_array($id,$transcript_id)==FALSE){
                        array_push($transcript_id,$id);
                    }
                }
            } 
        } // end foreach
        // load_and_display_interactions_with_ajax($gene_id,$uniprot_id,$transcript_id,null, "../");
        // echo '<i class="icon ion-md-analytics" large></i>';

        $cursor_js=$pv_interactionsCollection->aggregate(array(
            array('$match'=>array('src'=>"Uniprot ID")),
            array('$project' => array('mapping_file'=>1,'_id'=>0)),
            array('$unwind'=>'$mapping_file'),
            array('$match' => array('mapping_file.Uniprot ID'=>array('$in'=>$uniprot_id))),
            array('$project' => array('mapping_file.database_identifier'=>1,'mapping_file.protein_alias_2'=>1,'mapping_file.Virus Uniprot ID'=>1,'mapping_file.pmid'=>1,'mapping_file.author_name'=>1,'mapping_file.detection_method'=>1,'mapping_file.virus'=>1,'_id'=>0))
        ), array('cursor' => ["batchSize" => 100]));

        if (count($cursor_js['cursor']['firstBatch'])>0){
            foreach ($cursor_js['cursor']['firstBatch'] as $value) {
                foreach ($value as $data) {
                    echo '<p>'.$data['database_identifier'].'</p>';
                }
            }
        }else{
            echo '<p>Not result found.</p>';
        }


		echo '',
		'<script type="text/javascript">',
	       'load_network("'.$search.'", "'.htmlspecialchars( json_encode($cursor_js['cursor']['firstBatch']), ENT_QUOTES).'");',
		'</script>';

    } // end if cursor
}else{
    echo'
    <div id="summary">
        <h2>No Results found for \''.$search.'\'</h2>
    </div>';	
}

echo '
	<div class="svg-container">
		<svg width="90%" height="75%" shape-rendering="geometricPrecision"></svg>
	</div>';

new_cobra_footer();

?>