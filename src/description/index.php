<?php
//session_start();
require '../session/maintenance-session.php';
include '../functions/html_functions.php';
include '../functions/php_functions.php';
include '../functions/mongo_functions.php';
require('../session/control-session.php');

/*
define('ROOT_PATH', realpath(dirname(__FILE__)) .'/../../');

require ROOT_PATH.'src/functions/html_functions.php';
include ROOT_PATH.'src/functions/php_functions.php';
include ROOT_PATH.'src/functions/mongo_functions.php';
*/

new_cobra_header("../..");


new_cobra_body(isset($_SESSION['login'])? $_SESSION['login']:False,"Datasets and statistics","section_description","../..");

$db=mongoConnector();
$speciesCollection = new Mongocollection($db, "species");
$samplesCollection = new Mongocollection($db, "samples");
$mappingsCollection = new Mongocollection($db, "mappings");
$measurementsCollection = new Mongocollection($db, "measurements");
$publicationsCollection = new Mongocollection($db, "publications");
$interactionsCollection = new Mongocollection($db, "interactions");
$virusesCollection = new Mongocollection($db, "viruses");
#find_species_list($speciesCollection);
#$cursor = $speciesCollection->find(array(),array('_id'=>1,'full_name'=>1));



###EXPERIMENT REQUEST

make_species_list(find_species_list($speciesCollection),"../..");
echo '<br/>';
echo '<br/>';
echo '<br/>';
echo '<br/>';
echo '<br/>';
echo '<br/>';
echo '<div id="data_description">';


$experiment_cursor=find_all_xp_name($samplesCollection);
$experiment_table_string="";

###DISPLAY EXPERIMENT LIST

//$table_string.='<h1>COBRA Datasets</h1>';
 
$experiment_table_string.='<ul>';
 //$table_string.='<a href=experiments.php>test</a>';
foreach($experiment_cursor as $line) {
 	$title=$line['name'];
 	//echo str_replace(' ','\s',$title);
	$experiment_table_string.='<li value='.$title.'><a href=experiments.php?xp='.str_replace(' ','\s',$title).'>'.$title.'</a></li>';
}
 //makeDatatableFromFind($cursor);
$experiment_table_string.='</ul>';
add_accordion_panel($experiment_table_string, "Experiments","Experiments_lists");

echo'<br/>';

/*##MAPPING LIST

$table_string.='<h2> Mapping lists</h2> <div class="container"><ul>';
 //$table_string.='<a href=experiments.php>test</a>';
 foreach($cursor as $line) {
	#$table_string.='<li value='.$line['type'].'><a href=mappings.php?type='.$line['type'].'>'.$line['type'].'</a></li>';
	$table_string.='<li value='.$line['type'].'>'.$line['type'].'('.$line['species'].')</a></li>';

}
 //makeDatatableFromFind($cursor);
$table_string.='</div>';
*/
$mapping_table_string="";
###MAPPING REQUEST

$mapping_cursor=find_all_mappings($mappingsCollection);


###MAPPING TABLE

$mapping_table_string.='<table id="mapping" class="table table-hover">';
//$table_string.='<table id="mappingtable" class="table table-bordered table-hover" cellspacing="0" width="100%">';
$mapping_table_string.='<thead><tr>';
	
	//recupere le titre
	//$table_string.='<th>type</th>';
	$mapping_table_string.='<th>Source type</th>';
	$mapping_table_string.='<th>Version</th>';
	$mapping_table_string.='<th>Target Type</th>';
	$mapping_table_string.='<th>Version</th>';
	$mapping_table_string.='<th>Organism</th>';

	
	//fin du header de la table
$mapping_table_string.='</tr></thead>';
	
//Debut du corps de la table
$mapping_table_string.='<tbody>';

foreach($mapping_cursor as $line) {
$mapping_table_string.='<tr>';
	//$table_string.='<td>'.$line['type'].'</td>';
	$mapping_table_string.='<td>'.$line['src'].'</td>';
	$mapping_table_string.='<td>'.$line['src_version'].'</td>';
	$mapping_table_string.='<td>'.$line['tgt'].'</td>';
	$mapping_table_string.='<td>'.$line['tgt_version'].'</td>';
	$mapping_table_string.='<td>'.$line['species'].'</td>';
$mapping_table_string.='</tr>';

}
$mapping_table_string.='</tbody></table>';


add_accordion_panel($mapping_table_string, "Mappings", "mapping_table");
echo'<br/>';

$species_table_string="";

###SPECIES REQUEST

$species_cursor=find_all_species($speciesCollection);


###SPECIES TABLE



