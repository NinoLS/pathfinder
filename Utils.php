<?php

class Utils{
    public static $X_DEFAULT = 8;
    public static $Y_DEFAULT = 8;

    public static function chargerStyle(){
        ?>
        <style>
            td { width:60px; }
            td input[type=number] { width: 75%; margin-bottom:10%;}
            .b-red{border: 3px solid red;}
            .b-blue{border: 3px solid blue;}
            .b-green{ border: 2px solid green;}
        </style>

        <?php
    }

    public static function chargerScripts(){
        ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
            var couts;
            var couts_init;
            var chemin;
            var action;

            var xA;
            var yA;
            var xB;
            var yB;

            $(document).ready(function(){
                couts = new Object();
                couts_init = new Object();
                chemin = new Object();
                action = 0;

                $(".A_cbx").change(function(){
                    if($(this).is(":checked")){
                        $(".A_cbx").attr("disabled", "");
                        $(this).parent().find(".w-small").addClass("b-blue");
                        $(this).removeAttr("disabled");
                    } else {
                        $(".A_cbx").removeAttr("disabled");
                        $(".b-blue").removeClass("b-blue");
                    }

                });
                $(".B_cbx").change(function(){
                    if($(this).is(":checked")){
                        $(".B_cbx").attr("disabled", "");
                        $(this).parent().find(".w-small").addClass("b-red");
                        $(this).removeAttr("disabled");
                    } else {
                        $(".B_cbx").removeAttr("disabled");
                        $(".b-red").removeClass("b-red");
                    }

                });
            })

            function appliquerPoids(){
                let val = $("#poids_global").val();
                $("input[type='number']").val(val);
            }

            function lancerAlgo(){
                //init
                xA = parseFloat($(".A_cbx:checked").attr("id").split("_")[0]);
                yA = parseFloat($(".A_cbx:checked").attr("id").split("_")[1]);
                xB = parseFloat($(".B_cbx:checked").attr("id").split("_")[0]);
                yB = parseFloat($(".B_cbx:checked").attr("id").split("_")[1]);
                
                //couts
                couts[xA] = new Object();
                couts[xA][yA] = 0;

                for (let i = 0; i < <?=Utils::$X_DEFAULT?>; i++) {
                    couts_init[i] = new Object();
                    for (let j = 0; j < <?=Utils::$Y_DEFAULT?>; j++) {
                        couts_init[i][j] = $("#"+i+"_"+j+"_n").val();
                    }
                }

                do {
                    //trouver voisin
                    action = 0;
                    calculerCoutsVoisins();
                } while (action > 0);
                
                console.log(couts);
                console.log(chemin);
                colorerLesCases(chemin);
            }

            function trouverPoids(x,y){
                return $("#"+x+"_"+y+"_"+"n").val();
            }
            function caseExiste(x,y){
                x = parseInt(x);
                y = parseInt(y);
                return (
                    x >= 0 && x < <?= Utils::$X_DEFAULT ?>
                    &&
                    y >= 0 && y < <?= Utils::$Y_DEFAULT ?>);
            }

            function calculerCoutsVoisins(){
                console.log("-");
                for (let i = 0; i < Object.keys(couts_init).length; i++) {
                    for (let j = 0; j < Object.keys(couts_init[i]).length; j++) {
                        if(typeof couts[i] != "undefined" && typeof couts[i][j] != "undefined"){
                            calculerCaseVersCase(i,j,i  ,j-1);
                            calculerCaseVersCase(i,j,i  ,j+1);
                            calculerCaseVersCase(i,j,i-1,j  );
                            calculerCaseVersCase(i,j,i+1,j  );
                            
                        }
                        //diagonales
                        // calculerCaseVersCase(i,j,i-1,j-1);
                        // calculerCaseVersCase(i,j,i+1,j-1);
                        // calculerCaseVersCase(i,j,i-1,j+1);
                        // calculerCaseVersCase(i,j,i+1,j+1);
                    }
                }
            }
            function calculerCaseVersCase(xD,yD,xF,yF){
                if(caseExiste(xF,yF)){
                    if(typeof couts[xF] == "undefined"){
                        couts[xF] = new Object();
                    }
                    if(typeof couts[xF][yF] == "undefined" || parseInt(couts[xD][yD]) + parseInt(couts_init[xF][yF]) < parseInt(couts[xF][yF])){
                        couts[xF][yF] = parseInt(couts[xD][yD]) + parseInt(couts_init[xF][yF]);
                        chemin[yF+"_"+xF] = yD+"_"+xD;
                        action++;
                    }
                }

            }

            function colorerLesCases(chemins){
                var xTmp = xB;
                var yTmp = yB;
                var idTmp = "";
                while(xTmp != xA || yTmp != yA){
                    idTmp = chemin[xTmp+"_"+yTmp].split("_");
                    xTmp = idTmp[0];
                    yTmp = idTmp[1];
                    colorerCase(xTmp,yTmp);
                }
                $("#"+xA+"_"+yA+"_n").removeClass("b-green");
            }
            function colorerCase(xD,yD){
                $("#"+yD+"_"+xD+"_n").addClass("b-green");
            }
        </script>
        <?php
    }

    public static function chargerReglages(){
        ?>
        <input type="number" id="poids_global">
        <input type="button" value="Appliquer" onclick="appliquerPoids()">
        <input type="button" value="Lancer" onclick="lancerAlgo()">

        <?php
    }
}

?>