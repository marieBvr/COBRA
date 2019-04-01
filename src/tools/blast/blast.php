
<?php
require '../../session/maintenance-session.php';
require '../../functions/html_functions.php';
require '../../functions/php_functions.php';
require '../../functions/mongo_functions.php';
require '../../session/control-session.php';

new_cobra_header("../../..");
new_cobra_body(is_logged($_SESSION['login']),"Tools","section_tools","../../..");

if ((isset($_POST['search'])) && ($_POST['search']!='')){

//if (((isset($_POST['search'])) && ($_POST['search']!='')) && ((isset($_POST['sequence'])) && ($_POST['sequence']!=''))){


    $search_id=control_post(htmlspecialchars($_POST['search']));
    $species=control_post(htmlspecialchars($_POST['species']));
    //error_log('Here is the search id: '.$search_id);

    //$sequence=control_post(htmlspecialchars($_POST['sequence']));
    $db=mongoConnector();

	
    $sequencesCollection = new Mongocollection($db, "sequences");
    $mappingsCollection = new Mongocollection($db, "mappings");
    $full_mappingsCollection = new Mongocollection($db, "full_mappings");
    $jobsCollection = new Mongocollection($db, "jobs");

    
    $sequence_metadata=$sequencesCollection->find(array('mapping_file.Transcript ID'=>str_replace("__", ".",$search_id)),array('mapping_file.$'=>1));
    $uid=substr(str_shuffle(MD5(microtime())), 0, 20);
    foreach ($sequence_metadata as $data) {
        foreach ($data as $key=>$value) {    
            if ($key==="mapping_file"){
                foreach ($value as $values) {
                    

                    // on place le contenu dans une variable. (exemple hein ^^)

                    $contenu = '>'.str_replace("__", ".",$search_id)."\n";
                    $contenu .= $values['Transcript Sequence'];

                    // on ouvre le fichier en écriture avec l'option a
                    // il place aussi le pointeur en fin de fichier (il tentera de créer aussi le fichier si non existant)
                    $h = fopen('/tmp/'.$uid.'.fasta', "w+r") or die("Unable to open file!");
                    fwrite($h, $contenu);
                    fclose($h);
                }
            }
        }
    }
    list($html,$tab)=run_blast($uid);
    //error_log($data);
    
    
    
    //Here add code to populate jobs Mongo collection,
    date_default_timezone_set("Europe/Paris");
    $today = date("F j, Y, g:i a");
    $document = array("job_owner_firstname" => $_SESSION['lastname'],
                      "job_owner_lastname" => $_SESSION['firstname'],
                      "date" => $today,
                      "query_id"=> str_replace("__", ".",$search_id),
                      "job_data" => $html
                     );
    $jobsCollection->insert($document);
    $max_hits=0;
    $lines=  explode("\n",$tab);
    if (count($max_hits)!==0){
        echo '<table id="blast_results" class="table table-hover dataTable no-footer">'
        . '<thead>'
         . '<tr>'
                . '<th>Query seq id</th>'
                . '<th>Subject gene id</th>'
                . '<th>Subject transcript id</th>'
                . '<th>Ident (%)</th>'
                . '<th>E value</th>'
         . '</tr>'
        . '</thead>'
        . '<tbody>';
            
            foreach ($lines as $line) {
                echo '<tr>';
                $rows=  explode("\t",$line);
                //error_log($rows[0]."--------------------------------");
                echo '<td>'.$rows[0].'</td>';
                $gene=explode("|", $rows[1]);
                //echo '<td>'.$gene[0].'</td>';
                echo '<td><a class="nowrap" target = "_blank" href="../../../src/Multi-results.php?organism=All+species&search='.str_replace(".", "__",$gene[0]).'">'.$gene[0].'</a></td>';
                echo '<td><a class="nowrap" target = "_blank" href="../../../src/Multi-results.php?organism=All+species&search='.str_replace(".", "__",$gene[1]).'">'.$gene[1].'</a></td>';
                echo '<td>'.$rows[2].'</td>';
                echo '<td>'.$rows[3].'</td>';
                
//                foreach ($rows as $row) {
//                    //echo '<td>'.$value.'</td>';
//                    error_log($row);
//                }
                
                echo '</tr>';
            }
           
     echo '</tbody>'
          . '</table>';
        
    }
    else{
        echo '<p class="no_results"> Results: No hits found </br></p>';  
    }
}
new_cobra_footer();	

