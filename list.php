<?php
require_once "config.php";
require_once "Client.php";
$client = new \JsonRPC\Client('http://'.HOST.':'.PORT.'/jsonrpc',SECRET);


if(!isset($_GET['type'])){
    $_GET['type'] = 'active';
}

if($_GET['type']=='waiting'){
    $downloads = $client->execute('aria2.tellWaiting',array(0,1000));
}elseif ($_GET['type']=='active'){
    $downloads = $client->execute('aria2.tellActive');
}elseif ($_GET['type']=='completed'){
        $downloads_temp = $client->execute('aria2.tellStopped',array(0,1000));
foreach($downloads_temp as $download){
	if($download['status']=='complete'){
   	$downloads[] = $download;
 }
}
}elseif ($_GET['type']=='failed'){
    $downloads_temp = $client->execute('aria2.tellStopped',array(0,1000));
foreach($downloads_temp as $download){
	if($download['status']=='error'){
   	$downloads[] = $download;
 }
}
}



//print_r($downloads);


