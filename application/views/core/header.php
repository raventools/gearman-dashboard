<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gearman Dashboard</title>

    <link href="/assets/libraries/bootstrap-3.1.1/css/bootstrap.min.css" rel="stylesheet">
  </head>

  <body>
  	<nav class="navbar navbar-default" role="navigation">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <a class="navbar-brand" href="#servers">Gearman Dashboard</a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
	        <li><a href="#servers">Servers</a></li>
	        <li><a href="#metapackages">Metapackages</a></li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Workers <b class="caret"></b></a>
	          <ul class="dropdown-menu">
	            <li><a href="#workers/running">Running</a></li>
	            <li><a href="#workers/idle">Idle</a></li>
	            <li><a href="#workers/package">By Package</a></li>
	          </ul>
	        </li>
	        <li><a href="#processes">Processes</a></li>
	        <li><a href="#errors">Errors/Exceptions</a></li>
	      </ul>
	      
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
