<?php

class Utils{

    public static function chargerStyle(){
        ?>
        <style>
            td { width:90px; }
            td input[type=number] { width: 50%; margin-bottom:10%;}

        </style>

        <?php
    }

    public static function chargerScripts(){
        ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function(){
                $(".A_cbx").change(function(){
                    if($(this).is(":checked")){
                        $(".A_cbx").attr("disabled", "");
                        $(this).removeAttr("disabled");
                    } else {
                        $(".A_cbx").removeAttr("disabled");
                    }

                });
                $(".B_cbx").change(function(){
                    if($(this).is(":checked")){
                        $(".B_cbx").attr("disabled", "");
                        $(this).removeAttr("disabled");
                    } else {
                        $(".B_cbx").removeAttr("disabled");
                    }

                });
            })

        </script>
        <?php
    }

}

?>