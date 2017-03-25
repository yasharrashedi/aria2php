<ul class="list-inline">
	<li><a href="add.php?type=<?=$_GET['type']?>" class="btn btn-info"> <i
			class="fa fa-plus"></i> Add new Download
	</a></li>

	<li><a href="action.php?action=pauseall&type=<?=$_GET['type']?>"
		class="btn btn-warning"> <i class="fa fa-pause"></i> Pause All
	</a>
	
	<li><a href="action.php?action=unpauseall&type=<?=$_GET['type']?>"
		class="btn btn-success"> <i class="fa fa-play"></i> Resume All
	</a></li>
	<li><a
		href="action.php?action=redownload_failed&type=<?=$_GET['type']?>"
		class="btn btn-info"> <i class="fa fa-recycle"></i> Re-download Failed
	</a></li>
	<li><a href="action.php?action=savesession&type=<?=$_GET['type']?>"
		class="btn btn-success"> <i class="fa fa-floppy-o"></i> Save Session
	</a></li>
	<li><a href="action.php?action=purge&type=<?=$_GET['type']?>"
		class="btn btn-danger"> <i class="fa fa-trash-o"></i> Clear Completed
			or Failed
	</a></li>

	<li><a href="downloads" class="btn btn-default"> <i
			class="fa fa-download"></i> Downloaded Files
	</a></li>
</ul>


<ul class="list-inline">
	<li><a href="index.php?type=active" class="btn btn-default"> Active
			Downloads </a></li>
	<li><a href="index.php?type=waiting" class="btn btn-default"> Waiting
			Downloads </a></li>
	<li><a href="index.php?type=completed" class="btn btn-default">
			Completed Downloads </a></li>
	<li><a href="index.php?type=failed" class="btn btn-default"> Failed
			Downloads </a></li>
</ul>
