<?php
define('PATH', $_SERVER['DOCUMENT_ROOT']);




function new_cobra_header($path='null'){
    echo '<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Cobra Database">
    <!-- font Awesome -->
    <link href="'.$path.'/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="'.$path.'/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    
    
    <link rel="stylesheet" type="text/javascript" href="'.$path.'/js/pointer_events_polyfill.js"></script>

    <link href="'.$path.'/css/AdminLTE.css" rel="stylesheet" type="text/css" />

    <!-- Include iCheck skin -->
    <link rel="stylesheet" href="'.$path.'/css/iCheck/all.css" />
        

    <link rel="stylesheet" type="text/css" href="'.$path.'/js/Bootstrap-3.3.6/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="'.$path.'/js/DataTables-1.10.12/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="'.$path.'/js/Buttons-1.2.1/css/buttons.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="'.$path.'/css/cobra_styles2.css"/>	
    <link rel="stylesheet" href="https://unpkg.com/ionicons@4.5.5/dist/css/ionicons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

        
   <!-- <link rel="stylesheet" type="text/css" href="'.$path.'/js/Responsive-2.1.0/css/responsive.bootstrap.min.css"/>
    <script type="text/javascript" src="'.$path.'/js/Responsive-2.1.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="'.$path.'/js/Responsive-2.1.0/js/responsive.bootstrap.min.js"></script>-->

    <script type="text/javascript" src="'.$path.'/js/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="'.$path.'/js/Bootstrap-3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="'.$path.'/js/JSZip-2.5.0/jszip.min.js"></script>
    <script type="text/javascript" src="'.$path.'/js/pdfmake-0.1.18/build/pdfmake.min.js"></script>
    <script type="text/javascript" src="'.$path.'/js/pdfmake-0.1.18/build/vfs_fonts.js"></script>
    <script type="text/javascript" src="'.$path.'/js/DataTables-1.10.12/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="'.$path.'/js/DataTables-1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="'.$path.'/js/Buttons-1.2.1/js/dataTables.buttons.min.js"></script>    
    <script type="text/javascript" src="'.$path.'/js/Buttons-1.2.1/js/buttons.bootstrap.min.js"></script>
    <script type="text/javascript" src="'.$path.'/js/Buttons-1.2.1/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="'.$path.'/js/Buttons-1.2.1/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="'.$path.'/js/Buttons-1.2.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="'.$path.'/js/Buttons-1.2.1/js/buttons.print.min.js"></script>
    <!--script src="https://d3js.org/d3.v5.min.js"></script-->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/cytoscape/3.5.2/cytoscape.min.js"></script>
    <script type="text/javascript" src="'.$path.'/js/network.js"></script>
    <script type="text/javascript" src="'.$path.'/js/functions.js"></script>
    <script type="text/javascript" src="'.$path.'/js/require.min.js"></script>
        
    <script type="text/javascript" src="'.$path.'/css/Highcharts-4.1.8/js/highcharts.js"></script>
    <script type="text/javascript" src="'.$path.'/css/Highcharts-4.1.8/js/highcharts-more.js" ></script>
    <script type="text/javascript" src="'.$path.'/css/Highcharts-4.1.8/js/modules/funnel.js"></script>
    <script type="text/javascript" src="'.$path.'/css/Highcharts-4.1.8/js/modules/exporting.js"></script>
    <script type="text/javascript" src="'.$path.'/css/Highcharts-4.1.8/js/modules/heatmap.js"></script>
    <script type="text/javascript" src="'.$path.'/css/Highcharts-4.1.8/js/modules/data.js"></script>
    <script type="text/javascript" src="'.$path.'/js/app.min.js"></script> 
    <title>COBRA</title>';
    // include($path."/src/functions/piwik.php");
    echo '</head>';
}
function add_ajax_accordion_panel($function='null',$accordion_id='null', $body_panel_id='null',$loading_id='null',$area_id='null'){
    echo '<div class="panel-group" id="accordion_documents_'.$accordion_id.'">
            <div class="panel panel-default">
                <div class="panel-heading" onclick="'.$function.'" id="accordion-score">

                    <a class="accordion-toggle collapsed" href="#'.$body_panel_id.'" data-parent="#accordion_documents_'.$accordion_id.'" data-toggle="collapse">
                            <strong>Top ranking susceptibility genes using COBRA scoring function</strong>
                            
                    </a>
                </div>
                <center>
                    <div class="'.$loading_id.'" style="display: none"></div>
                </center>
                <div class="panel-body panel-collapse collapse" id="'.$body_panel_id.'">
                    <div class="'.$area_id.'"> 

                    <!--here comes the table div-->
                    </div>
                </div>
            </div>
        </div>
    <div class="shift_line"></div>';
}
function add_accordion_panel($table_string,$panel_title='null',$unique_id='null'){
    echo'<div class="panel-group" id="accordion_documents'.$unique_id.'" style="border-radius: 10px;display: table;margin: auto auto;vertical-align:middle;display:inline-block;width:100%;">
                <div class="panel panel-default">
                    <div class="panel-heading">  
                            <a class="accordion-toggle collapsed" href="#'.$unique_id.'" data-parent="#accordion_documents'.$unique_id.'" data-toggle="collapse">
                                <strong>'.$panel_title.'</strong>
                            </a>				
                    </div>
                    <div class="panel-body panel-collapse collapse" id="'.$unique_id.'">
                        
                            
                           '.$table_string.'
                        
                    </div>
                </div>
            </div>    
     <br/>';
}

