<?php
require_once "config.php";
if(isset($_GET['logout'])){
  unset($_SESSION['user']);
  header('Location: login.php');
}

if(!empty($_POST)){

  if($_POST['user']== GUI_USERNAME && $_POST['pass']==GUI_PASSWORD){
    $_SESSION['user'] = GUI_USERNAME;
    header('Location: index.php');
  }else{
    header('Location: login.php?error=invalid_userpass');
  }

}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Aria2 PHP Manager</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/styles.css" rel="stylesheet">
      <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    </head>
	<body>
<!-- Header -->
<div id="top-nav" class="navbar navbar-inverse navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-toggle"></span>
      </button>
      <a class="navbar-brand" href="index.php">Aria2 PHP Client</a>
    </div>

  </div><!-- /container -->
</div>
<!-- /Header -->

<!-- Main -->
<div class="container">

  <?php
  if(isset($_GET['error'])){?>

      <div class="alert alert-danger">
        Invalid username or password
      </div>
  <?php
  }
  ?>

  <form method="post">
    Username: <input class="form-control" name="user">
    <br>
    Password: <input class="form-control" name="pass" type="password">
    <br>
    <input type="submit" value="Login" class="btn btn-success">
  </form>

</div><!--/container-->
<!-- /Main -->


	<!-- script references -->
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>