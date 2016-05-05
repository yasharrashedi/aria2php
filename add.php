<?php
require_once "config.php";
require_once "Client.php";

if(!isset($_SESSION['user'])){
  header('Location: login.php');
}

if(!empty($_POST['uri'])){
$client = new \JsonRPC\Client('http://'.HOST.':'.PORT.'/jsonrpc',SECRET);
  try{
    $response = $client->execute('aria2.addUri',array(array($_POST['uri'])));
  }catch (Exception $e){
    die($e->getMessage());
  }
  header('Location: index.php?type='.$_GET['type']);
}

if(!empty($_POST['multiuri'])){
  $client = new \JsonRPC\Client('http://'.HOST.':'.PORT.'/jsonrpc');
  $uris = explode("\n",$_POST['multiuri']);
  foreach($uris as $uri){
    try{
      $response = $client->execute('aria2.addUri',array(array(trim($uri))));
    }catch (Exception $e){
      die($e->getMessage());
    }
  }

  header('Location: index.php?type='.$_GET['type']);
}


if(!empty($_FILES['torrent'])) {

  $uploaddir = './uploads/';
  $uploadfile = $uploaddir . basename($_FILES['torrent']['name']);

  if (move_uploaded_file($_FILES['torrent']['tmp_name'], $uploadfile)) {
    $torrentContent = base64_encode(file_get_contents($uploadfile));
//    die($torrentContent);
    $client = new \JsonRPC\Client('http://'.HOST.':'.PORT.'/jsonrpc',SECRET);
    try{
      $response = $client->execute('aria2.addTorrent',array($torrentContent));
    }catch (Exception $e){
      die($e->getMessage());
    }
    header('Location: index.php?type='.$_GET['type']);
  } else {
    echo "Possible file upload attack!\n";
    die();
  }


}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Radfa Downloader</title>
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
      <a class="navbar-brand" href="index.php">Radfa Downloader</a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        
        <li class="dropdown">
          <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#">
            <i class="fa fa-user"></i> <?=$_SESSION['user']?> <span class="caret"></span></a>
          <ul id="g-account-menu" class="dropdown-menu" role="menu">
            <li><a href="login.php?logout=true"><i class="fa fa-times"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div><!-- /container -->
</div>
<!-- /Header -->

<!-- Main -->
<div class="container">

  <?php include "menu.php";?>

  <div class="row">

    <div class="col-md-12">
      <p>&nbsp;</p>

      <fieldset>
        <legend>Single File</legend>
<form method="post">
  Download Link: <input type="text" name="uri" class="form-control">
  <br>
  <input type="submit" value="Add download link" class="btn btn-success">
</form>
      </fieldset>
<p>&nbsp;</p>
      <fieldset>
        <legend>Multiple Files</legend>
        Each file in a line
        <form method="post">
          Download Links: <textarea class="form-control" name="multiuri"></textarea>

          <br>
          <input type="submit" value="Add multiple download links" class="btn btn-success">
        </form>
      </fieldset>

      <p>&nbsp;</p>
      <fieldset>
        <legend>Torrent</legend>
        Upload Torrent file
        <form method="post" enctype="multipart/form-data">
          Torrent file: <input type="file" name="torrent">
          <br>
          <input type="submit" value="Add torrent" class="btn btn-success">
        </form>
      </fieldset>
  </div><!--/row-->
  
</div><!--/container-->
<!-- /Main -->


	<!-- script references -->
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>