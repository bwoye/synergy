<?php

include_once "header.php";
?>
<style>
   td {
       width:150px;
       padding:3px;
   }

   #skilling td{
       width:100px;
   }



    h3 {
        padding-top: 15px;
    }

    .stories {
        display: flex;
    }

    .asideleft {
        width: 30%;
        min-height: 200px;
        background-color: red;
    }

    .mymain {
        width: 70%;
        min-height: 200px;
        background-color: blue;
        padding-left: 10px;
        color:white;
    }
</style>
<script src="js/mmslogin.js"></script>
<section class="main-container" style="background-color:#bdfdfc">
    <div class="main-wrapper">
        <?php
        if (isset($_GET['invalidUser'])) {
            echo "<h3 class='notices'>Invalid user or password</h3>";
        } elseif (isset($_GET['invalidGrantee'])) {
            echo "<h3 class='notices'>Bad Grantee file or password</h3>";
        } elseif (isset($_SESSION['userid'])) {
            if ($_SESSION['userid'] == "grantee")
                echo "<h2>Notice Board</h2>";
        }
        ?>
        <div class="stories" style="padding-bottom:10px">
            <div class="asideleft">
            </div>
            <div class="mymain">
                <!-- <span id="sdfbudget"></span><br/>
                <span id="ownbudget"></span><br />
                <span id="uncategorised">

                </span> -->
            </div>
        </div>

    </div>
</section>
<input type="hidden" id="tzone">
<?php
include_once "footer.php";
?>