<?php

$handle = opendir("../monthlyreports");
echo "<ul>";
while($file = readdir($handle)){
    if($file =='.' || $file == '..'){
        continue;
    }
    echo "<li>".$file."</li>";
}
echo "</ul>";
closedir($handle);
?>