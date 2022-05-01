//couts, chemin
var couts;
var couts_init;
var chemin;
var action;

//points
var xA,yA;
var xB,yB;

//compteur
var count = 1;

//ressources
const PUT_FLAG_BEGIN = 0;
const PUT_FLAG_END = 1;
const PUT_COSTS = 2;
const PUT_WALLS = 3;

var X_DEFAULT; 
var Y_DEFAULT;
var TD_WIDTH; 
var TD_HEIGHT; 

$(document).ready(function(){
    //0 - Désactiver boutons
    desactiverBouton("btn_lancer");
    desactiverBouton("btn_barrage");
    desactiverBouton("btn_aleatoire");

    //1 - Initialiser donnees
    couts = new Object();
    couts_init = new Object();
    chemin = new Object();
    action = 0;

    X_DEFAULT = $("#X_DEFAULT").val();
    Y_DEFAULT = $("#Y_DEFAULT").val();
    TD_WIDTH = $("#TD_WIDTH").val();
    TD_HEIGHT = $("#TD_HEIGHT").val();

    //2 - Initialiser couts
    for (let i = 0; i < X_DEFAULT; i++) {
        couts_init[i] = new Object();
        for (let j = 0; j < Y_DEFAULT; j++) {
            couts_init[i][j] = 1;
        }
    }

    //3 - Placer flag & couts
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

// #region étape
function getStep(){
    return parseInt($("#step").val());
}
function incrementStep(value = null){
    if(value !== null){
        $("#step").val(value);
    } else {
        $("#step").val(parseInt($("#step").val())+1);
    }
}
function decrementStep(){
    $("#step").val(parseInt($("#step").val())-1);
}
// #endregion

// #region souris actions
function caseMouseEnter(td){
    switch (getStep()) {
        case PUT_FLAG_BEGIN:
            td.find("img")
                .attr("src","./images/depart.png")
                .attr("width", (TD_WIDTH-7) + "px")
                .attr("heigth", (TD_HEIGHT-7) + "px")
                .attr("style", "opacity:0.5;");
            break;
    
        case PUT_FLAG_END:
            if(td.attr("id") !== "flag_begin"){
                td.find("img")
                    .attr("src","./images/fin.png")
                    .attr("width", (TD_WIDTH-7) + "px")
                    .attr("heigth", (TD_HEIGHT-7) + "px")
                    .attr("style", "opacity:0.5;");
            }
            break;
        
        case PUT_COSTS:
            if(td.attr("id") !== "flag_begin" && td.attr("id") !== "flag_end" && !td.hasClass("wall")){
                let input = td.find("input");
                td.find("input").removeClass("hidden");
            }
            break;
        case PUT_WALLS:
            if(td.attr("id") !== "flag_begin" && td.attr("id") !== "flag_end" && !td.hasClass("wall")){
                td.addClass("wall-h");
            }
            break;
    }
}
function caseMouseClick(td){
    switch (getStep()) {
        case PUT_FLAG_BEGIN:
            td.find("img")
                .attr("src","./images/depart.png")
                .attr("width", (TD_WIDTH-7) + "px")
                .attr("heigth", (TD_HEIGHT-7) + "px")
                .attr("style", "opacity:1;")
                .parent().attr("id", "flag_begin");
            incrementStep();
            break;
    
        case PUT_FLAG_END:
            if(td.attr("id") !== "flag_begin"){
                td.find("img")
                    .attr("src","./images/fin.png")
                    .attr("width", (TD_WIDTH-7) + "px")
                    .attr("heigth", (TD_HEIGHT-7) + "px")
                    .attr("style", "opacity:1;")
                    .parent().attr("id", "flag_end");
                incrementStep();
                
                //activer lancer, barrage, aleatoire
                activerBouton("btn_lancer")
                activerBouton("btn_barrage");
                activerBouton("btn_aleatoire")
            }
            break;
        
        case PUT_COSTS:
            if(td.attr("id") !== "flag_begin" && td.attr("id") !== "flag_end" && !td.hasClass("wall")){
                let input = td.find("input");
                input.val(parseInt(input.val())+1);
                couts_init[td.attr("x")][td.attr("y")]++;
            }
            break;
        case PUT_WALLS:
            if(td.attr("id") !== "flag_begin" && td.attr("id") !== "flag_end"){
                td.addClass("wall");
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
        case PUT_WALLS:
            if(td.attr("id") !== "flag_begin" && td.attr("id") !== "flag_end"){
                td.removeClass("wall-h");
            }
            break;
    }
        
}
// #endregion

// #region boutons actions
function lancerAlgo(){
    //init
    xA = parseInt($("#flag_begin").attr("x"));
    yA = parseInt($("#flag_begin").attr("y"));
    xB = parseInt($("#flag_end").attr("y"));
    yB = parseInt($("#flag_end").attr("x"));
    
    //couts
    couts[xA] = new Object();
    couts[xA][yA] = 0;

    //couts voisin
    do {
        action = 0;
        calculerCoutsVoisins();
    } while (action > 0);

    //desactiver les boutons 
    desactiverBouton("btn_barrage");
    desactiverBouton("btn_aleatoire");

    //lancer -> relancer
    $("#btn_lancer")
        .find("span").html("relancer")
        .click(function(){
            location.reload();
        });

    //colorer
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
function lancerBarrage(){
    //étape
    incrementStep(PUT_WALLS);

    //case en gris
    $("td").addClass("td-grey");

    //bouton selected
    $("#btn_barrage").addClass("hidden");
    $("#btn_terminer").removeClass("hidden");
    
    //desactiver autres btns
    desactiverBouton("btn_lancer");
    desactiverBouton("btn_aleatoire");
}
function arreterBarrage(){
    //étape
    incrementStep(PUT_COSTS);

    //case en gris
    $("td").removeClass("td-grey");

    //bouton selected
    $("#btn_barrage").removeClass("hidden");
    $("#btn_terminer").addClass("hidden");
    
    //desactiver autres btns
    activerBouton("btn_lancer");
    activerBouton("btn_aleatoire");
}
// #endregion

// #region bouton
function desactiverBouton(id){
    $("#" + id)
        .addClass("btn-disabled")
        .attr("disabled","");
}
function activerBouton(id){
    $("#" + id)
        .removeClass("btn-disabled")
        .removeAttr("disabled");
}

function randomBetween(min, max){
    return Math.floor(Math.random() * max) + min;
}

// #region case
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
// #endregion