$species_table_string.='<table id="species" class="table table-hover">';
$species_table_string.='<thead><tr>';
	
	//recupere le titre
	$species_table_string.='<th>Full name</th>';
	$species_table_string.='<th>Species</th>';
	$species_table_string.='<th>Aliases</th>';
	$species_table_string.='<th>Top level</th>';
	//$table_string.='<th>tgt</th>';
	//$table_string.='<th>tgt_version</th>';
	//$table_string.='<th>species</th>';

	
	//fin du header de la table
$species_table_string.='</tr></thead>';
	
//Debut du corps de la table
$species_table_string.='<tbody>';

foreach($species_cursor['result'] as $line) {
$species_table_string.='<tr>';
	$species_table_string.='<td>'.$line['full_name'].'</td>';
	$species_table_string.='<td>'.$line['species'].'</td>';
	if (is_array($line['aliases'])){
		$species_table_string.='<td>';
		for ($i=0;$i<count($line['aliases']);$i++){
		//foreach ($line['aliases'] as $alias){
			if ($i==count($line['aliases'])-1){
				$species_table_string.=$line['aliases'][$i];
			}
			else{
				$species_table_string.=$line['aliases'][$i].', ';
			}
			
			//echo $alias.' ';
		}
		$species_table_string.='</td>';
		
	}
	else{
		$species_table_string.='<td>'.$line['aliases'].'</td>';
		}
	$species_table_string.='<td>'.$line['top'].'</td>';
	//$table_string.='<td>'.$line['tgt'].'</td>';
	//$table_string.='<td>'.$line['tgt_version'].'</td>';
	//$table_string.='<td>'.$line['species'].'</td>';
$species_table_string.='</tr>';

}
$species_table_string.='</tbody></table>';
add_accordion_panel($species_table_string, "Species", "Species_table");
echo'<br/>';

$virus_table_string="";

###VIRUSES REQUEST

$virus_cursor=find_all_viruses($virusesCollection);



###VIRUSES TABLE



$virus_table_string.='<table id="virus" class="table table-hover">';

//$table_string.='<table id="virus" class="table table-bordered" cellspacing="0" width="100%">';
$virus_table_string.='<thead><tr>';
	
	//recupere le titre
	$virus_table_string.='<th>full name</th>';
	$virus_table_string.='<th>species</th>';
	$virus_table_string.='<th>Aliases</th>';
	$virus_table_string.='<th>top level</th>';
	//$table_string.='<th>tgt</th>';
	//$table_string.='<th>tgt_version</th>';
	//$table_string.='<th>species</th>';

	
	//fin du header de la table
$virus_table_string.='</tr></thead>';
	
//Debut du corps de la table
$virus_table_string.='<tbody>';

foreach($virus_cursor['result'] as $line) {
$virus_table_string.='<tr>';
	$virus_table_string.='<td>'.$line['full_name'].'</td>';
	$virus_table_string.='<td>'.$line['species'].'</td>';
	if (is_array($line['aliases'])){
		$virus_table_string.='<td>';
		for ($i=0;$i<count($line['aliases']);$i++){
		//foreach ($line['aliases'] as $alias){
			if ($i==count($line['aliases'])-1){
				$virus_table_string.=$line['aliases'][$i];
			}
			else{
				$virus_table_string.=$line['aliases'][$i].', ';
			}
			
			//echo $alias.' ';
		}
		$virus_table_string.='</td>';
		
	}
	else{
		$virus_table_string.='<td>'.$line['aliases'].'</td>';
		}
	$virus_table_string.='<td>'.$line['top'].'</td>';
	//$table_string.='<td>'.$line['tgt'].'</td>';
	//$table_string.='<td>'.$line['tgt_version'].'</td>';
	//$table_string.='<td>'.$line['species'].'</td>';
$virus_table_string.='</tr>';

}
$virus_table_string.='</tbody></table>';
add_accordion_panel($virus_table_string, "Viruses", "virus_table");
echo'<br/>';


echo'</div>';





#'type'=>1,'src'=>1,'tgt'=>1,'src_version'=>1,'tgt_version'=>1)
#new_cobra_species_container();


new_cobra_footer();


?>
<script type="text/javascript" class="init">
$(document).ready(function() {
	$('#mappingtable').dataTable( {
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
	$('#speciestable').dataTable( {
		"scrollX": false,
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
	$('#virustable').dataTable( {
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
    $('#mapping').DataTable( {
        responsive: true,
        
		
        
    } );
    $('#species').DataTable( {
        responsive: true,
        
		
        
    } );
    $('#virus').DataTable( {
        responsive: true,
        
		
        
    } );

} );

</script>
