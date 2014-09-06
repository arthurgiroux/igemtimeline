<?php
	include_once('config.php');
 	include_once('bootstrap.php');

	$dbh = new PDO('mysql:host='.$host.';dbname='.$database, $user, $password);

	$dbh->exec("SET NAMES UTF8");

	if (!empty($_POST)) {

		$insertQuery = $dbh->prepare("UPDATE `igem_posts` SET `title`=?, `text`=?, `date`=?, `tag`=?, `asset`=?, `caption`=? WHERE id=?");
		
		$data = array(
			$_POST['title'],
			$_POST['text'],
			$_POST['date'],
			$_POST['tag'],
			$_POST['asset'],
			$_POST['caption'],
			$_GET['id']
		);

		$insertQuery->execute($data);

		updateIGEM(getJSON($dbh), $igem_username, $igem_password);
		header('Location: index.php');
	}
	else {
		$select = $dbh->prepare('SELECT * FROM `igem_posts` WHERE `id`=?');
		 $select->execute(array($_GET['id']));
		$item = $select->fetch();
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
 	<link href="css/font-awesome.min.css" rel="stylesheet" media="screen">
    <link href="css/summernote.css" rel="stylesheet" media="screen">
    <link href="css/summernote-bs3.css" rel="stylesheet" media="screen">
    <link href="css/datepicker.css" rel="stylesheet" media="screen">

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
      </div>
    </div>

    <div class="container">
		<form role="form" method="post">
		  <div class="form-group">
		    <label for="title">Title</label>
		    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" />
		  </div>
		  <div class="form-group">
		    <label for="text">Text</label>
		    <textarea class="form-control summernote" rows="5" name="text"><?php echo htmlspecialchars($item['text']); ?></textarea>
		  </div>
		  <div class="form-group">
		    <label for="date">Date</label>
		    <input type="text" class="form-control datepicker" id="date" name="date" value="<?php echo htmlspecialchars($item['date']); ?>" />
		  </div>
		  <div class="form-group">
		    <label for="team">Team</label>
			<select class="form-control" name="tag">
			  <option value="Yeast" <?php if ($item['tag'] == 'Yeast'): ?>selected<?php endif; ?>>Yeast</option>
			  <option value="Bacteria" <?php if ($item['tag'] == 'Bacteria'): ?>selected<?php endif; ?>>Bacteria</option>
			  <option value="Microfluidics" <?php if ($item['tag'] == 'Microfluidics'): ?>selected<?php endif; ?>>Microfluidics</option>
			  <option value="I.T" <?php if ($item['tag'] == 'I.T'): ?>selected<?php endif; ?>>I.T</option>
			</select>
		  </div>
		  <div class="form-group">
		    <label for="asset">Asset (image or twitter or youtube)</label>
		    <input type="text" class="form-control" id="asset" name="asset" value="<?php echo htmlspecialchars($item['asset']); ?>" />
		    <span class="help-block"><a href="http://igem.org/Special:Upload" target="_blank">Upload on iGEM's server</a></span>
		  </div>

		  <div class="form-group">
		    <label for="title">Caption</label>
		    <input type="text" class="form-control" id="caption" name="caption" value="<?php echo htmlspecialchars($item['caption']); ?>" />
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
    <script src="js/summernote.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
		$(document).ready(function() {
		  $('.summernote').summernote();
		  $('.datepicker').datepicker({ format:'yyyyy-mm-dd' });
		});
	</script>
  </body>
</html>