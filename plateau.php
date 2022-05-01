<?php

class Plateau{
    private $x;
    private $y;
    private $cases;

    public function __construct($x,$y){
        $this->x = $x;
        $this->y = $y;
        $this->cases = [];

        $this->afficherPlateau();
    }

    public function afficherPlateau(){
        echo "<form>";
        echo "<table>";
        
        echo "</table>";
        echo "</form>";
    }
}

?>