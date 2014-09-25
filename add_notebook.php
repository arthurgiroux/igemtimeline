<?php
	include_once('config.php');
 	include_once('bootstrap.php');


	$dbh = new PDO('mysql:host='.$host.';dbname='.$database, $user, $password);

	$dbh->exec("SET NAMES UTF8");

	if (!empty($_POST)) {

		$insertQuery = $dbh->prepare("INSERT INTO `igem_notebook`(`title` ,`text` ,`date` ,`team`) VALUES(?, ?, ?, ?)");
		
		$data = array($_POST['title'],
			$_POST['text'],
			$_POST['date'],
			$_POST['team'],
		);

		$insertQuery->execute($data);

		updateNotebook($dbh, $_POST['team'], $igem_username, $igem_password);
		header('Location: notebook.php');
	}

?>



<!DOCTYPE html>
<html>
  <head>
    <title>iGEM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/datepicker.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-tagsinput.css" rel="stylesheet" media="screen">
	<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
	<script>tinymce.init({
    selector: "textarea",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});</script>


    <style type="text/css">
		body {
		  padding-top: 80px;
		}
    	td img {
    		padding: 2px;
    	}
    	table {
    		margin-top: 20px;
    	}
    </style>
  </head>
  <body>
     <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/igem/">iGEM</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Timeline</a></li>
            <li class="active"><a href="notebook.php">Notebook</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">
		<form role="form" method="post">
		  <div class="form-group">
		    <label for="title">Title</label>
		    <input type="text" class="form-control" id="title" name="title" />
		  </div>
		  <div class="form-group">
		    <label for="date">Date</label>
		    <input type="text" class="form-control datepicker" id="date" name="date" />
		  </div>
		  <div class="form-group">
		    <label for="team">Team</label>
			<select class="form-control" name="team">
				<option value="Yeast">Yeast</option>
				<option value="Bacteria">Bacteria</option>
				<option value="Microfluidics">Microfluidics</option>
				<option value="I.T">I.T</option>
			</select>
		  </div>
		  <div class="form-group">
		    <label for="text">Text (<a href="http://2014.igem.org/Special:Upload" target="_blank">Upload an image iGEM's server</a>)</label>
		    <textarea class="form-control" rows="20" name="text"></textarea>
		  </div>

		  <div style="text-align: center;">
		  	<button type="submit" class="btn btn-default">Submit</button>
		  </div>
		</form>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
		$(document).ready(function() {
			$('.datepicker').datepicker({ format:'yyyy-mm-dd' });
		});
    </script>
  </body>
</html>