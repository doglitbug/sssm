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
            <?php
            //Hide all links except for log in if not logged in
            if (loggedIn()) {
                ?>
                <ul class="nav navbar-nav">
                    <?php
                    //Users links, manager only
                    if (isset($_SESSION['manager']) && $_SESSION['manager'] == '1') {
                        ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Staff management<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../user/new.php">Add new</a></li>
                                <li><a href="../user/modify.php">Change</a></li>
                                <li><a href="../user/delete.php">Remove</a></li>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>
                    <li><a href="../roster/view.php">Roster</a></li>
                    <li><a href="../available/view.php">Available shifts</a></li>
                    <li><a href="../schedule/view.php">Schedule</a></li>
                </ul>

                <!--log/profile links aligned to right-->
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href='../user/modify.php'>Edit my details</a></li>
                            <li><a href='../user/logout.php'>Logout</a></li>
                        </ul>
                    </li>
                </ul>

                <?php
            } else {
                ?>
                <!--log/profile links aligned to right-->
                <ul class="nav navbar-nav navbar-right">
                    <li><a href='../user/login.php'>Login</a></li>
                </ul>
                <?php
            }
            ?>
        </div>
    </div>
</nav>