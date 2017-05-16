# Simple Staff Schedule Management

Online platform for small companies that run on varying rosters

Replace uncertainty around staff availability and roster times by replacing pinned up paper rosters with this online system.

Allow staff to provide availability which can be viewed from anywhere, anytime when doing rosters.

Allow staff to view and pick up available shifts from offsite

Allow managers to see staff availability and set rosters from offsite

# To run
Run using latest scotch box (vagrant)

You will need to place the following text in scripts/connectvars.php
```
<?php

//Define database connection constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'sssm');
?>
```
The file ERD.mwb can be used to forward engineer the database structure using Workbench

Loading http://localhost:8080/populate/createUsers.php will populate sample data
