<?php
    require './plateau.php';
    require './Utils.php';
    Utils::chargerStyle();
    Utils::chargerScripts();
    Utils::chargerReglages();

    $p = new Plateau(Utils::$X_DEFAULT,Utils::$Y_DEFAULT);
    
?>