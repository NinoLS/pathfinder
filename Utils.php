<?php

class Utils{
    public static $X_DEFAULT = 8;
    public static $Y_DEFAULT = 8;
    public static $TD_WIDTH_PX = 45;
    public static $TD_HEIGHT_PX = 45;

    public static function chargerStyle(){
        ?>
        <style>
            *{ font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size:16.8px;}
            td { border: 1px solid black; }
            td { width:<?=self::$TD_WIDTH_PX?>px; height:<?=self::$TD_HEIGHT_PX?>px; }
            td input[type=number] { width: 75%; margin-bottom:10%;}
            .b-red{border: 3px solid red;}
            .b-blue{ 
                border: 2px solid blue;
            }

            .line{
                border-style: solid;
            }
            .bottom_left{
                margin:45% 0 0 0;
                width:50%;
                border-width: 2px 2px 0px 0; 
            }
            .left_top{
                margin:0 0 45% 0;
                width:50%;
                border-width: 0px 2px 2px 0; 
            }
            .top_right{
                margin:0% 0 45% 45%;
                width:50%;
                border-width: 0px 0px 2px 2px; 
            }
            .right_bottom{
                margin:45% 0 0 45%;
                width:50%;
                border-width: 2px 0px 0px 2px; 
            }
            .hori{
                margin:45% 0 0 0;
                width:100%;
                border-width: 2px 0px 0px 0px; 
            }
            .vert{
                margin:0 45% 0 0;
                width:50%;
                height:100%;
                border-width: 0px 2px 0px 0px; 
            }

            /*width*/
            .w-100{
                width:100%;
            }
            .w-50{
                width:50%;
            }
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

            const PUT_FLAG_BEGIN = 0;
            const PUT_FLAG_END = 1;
            const PUT_COSTS = 2;

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

                //depart
                $("td").on({
                    mouseenter: function() {
                        caseMouseEnter($(this));
                    },
                    click: function(){
                        caseMouseClick($(this));
                    },
                    mouseleave: function() {
                        caseMouseLeave($(this));
                    }
                });
            });

            function getStep(){
                return parseInt($("#step").val());
            }
            function incrementStep(){
                $("#step").val(parseInt($("#step").val())+1);
            }

            function caseMouseEnter(td){
                switch (getStep()) {
                    case PUT_FLAG_BEGIN:
                        td.find("img")
                            .attr("src","./images/depart.png")
                            .attr("width", "<?=self::$TD_WIDTH_PX-7?>px")
                            .attr("heigth", "<?=self::$TD_HEIGHT_PX-7?>px")
                            .attr("style", "opacity:0.5;");
                        break;
                
                    case PUT_FLAG_END:
                        if(td.attr("id") !== "flag_begin"){
                            td.find("img")
                                .attr("src","./images/fin.png")
                                .attr("width", "<?=self::$TD_WIDTH_PX-7?>px")
                                .attr("heigth", "<?=self::$TD_HEIGHT_PX-7?>px")
                                .attr("style", "opacity:0.5;");
                        }
                        break;
                    
                    case PUT_COSTS:
                        break;
                }
            }
            function caseMouseClick(td){
                switch (getStep()) {
                    case PUT_FLAG_BEGIN:
                        td.find("img")
                            .attr("src","./images/depart.png")
                            .attr("width", "<?=self::$TD_WIDTH_PX-7?>px")
                            .attr("heigth", "<?=self::$TD_HEIGHT_PX-7?>px")
                            .attr("style", "opacity:1;")
                            .parent().attr("id", "flag_begin");
                        incrementStep();
                        break;
                
                    case PUT_FLAG_END:
                        if(td.attr("id") !== "flag_begin"){
                            td.find("img")
                                .attr("src","./images/fin.png")
                                .attr("width", "<?=self::$TD_WIDTH_PX-7?>px")
                                .attr("heigth", "<?=self::$TD_HEIGHT_PX-7?>px")
                                .attr("style", "opacity:1;")
                                .parent().attr("id", "flag_end");
                            incrementStep();
                        }
                        break;
                    
                    case PUT_COSTS:
                        break;
                }

            }
            function caseMouseLeave(td){
                switch (getStep()) {
                    case PUT_FLAG_BEGIN:
                        td.find("img")
                            .attr("src","")
                            .attr("width", "0px")
                            .attr("heigth", "0px")
                        break;
                
                    case PUT_FLAG_END:
                        if(td.attr("id") !== "flag_begin"){
                            td.find("img")
                            .attr("src","")
                            .attr("width", "0px")
                            .attr("heigth", "0px")
                        }
                        break;
                    
                    case PUT_COSTS:
                        break;
                }
                
            }

            function appliquerPoids(){
                let val = $("#poids_global").val();
                $("input[type='number']").val(val);
            }

            function lancerAlgo(){
                //init
                xA = parseInt($("#flag_begin").attr("x"));
                yA = parseInt($("#flag_begin").attr("y"));
                xB = parseInt($("#flag_end").attr("y"));
                yB = parseInt($("#flag_end").attr("x"));
                
                //couts
                couts[xA] = new Object();
                couts[xA][yA] = 0;

                for (let i = 0; i < <?=Utils::$X_DEFAULT?>; i++) {
                    couts_init[i] = new Object();
                    for (let j = 0; j < <?=Utils::$Y_DEFAULT?>; j++) {
                        couts_init[i][j] = trouverCase(i,j).val();
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

            function caseExiste(x,y){
                x = parseInt(x);
                y = parseInt(y);
                return (
                    x >= 0 && x < <?= Utils::$X_DEFAULT ?>
                    &&
                    y >= 0 && y < <?= Utils::$Y_DEFAULT ?>);
            }

            function trouverCase(x,y){
                return $("td[x_y='"+x+"_"+y+"']");
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
                $("#flag_begin").removeClass("b-blue");
            }
            function colorerCase(xD,yD){
                let td = trouverCase(yD,xD);
                td.addClass("b-blue");
                td.html("<div class='line bottom_left'> </div>");
                
            }

            
        </script>
        <?php
    }

    public static function chargerReglages(){
        ?>
        <input type="button" value="Lancer" onclick="lancerAlgo()">
        <input type="hidden" value="0" id="step">
        <?php
    }
}

?>