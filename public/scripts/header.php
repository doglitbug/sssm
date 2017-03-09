<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
		<meta name="description" content="Simple Staff Schedule Management - Provides online scheduling to replace onsite paper systems" />
		<meta name="author" content="Arron Dick" />

		<title>
		<?php
		//Check the calling page has provided a page title to be used, otherwise default to Application name
		if (isset($title)){
			echo $title;
		} else {
			echo "SSSM";
		}
		?>
		</title>

		<link rel="stylesheet" type="text/css" href="scripts/styles.css" />

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
	</head>
	<body>
		<div class="container">