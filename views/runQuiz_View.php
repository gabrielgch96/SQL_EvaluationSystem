<?php
 echo $id;
 echo "testting";

 require_once("../model/Quiz_DB.php");
 function test(){
     echo("hello");
 }
?>


 <body>

 <form action="runQuiz_View" name="" method ="POST">
        <ul>
        <?php
        
                foreach ($dbs as $index => $bdName) {
                $Questionid =  $index +1 ;
                ?> 
                <li> <?php echo $bdName['question_text'];  ?>  <br>

                <textarea name="Question<?php echo $Questionid ?>" id="<?php echo $Questionid ?>" cols="30" rows="10" required> </textarea>       
                    <li>
                
                
                 <?php
                }
        ?>
        <input name="count" value = "<?php echo $index+1; ?>" hidden />
        <?php $count = $index; ?>
        </ul>
        <button type = "submit"> Submit</button>
    </form>
                
   </body>     
</html>
