<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="styles/reset.css">
    <link rel="stylesheet" type="text/css" href="styles/login.css">
    <script src="js/jquery.js"></script>
</head>

<body>
    <header>
        <nav>
            <div class="main-wrapper">
                <ul>
                    <!-- <li><a href='inex.php'>Home</a></li>
                <li><a href='news.html'>News</a></li> -->
                    <li><a href="index.php">Notice Board</a></li>
                    <?php
                    if (isset($_SESSION['userid'])) {
                        if ($_SESSION['userid'] == "grantee") {
                            echo '<li><a href="grantee.html">Member List</a></li>';
                            echo '<li><a href="returns.php">Returns</a></li>';
                            echo "<li><a href='changepass.php'>Change Password</a></li>";
                        } else {
                            echo "<li><a href='entities.html'>Entities</a></li>";
                        }
                    }
                    ?>
                </ul>
                <div class="nav-login">

                    <?php
                    if (isset($_SESSION['userid'])) {
                        echo '<form action="php/logout.php" method="POST">
                            <button name="submit" type="submit">Logout</button>
                        </form>';
                    } else {
                        echo '<form id="login-form" action="php/login.php" method="POST">
                        <select id="utype" name="utype">
                            <option value="grantee">Grantee</option>
                            <option value="admin">Admin</option>
                        </select>
                        <input type="text" name="userid" id="userid" placeholder="userid/file number">
                        <input type="password" name="kpass" id="kpass" placeholder="password">
                        <input type="hidden" name="tzone" id="tzone">
                        <button type="submit" name="submit">Login</button>
                    </form>';
                    }
                    ?>
                    <input type="hidden" name="tzone" id="tzone">
                </div>
            </div>
        </nav>
    </header>

    <script>
        var kzone = new Date();
        var jkzone = kzone.getTimezoneOffset();
        document.getElementById('tzone').value = -1 * jkzone;
    </script>