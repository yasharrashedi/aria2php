<?php
include "config.php";
require_once "Client.php";
$client = new \JsonRPC\Client ( 'http://' . HOST . ':' . PORT . '/jsonrpc', SECRET );
$debug = false;
if(! $_GET['gid']) {
	$_GET['gid'] = 0;
}
ini_set ( 'display_errors', 'On' );
if($debug) {
	error_log ( '***********************\ncall action=' . $_GET['action'] );
}
if($_GET['action'] == 'pause') {
	$client->execute ( 'aria2.pause', array($_GET['gid']) );
}elseif($_GET['action'] == 'resume') {
	$client->execute ( 'aria2.unpause', array($_GET['gid']) );
}elseif($_GET['action'] == 'removeExt') {
	$status = $client->execute ( 'aria2.tellStatus', array($_GET['gid']) );
	if($status != null) {
		if($status != null) {
			$path = $status['files'];
			$rmpath = $path;
			if($path != null) {
				$rmpath = $path[0]['path'];
			}
			if($status['status'] == 'complete') {
				$client->execute ( 'aria2.removeDownloadResult', array($_GET['gid']) );
			}else if($status['status'] == 'failed') {
				$client->execute ( 'aria2.removeDownloadResult', array($_GET['gid']) );
			}else {
				$client->execute ( 'aria2.forceRemove', array($_GET['gid']) );
			}
			if($rmpath != null) {
				print_r ( 'unlink ' . unlink ( $rmpath ) );
			}
		}
	}
}elseif($_GET['action'] == 'remove') {
	$status = $client->execute ( 'aria2.tellStatus', array($_GET['gid']) );
	if($status != null) {
		$path = $status['files'];
		$rmpath = $path;
		if($path != null) {
			$rmpath = $path[0]['path'];
		}
		if($status['status'] == 'complete') {
			$client->execute ( 'aria2.removeDownloadResult', array($_GET['gid']) );
		}else if($status['status'] == 'failed') {
			$client->execute ( 'aria2.removeDownloadResult', array($_GET['gid']) );
			if($rmpath != null) {
				unlink ( $rmpath );
			}
		}else {
			$client->execute ( 'aria2.forceRemove', array($_GET['gid']) );
		}
	}
}elseif($_GET['action'] == 'pauseall') {
	$client->execute ( 'aria2.forcePauseAll' );
}elseif($_GET['action'] == 'unpauseall') {
	$client->execute ( 'aria2.unpauseAll' );
}elseif($_GET['action'] == 'purge') {
	$client->execute ( 'aria2.purgeDownloadResult' );
}elseif($_GET['action'] == 'savesession') {
	$client->execute ( 'aria2.saveSession' );
}elseif($_GET['action'] == 'redownload_failed') {
	$downloads = $client->execute ( 'aria2.tellStopped', array(0, 1000) );
	foreach($downloads as $download) {
		if($download['status'] == 'error') {
			$uri = $download['files'][0]['uris'][0]['uri'] . PHP_EOL;
			try {
				$response = $client->execute ( 'aria2.addUri', array(array(trim ( $uri ))) );
			}catch(Exception $e) {
				die ( $e->getMessage () );
			}
		}
	}
}
if($_GET['action'] == 'moveup') {
	$client->execute ( 'aria2.changePosition', array($_GET['gid'], - 1, 'POS_CUR') );
}elseif($_GET['action'] == 'movedown') {
	$client->execute ( 'aria2.changePosition', array($_GET['gid'], + 1, 'POS_CUR') );
}elseif($_GET['action'] == 'movetop') {
	$client->execute ( 'aria2.changePosition', array($_GET['gid'], 0, 'POS_SET') );
}elseif($_GET['action'] == 'movebottom') {
	$client->execute ( 'aria2.changePosition', array($_GET['gid'], 2000, 'POS_END') );
}
if($debug) {
	error_log ( '------------------\n' );
}
header ( 'Location: index.php?type=' . $_GET['type'] );