<?php

class Utils{
    public static $X_DEFAULT = 10;
    public static $Y_DEFAULT = 20;
    public static $TD_WIDTH_PX = 45;
    public static $TD_HEIGHT_PX = 45;

    public static function chargerStyle(){
        ?>
        <link rel="stylesheet" href="./css/style_other.css">
        <link rel="stylesheet" href="./css/style.css">
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

            var count = 1;

            const PUT_FLAG_BEGIN = 0;
            const PUT_FLAG_END = 1;
            const PUT_COSTS = 2;

            var X_DEFAULT; 
            var Y_DEFAULT; 

            $(document).ready(function(){
                couts = new Object();
                couts_init = new Object();
                chemin = new Object();
                action = 0;

                X_DEFAULT = $("#X_DEFAULT").val();
                Y_DEFAULT = $("#Y_DEFAULT").val();

                $(".A_cbx").change(function(){
                    if($(this).is(":checked")){
                        $(".A_cbx").attr("disabled", "");
                        // $(this).parent().find(".w-small").addClass("b-blue");
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

                //placer flag & couts
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

                //initialiser couts
                for (let i = 0; i < X_DEFAULT; i++) {
                    couts_init[i] = new Object();
                    for (let j = 0; j < Y_DEFAULT; j++) {
                        couts_init[i][j] = 1;
                    }
                }
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
                        if(td.attr("id") !== "flag_begin" && td.attr("id") !== "flag_end" && !td.hasClass("wall")){
                            let input = td.find("input");
                            td.find("input").removeClass("hidden");
                        }
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
                            $("#btn_block").removeAttr("disabled");
                        }
                        break;
                    
                    case PUT_COSTS:
                        if(td.attr("id") !== "flag_begin" && td.attr("id") !== "flag_end" && !td.hasClass("wall")){
                            let input = td.find("input");
                            input.val(parseInt(input.val())+1);
                            couts_init[td.attr("x")][td.attr("y")]++;
                        }
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
                        if(td.attr("id") !== "flag_begin" && td.attr("id") !== "flag_end" && !td.hasClass("wall")){
                            let input = td.find("input");
                            if(parseInt(input.val()) == 1){
                                input.addClass("hidden");
                            }
                        }
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

                do {
                    //trouver voisin
                    action = 0;
                    calculerCoutsVoisins();
                } while (action > 0);
                
                // console.log(couts);
                // console.log(chemin);
                colorerLesCases(chemin);
            }

            function lancerAleatoire(){
                //retirer les murs partout
                $(".wall").removeClass("wall");

                //compteur
                let n = 0;
                let n_max = randomBetween(X_DEFAULT*Y_DEFAULT/6, X_DEFAULT*Y_DEFAULT/4);

                //case aléatoire
                while(n <= n_max){
                    trouverCase(
                        randomBetween(0, X_DEFAULT),
                        randomBetween(0, Y_DEFAULT)
                    ).addClass("wall");
                    n++;
                }

                //pas sur cases départ & arrivé
                $("#flag_begin").removeClass("wall");
                $("#flag_end").removeClass("wall");
            }

            function randomBetween(min, max){
                return Math.floor(Math.random() * max) + min;
            }

            function caseExiste(x,y){
                x = parseInt(x);
                y = parseInt(y);
                return (
                    x >= 0 && x < X_DEFAULT
                    &&
                    y >= 0 && y < Y_DEFAULT);
            }

            function trouverCase(x,y){
                return $("td[x_y='"+x+"_"+y+"']");
            }

            function calculerCoutsVoisins(){
                // console.log("-");
                for (let i = 0; i < Object.keys(couts_init).length; i++) {
                    for (let j = 0; j < Object.keys(couts_init[i]).length; j++) {
                        if(typeof couts[i] != "undefined" && typeof couts[i][j] != "undefined"){
                            if(!trouverCase(i,j).hasClass("wall")){
                                if(!trouverCase(i, j-1).hasClass("wall")){
                                    calculerCaseVersCase(i,j,i  ,j-1);
                                }
                                if(!trouverCase(i,j+1).hasClass("wall")){
                                    calculerCaseVersCase(i,j,i  ,j+1);
                                }
                                if(!trouverCase(i-1,j).hasClass("wall")){
                                    calculerCaseVersCase(i,j,i-1,j  );
                                }
                                if(!trouverCase(i+1,j).hasClass("wall")){
                                    calculerCaseVersCase(i,j,i+1,j  );
                                }
                            }
                            
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

                var xBefore = parseInt(xB);
                var yBefore = parseInt(yB);
                
                var idTmp = chemin[xBefore+"_"+yBefore].split("_");
                var xActual = parseInt(idTmp[0]);
                var yActual = parseInt(idTmp[1]);
                
                idTmp = chemin[xActual+"_"+yActual].split("_");
                var xAfter = parseInt(idTmp[0]);
                var yAfter = parseInt(idTmp[1]);

                //classe
                let from_to = ""; 
                while(xBefore != xA || yBefore != yA){
                    console.log("before: ("+xBefore+","+yBefore+")");
                    console.log("actual: ("+xActual+","+yActual+")");
                    console.log("after: ("+xAfter+","+yAfter+")");
                    console.log("-");

                    //case précédente à GAUCHE case actuelle
                    if(xBefore < xActual){
                        //case actuelle à GAUCHE case prochaine
                        if(xActual < xAfter){
                            from_to = "left_right";
                        //case actuelle EN DESSOUS case prochaine
                        } else if(yActual < yAfter) {
                            from_to="bottom_left";
                        //case actuelle AU DESSUS case prochaine
                        } else if(yActual > yAfter){
                            from_to="top_left";
                        } //else IMPOSSIBLE
                    }
                    //case précédente à DROITE case actuelle
                    else if(xBefore > xActual){
                        //case actuelle à DROITE case prochaine
                        if(xActual > xAfter){
                            from_to = "left_right";
                        //case actuelle EN DESSOUS case prochaine
                        } else if(yActual < yAfter) {
                            from_to="bottom_right";
                        //case actuelle AU DESSUS case prochaine
                        } else if(yActual > yAfter){
                            from_to="top_right";
                        } //else IMPOSSIBLE

                    }

                    //case précédente meme COLONNE case actuelle
                    else {
                        //case précédente en DESSOUS case actuelle
                        if(yBefore < yActual){
                            //case actuelle A GAUCHE case prochaine
                            if(xActual < xAfter) {
                                from_to="top_right";
                            //case actuelle A DROITE case prochaine
                            } else if(xActual > xAfter){
                                from_to="top_left";
                            } else {
                                from_to="bottom_top";
                            }
                        //case précédente au DESSUS case 
                        } else if(yBefore > yActual) {
                            //case actuelle A GAUCHE case prochaine
                            if(xActual < xAfter) {
                                from_to="bottom_right";
                            //case actuelle A DROITE case prochaine
                            } else if(xActual > xAfter){
                                from_to="bottom_left";
                            } else {
                                from_to="bottom_top";
                            }
                        } //else impossible
                    }

                    
                    colorerCase(xActual,yActual,from_to);
                    //set befores
                    xBefore = xActual;
                    yBefore = yActual;
                    
                    //set actuals
                    xActual = xAfter;
                    yActual = yAfter;
                    
                    //set afters
                    idTmp = chemin[xAfter+"_"+yAfter].split("_");
                    xAfter = parseInt(idTmp[0]);
                    yAfter = parseInt(idTmp[1]);
                }
                $("#flag_begin").removeClass("b-blue");

            }
            function colorerCase(xD,yD,from_to){
                let td = trouverCase(yD,xD);
                // td.addClass("b-blue");
                td.html("<div class='line "+from_to+"' style='display:none;'> </div>");
                
                //animation
                td.find("div").delay(count*30).fadeIn(100);
                count+=2;
            }

            function lancerBlock(){
                $(this).addClass("btn-selected");
                $("td").addClass("td-grey");

            }


            
        </script>
        <?php
    }

    public static function chargerReglages(){
        echo self::createButton("lancer","button",null,"lancerAlgo()"); 
        echo self::createButton("barrage","button","btn_block","lancerBlock()"); 
        echo self::createButton("aleatoire","button",null,"lancerAleatoire()"); 
        ?>
        <input type="hidden" value="0" id="step">
        <input type="hidden" value="<?=self::$X_DEFAULT?>" id="X_DEFAULT">
        <input type="hidden" value="<?=self::$Y_DEFAULT?>" id="Y_DEFAULT">
        <?php
    }

    public static function createButton($text,$type,$id = null,$onClick = null){
        return '<button type="'.$type.'" class="btn" id="'.$id.'" onclick="'.$onClick.'">
                  <div class="b-left"></div>
                  <div class="b-top"></div>
                  <span>'.$text.'</span>
                  <div class="b-right"></div>
                  <div class="b-bottom"></div>
                </button>';
    }
    
}

?>