<nav class="navbar navbar-inverse">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
	      	</button>
	      	<a class="navbar-brand" href="../index.php">SSSM</a>
	      </div>

	     <div class="collapse navbar-collapse" id="bs-navbar">
	     	<ul class="nav navbar-nav">
	     	<?php
	     	//Users links, manager only
	      	if (isset($_SESSION['manager']) && $_SESSION['manager']=='1'){
	      		?>
	      		<li class="dropdown">
	      			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">User management<span class="caret"></span></a>
	      			<ul class="dropdown-menu">
	      				<li><a href="../user/new.php">New</a></li>
	      				<li><a href="../user/modify.php">Modify</a></li>
	      				<li><a href="../user/delete.php">Delete</a></li>
	      			</ul>
	      		</li>
	      	<?php
	      	}
	     	?>
	      		<li><a href="../schedule/view.php">Availability</a></li>
	     		<li><a href="../roster/view.php">Roster</a></li>
	     	</ul>

	     	<!--log/profile links -->
	     	<ul class="nav navbar-nav navbar-right">
        		<li class="dropdown">
          			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Myself<span class="caret"></span></a>
          			<ul class="dropdown-menu">
          			<!-- TODO Hide unneeded links, eg no log in if already logged in -->
	     				<li><a href="../user/login.php">Login</a></li>
	     				<li><a href="../user/modify.php">Edit my details</a></li>
	     				<li><a href="../user/logout.php">Logout</a></li>
	     			</ul>
	     		</li>
	     	</ul>
	     </div>
    </div>
</nav>