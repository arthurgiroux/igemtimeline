<?php
    include_once('config.php');
    include_once('bootstrap.php');

	$dbh = new PDO('mysql:host='.$host.';dbname='.$database, $user, $password);

	$dbh->exec("SET NAMES UTF8");

?>



<!DOCTYPE html>
<html>
  <head>
    <title>iGEM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">

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

    <div style="text-align: right;">
    	<a href="/igem/add.php" class="btn btn-primary">Add</a>
	</div>

	<table class="table table-striped">
	  <thead>
	  	<tr>
	  		<th>Date</th>
	  		<th>Title</th>
	  		<th>Team</th>
	  		<th>Action</th>
	  	</tr>
	  </thead>
	  <tbody>

	    <?php
	    	$select = $dbh->query('SELECT * FROM `igem_posts` ORDER BY `id` DESC');

	    	while ($item = $select->fetch()) {
	    		?>
	    			<tr>
	    				<td><?php echo htmlspecialchars($item['date']); ?></td>
	    				<td><?php echo htmlspecialchars($item['title']); ?></td>
	    				<td><?php echo htmlspecialchars($item['tag']); ?></td>
	    				<td><a href="/igem/edit.php?id=<?php echo htmlspecialchars($item['id']); ?>" class="btn btn-primary">Edit</a></td>
	                </tr>
	    		<?php
	    	}
	    ?>
	    </tbody>
	</table>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
