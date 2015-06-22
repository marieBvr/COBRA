<?php

function new_cobra_header(){
echo'
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>COBRA</title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<!-- bootstrap 3.0.2 -->
<link href="/database/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<!-- font Awesome -->
<link href="/database/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<!-- Ionicons -->
<link href="/database/css/ionicons.min.css" rel="stylesheet" type="text/css" />

<!-- Theme style -->
<link href="/database/css/AdminLTE.css" rel="stylesheet" type="text/css" />

<!-- Datatable style -->
<link rel="stylesheet" type="text/css" href="/database/css/dataTables.bootstrap.css">

<!-- Cobra style -->
<link rel="stylesheet" type="text/css" href="/database/css/cobra_styles.css">	

<!-- tab icon style -->
<!-- <link rel="shortcut icon" href="http://www.votresite.com/favicon.ico"> -->

<!-- jQuery 2.0.2 -->
<script src="/database/js/jquery.min.js"></script>

<!-- Bootstrap -->
<script src="/database/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript" src="/database/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="/database/js/dataTables.bootstrap.js"></script>

<!-- AdminLTE App -->
<script src="/database/js/app.js" type="text/javascript"></script>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
<!--[if lt IE 9]>
<!--  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script> -->
<!--  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script> -->
<![endif]-->
</head>';
}


function new_cobra_body($IsLogged='null', $type='null'){
echo'

    
    <body class="skin-blue">
    <!-- header logo: style can be found in header.less -->

    	<div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        	<aside class="left-side sidebar-offcanvas">     
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
               <!-- Sidebar user panel -->
               <div class="cobra-logo">
               	<img src="/database/images/cobra-icon.png" />
                  <p>COBRA</p>
               </div>

               <!-- sidebar menu: : style can be found in sidebar.less -->
            	<ul class="sidebar-menu">
                  <li>
							<a href="/database/">
								<i class="fa fa-home"></i> 
								<span>About COBRA</span>
							</a>
               	</li>
                  <li>
                     <a href="/database/src/search/">
                        <i class="fa fa-search"></i> 
                        <span>Quick Search</span>
                     </a>
                  </li>
                  <li>
                     <a href="/database/src/description/">
                        <i class="fa fa-leaf"></i> <span>Dataset and Statistics</span>
                     </a>
                  </li>
                  <li>
                     <a href="/database/wiki/">
                        <i class="fa fa-info"></i> <span>Wiki</span>
                     </a>
                  </li>
                  <li>
                     <a href="/database/src/tools/">
                        <i class="fa fa-cogs"></i> <span>Tools</span>
                     </a>
                  </li>
                  <li>
                     <a href="/database/src/info/WebSite/">
                        <i class="fa fa-question"></i> <span>Help</span>
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
            	
            	
            	
            	echo '<ol class="breadcrumb">
                  <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li><a href="/database/src/description/">description</a></li>
                  <li><a href="/wiki/">wiki home</a></li>
                  <li><a href="/database/src/search/">Quick search</a></li>
                  ';if ($IsLogged){echo '
                  <li><a href="/database/src/users/user.php?firstname='.$_SESSION['firstname'].'&lastname='.$_SESSION['lastname'].'">'.$_SESSION['firstname'].' '.$_SESSION['lastname'].'</a></li>';
                  echo '<li><a href="/database/login.php?act=logout">Logout</a></li>';}
                  else{
                  	echo '<li><a href="/database/login.php">Login</a></li>';
                  }
                  echo '
                  
               	
               </ol>
            </section>

            <!-- Main content -->
            <section class="content">
';

}



function new_content_header(){

}
function new_href($link='null'){
	echo '<a href=\"'.$link.'\">'.$link.'</a>';
}
function new_cobra_footer(){
echo'

                    <hr />
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-lg-12 connectedSortable">
                            <p class="text-muted" style="text-align: right;">
                                Original template <a href="http://almsaeedstudio.com/AdminLTE/">AdminLTE Dashboard and Control Panel Template</a> by <a href="http://almsaeedstudio.com">Almaseed Studio</a>
                            </p>
                            <p class="text-muted" style="text-align: right;">
                                Database and website created by the <a href="http://www.cbib.u-bordeaux2.fr/">CBiB</a>
                            </p>
                        </section><!-- right col -->
                    </div>
                    <!-- /.row (main row) -->
                </section>
            <!-- /.content -->
            </aside>
            <!-- /.right-side -->
        </div>
<!-- ./wrapper -->
    </body>
</html>

    
    ';

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
          				<span class="sp-img"><img src="/database/images/A_thaliana.jpg" alt="Arabidopsis thaliana" title="Browse Arabidopsis thaliana" height="48" width="48" /></span>
          				<span>Arabidopsis thaliana</span>
         
        			</a>
        			
      			</div>
      			</br>
      			<div class="species-box">
        			<a href="Arabidopsis_thaliana/Info/Index">
          				<span class="sp-img"><img src="/database/images/barley.jpg" alt="Arabidopsis thaliana" title="Browse Arabidopsis thaliana" height="48" width="48" /></span>
          				<span>Hordeum vulgare</span>
        			</a>
        			
      			</div>
      			</br>
      			<div class="species-box">
        			<a href="Arabidopsis_thaliana/Info/Index">
          				<span class="sp-img"><img src="/database/images/tomato.jpg" alt="Arabidopsis thaliana" title="Browse Arabidopsis thaliana" height="48" width="48" /></span>
          				<span>Solanum lycopersicum</span>
        			</a>
        			
      			</div>
      			</div>
      			<div class="col-xs-6">
      			</br>
      			<div class="species-box">
        			<a href="Arabidopsis_thaliana/Info/Index">
          				<span class="sp-img"><img src="/database/images/melon.jpg" alt="Arabidopsis thaliana" title="Browse Arabidopsis thaliana" height="48" width="48" /></span>
          				<span>Cucumis melo</span>
        			</a>
        			
      			</div>
      			</br>
      			<div class="species-box">
        			<a href="Arabidopsis_thaliana/Info/Index">
          				<span class="sp-img"><img src="/database/images/prunus.jpg" alt="Arabidopsis thaliana" title="Browse Arabidopsis thaliana" height="48" width="48" /></span>
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



















?>
