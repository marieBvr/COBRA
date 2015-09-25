<?php
//session_start();
include './functions/html_functions.php';
include './functions/php_functions.php';
include './functions/mongo_functions.php';
include '../wiki/vendor/autoload.php';
require('./session/control-session.php');





new_cobra_header();

new_cobra_body($_SESSION['login'],"Result Summary","section_result_summary");

if (((isset($_GET['organism'])) && ($_GET['organism']!='')) && ((isset($_GET['search'])) && ($_GET['search']!=''))){


	$organism=control_post(htmlspecialchars($_GET['organism']));
	$search=control_post(htmlspecialchars($_GET['search']));
	

	$db=mongoConnector();

	$grid = $db->getGridFS();
	//Selection des collections
	$samplesCollection = new MongoCollection($db, "samples");
	$speciesCollection = new Mongocollection($db, "species");
	$mappingsCollection = new Mongocollection($db, "mappings");
	$measurementsCollection = new Mongocollection($db, "measurements");
	$virusesCollection = new Mongocollection($db, "viruses");
	$interactionsCollection = new Mongocollection($db, "interactions");
	$orthologsCollection = new Mongocollection($db, "orthologs");
    $GOCollection = new Mongocollection($db, "gene_ontology");

	
	//get_all_results_from_samples($measurementsCollection,$samplesCollection,$search);

    //if more than one results (often the case when search by gene symbol or keywords

    //put the search box again...
    make_species_list(find_species_list($speciesCollection));
    
    
   
    $go_id_list=array();
    $go_grid_plaza_id_list=array();
    $go_grid_id_list=array();
    $gene_alias=array();
    $gene_id=array();
    $gene_symbol=array();
    $descriptions=array();
    $proteins_id=array();
    $plaza_ids=array();
    $est_id=array();
    $go_duo_list=array();
    //echo '<hr>';
    
    //$timestart=microtime(true);
    //get_everything using full table mapping
    $cursor=$mappingsCollection->aggregate(array(
        array('$match' => array('type'=>'full_table')),  
        array('$project' => array('mapping_file'=>1,'species'=>1,'_id'=>0)),
        array('$unwind'=>'$mapping_file'),
        array('$match' => array('$or'=> array(array('mapping_file.Plaza ID'=>$search),array('mapping_file.Uniprot ID'=>$search),array('mapping_file.Protein ID'=>$search),array('mapping_file.Protein ID 2'=>$search),array('mapping_file.Alias'=>$search),array('mapping_file.Probe ID'=>$search),array('mapping_file.Gene ID'=>$search),array('mapping_file.Gene ID 2'=>$search),array('mapping_file.Symbol'=>$search)))),
        array('$project' => array("mapping_file"=>1,'species'=>1,'_id'=>0))));

    //var_dump($cursor);

    
    if (count($cursor['result'])>0){
        foreach ($cursor['result'] as $result) {
            //echo $result['mapping_file']['Gene ID 2'];
            //echo $result['mapping_file']['Gene ontology ID'];
            $go_id_evidence = explode("_", $result['mapping_file']['Gene ontology ID']);
            foreach ($go_id_evidence as $duo) {
                if (!in_array($duo, $go_duo_list)){
                    $tmp_array=array();
                    array_push($go_duo_list, $duo);
                    $duo_id=explode("-", $duo);
                    $tmp_array['evidence']=$duo_id[1];
                    $tmp_array['GO_ID']=$duo_id[0];
                    array_push($go_id_list,$tmp_array);
                }

            }
            if (in_array($result['mapping_file']['Uniprot ID'],$proteins_id)==FALSE){
                array_push($proteins_id,$result['mapping_file']['Uniprot ID']);
            }
//            if (in_array($result['mapping_file']['Protein ID'],$proteins_id)==FALSE){
//                array_push($proteins_id,$result['mapping_file']['Uniprot ID']);
//            }
            if (in_array($result['mapping_file']['Description'],$descriptions)==FALSE){

                array_push($descriptions,$result['mapping_file']['Description']);
            }
            if (in_array($result['mapping_file']['Gene ID'],$gene_id)==FALSE){

                array_push($gene_id,$result['mapping_file']['Gene ID']);
            }
            $symbol_list=explode(",", $result['mapping_file']['Symbol']);
            foreach ($symbol_list as $symbol) {
                //echo 'symbol : '.$symbol;
                if (in_array($symbol,$gene_symbol)==FALSE){
                    array_push($gene_symbol,$symbol);
                }
                

                
            }
            if (in_array($result['mapping_file']['Gene ID 2'],$gene_alias)==FALSE && $result['mapping_file']['Gene ID 2']!="NA"){

                array_push($gene_alias,$result['mapping_file']['Gene ID 2']);
            }
            if (in_array($result['mapping_file']['Alias'],$gene_alias)==FALSE && $result['mapping_file']['Alias']!="NA"){

                array_push($gene_alias,$result['mapping_file']['Alias']);
            }
            if (in_array($result['mapping_file']['Probe ID'],$est_id)==FALSE){

                array_push($est_id,$result['mapping_file']['Probe ID']);
            }
            array_push($plaza_ids,$result['mapping_file']['Plaza ID']);
            $plaza_id=$result['mapping_file']['Plaza ID'];
            $species=$result['species'];

        }
  //    $timeend=microtime(true);
  //    $time=$timeend-$timestart;
  //    //Afficher le temps d'éxecution
  //    $page_load_time = number_format($time, 3);
  //    echo "Debut du script: ".date("H:i:s", $timestart);
  //    echo "<br>Fin du script: ".date("H:i:s", $timeend);
  //    echo "<br>Script for plaza id execute en " . $page_load_time . " sec";
  //
  //    echo '<hr>';
  //    $timestart=microtime(true);
        $total_go_biological_process=array();
        $total_go_cellular_component=array();
        $total_go_molecular_function=array();
        if (count($go_id_list)!=0){

            foreach ($go_id_list as $go_info){

                //$timestart1=microtime(true);
                $go_term=$GOCollection->find(array('GO_collections.id'=>$go_info['GO_ID']),array('GO_collections.$'=>1,'_id'=>0));
                foreach ($go_term as $term){
                    foreach ($term as $go){
                        foreach ($go as $value){
                           if ($value['namespace']=='molecular_function'){


                                //$go_info['GO_ID']=$value['id'];
                                $go_info['description']=$value['name'];
                                $go_info['namespace']=$value['namespace'];
                                //echo $value['name'];
                                //$go_info['evidence']=$go_id_list[$i]['evidence'];
                                array_push($total_go_molecular_function, $go_info);
                                array_push($already_added_go_term,$go_info);
                            }
                            if ($value['namespace']=='biological_process') {
                                $go_info['description']=$value['name'];   
                                $go_info['namespace']=$value['namespace'];
                                array_push($total_go_biological_process, $go_info);
                                array_push($already_added_go_term,$go_info);

                            }
                            if ($value['namespace']=='cellular_component'){
                                $go_info['description']=$value['name']; 
                                $go_info['namespace']=$value['namespace'];
                                array_push($total_go_cellular_component, $go_info);
                                array_push($already_added_go_term,$go_info);
                            }   
                           //echo $go['namespace']; 
                        }

                    }

                }

            }
        }
echo   '<div id="summary">   
            <div id="protein-details">
            

                <div id="section_description">'.$gene_id[0].'
                    <div id="organism" class="right"><h4>'.$species.'</h4></div>';
                echo '<h1>';
                for ($i = 0; $i < count($gene_symbol); $i++) {
                    if ($i==count($gene_symbol)-1){
                        echo $gene_symbol[$i];
                    }
                    else{
                        echo $gene_symbol[$i].', ';
                    }
                    
                }
                if (count($gene_symbol)==0){
                    echo $gene_alias[0];
                }
                echo '</h1> ';
                if (count($descriptions)>0){
                    echo'<div id="aliases"> Description : ';
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
                
                if (count($gene_alias)>0){
                    echo'<div id="aliases"> Alias : ';
                    for ($i = 0; $i < count($gene_alias); $i++) {
                        if ($i==count($gene_alias)-1){
                            echo $gene_alias[$i];
                        }
                        else{
                            echo $gene_alias[$i].', ';
                        }
                    }

                    echo '</div>';
                }
                if (count($proteins_id)>0){
                    echo'<div id="protein aliases"> Protein ids : ';
                    for ($i = 0; $i < count($proteins_id); $i++) {
                        if ($i==count($proteins_id)-1){
                            echo'<a target="_BLANK" href="http://www.uniprot.org/uniprot/'.$proteins_id[$i].'" title="UniprotKB Swissprot and Trembl Sequences">'.$proteins_id[$i].'</a>';
                            //echo $proteins_id[$i];
                        }
                        else{
                            echo'<a target="_BLANK" href="http://www.uniprot.org/uniprot/'.$proteins_id[$i].'" title="UniprotKB Swissprot and Trembl Sequences">'.$proteins_id[$i].'</a>, ';

                            //echo $proteins_id[$i].', ';
                        }
                    }
                    echo '</div>';
                }
                echo'
                </div>
                <div id="expression_profile">
                    <div id="title" class="right"><h4>Expression profile</h4></div>';
                
                $cursor=$measurementsCollection->find(array('$or'=> array(array('gene'=>$gene_id[0]),array('gene'=>$gene_alias[0]))));
//                foreach ($cursor as $result) {
//                    foreach ($result as $key => $value) {
//                    
//                        echo $value;
//
//}
//                        
//                    }
//                    
//                    //var_dump($value);
//                }
                var_dump($value);
                echo'
                </div>             
                <div id="goTerms">
                    <h3>Gene Ontology</h3>
                    <div class="goTermsBlock">
                        <br/>
                        <div class="panel-group" id="accordion_documents">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <a class="accordion-toggle collapsed" href="#go_process" data-parent="#accordion_documents" data-toggle="collapse">
                                        <strong>Biological Process </strong> ('.  count($total_go_biological_process).')
                                    </a>				
                                </div>
                                <div class="panel-body panel-collapse collapse" id="go_process">
                                ';
                                if (count($total_go_biological_process)!=0){
                                    echo'
                                    <div class="goProcessTerms goTerms">
                                    ';
                                    foreach ($total_go_biological_process as $go_info){
                                    echo'
                                        <ul>
                                            <span class="goTerm">
                                                <li>

                                                    <a target="_blank" href="http://amigo.geneontology.org/amigo/term/'.$go_info['GO_ID'].'" title="'.$go_info['description'].'">'.$go_info['description'].'</a>
                                                    <span class="goEvidence">[<a href="http://www.geneontology.org/GO.evidence.shtml#'.$go_info['evidence'].'" title="Go Evidence Code">'.$go_info['evidence'].'</a>]
                                                    </span>
                                            </span>
                                        </ul>';
                                    }
                                    echo'
                                    </div>';
                                }
                                echo'
                                </div>
                            </div>
                        </div>';
                        echo'
                        <div class="panel-group" id="accordion_documents">
                            <div class="panel panel-default">
                                <div class="panel-heading">

                                    <a class="accordion-toggle collapsed" href="#go_component" data-parent="#accordion_documents" data-toggle="collapse">
                                        <strong>Cellular Component </strong> ('.  count($total_go_cellular_component).')
                                    </a>				

                                </div>
                                <div class="panel-body panel-collapse collapse" id="go_component">
                                ';
                                if (count($total_go_cellular_component)!=0){
                                    echo'
                                    <div class="goProcessTerms goTerms">

                                    ';
                                    foreach ($total_go_cellular_component as $go_info){
                                    echo'
                                        <ul>
                                            <span class="goTerm">
                                                <li>

                                                    <a target="_blank" href="http://amigo.geneontology.org/amigo/term/'.$go_info['GO_ID'].'" title="'.$go_info['description'].'">'.$go_info['description'].'</a>
                                                    <span class="goEvidence">[<a href="http://www.geneontology.org/GO.evidence.shtml#'.$go_info['evidence'].'" title="Go Evidence Code">'.$go_info['evidence'].'</a>]
                                                    </span>
                                            </span>
                                        </ul>';
                                    }
                                    echo'
                                    </div>';
                                }
                                echo'
                                </div>
                            </div>
                        </div>    
                        <!--<br/>-->';
                                echo'
                        <div class="panel-group" id="accordion_documents">
                            <div class="panel panel-default">
                                <div class="panel-heading">

                                    <a class="accordion-toggle collapsed" href="#go_function" data-parent="#accordion_documents" data-toggle="collapse">
                                        <strong>Molecular Function </strong> ('.  count($total_go_molecular_function).')
                                    </a>				

                                </div>
                                <div class="panel-body panel-collapse collapse" id="go_function">
                                ';
                                if (count($total_go_molecular_function)!=0){
                                    echo'
                                    <div class="goProcessTerms goTerms">

                                    ';
                                    foreach ($total_go_molecular_function as $go_info){
                                    echo'
                                        <ul>
                                            <span class="goTerm">
                                                <li>

                                                    <a target="_blank" href="http://amigo.geneontology.org/amigo/term/'.$go_info['GO_ID'].'" title="'.$go_info['description'].'">'.$go_info['description'].'</a>
                                                    <span class="goEvidence">[<a href="http://www.geneontology.org/GO.evidence.shtml#'.$go_info['evidence'].'" title="Go Evidence Code">'.$go_info['evidence'].'</a>]
                                                    </span>
                                            </span>
                                        </ul>';
                                    }
                                    echo'
                                    </div>';
                                }
                                echo'
                                </div>
                            </div>
                        </div>';                               
                        echo'
                    </div>
                </div>
                <div id="linkouts">
                    <h3>External Database Linkouts</h3>';
             		//<a target="_BLANK" href="http://arabidopsis.org/servlets/TairObject?type=locus&name='.$search.'" title="TAIR AT5G03160 LinkOut">TAIR</a>
             	  //<!--| <a target="_BLANK" href="http://www.ncbi.nlm.nih.gov/gene/831917" title="Entrez-Gene 831917 LinkOut">Entrez Gene</a> 
             	  //| <a target="_BLANK" href="http://www.ncbi.nlm.nih.gov/sites/entrez?db=protein&cmd=DetailsSearch&term=NP_195936" title="NCBI RefSeq Sequences">RefSeq</a> -->
             	  //';
                    
                    if ($species == "Arabidopsis thaliana"){
                        echo'<a target="_BLANK" href="http://arabidopsis.org/servlets/TairObject?type=locus&name='.$search.'" title="TAIR AT5G03160 LinkOut">TAIR</a>';
                    }
                    else if ($species == "Solanum lycopersicum"){
                        
                        echo'<a target="_BLANK" href="http://solgenomics.net/search/unigene.pl?unigene_id='.$search.'">Sol genomics</a>';
                    }
                    else if ($species == "Cucumis melo"){
                        
                        
                    }
                    else if ($species == "Hordeum vulgare"){
                        
                        
                    }
                    else{
                        
                    }
                    for ($i = 0; $i < count($proteins_id); $i++) {                        
                        echo'| <a target="_BLANK" href="http://www.uniprot.org/uniprot/'.$proteins_id[$i].'" title="UniprotKB Swissprot and Trembl Sequences">UniprotKB</a>';   
                    } 
                    echo'
                </div>
            <div class="bottomSpacer"></div>    
            
        </div>
         
            <input type="hidden" id="displayView" value="summary" />
            <input type="hidden" id="displaySort" value="" />
            
        <div id="stat-details">
            <div id="statsAndFilters">

				
				<h3>Current Interactors</h3>
				';
                $interaction_array=get_interactor($gene_alias,$descriptions, $gene_symbol,$proteins_id,$species,$interactionsCollection);
                $counter=0;
                //$timestart=microtime(true);
                foreach ($interaction_array as $array){
                    if ($counter==0){
                        $total_protein_intact=count($array);

                    }
                    else if ($counter==1){
                        $total_protein_litterature=0;
                        foreach ($array as $intact){
                            $total_protein_litterature++;
                        }
                    }
                    else{
                        $total_protein_biogrid=0;
                        $tgt="";
                        $tgt_array=array();
                        foreach ($array as $intact){
                            foreach ($intact as $value) {
                                if ($value[0]=='tgt'){
                                    $tgt=$value[1];
                                }
                                
                            }
                            if (in_array($tgt,$tgt_array)===FALSE){
                               array_push($tgt_array, $tgt);
                               $total_protein_biogrid++; 
                            }

                            
                        }
                    }
                    $counter++;
                }
                $counter=0;
                $pub_list=array();
                foreach ($interaction_array as $array){
                    if ($counter==0){
                        echo'
                        <div class="panel-group" id="accordion_documents">
                            <div class="panel panel-default">
                                <div class="panel-heading">

                                    <a class="accordion-toggle collapsed" href="#lit_interact" data-parent="#accordion_documents" data-toggle="collapse">
                                        <strong> Host Pathogen Interaction/IntAct database </strong> ('. $total_protein_intact.')
                                    </a>				

                                </div>
                                <div class="panel-body panel-collapse collapse" id="lit_interact">';

                                    echo'
                                    <div class="goProcessTerms goTerms">';

                                    echo'';

                                    $total_protein_intact=0;
                                    foreach ($array as $intact){
                                        $string_seq='<ul><span class="goTerm">';
                                        foreach ($intact as $attributes){

                                            if ($attributes[0]=='src'){

                                                $string_seq.='<li value='.$ $attributes[1].'> host protein: <a href="http://www.uniprot.org/uniprot/'.$attributes[1].'">'.$attributes[1].'</a></li>';

                                            }
                                            elseif ($attributes[0]=='tgt') {
                                                 $tgt=$attributes[1];
                                                $string_seq.='<li value='.$ $attributes[1].'> viral protein: <a href="http://www.uniprot.org/uniprot/'.$attributes[1].'">'.$attributes[1].'</a></li>';

                                            }
                                            elseif ($attributes[0]=='method') {
                                                 $string_seq.='<li value='.$ $attributes[1].'> method: '.$attributes[1].'</li>';

                                            }
                                           
                                            elseif ($attributes[0]=='pub') {
                                                 $string_seq.='<li value='.$ $attributes[1].'> publication: <a href="http://www.ncbi.nlm.nih.gov/pubmed/'.$attributes[1].'">'.$attributes[1].'</a></li>';
                                                 $found=FALSE;
                                                 foreach ($pub_list as $pub) {
                                                     if ($attributes[1]==$pub){
                                                         $found=TRUE;
                                                     }
                                                 }
                                                 if ($found==FALSE){
                                                     array_push($pub_list, $attributes[1]);
                                                 }
                                                     
                                                 

                                            }
                                            elseif ($attributes[0]=='host_name') {
                                                $string_seq.='<li value='.$ $attributes[1].'> host name: '.$attributes[1].'</li>';

                                            }
                                            elseif ($attributes[0]=='virus_name') {
                                                $string_seq.='<li value='.$ $attributes[1].'> virus name: '.$attributes[1].'</li>';

                                            }
                                            elseif ($attributes[0]=='host_taxon') {
                                                $string_seq.='<li value='.$ $attributes[1].'> host taxon: '.$attributes[1].'</li>';

                                            }
                                            elseif ($attributes[0]=='virus_taxon') {
                                                $string_seq.='<li value='.$ $attributes[1].'> virus taxon: '.$attributes[1].'</li>';

                                            }
                                            else{

                                            }


                                        }
                                        $string_seq.='</ul></span>';
                                        add_accordion_panel($string_seq, $tgt, $tgt);
                                        $total_protein_intact++;

                                    }
                                    $counter++;
                                    echo'
                                    </div>';

                                echo'
                                </div></div></div>';
                    }
                    else if ($counter==1){
                        echo'
                               
                            
                        <div class="panel-group" id="accordion_documents">
                            <div class="panel panel-default">
                                <div class="panel-heading">

                                    <a class="accordion-toggle collapsed" href="#database_interact" data-parent="#accordion_documents" data-toggle="collapse">
                                        <strong> Litterature plant/virus database </strong> ('.  $total_protein_litterature.')
                                    </a>				

                                </div>
                                <div class="panel-body panel-collapse collapse" id="database_interact">
                                ';

                                echo'
                                <div class="goProcessTerms goTerms">

                                ';
                                $total_protein_litterature=0;
                                foreach ($array as $lit){
                                    
                                    $string_seq='<ul><span class="goTerm">';
                                    foreach ($lit as $attributes){
                                        

                                        if ($attributes[0]=='src'){
                                            $string_seq.='<li value='.$ $attributes[1].'> host protein: '.$attributes[1].'</li>';
                                        }
                                        elseif ($attributes[0]=='tgt') {
                                            $tgt=$attributes[1];
                                            $string_seq.='<li value='.$ $attributes[1].'> viral protein: '.$attributes[1].'</li>';
                                        }
                                        elseif ($attributes[0]=='method') {
                                            $string_seq.='<li value='.$ $attributes[1].'> method: '.$attributes[1].'</li>';
                                        }
                                        elseif ($attributes[0]=='pub') {
                                            $string_seq.='<li value='.$ $attributes[1].'> publication: <a href="http://www.ncbi.nlm.nih.gov/pubmed/'.$attributes[1].'">'.$attributes[1].'</a></li>';
                                            $found=FALSE;
                                            foreach ($pub_list as $pub) {
                                                if ($attributes[1]==$pub){
                                                    $found=TRUE;
                                                }
                                            }
                                            if ($found==FALSE){
                                                array_push($pub_list, $attributes[1]);
                                            }
                                        }
                                        elseif ($attributes[0]=='host_name') {
                                            $string_seq.='<li value='.$ $attributes[1].'> host name: '.$attributes[1].'</li>';
                                        }
                                        elseif ($attributes[0]=='virus_name') {
                                            $string_seq.='<li value='.$ $attributes[1].'> viral name: '.$attributes[1].'</li>';
                                        }
                                        elseif ($attributes[0]=='Accession_number') {
                                            $string_seq.='<li value='.$ $attributes[1].'> Accession number: '.$attributes[1].'</li>';
                                        }
                                        elseif ($attributes[0]=='Putative_function') {
                                            $string_seq.='<li value='.$ $attributes[1].'> Putative function: '.$attributes[1].'</li>';
                                        }
                                        else{

                                        }


                                    }
                                    $string_seq.='</ul></span>';
                                    add_accordion_panel($string_seq, $tgt, $tgt);
                                    $total_protein_litterature++;

                                }
                                $counter++;
                                

                                echo'
                                </div>';

                            echo'
                            </div>
                        </div></div>';
                    }
                    else{
                        echo'
                               
                            
                        <div class="panel-group" id="accordion_documents">
                            <div class="panel panel-default">
                                <div class="panel-heading">

                                    <a class="accordion-toggle collapsed" href="#database_biogrid" data-parent="#accordion_documents" data-toggle="collapse">
                                        <strong> Biogrid plant/plant interaction database </strong> ('.  $total_protein_biogrid.')
                                    </a>				

                                </div>
                                <div class="panel-body panel-collapse collapse" id="database_biogrid">
                                ';

                                echo'
                                <div class="goProcessTerms goTerms">

                                ';
                                $total_protein_biogrid=0;
                                $tgt="";
                                $tgt_array=array();
                                foreach ($array as $lit){
                                    
                                    $string_seq='<ul><span class="goTerm">';
                                    
                                    foreach ($lit as $attributes){
                                        

                                        if ($attributes[0]=='src'){
                                            $string_seq.='<li value='.$ $attributes[1].'> protein A: '.$attributes[1].'</li>';
                                        }
                                        elseif ($attributes[0]=='tgt') {
                                            $tgt=$attributes[1];
                                            
                                            
                                            
                                            //http://plants.ensembl.org/Arabidopsis_thaliana/Search/Results?species=Arabidopsis%20thaliana;idx=;q=FKF1;site=ensemblunit                                            if (){
                                            //http://plants.ensembl.org/Arabidopsis_thaliana/Search/Results?species=Arabidopsis%20thaliana;idx=;q=CUL1;site=ensemblunit
                                            $string_seq.='<li value='.$ $attributes[1].'> protein B: '.$attributes[1].'</li>';
                                        }
                                        elseif ($attributes[0]=='method') {
                                            $string_seq.='<li value='.$ $attributes[1].'> method: '.$attributes[1].'</li>';
                                        }
                                        elseif ($attributes[0]=='pub') {
                                            $string_seq.='<li value='.$ $attributes[1].'> publication: <a href="http://www.ncbi.nlm.nih.gov/pubmed/'.$attributes[1].'">'.$attributes[1].'</a></li>';
                                            $found=FALSE;
                                            foreach ($pub_list as $pub) {
                                                if ($attributes[1]==$pub){
                                                    $found=TRUE;
                                                }
                                            }
                                            if ($found==FALSE){
                                                array_push($pub_list, $attributes[1]);
                                            }
                                        }
                                        elseif ($attributes[0]=='host A name') {
                                            $string_seq.='<li value='.$ $attributes[1].'> host name A: '.$attributes[1].'</li>';
                                        }
                                        elseif ($attributes[0]=='host B name') {
                                            $string_seq.='<li value='.$ $attributes[1].'> host name B: '.$attributes[1].'</li>';
                                        }
                                        elseif ($attributes[0]=='Accession_number') {
                                            $string_seq.='<li value='.$ $attributes[1].'> Authors: '.$attributes[1].'</li>';
                                        }
//                                        elseif ($attributes[0]=='Putative_function') {
//                                            $string_seq.='<li value='.$ $attributes[1].'> Putative function :'.$attributes[1].'</li>';
//                                        }
                                        else{

                                        }


                                    }
                                    $string_seq.='</ul></span>';
                                    if (in_array($tgt,$tgt_array)===FALSE){
                                        array_push($tgt_array, $tgt);
                                        add_accordion_panel($string_seq, $tgt, $tgt);
                                        $total_protein_biogrid++;
                                    }
                                    

                                }
                                $counter++;
                                

                                echo'
                                </div>';

                            echo'
                            </div>
                        </div></div>';
                    }
                }
                        
//                $timeend=microtime(true);
//                $time=$timeend-$timestart;
//                //Afficher le temps d'éxecution
//                $page_load_time = number_format($time, 3);
//                echo "starting script at: ".date("H:i:s", $timestart);
//                echo "<br>Ending script at: ".date("H:i:s", $timeend);
//                echo "<br>Script for interaction data executed in " . $page_load_time . " sec";          

                                

               echo'<div class="physical-ltp statisticRow">
                        <div class="physical colorFill" style="width: 0%;"></div>
                        <!--<div class="statDetails">
                            <div class="left"></div>
                            <div class="right"></div>
                                '; 
                            //$total_plant_virus=$total_protein_litterature+$total_protein_intact;
                            //$total_plant_plant=$total_protein_biogrid;
                            //echo $total_plant_virus.' Plant/Virus Interactions <br>';
                            //echo $total_plant_plant.' Plant/Plant Interactions
                           
                        echo '</div>-->
                        <div id="pubStats" class="right">
                            <strong>Publications:</strong>'.count($pub_list).'
                        </div>
                    </div>
                    <div class="genetic-ltp statisticRow">
                        <div class="genetic colorFill" style="width: 0%;"></div>
                        <div class="statDetails"></div>
                    </div>
                    <br></br> 
            </div>';
        //$timestart=microtime(true);
       echo'<div id="ortholog_section">
            <h3>Orthologs</h3>
                <div class="panel-group" id="accordion_documents">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>
                                <a class="accordion-toggle collapsed" href="#ortho-table" data-parent="#accordion_documents" data-toggle="collapse">
                                        Homologs table
                                </a>				
                            </h3>
                        </div>
                        <div class="panel-body panel-collapse collapse" id="ortho-table">
                            <table class="table table-condensed table-hover table-striped">                                                                <thead>
                                <tr>';
                                    echo "<th>gene ID</th>";
                                    echo "<th>protein ID</th>";
                                    echo "<th>species</th>";
                                    echo'
                                </tr>
                                </thead>

                                <tbody>';
                                    //$timestart=microtime(true);
                                    echo small_table_ortholog_string($mappingsCollection,$orthologsCollection,$organism,$plaza_id);
    //                                        $timeend=microtime(true);
    //                                        $time=$timeend-$timestart;
    //
    //                                        //Afficher le temps d'éxecution
    //                                        $page_load_time = number_format($time, 3);
    //                                        echo "Debut du script: ".date("H:i:s", $timestart);
    //                                        echo "<br>Fin du script: ".date("H:i:s", $timeend);
    //                                        echo "<br>Script aggregate and var dump execute en " . $page_load_time . " sec";
                           echo'</tbody>

                            </table>
                        </div>

                    </div>
                </div>
                <div id="shift_line"></div>
            </div>';
//                $timeend=microtime(true);
//                $time=$timeend-$timestart;
//                //Afficher le temps d'éxecution
//                $page_load_time = number_format($time, 3);
//                echo "starting script at: ".date("H:i:s", $timestart);
//                echo "<br>Ending script at: ".date("H:i:s", $timeend);
//                echo "<br>Script for ortholog data executed in " . $page_load_time . " sec";   
                  //$protein="Q39255";   
                  
   echo'</div>
        </div>';
//    $timeend=microtime(true);
//    $time=$timeend-$timestart;
//    //Afficher le temps d'éxecution
//    $page_load_time = number_format($time, 3);
//    echo "Debut du script: ".date("H:i:s", $timestart);
//    echo "<br>Fin du script: ".date("H:i:s", $timeend);
//    echo "<br>Script for plaza id execute en " . $page_load_time . " sec";
//
//    echo '<hr>';
//    
    }
    else{
        echo'<div id="summary">
        <h2>No Results found for \''.$search.'\'</h2>'
      . '</div>';	
    }
}
else{
	echo'<div class="container">
        <h2>you have uncorrectly defined your request</h2>'
      . '</div>';	
}


//echo '<hr>';





//HTML OUTPUT
//echo   '<div id="summary">   
//            <div id="protein-details">'.$gene_id[0].'
//            
//                <div id="organism" class="right"><h4>'.$organism.'</h4></div>';
//                echo '<h1>'.$gene_symbol[0].'</h1> ';
//                echo'<div id="aliases"> Description : ';
//                for ($i = 0; $i < count($descriptions); $i++) {
//                    if ($i==count($descriptions)-1){
//                        echo $descriptions[$i];
//                    }
//                   
//                    else{
//                        echo $descriptions[$i].', ';
//                    }
//                }
//                echo '</div>';
//                
//                echo'<div id="aliases"> Alias : ';
//                for ($i = 0; $i < count($gene_alias); $i++) {
//                    if ($i==count($gene_alias)-1){
//                        echo $gene_alias[$i];
//                    }
//                    else{
//                        echo $gene_alias[$i].', ';
//                    }
//                }
//                
//                echo '</div>';
//                echo'<div id="protein aliases"> Protein ids : ';
//                for ($i = 0; $i < count($proteins_id); $i++) {
//                    if ($i==count($proteins_id)-1){
//                        echo $proteins_id[$i];
//                    }
//                    else{
//                        echo $proteins_id[$i].', ';
//                    }
//                }
//                echo '</div>';
//                echo'
//               
//                <div id="goTerms">
//                    <div class="goTermsBlock">
//                        <br/>
//                        <div class="panel-group" id="accordion_documents">
//                            <div class="panel panel-default">
//                                <div class="panel-heading">
//                                    <a class="accordion-toggle collapsed" href="#go_process" data-parent="#accordion_documents" data-toggle="collapse">
//                                        <strong>Gene Ontology Biological Process </strong> ('.  count($total_go_biological_process).')
//                                    </a>				
//                                </div>
//                                <div class="panel-body panel-collapse collapse" id="go_process">
//                                ';
//                                if (count($total_go_biological_process)!=0){
//                                    echo'
//                                    <div class="goProcessTerms goTerms">
//                                    ';
//                                    foreach ($total_go_biological_process as $go_info){
//                                    echo'
//                                        <ul>
//                                            <span class="goTerm">
//                                                <li>
//
//                                                    <a target="_blank" href="http://amigo.geneontology.org/amigo/term/'.$go_info['GO_ID'].'" title="'.$go_info['description'].'">'.$go_info['description'].'</a>
//                                                    <span class="goEvidence">[<a href="http://www.geneontology.org/GO.evidence.shtml#'.$go_info['evidence'].'" title="Go Evidence Code">'.$go_info['evidence'].'</a>]
//                                                    </span>
//                                            </span>
//                                        </ul>';
//                                    }
//                                    echo'
//                                    </div>';
//                                }
//                                echo'
//                                </div>
//                            </div>
//                        </div>';
//                        echo'
//                        <div class="panel-group" id="accordion_documents">
//                            <div class="panel panel-default">
//                                <div class="panel-heading">
//
//                                    <a class="accordion-toggle collapsed" href="#go_component" data-parent="#accordion_documents" data-toggle="collapse">
//                                        <strong>Gene Ontology Cellular Component </strong> ('.  count($total_go_cellular_component).')
//                                    </a>				
//
//                                </div>
//                                <div class="panel-body panel-collapse collapse" id="go_component">
//                                ';
//                                if (count($total_go_cellular_component)!=0){
//                                    echo'
//                                    <div class="goProcessTerms goTerms">
//
//                                    ';
//                                    foreach ($total_go_cellular_component as $go_info){
//                                    echo'
//                                        <ul>
//                                            <span class="goTerm">
//                                                <li>
//
//                                                    <a target="_blank" href="http://amigo.geneontology.org/amigo/term/'.$go_info['GO_ID'].'" title="'.$go_info['description'].'">'.$go_info['description'].'</a>
//                                                    <span class="goEvidence">[<a href="http://www.geneontology.org/GO.evidence.shtml#'.$go_info['evidence'].'" title="Go Evidence Code">'.$go_info['evidence'].'</a>]
//                                                    </span>
//                                            </span>
//                                        </ul>';
//                                    }
//                                    echo'
//                                    </div>';
//                                }
//                                echo'
//                                </div>
//                            </div>
//                        </div>    
//                        <!--<br/>-->';
//                                echo'
//                        <div class="panel-group" id="accordion_documents">
//                            <div class="panel panel-default">
//                                <div class="panel-heading">
//
//                                    <a class="accordion-toggle collapsed" href="#go_function" data-parent="#accordion_documents" data-toggle="collapse">
//                                        <strong>Gene Ontology Molecular Function </strong> ('.  count($total_go_molecular_function).')
//                                    </a>				
//
//                                </div>
//                                <div class="panel-body panel-collapse collapse" id="go_function">
//                                ';
//                                if (count($total_go_molecular_function)!=0){
//                                    echo'
//                                    <div class="goProcessTerms goTerms">
//
//                                    ';
//                                    foreach ($total_go_molecular_function as $go_info){
//                                    echo'
//                                        <ul>
//                                            <span class="goTerm">
//                                                <li>
//
//                                                    <a target="_blank" href="http://amigo.geneontology.org/amigo/term/'.$go_info['GO_ID'].'" title="'.$go_info['description'].'">'.$go_info['description'].'</a>
//                                                    <span class="goEvidence">[<a href="http://www.geneontology.org/GO.evidence.shtml#'.$go_info['evidence'].'" title="Go Evidence Code">'.$go_info['evidence'].'</a>]
//                                                    </span>
//                                            </span>
//                                        </ul>';
//                                    }
//                                    echo'
//                                    </div>';
//                                }
//                                echo'
//                                </div>
//                            </div>
//                        </div>';                               
//                        echo'
//                    </div>
//                </div>
//                <div id="linkouts">
//                    <h3>External Database Linkouts</h3>
//             		<a target="_BLANK" href="http://arabidopsis.org/servlets/TairObject?type=locus&name='.$search.'" title="TAIR AT5G03160 LinkOut">TAIR</a>
//             	  <!--| <a target="_BLANK" href="http://www.ncbi.nlm.nih.gov/gene/831917" title="Entrez-Gene 831917 LinkOut">Entrez Gene</a> 
//             	  | <a target="_BLANK" href="http://www.ncbi.nlm.nih.gov/sites/entrez?db=protein&cmd=DetailsSearch&term=NP_195936" title="NCBI RefSeq Sequences">RefSeq</a> -->
//             	  ';
//                    
//                    
//                    for ($i = 0; $i < count($proteins_id); $i++) {                        
//                        echo'| <a target="_BLANK" href="http://www.uniprot.org/uniprot/'.$proteins_id[$i].'" title="UniprotKB Swissprot and Trembl Sequences">UniprotKB</a>';   
//                    } 
//                    echo'
//                </div>
//                <div class="bottomSpacer"></div>    
//            </div>
//         
//            <input type="hidden" id="displayView" value="summary" />
//            <input type="hidden" id="displaySort" value="" />
//            
//            <div id="stat-details">
// 				<div id="interaction-tabs">
//                <ul>
//                    <li title="stats" id="statsTab" class="noClickTab">Stats & Options</li>
//                </ul>
//            </div>
//         	
//     
//     	
//            <div id="statsAndFilters">
//
//				
//				<h3>Current Interactors</h3>
//				';
//                $interaction_array=get_interactor($gene_alias,$descriptions, $gene_symbol,$proteins_id,$species,$interactionsCollection);
//                $counter=0;
//                
//                foreach ($interaction_array as $array){
//                    if ($counter==0){
//                        $total_protein_intact=count($array);
//
//                    }
//                    else{
//                        $total_protein_litterature=0;
//                        foreach ($array as $intact){
//                            $total_protein_litterature++;
//                        }
//                    }
//                    $counter++;
//                }
//                $counter=0;
//                $pub_list=array();
//                foreach ($interaction_array as $array){
//                    if ($counter==0){
//                        echo'
//                        <div class="panel-group" id="accordion_documents">
//                            <div class="panel panel-default">
//                                <div class="panel-heading">
//
//                                    <a class="accordion-toggle collapsed" href="#lit_interact" data-parent="#accordion_documents" data-toggle="collapse">
//                                        <strong> Intact Database </strong> ('. $total_protein_intact.')
//                                    </a>				
//
//                                </div>
//                                <div class="panel-body panel-collapse collapse" id="lit_interact">';
//
//                                    echo'
//                                    <div class="goProcessTerms goTerms">';
//
//                                    echo'';
//
//                                    $total_protein_intact=0;
//                                    foreach ($array as $intact){
//                                        $string_seq='<ul><span class="goTerm">';
//                                        foreach ($intact as $attributes){
//
//                                            if ($attributes[0]=='src'){
//
//                                                $string_seq.='<li value='.$ $attributes[1].'> host protein :<a href="http://www.uniprot.org/uniprot/'.$attributes[1].'">'.$attributes[1].'</a></li>';
//
//                                            }
//                                            elseif ($attributes[0]=='tgt') {
//                                                 $tgt=$attributes[1];
//                                                $string_seq.='<li value='.$ $attributes[1].'> viral protein :<a href="http://www.uniprot.org/uniprot/'.$attributes[1].'">'.$attributes[1].'</a></li>';
//
//                                            }
//                                            elseif ($attributes[0]=='method') {
//                                                 $string_seq.='<li value='.$ $attributes[1].'> method :'.$attributes[1].'</li>';
//
//                                            }
//                                           
//                                            elseif ($attributes[0]=='pub') {
//                                                 $string_seq.='<li value='.$ $attributes[1].'> publication :<a href="http://www.ncbi.nlm.nih.gov/pubmed/'.$attributes[1].'">'.$attributes[1].'</a></li>';
//                                                 $found=FALSE;
//                                                 foreach ($pub_list as $pub) {
//                                                     if ($attributes[1]==$pub){
//                                                         $found=TRUE;
//                                                     }
//                                                 }
//                                                 if ($found==FALSE){
//                                                     array_push($pub_list, $attributes[1]);
//                                                 }
//                                                     
//                                                 
//
//                                            }
//                                            elseif ($attributes[0]=='host_name') {
//                                                $string_seq.='<li value='.$ $attributes[1].'> host name :'.$attributes[1].'</li>';
//
//                                            }
//                                            elseif ($attributes[0]=='virus_name') {
//                                                $string_seq.='<li value='.$ $attributes[1].'> virus name :'.$attributes[1].'</li>';
//
//                                            }
//                                            elseif ($attributes[0]=='host_taxon') {
//                                                $string_seq.='<li value='.$ $attributes[1].'> host taxon :'.$attributes[1].'</li>';
//
//                                            }
//                                            elseif ($attributes[0]=='virus_taxon') {
//                                                $string_seq.='<li value='.$ $attributes[1].'> virus taxon :'.$attributes[1].'</li>';
//
//                                            }
//                                            else{
//
//                                            }
//
//
//                                        }
//                                        $string_seq.='</ul></span>';
//                                        add_accordion_panel($string_seq, $tgt, $tgt);
//                                        $total_protein_intact++;
//
//                                    }
//                                    $counter++;
//                                    echo'
//                                    </div>';
//
//                                echo'
//                                </div></div></div>';
//                    }
//                    else{
//                        echo'
//                               
//                            
//                        <div class="panel-group" id="accordion_documents">
//                            <div class="panel panel-default">
//                                <div class="panel-heading">
//
//                                    <a class="accordion-toggle collapsed" href="#database_interact" data-parent="#accordion_documents" data-toggle="collapse">
//                                        <strong> Litterature database </strong> ('.  $total_protein_litterature.')
//                                    </a>				
//
//                                </div>
//                                <div class="panel-body panel-collapse collapse" id="database_interact">
//                                ';
//
//                                echo'
//                                <div class="goProcessTerms goTerms">
//
//                                ';
//                                $total_protein_litterature=0;
//                                foreach ($array as $lit){
//                                    
//                                    $string_seq='<ul><span class="goTerm">';
//                                    foreach ($lit as $attributes){
//                                        
//
//                                        if ($attributes[0]=='src'){
//                                            $string_seq.='<li value='.$ $attributes[1].'> host protein :'.$attributes[1].'</li>';
//                                        }
//                                        elseif ($attributes[0]=='tgt') {
//                                            $tgt=$attributes[1];
//                                            $string_seq.='<li value='.$ $attributes[1].'> viral protein :'.$attributes[1].'</li>';
//                                        }
//                                        elseif ($attributes[0]=='method') {
//                                            $string_seq.='<li value='.$ $attributes[1].'> method :'.$attributes[1].'</li>';
//                                        }
//                                        elseif ($attributes[0]=='pub') {
//                                            $string_seq.='<li value='.$ $attributes[1].'> publication :'.$attributes[1].'</li>';
//                                            $found=FALSE;
//                                            foreach ($pub_list as $pub) {
//                                                if ($attributes[1]==$pub){
//                                                    $found=TRUE;
//                                                }
//                                            }
//                                            if ($found==FALSE){
//                                                array_push($pub_list, $attributes[1]);
//                                            }
//                                        }
//                                        elseif ($attributes[0]=='host_name') {
//                                            $string_seq.='<li value='.$ $attributes[1].'> host name :'.$attributes[1].'</li>';
//                                        }
//                                        elseif ($attributes[0]=='virus_name') {
//                                            $string_seq.='<li value='.$ $attributes[1].'> viral name :'.$attributes[1].'</li>';
//                                        }
//                                        elseif ($attributes[0]=='Accession_number') {
//                                            $string_seq.='<li value='.$ $attributes[1].'> Accession number :'.$attributes[1].'</li>';
//                                        }
//                                        elseif ($attributes[0]=='Putative_function') {
//                                            $string_seq.='<li value='.$ $attributes[1].'> Putative function :'.$attributes[1].'</li>';
//                                        }
//                                        else{
//
//                                        }
//
//
//                                    }
//                                    $string_seq.='</ul></span>';
//                                    add_accordion_panel($string_seq, $tgt, $tgt);
//                                    $total_protein_litterature++;
//
//                                }
//                                $counter++;
//                                
//
//                                echo'
//                                </div>';
//
//                            echo'
//                            </div>
//                        </div></div>';
//                    }
//                }
//                        echo'
//                           
//
//                                
//
//                        <div class="physical-ltp statisticRow">
//                            <div class="physical colorFill" style="width: 0%;"></div>
//                            <div class="statDetails">
//                                <div class="left"></div>
//                                <div class="right"></div>
//                                    '; 
//                                $total=$total_protein_litterature+$total_protein_intact;
//                        
//                                echo $total.' Physical Interactions
//                            </div>
//                            <div id="pubStats" class="right">
//                                <strong>Publications:</strong>'.count($pub_list).'
//                            </div>
//                        </div>
//                        <div class="genetic-ltp statisticRow">
//                            <div class="genetic colorFill" style="width: 0%;"></div>
//                            <div class="statDetails"></div>
//                        </div>
//                        <br></br>
//                        <!--<div class="right" style="margin-top: 3px">
//                           test
//                        </div>-->
//                        <h3>ORTHOLOGS</h3>
//                            <a id="filterLink" href="http://thebiogrid.org/scripts/displayFilterList.php">
//                                <div id="filterButton" class="noFilter" style="background-color: rgb(238, 238, 238); color: rgb(51, 51, 51);"></div>
//                            </a>
//                    </div>
//			
//            ';
//            echo'<div class="panel-group" id="accordion_documents">
//                        <div class="panel panel-default">
//                            <div class="panel-heading">
//                                <h3>
//                                    <a class="accordion-toggle collapsed" href="#ortho-table" data-parent="#accordion_documents" data-toggle="collapse">
//                                            Homologs table
//                                    </a>				
//                                </h3>
//                            </div>
//                            <div class="panel-body panel-collapse collapse" id="ortho-table">
//                                <table class="table table-condensed table-hover table-striped">                                                                <thead>
//                                    <tr>';
//                                        echo "<th>gene ID</th>";
//                                        echo "<th>protein ID</th>";
//                                        echo "<th>species</th>";
//                                        echo'
//                                    </tr>
//                                    </thead>
//
//                                    <tbody>';
//                                        //$timestart=microtime(true);
//                                        echo small_table_ortholog_string($mappingsCollection,$orthologsCollection,$organism,$plaza_id);
////                                        $timeend=microtime(true);
////                                        $time=$timeend-$timestart;
////
////                                        //Afficher le temps d'éxecution
////                                        $page_load_time = number_format($time, 3);
////                                        echo "Debut du script: ".date("H:i:s", $timestart);
////                                        echo "<br>Fin du script: ".date("H:i:s", $timeend);
////                                        echo "<br>Script aggregate and var dump execute en " . $page_load_time . " sec";
//                               echo'</tbody>
//
//                                </table>
//                            </div>
//
//                        </div>
//                    </div>';
//                  //$protein="Q39255";   
//                  
//       echo'</div>
//        </div>';


new_cobra_footer();

?>






<script type="text/javascript" class="init">
	$(document).ready(function() {
		$('#example').dataTable( {
			"scrollX": true,
			"jQueryUI": true,
			"pagingType": "full_numbers",
			"oLanguage": { 
				"sProcessing":   "Processing...",
				"sLengthMenu":   "display _MENU_ items",
				"sZeroRecords":  "No item found",
				"sInfo": "Showing item _START_ to _END_ on  _TOTAL_ items",
				"sInfoEmpty": "Displaying item 0 to 0 on 0 items",
				"sInfoFiltered": "(filtered from _MAX_ items in total)",
				"sInfoPostFix":  "",
				"sSearch":       "Search: ",
				"sUrl":          "",
				"oPaginate": {
					"sFirst":    "First",
					"sPrevious": "Previous",
					"sNext":     "Next",
					"sLast":     "Last"
				}
			},
			"language": {
							"decimal": ",",
							"thousands": "."
				}
		});
	});
	$(document).ready(function() {
		$('#samplestable').dataTable( {
			"scrollX": true,
			"jQueryUI": true,
			"pagingType": "full_numbers",
			"oLanguage": { 
				"sProcessing":   "Processing...",
				"sLengthMenu":   "display _MENU_ items",
				"sZeroRecords":  "No item found",
				"sInfo": "Showing item _START_ to _END_ on  _TOTAL_ items",
				"sInfoEmpty": "Displaying item 0 to 0 on 0 items",
				"sInfoFiltered": "(filtered from _MAX_ items in total)",
				"sInfoPostFix":  "",
				"sSearch":       "Search: ",
				"sUrl":          "",
				"oPaginate": {
					"sFirst":    "First",
					"sPrevious": "Previous",
					"sNext":     "Next",
					"sLast":     "Last"
				}
			},
			"language": {
							"decimal": ",",
							"thousands": "."
				}
		});
	});
</script>






