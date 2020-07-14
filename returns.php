<?php
include_once "header.php";
?>

<script>
$(document).ready(function(){
    
});
</script>
    <section class="main-container">
        <div class="main-wrapper">
            <?php
            if(isset($_SESSION['userid'])){
                echo "<div id='listing'></div>";
            }
            ?>        
            
            <div id="results"></div>
        </div>
    </section>

    <?php
include_once "footer.php";
?>
