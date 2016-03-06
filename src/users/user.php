 <?php
 	//session_start();
 	include '../functions/html_functions.php';
 	include '../functions/php_functions.php';
 	include '../functions/mongo_functions.php';
 	require '../session/control-session.php';
 
	new_cobra_header("../..");


	new_cobra_body($_SESSION['login'],"Users details","section_user_details","../..");
 	if ((isset($_GET['firstname'])) && ((isset($_GET['lastname'])))){
 		$firstname=htmlentities(trim($_GET['firstname']));
 		$lastname=htmlentities(trim($_GET['lastname']));
 	
 	}
 	else{
 		echo '<p>You are not registered </p>'."\n";
 		exit();

 	}
 	$db=mongoConnector();
	$usersCollection = new Mongocollection($db, "users");
    $jobsCollection = new Mongocollection($db, "jobs");
    $speciesCollection = new Mongocollection($db, "species");
    $historyCollection = new Mongocollection($db, "history");

	$user=$usersCollection->find(array("firstname"=>$firstname,"lastname"=>$lastname),array());
    make_species_list(find_species_list($speciesCollection),"../..");
 	make_user_preferences($user,$usersCollection);
    
    //une table avec tous les jobs blast.
    
    $jobs=$jobsCollection->find(array("job_owner_firstname"=>$lastname,"job_owner_lastname"=>$firstname),array());
    
    //$json_string = json_encode($data);
    
    $table_string='';
    echo '<div id="BlastJobsTable"><h3> Blast Jobs</h3>';
    $table_string.='<table id="blast_jobs" class="table table-hover">';
    //$table_string.='<table id="mappingtable" class="table table-bordered table-hover" cellspacing="0" width="100%">';
    $table_string.='<thead><tr>';

        //recupere le titre
        //$table_string.='<th>type</th>';
        $table_string.='<th>Query id</th>';
        $table_string.='<th>Date</th>';
        $table_string.='<th>Results</th>';



        //fin du header de la table
    $table_string.='</tr></thead>';

    //Debut du corps de la table
    $table_string.='<tbody>';
    foreach ($jobs as $line) {
        $table_string.='<tr>';
            //$table_string.='<td>'.$line['type'].'</td>';
            $table_string.='<td>'.$line['query_id'].'</td>';
            $table_string.='<td>'.$line['date'].'</td>';
            $table_string.='<td><a href="../tools/blast/blast_result.php?id='.$line['_id'].'">View results</td>';

        $table_string.='</tr>';

    }
    $table_string.='</tbody></table>';
    $table_string.='</div>';

    echo $table_string;
    
    echo '</br>';
    echo '</br>';
    echo '<br/>';
    
    //une table avec tous l'historique de recherche
        
    $table_string='';
    
    $searches=$historyCollection->find(array("lastname"=>$lastname,"firstname"=>$firstname,"type" => "search"),array());

    
    echo '<div id="search_history"><h3> Search history</h3>';
    $table_string.='<table id="history" class="table table-hover">';
    //$table_string.='<table id="mappingtable" class="table table-bordered table-hover" cellspacing="0" width="100%">';
    $table_string.='<thead><tr>';

        //recupere le titre
        //$table_string.='<th>type</th>';
        $table_string.='<th>Query id</th>';
        $table_string.='<th>Date</th>';
        



        //fin du header de la table
    $table_string.='</tr></thead>';

    //Debut du corps de la table
    $table_string.='<tbody>';
    foreach ($searches as $line) {
        $table_string.='<tr>';
            //$table_string.='<td>'.$line['type'].'</td>';
            $table_string.='<td>'.$line['search id'].'</td>';
            $table_string.='<td>'.$line['date'].'</td>';

        $table_string.='</tr>';

    }
    $table_string.='</tbody></table></div>';

    echo $table_string;
    
    echo '</br>';

    
    
 	new_cobra_footer();
 	
 
 
 ?>


<script type="text/javascript" class="init">

$(document).ready(function() {
    $('#blast_jobs').DataTable( {
        responsive: true    
    });
    $('#history').DataTable( {
        responsive: true    
    });

});

</script>