function new_cobra_body($IsLogged='null', $type='null',$section_id='null',$path='null'){
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    //<li';if($uri == "/src/wiki"){ echo 'class="active"'; } echo '>
    echo'
    <body class="skin-black">
        <!-- header logo: style can be found in header.less -->
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
        	<aside id="menu" class="left-side sidebar-offcanvas">     
            <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                <!-- Sidebar user panel -->';
    if ($IsLogged){
    
                echo'<a href="'.$path.'/src/search/">
                         <div class="cobra-logo">
                             <img src="'.$path.'/images/cobra-icon.png" alt="COBRA logo" />
                             <p>COBRA</p>
                         </div>
                      </a>

                     <!-- sidebar menu: : style can be found in sidebar.less -->
                     <ul class="sidebar-menu"  >
                         <li';if($uri == "/" || $uri == "/index.php"){ echo ' class="active"'; } echo '>
                             <a href="'.$path.'/">
                                <i class="fa fa-home"></i> 
                                <span>Home</span>
                             </a>
                         </li>
                          <!--<li id="dropdown">
                            <a data-toggle="collapse" href="#dropdown-lvl1">
                                <span class="glyphicon glyphicon-user"></span> Search history <span class="caret"></span>
                            </a>

                            
                            <div id="dropdown-lvl1" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <ul class="nav navbar-nav">
                                        <li><a href="#">Link</a></li>
                                        <li><a href="#">Link</a></li>
                                        <li><a href="#">Link</a></li>
                                        <li><a href="#">Link</a></li>
                                        <li><a href="#">Link</a></li>
                                        <li><a href="#">Link</a></li>
                                       
                                        <li class="panel panel-default" id="dropdown">
                                            <a data-toggle="collapse" href="#dropdown-lvl2">
                                                <span class="glyphicon glyphicon-off"></span> Sub Level <span class="caret"></span>
                                            </a>
                                            <div id="dropdown-lvl2" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <ul class="nav navbar-nav">
                                                        <li><a href="#">Link</a></li>
                                                        <li><a href="#">Link</a></li>
                                                        <li><a href="#">Link</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>-->
                         <li';if($uri == "/src/search" || $uri == "/"){ echo ' class="active"'; } echo '>
                             <a href="'.$path.'/src/search/">
                                <i class="fa fa-search"></i> 
                                <span>Search</span>
                             </a>
                         </li>
                         <li>
                             <a href="'.$path.'/src/description/">
                                 <i class="fa fa-leaf"></i> <span>Data</span>
                             </a>
                         </li>
                         <li>
                             <a href="'.$path.'/src/users/user.php?firstname='.$_SESSION['firstname'].'&lastname='.$_SESSION['lastname'].'">
                                 <i class="glyphicon glyphicon-user"></i> <span>User</span>
                             </a>
                         </li>
                         <!--<li >
                             <a href="'.$path.'/src/tools/">
                                 <i class="fa fa-cogs"></i> <span>Tools</span>
                             </a>
                         </li>-->
                         <li >
                             <a href="'.$path.'/src/docs/">
                                <i class="fa fa-upload"></i> <span>Uploads</span>
                             </a>
                         </li>
                         ';
                         if ($_SESSION['firstname']==="Dartigues"){
                           echo'<li >
                             <a href="'.$path.'/src/todo/">
                                <i class="fa fa-upload"></i> <span>TODO list</span>
                             </a>
                               </li>';
                         }
              echo' </ul>
                </section>
            <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">     
                <!-- Content Header (Page header) -->
                <section class="content-header">';
            	
            	
                echo '<h1>'.$type.'<small>COBRA a plant virus interaction database</small></h1>';
            	
            	
            	
            	echo '<ol class="breadcrumb">
                        <!--<li><a href="https://services.cbib.u-bordeaux2.fr/cobra/"><i class="fa fa-dashboard"></i> Home</a></li>-->
                        <!--<li><a href="https://services.cbib.u-bordeaux2.fr/cobra/src/description/">description</a></li>-->
                        <!--<li><a href="https://services.cbib.u-bordeaux2.fr/cobra/wiki/">wiki home</a></li>-->
                        <li><a href="'.$path.'/">Home page</a></li>
                        <li><a href="'.$path.'/src/users/user.php?firstname='.$_SESSION['firstname'].'&lastname='.$_SESSION['lastname'].'">'.$_SESSION['firstname'].' '.$_SESSION['lastname'].'</a></li>
                        <li><a href="'.$path.'/login.php?act=logout">Logout</a></li>
                        </ol>';
                
                echo '
                  
               	</section>

                <!-- Main content -->
                <div class="shift_line"></div>
                <section class="container" id="'.$section_id.'">';
    }
    else{
        echo'    <a href="'.$path.'/src/search/">
                <div class="cobra-logo">
                    <img src="'.$path.'/images/cobra-icon.png" alt="COBRA logo" />
                    <p>COBRA</p>
                </div></a>

                <!-- sidebar menu: : style can be found in sidebar.less -->
            	<ul class="sidebar-menu">
                  <li';if($uri == "/login.php"){ echo ' class="active"'; } echo '>
                    <a href="'.$path.'/login.php">
                       <i class="fa fa-home"></i> 
                       <span>Login</span>
                    </a>
               	</li>
                  
               </ul>
            </section>
            <!-- /.sidebar -->
         </aside>

      	<!-- Right side column. Contains the navbar and content of the page -->
 	<aside class="right-side">     
            <!-- Content Header (Page header) -->
            <section class="content-header">';
            	
            	
            echo '<h1>'.$type.'<small>COBRA a plant virus interaction database</small></h1>';
            echo '<ol class="breadcrumb">';
                echo '<li><a href="'.$path.'/login.php">Login</a></li>

                  </ol>
            </section>

            <!-- Main content -->
            <div class="shift_line"></div>
            <section class="container" id="'.$section_id.'">';
    }
                  //$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                //echo $uri;
                  //';if($uri == "/src/wiki"){ echo 'class="active"'; } echo '

}

