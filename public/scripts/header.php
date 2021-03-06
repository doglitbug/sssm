<?php
require_once('startsession.php');
require_once('databaseconnection.php');
require_once('sharedFunctions.php');
?>

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
            if (isset($title)) {
                echo $title;
            } else {
                echo "SSSM";
            }
            ?>
        </title>

        <link rel="stylesheet" type="text/css" href="../scripts/styles.css" />

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
        
        <!-- Moved from footer so that jquery will work -->
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    </head>
    <body>
        <?php
        require_once('navbar.php');
        ?>
        <div class="container">

            <?php
//Check access permission
            if (isset($manager) && $manager == true && (!isset($_SESSION['manager']) || $_SESSION['manager'] != '1')) {
                header("Location: /../errors/403.php");
                die('You are not allowed to access this page.');
            }

//Check if login is required and not logged in
            if (isset($login) && $login == true && !isset($_SESSION['username'])) {
                echo "<h1>Login required</h1>\n";
                echo "<ul>\n";
                echo "<li><a href='../user/login.php'>Click here to log in</a></li>\n";
                echo "<li><a href='../index.php'>Click here to return to home page</a></li>\n";
                echo "</ul>\n";
                require_once('footer.php');
                die();
            }
            ?>