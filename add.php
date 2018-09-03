<?php

require("config.php");
require("function.php");

/* -------Dodawanie rekordów--------- */

if($_POST['action'] == 'add')
 
{
    //Przypisanie do zmiennych danych z formularza
    $parent_id = $_POST['parent_id'];
    $name = $_POST['name'];

    if(!empty($name) )
    {
    $result = $conn->prepare('INSERT INTO tree( name,parent_id) VALUES( :name , :parent_id )');
    $result -> bindValue(':name', $name, PDO::PARAM_STR);
    $result -> bindValue(':parent_id', $parent_id, PDO::PARAM_INT);
    $result->execute();

    header('Location: http://tree.ideo'); //Przenosi spowrotem na strone główną
    
    }else
    { 
        header('Location: http://tree.ideo/?empty');   

     }
}

/* ----------Usuwanie rekordów----------- */

if($_POST['action'] == 'delete')
{
     $del = $_POST['delete'];
    
    
    if (isset($_POST['del_family'])) 
    {
        // Usuwamy rodzica z dziecmi

        $family =selectChild($conn,':del');
        
       foreach($family as $element)
       {
            $result = $conn -> prepare('DELETE FROM tree WHERE ( id = '.(int)$element. ')');   
            $result -> execute();
       }


        $result = $conn-> prepare('DELETE FROM tree WHERE (id = :del )'); 
        $result -> bindValue(':del', $del, PDO::PARAM_INT);

        $result -> execute(); 
    } 
       if (isset($_POST['delete_base'])) 
        {
               $result = $conn->exec('DELETE FROM `tree` ; ALTER TABLE tree AUTO_INCREMENT = 1;');
    
                header('Location: http://tree.ideo');
        }
    
        elseif (isset($_POST['del_parent'])) 
        {
            // Usuwamy rodzica a dzieci nie 
            //Wyciągamy wartość rodzica dla  parent_id
             
            $result = $conn->prepare('SELECT parent_id FROM tree WHERE id = :del ');
            $result -> bindValue(':del', $del, PDO::PARAM_INT);
            $result ->execute();
            
            $parent_id = $result -> fetch();   // Wartośc parent_id rodzica


            //Aktualizacja parent_id dzieci nieusunietych
            $result = $conn->prepare('UPDATE  tree SET parent_id = '.$parent_id ["parent_id"].' where parent_id = :del ');
            $result -> bindValue(':del', $del, PDO::PARAM_INT);
            $result->execute();
            
            $result = $conn-> prepare('DELETE FROM tree WHERE (id = :del)');  
            $result -> bindValue(':del', $del, PDO::PARAM_INT);
            $result -> execute(); 
        

        }

    
    
    header('Location: http://tree.ideo');
    
}

/* ----------Sortowanie rekordów----------- */

if($_POST['action'] == 'sort')
{
    $sort = $_POST['sort2'];

    header("Location: http://tree.ideo/?sort=$sort");
}

/* --------Edycja rekordów--------- */

if($_POST['action'] == 'edit')
 
{
    $edit2 = $_POST['edit_id'];
    $edit_name = $_POST['edit_name'];
    
    if(!empty($edit_name) )
    {    
        $result = $conn->prepare('UPDATE  tree SET name = :edit_name WHERE (id = :edit2)');

        $result -> bindValue(':edit_name', $edit_name, PDO::PARAM_STR);
        $result -> bindValue(':edit2', $edit2, PDO::PARAM_INT);

        $result->execute();

        header('Location: http://tree.ideo');
    } 
    else
    { 
        header('Location: http://tree.ideo/?empty');   

    }
}
/* --------Przenoszenie rekordów--------- */

if($_POST['action'] == 'transfer')
 
{
    $transfer_old = $_POST['transfer_old'];
    $transfer_new = $_POST['transfer_new'];
    
    if($transfer_new != $transfer_old)
    {
        if((checkChild($conn,$transfer_old,$transfer_new ))  )
        {
            $result = $conn->prepare('UPDATE  tree SET parent_id = :transfer_new WHERE (id =  :transfer_old )');

            $result -> bindValue(':transfer_old', $transfer_old, PDO::PARAM_INT);
            $result -> bindValue(':transfer_new', $transfer_new, PDO::PARAM_INT);

            $result->execute();
            header('Location: http://tree.ideo');   

        }
        else
        { 
            header('Location: http://tree.ideo/?send');   

        }
    }
    else
    { 
        header('Location: http://tree.ideo/?send');   

     }
   
}

if($_POST['action'] == 'create_example_base'){
    $result = $conn->exec('
    DELETE FROM `tree` ;
    ALTER TABLE tree AUTO_INCREMENT = 1;
    INSERT INTO `tree`(`id`, `name`, `parent_id`,`sort`) VALUES (1,"Gatunek",0,1);
    INSERT INTO `tree`(`id`, `name`, `parent_id`,`sort`) VALUES (2,"Komedia",1,2);
    INSERT INTO `tree`(`id`, `name`, `parent_id`,`sort`) VALUES (3,"Dramat",1,3);
    INSERT INTO `tree`(`id`, `name`, `parent_id`,`sort`) VALUES (4,"Akcja",1,4);
    INSERT INTO `tree`(`id`, `name`, `parent_id`,`sort`) VALUES (5,"Świat Według Kiepskich",2,5);
    INSERT INTO `tree`(`id`, `name`, `parent_id`,`sort`) VALUES (6,"Trzynasty posterunek",2,6);
    INSERT INTO `tree`(`id`, `name`, `parent_id`,`sort`) VALUES (7,"Szefowie szefów",2,7);
    INSERT INTO `tree`(`id`, `name`, `parent_id`,`sort`) VALUES (8,"Dramat 1",3,8);
    INSERT INTO `tree`(`id`, `name`, `parent_id`,`sort`) VALUES (9,"Dramat 2",3,9);
    INSERT INTO `tree`(`id`, `name`, `parent_id`,`sort`) VALUES (10,"Super Dramat",3,10);
    INSERT INTO `tree`(`id`, `name`, `parent_id`,`sort`) VALUES (11,"Akcja 1",4,11);
    INSERT INTO `tree`(`id`, `name`, `parent_id`,`sort`) VALUES (12,"Moze byc akcja",4,12);
    INSERT INTO `tree`(`id`, `name`, `parent_id`,`sort`) VALUES (13,"Słaba akcja",4,13);
    ');
    
    header('Location: http://tree.ideo');

}
if($_POST['action'] == 'cancel')
{
    header('Location: http://tree.ideo');  
}
$conn = null;

?>
