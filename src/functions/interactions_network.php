<?php
require '../session/maintenance-session.php';
require '../functions/html_functions.php';
require '../functions/php_functions.php';
require '../functions/mongo_functions.php';
require '../session/control-session.php';

// header('Content-type: application/json');
if (isset($_POST['gene_ids'],$_POST['transcript_ids'],$_POST['protein_ids'],$_POST['species'],$_POST['mode'], $_POST['type'])){ 
    $db=mongoConnector(); 
    $gene_id=json_decode($_POST['gene_ids']);
    $protein_ids = json_decode($_POST['protein_ids']);
    $transcript_ids = json_decode($_POST['transcript_ids']);
    $species=$_POST['species'];
    $type = $_POST['type'];

    ////////////////////////////////////
    ///////// PV
    ////////////////////////////////////
    if ( $type == 'PV'){
		$pv_interactionsCollection = new Mongocollection($db, "pv_interactions");
		$pv_result=get_hpidb_plant_virus_interactor($protein_ids,$pv_interactionsCollection,$species);
		$lp_result=get_litterature_plant_virus_interactor($gene_id,$pv_interactionsCollection,$species);
		$final_result = array_merge($pv_result['cursor']['firstBatch'], $lp_result['cursor']['firstBatch']);
		if (count($final_result)>0){
			echo json_encode($final_result);
		}else{
			echo json_encode("No result found.");
		}

	////////////////////////////////////
	////////// PP
	////////////////////////////////////
    }else if ( $type == 'PP'){
    	$pp_interactionsCollection = new Mongocollection($db, "pp_interactions");
    	$pp_result = get_intact_plant_plant_interactor($protein_ids,$pp_interactionsCollection,$species);
    	$biogrid_array=get_biogrid_plant_plant_interactor($gene_id, $pp_interactionsCollection, $species);
    	$string_array=get_string_plant_plant_interactor($transcript_ids, $pp_interactionsCollection, $species);
    	// concaten all the results 
    	$final_result = array_merge($pp_result['cursor']['firstBatch'], $string_array['cursor']['firstBatch'], $biogrid_array['cursor']['firstBatch'] );
		if (count($final_result)>0){
			echo json_encode($final_result);
		}else{
			echo json_encode("No result found.");
		}
    }else{
    	echo json_encode("No result found. Wrong type of interaction");
    }
}else{
	echo json_encode("No result found.");
}

?>