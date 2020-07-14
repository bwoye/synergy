<?php
include_once "header.php";
?>

<script>
    $(document).ready(function(){
        $(document).on('submit','#mypassform',function(ev){
            ev.preventDefault();
           if($.trim($('#pkword1').val()) != $.trim($('#pkword2').val())){
            $(document).find('#results').html("Password mistmatch");
               return false;
           }
            var snd = {
                pkword1:$('#pkword1').val(),
                original:$('#pkwordoriginal').val()
            }

            $.post('php/changepass.php',snd,function(some){
                //alert(some);
                var mines = JSON.parse(some);
                $(document).find('#results').html(mines.errmsg);
            });            
        });
    });
</script>
    <section class="main-container">
        <div class="main-wrapper">
            <?php
            if(isset($_SESSION['userid'])){
                echo '<h2>Change Password</h2>
                <form class="changepass-form" id="mypassform">
                    <table id="changepass-table">
                    <tr><td><label for="pkwordoriginal">Current Password</label></td>
                    <td><input type="password" name="pkwordoriginal" id="pkwordoriginal" placeholder="current password"></td></tr>
                    <tr><td><label for="pkword1">New Password</label></td>
                    <td><input type="password" name="pkword1" id="pkword1" placeholder="new password"></td></tr>
                    <tr><td><label for="pkword2">Verify Password</label></td>
                    <td><input type="password" name="pkword2" id="pkword2" placeholder="verify Password"></td></tr>
                    <tr><td colspan="2"><button type="submit" name="submit" id="submit">Submit</button></td></tr>
                    </table>
                </form>';
            }
            ?>        

            <div id="results"></div>
        </div>
    </section>

    <?php
include_once "footer.php";
?>