function new_href($link='null'){
	echo '<a href=\"'.$link.'\">'.$link.'</a>';
}

function new_cobra_footer($val=false){
echo'
    </section>
        <section class="col-md-12" id="footer">
            <h1>  </h1>
             <p class="text-muted" style="text-align: left">
                <br/>
                Database and website created by the <a href="http://www.cbib.u-bordeaux.fr/">CBiB</a> and <a href="https://www6.bordeaux-aquitaine.inra.fr/bfp/Recherche/Equipe-de-Virologie-vegetale">INRA</a>
            </p>';
        if ($val){
            echo'
            <p class="text-muted" style="text-align: right">Original template <a href="http://almsaeedstudio.com/AdminLTE/">AdminLTE Dashboard and Control Panel Template</a> by <a href="http://almsaeedstudio.com">Almaseed Studio</a></p>';
        }
        
echo ' </section>
    </aside>
</div>
</body>
</html>';

}


function new_input_file(){
	echo '
			<div class="form-group bg-gray" ;style="border: 2px solid grey">
				<label for="exampleInputFile">File input</label>
				<input type="file" id="exampleInputFile">
				<p class="help-block">enter list of Ids.</p>
			</div>';

}
function new_cobra_species_container(){
echo'

	<div class="full_species">
        <div class="static_favourite_species">
        	<h2>Popular species</h2>
        	<div class="species_list_container species-list">
      		<div class="form-field ff-multi">
					<div class=ff-inline ff-right">
      
      			<div class="col-xs-6">
      			<div class="species-box">
        			<a href="Arabidopsis_thaliana/Info/Index">
          				<span class="sp-img"><img src="/images/A_thaliana.jpg" alt="Arabidopsis thaliana" title="Browse Arabidopsis thaliana" height="48" width="48" /></span>
          				<span>Arabidopsis thaliana</span>
         
        			</a>
        			
      			</div>
      			</br>
      			<div class="species-box">
        			<a href="Arabidopsis_thaliana/Info/Index">
          				<span class="sp-img"><img src="/images/barley.jpg" alt="Arabidopsis thaliana" title="Browse Arabidopsis thaliana" height="48" width="48" /></span>
          				<span>Hordeum vulgare</span>
        			</a>
        			
      			</div>
      			</br>
      			<div class="species-box">
        			<a href="Arabidopsis_thaliana/Info/Index">
          				<span class="sp-img"><img src="/images/tomato.jpg" alt="Arabidopsis thaliana" title="Browse Arabidopsis thaliana" height="48" width="48" /></span>
          				<span>Solanum lycopersicum</span>
        			</a>
        			
      			</div>
      			</div>
      			<div class="col-xs-6">
      			</br>
      			<div class="species-box">
        			<a href="Arabidopsis_thaliana/Info/Index">
          				<span class="sp-img"><img src="/images/melon.jpg" alt="Arabidopsis thaliana" title="Browse Arabidopsis thaliana" height="48" width="48" /></span>
          				<span>Cucumis melo</span>
        			</a>
        			
      			</div>
      			</br>
      			<div class="species-box">
        			<a href="Arabidopsis_thaliana/Info/Index">
          				<span class="sp-img"><img src="/images/prunus.jpg" alt="Arabidopsis thaliana" title="Browse Arabidopsis thaliana" height="48" width="48" /></span>
          				<span>Prunus domestica</span>
        			</a>
        			
      			</div>
      			</div>
      			</div>
      		</div>	
      			
    		</div>
    	</div>
    </div>
';
}
function display_cy_panel($element, $path){
    // SVG container
    echo'
    <div class="svg-container">
        <div style="float:left;height:650px;width:1000px;display:block;" id="cy""></div>
    </div>';
    // Accordion and panels of network information
    echo'
    <div class="nav-network" id="accordion" style="float:left;width:400px;">
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#display-info" aria-expanded="true" aria-controls="display-info">
            <i class="fas fa-info-circle fa-3x"></i>
        </button>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#display-query" aria-expanded="false" aria-controls="display-query">
            <i class="fas fa-search fa-3x"></i>
        </button>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#display-config" aria-expanded="false" aria-controls="display-config">
            <i class="fas fa-cog fa-3x"></i>
        </button>
        <div class="collapse" id="display-info" style="float:left;width:400px;">
            <br />
            <div class="alert alert-info">Click on a <b>node</b> or an <b>edge</b> to display information about it.</div>
        </div>
        <div class="collapse" id="display-query" style="float:left;width:400px;">
            <br />
            <div class="panel panel-default">
                <div class="panel-heading">Query</div>
                <div class="panel-body">Queries</div>
            </div>
        </div>
        <div class="collapse" id="display-config" style="float:left;width:400px;">
            <br />
            <div class="panel panel-default">
                <div class="panel-heading">Setting</div>
                <div class="panel-body">
                    <div id=checkbox-interaction>
                        <b>Data interaction:</b>
                        <div class="checkbox">
                            <label for="pv_check">
                              <input type="checkbox" id="pv_check" checked=true disabled> Plant Virus
                            </label>
                        </div>
                        <div class="checkbox">
                            <label for="pp_check">
                              <input type="checkbox" id="pp_check" data-element="'.$element.'" data-path="'.$path.'"> Plant Plant
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}







