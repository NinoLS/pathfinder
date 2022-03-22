<?php
    require './plateau.php';
    require './Utils.php';
    Utils::chargerStyle();
    Utils::chargerScripts();
    Utils::chargerReglages();

    $p = new Plateau(8,8);

?>