<?php include "list.php"; ?>
<?php
if(! isset ( $_SESSION['user'] )) {
	header ( 'Location: login.php' );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>Aria2 PHP Manager</title>
<meta name="generator" content="Bootply" />
<meta name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
	<!-- Header -->
	<div id="top-nav" class="navbar navbar-inverse navbar-static-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target=".navbar-collapse">
					<span class="icon-toggle"></span>
				</button>
				<a class="navbar-brand" href="index.php">Aria2 PHP Client</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">

					<li class="dropdown"><a class="dropdown-toggle" role="button"
						data-toggle="dropdown" href="#"> <i class="fa fa-user"></i> <?= $_SESSION['user'] ?> <span
							class="caret"></span></a>
						<ul id="g-account-menu" class="dropdown-menu" role="menu">
							<li><a href="login.php?logout=true"><i class="fa fa-times"></i>
									Logout</a></li>
						</ul></li>
				</ul>
			</div>
		</div>
		<!-- /container -->
	</div>
	<!-- /Header -->

	<!-- Main -->
	<div class="container">

    <?php include "menu.php"; ?>

    <div class="row">

			<div class="col-md-12">

				<table class="table table-striped">
					<thead>
						<tr>
							<td>GID</td>
							<th>URL</th>
							<th>Position</th>
							<th>Percent</th>
							<th>Completed</th>
							<th>Size</th>
							<th>Download Speed</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
                <?php
																if($downloads != null) {
																	foreach($downloads as $download) {
																		if(array_key_exists ( 'bittorrent', $download )) {
																			?>
                        <tr>
							<td>
                                        <?= $download['gid'] ?>
                            </td>
							<td width="300"><span class="label label-danger"> Torrent </span>
								<span class="center-block small">&nbsp;</span> <span
								class="center-block small">Files:</span>
                                <?php foreach($download['files'] as $file) {?>
                                    <label
								class="center-block small text-nowrap"><?=str_replace(DOWNLOAD_PATH,'',$file['path'])?></label>
                                <?php } ?>
                            </td>
							<td width="150">
								<div class="btn-group">
									<a
										href="action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=moveup"
										class="btn  btn-default"> <i class="fa fa-angle-up"></i> Move
										Up
									</a>
									<button type="button" class="btn btn-default dropdown-toggle"
										data-toggle="dropdown">
										<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a
											href="action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=movetop">
												<i class="fa fa fa-angle-double-up"></i> Move top
										</a></li>
										<li><a
											href="action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=movedown">
												<i class="fa fa fa-angle-down"></i> Move down
										</a></li>
										<li><a
											href="action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=movebottom">
												<i class="fa fa fa-angle-double-down"></i> Move bottom
										</a></li>
									</ul>
								</div>

							</td>
							<td width="100">
                                <?php
																			if($download['totalLength'] > 0) {
																				$result = 100 - ((($download['totalLength'] - $download['completedLength']) / $download['totalLength']) * 100);
																				$progress = number_format ( $result, 2 );
																				$progressRound = round ( $progress );
																			}else {
																				$progress = 0;
																				$progressRound = 0;
																			}
																			echo $progress . '%';
																			?>
                                <div class="progress">
									<div class="progress-bar progress-bar-success" role="progressbar"
                                         aria-valuenow="<?= $progressRound ?>%" aria-valuemin="0" aria-valuemax="100"
                                         style="width: <?= $progressRound ?>%;">
										<span class="sr-only"><?= $progressRound ?>% Complete</span>
									</div>
								</div>

							</td>
							<td>
                                <?= number_format(($download['completedLength'] / 1024) / 1024, 2) ?> MB
                            </td>
							<td>
                                <?= number_format(($download['totalLength'] / 1024) / 1024, 2) ?> MB
                            </td>
							<td>
                                <?= number_format($download['downloadSpeed'] / 1024, 2) ?> KB/s
<br>
                                Upload: <?= number_format($download['uploadSpeed'] / 1024, 2) ?> KB/s
<br />
                                Peers: <?=$download['connections']?>
<br />
																Seeds: <?=$download['numSeeders']?>             

                            </td>
							<td width="140"><strong>
                                    <?= $download['status'] ?>
                                </strong> <br>

								<div class="btn-group">
                                    <?php
																			if($download['status'] == 'paused') {
																				?>
                                        <a
										href="action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=resume"
										class="btn btn-default"> <i class="fa fa-play"></i> Resume
									</a>
                                    <?php } elseif ($download['status'] == 'active' || $download['status'] == 'waiting') { ?>
                                        <a
										href="action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=pause"
										class="btn btn-default"> <i class="fa fa-pause"></i> Pause
									</a>
                                    <?php
																			}
																			?>
                                    <button type="button"
										class="btn btn-default dropdown-toggle" data-toggle="dropdown">
										<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="javascript:void(0)"
											onclick=" if(confirm('删除下载？','1') != null){ window.location.href='action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=remove' } "><i
												class="fa fa-times"> </i> Remove </a></li>
									</ul>
								</div></td>
						</tr>
                    <?php } else { ?>
                        <tr>
							<td>
                                <?= $download['gid'] ?>
                            </td>
							<td width="300">
                                <?= $download['files'][0]['uris'][0]['uri'] ?>

                            </td>
							<td width="150">

								<div class="btn-group">
									<a
										href="action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=moveup"
										class="btn  btn-default"> <i class="fa fa-angle-up"></i> Move
										Up
									</a>
									<button type="button" class="btn btn-default dropdown-toggle"
										data-toggle="dropdown">
										<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a
											href="action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=movetop">
												<i class="fa fa fa-angle-double-up"></i> Move top
										</a></li>
										<li><a
											href="action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=movedown">
												<i class="fa fa fa-angle-down"></i> Move down
										</a></li>
										<li><a
											href="action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=movebottom">
												<i class="fa fa fa-angle-double-down"></i> Move bottom
										</a></li>
									</ul>
								</div>

							</td>
							<td width="100">
                                <?php
																			if($download['totalLength'] > 0) {
																				$result = 100 - ((($download['totalLength'] - $download['completedLength']) / $download['totalLength']) * 100);
																				$progress = number_format ( $result, 2 );
																				$progressRound = round ( $progress );
																			}else {
																				$progress = 0;
																				$progressRound = 0;
																			}
																			echo $progress . '%';
																			?>
                                <div class="progress">
									<div class="progress-bar progress-bar-success" role="progressbar"
                                         aria-valuenow="<?= $progressRound ?>%" aria-valuemin="0" aria-valuemax="100"
                                         style="width: <?= $progressRound ?>%;">
										<span class="sr-only"><?= $progressRound ?>% Complete</span>
									</div>
								</div>

							</td>
							<td>
                                <?= number_format(($download['completedLength'] / 1024) / 1024, 2) ?> MB
                            </td>
							<td>
                                <?= number_format(($download['totalLength'] / 1024) / 1024, 2) ?> MB
                            </td>
							<td>
                                <?= number_format($download['downloadSpeed'] / 1024, 2) ?> KB/s
                            </td>
							<td width="140"><strong>
                                    <?= $download['status'] ?>
                                </strong> <br>

								<div class="btn-group">
                                    <?php if ($download ['status'] == 'paused') { ?>
                                        <a
										href="action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=resume"
										class="btn btn-default"> <i class="fa fa-play"></i> Resume
									</a>
                                    <?php } elseif ($download['status'] == 'active' || $download['status'] == 'waiting') { ?>
                                        <a
										href="action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=pause"
										class="btn btn-default"> <i class="fa fa-pause"></i> Pause
									</a>
                                    <?php
																			}
																			?>
                                    <button type="button"
										class="btn btn-default dropdown-toggle" data-toggle="dropdown">
										<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="javascript:void(0)"
											onclick=" if(confirm('Remove ？\nRemove Download','1') != null){ 
										window.location.href='action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=remove'; 
										}else {alert('cancel');}"><i class="fa fa-times"></i> Remove </a></li>
										<?php if($_GET['type'] == 'completed'){ /**/?>
											<li><a href="javascript:void(0)"
											onclick=" if(confirm('  Remove ？\nRemove Record And Files ！','1') != null){
											window.location.href='action.php?type=<?=$_GET['type']?>&gid=<?= $download['gid'] ?>&action=removeExt';}
											else {alert('cancel');}"><i class="fa fa-times"></i> Complete
												Remove </a></li>
										<?php }?>
									</ul>
								</div></td>
						</tr>
                    <?php
																		}
																	}
																}
																?>
                </tbody>
				</table>


			</div>
			<!--/row-->

		</div>
		<!--/container-->
	</div>
	<!-- /Main -->


	<!-- script references -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>

</body>
</html>

