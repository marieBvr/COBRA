<?php
include '../functions/html_functions.php';
include '../functions/php_functions.php';
include '../functions/mongo_functions.php';
require('../session/control-session.php');

//////////////////////////////////////////////////////
// This document has the following structure:
// 1/ collect info from mongo database
// 2/ Write to html page js function 
// 3/ Write description information of the entitie
/////////////////////////////////////////////////////




new_cobra_header("../..");
new_cobra_body(isset($_SESSION['login'])? $_SESSION['login']:False,"Network","section_network","../..");
$db=mongoConnector();
$historyCollection = new Mongocollection($db, "history");
date_default_timezone_set('Europe/Paris');

////////////////////////////////////////////////////////////////////////////////////
// 1/ collect info from mongo database
////////////////////////////////////////////////////////////////////////////////////
if ((isset($_GET['search']) && $_GET['search']!='' && $_GET['search']!='NA') && 
    (isset($_GET['species']) && $_GET['species']!='' && $_GET['species']!='NA') ){

	// Save search to user history
    array_push($_SESSION['historic'],$_GET['search']);
    // Control input
	$search=control_post(htmlspecialchars($_GET['search']));
    $species=control_post(htmlspecialchars($_GET['species']));

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
        array('$match' => array('type'=>'full_table','species'=>$species)),  
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
    $method_list=array();
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
            // Gene symbol
            if (isset($result['mapping_file']['Symbol'])&& $result['mapping_file']['Symbol']!='' && $result['mapping_file']['Symbol']!='NA'){
                $symbol_list=explode(",", $result['mapping_file']['Symbol']);
                foreach ($symbol_list as $symbol) {
                    if (in_array($symbol,$gene_symbol)==FALSE && $symbol!='NA'){
                        array_push($gene_symbol,$symbol);
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
            // Description
            if (isset($result['mapping_file']['Description'])&& $result['mapping_file']['Description']!='' && $result['mapping_file']['Description']!='NA'){
                if (in_array($result['mapping_file']['Description'],$descriptions)==FALSE){
                    array_push($descriptions,$result['mapping_file']['Description']);
                }
            } 
            // Method
            if (isset($result['mapping_file']['detection_method'])&& $result['mapping_file']['detection_method']!='' && $result['mapping_file']['detection_method']!='NA'){
                if (in_array($result['mapping_file']['detection_method'],$method_list)==FALSE){
                    array_push($method_list,$result['mapping_file']['method']);
                }
            }

        } // end foreach
        ////////////////////////////////////////////////////////////////////////////////////
        // 3/ Write description information of the entitie
        ////////////////////////////////////////////////////////////////////////////////////
        echo '
        <div id="section_description" width="50%">
            <div id="section_description"><br />
                <h1>';
                        
                    for ($i = 0; $i < count($gene_symbol); $i++) {
                        if ($i==count($gene_symbol)-1){
                            $gene_name = $gene_symbol[$i];
                            echo $gene_symbol[$i];
                        }
                        else{
                            echo $gene_symbol[$i].', ';
                            $gene_name = $gene_symbol[$i].', ';
                        }
                    }
                    if (count($gene_symbol)==0){
                        for ($i = 0; $i < count($gene_alias); $i++) {
                            if ($gene_alias[$i]!='NA'){
                                if ($i==count($gene_alias)-1){
                                    echo $gene_alias[$i];
                                    $gene_name = $gene_alias[$i];
                                }
                                else{
                                    echo $gene_alias[$i].', ';
                                    $gene_name = $gene_alias[$i].', ';
                                }
                            }
                        }
                    }
                    echo '&nbsp;&nbsp;
                    <a target="_blank" href="../result_search_5.php?organism='.str_replace(" ", "+", $species).'&search='.$gene_id[0].'">
                        <i class="fas fa-eye"></i>
                    </a>
                </h1>';
                if (count($descriptions)>0){
                    echo'<div id="description"> <b>Description</b> : ';
                    for ($i = 0; $i < count($descriptions); $i++) {
                        
                        if ($i==count($descriptions)-1){
                            echo $descriptions[$i];
                        }

                        else{
                            echo $descriptions[$i].', ';
                        }
                    }
                    echo '</div>';
                }
                echo '<div id="specie"><b>Species</b> : <i>'.$species.'</i></div>';
                if (count($uniprot_id)>0){
                    if ($uniprot_id[0]!='NA'){
                        echo'<div id="protein_aliases"> <B>Protein ids</B> : ';
                        for ($i = 0; $i < count($uniprot_id); $i++) {
                            if ($i==count($uniprot_id)-1){
                                echo'<a target="_BLANK" href="http://www.uniprot.org/uniprot/'.$uniprot_id[$i].'" title="UniprotKB Swissprot and Trembl Sequences">'.$uniprot_id[$i].'</a>';
                            }
                            else{
                                echo'<a target="_BLANK" href="http://www.uniprot.org/uniprot/'.$uniprot_id[$i].'" title="UniprotKB Swissprot and Trembl Sequences">'.$uniprot_id[$i].'</a>, ';
                            }
                        }
                        echo '</div>';
                    }
                }
                echo'<div id="aliases"><b>Aliases</b> : '; 
                if (isset($gene_id[0])){echo $gene_id[0];}else{echo $gene_id_bis[0];};
                echo '</div>';
            echo '</div>
        </div>';
        ////////////////////////////////////////////////////////////////////////////////////
        // 2/ Write to html page js function
        ////////////////////////////////////////////////////////////////////////////////////
        echo '<div id="network-data-init" gene-name="'.$gene_name.'" data-mode="PV" data-protein="'.htmlspecialchars( json_encode($uniprot_id), ENT_QUOTES ).'" data-transcript="'.htmlspecialchars( json_encode($transcript_id), ENT_QUOTES ).'"  data-species="'.$species.'"  data-id="'.$gene_id[0].'" data-gene="'.htmlspecialchars( json_encode($gene_id), ENT_QUOTES ).'" method="'.htmlspecialchars( json_encode($method_list), ENT_QUOTES ).'" display:none></div>';

		echo '',
		'<script type="text/javascript">',
	       'load_network("network-data-init", "../");',
		'</script>';

        // in  html_functions.php
        display_cy_panel("network-data-init", "../");

    } // end if cursor
}else{
    echo'
    <div id="summary">
        <h2>No Results found for \''.$search.'\'</h2>
    </div>';	
}


new_cobra_footer();

?>