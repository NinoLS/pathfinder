<?php

class Utils{
    public static $X_DEFAULT = 10;
    public static $Y_DEFAULT = 20;
    public static $TD_WIDTH = 45;
    public static $TD_HEIGHT = 45;

    public static function chargerStyle(){
        ?>
        <link rel="stylesheet" href="./css/style_other.css">
        <link rel="stylesheet" href="./css/style.css">
        <?php
    }

    public static function chargerScripts(){
        ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="./js/swal.js"></script>
        <script src="./js/main.js"></script>
        <?php
    }

    public static function chargerReglages(){
        echo self::createButton("lancer","button","btn_lancer","lancerAlgo()", "btn-disabled", "disabled"); 
        echo self::createButton("barrage","button","btn_barrage","lancerBarrage()", "btn-disabled", "disabled"); 
        echo self::createButton("terminé","button","btn_terminer","arreterBarrage()", "hidden"); 
        echo self::createButton("aleatoire","button","btn_aleatoire","lancerAleatoire()", "btn-disabled", "disabled"); 
        ?>
        <input type="hidden" value="0" id="step">
        <input type="hidden" value="<?=self::$X_DEFAULT?>" id="X_DEFAULT">
        <input type="hidden" value="<?=self::$Y_DEFAULT?>" id="Y_DEFAULT">
        <input type="hidden" value="<?=self::$TD_WIDTH?>" id="TD_WIDTH">
        <input type="hidden" value="<?=self::$TD_HEIGHT?>" id="TD_HEIGHT">
        <?php
    }

    public static function createButton($text,$type,$id = null,$onClick = null, $more_classes = "", $more_attrs = ""){
        return 
            '<button type="'.$type.'" class="btn '.$more_classes.'" id="'.$id.'" onclick="'.$onClick.'" '.$more_attrs.'>
                <div class="b-left"></div>
                <div class="b-top"></div>
                <span>'.$text.'</span>
                <div class="b-right"></div>
                <div class="b-bottom"></div>
            </button>';
    }
    
}

?>