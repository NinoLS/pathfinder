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
        if($this->cases === []){
            echo "<form>";
            echo "<table>";
            
            //THEAD
            echo "<thead>";
            for ($i=0; $i < $this->x; $i++) { 
                echo "<tr>";
                for ($j=0; $j < $this->y; $j++) {
                    echo "<th></th>";
                }
            }
            echo "</thead>";


            //TBODY
            echo "<tbody>";
            for ($i=0; $i < $this->x; $i++) { 
                echo "<tr>";
                for ($j=0; $j < $this->y; $j++) { 
                    echo "
                    <td>
                        <img src='' class='put_flag'>
                    </td>
                    ";
                }
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</form>";
        }
    }
    
    

    

}

?>