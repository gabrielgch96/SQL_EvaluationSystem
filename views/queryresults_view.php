<?php

?>

<!DOCTYPE html>
<html>
<body>
        <?php
        $index = 0;
        echo"<br>";
                foreach ($queryResultArr as $valuesOfdb) {
                $Questionid =  $index++;
                echo " [";
                    if(!isset($valuesOfdb)){
                        echo "Wrong Syntax";
                    }else{
                        foreach ($valuesOfdb as $valuesdb){
                            foreach ($valuesdb as $values){
                                echo $values;
                                echo",";    
                            }
                            echo "<br>";   
                        }
                    }
                echo "]";
                ?><br><?php 
                ?> 
                
                <li> <br>
                 <?php
                }
        ?>
        </ul>
</body>
</html>