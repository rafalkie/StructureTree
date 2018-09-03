<?php

/* Funkcja sprawdzająca czy dany wezeł ma wezeł lub liście */

function hasChildren($rows,$id) 
{
    foreach ($rows as $row) 
    {
        if ($row['parent_id'] == $id)
            return true;
    }
    return false;
}

/*  Buduje drzewo zwraca w postaci listy  */

function buildTree($rows,$parent=0)
{      
  $result = "<ul class=' activeSubMenu'>";
    
  foreach ($rows as $row)
  {
    if ($row['parent_id'] == $parent)
    {
        $result.= "<li class='hasSubMenu '><a>{$row['name']}</a>"; 
         
        if (hasChildren($rows,$row['id']))
            $result.= buildTree($rows,$row['id']);
        
            $result.= "</li>";
        }
  }
  $result.= "</ul>";
    
  return $result;
}

/* Szuka wszystkich węzłów i liści gałęzi */

function searchChild($rows,$parent)
{ 
       static $result_child=[];  
        foreach ($rows as $row )
    {  
        if ($row['parent_id'] == $parent)
        {
                $result_child[]=$row['id'] ; 
                
                if (hasChildren($rows,$row['id']))
                { 
                   searchChild($rows,$row['id'] );    
                }
        }
    }
    return $result_child;
}

/* Wyciąga z bazy wszystkie elementy drzewa , zapisuje do tablicy i na końcu wywołuje funkcje budującą drzewo  */

   
//function selectStore($conn,$test=1) 
//{
//   
//
//    
//    $sth = $conn->prepare("  SELECT * FROM tree WHERE  (parent_id != 1 AND parent_id != $test ) ");
//
//    $sth->execute();
//    
//    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
//    
//    $sth2 = $conn->prepare(" SELECT * FROM tree WHERE  (parent_id = 1 OR parent_id = $test ) order by name asc ");
//    $sth2->execute();
//    $result2 = $sth2->fetchAll(PDO::FETCH_ASSOC);
//    $wynik = array_merge_recursive($result2, $result);
//    echo buildTree($wynik);
//} 
function selectStore($conn,$test=1) 
{
   
    
    $sth2 = $conn->prepare(" SELECT * FROM tree order by sort asc ");
    $sth2->execute();
    $result2 = $sth2->fetchAll(PDO::FETCH_ASSOC);
//  usort($result2,'id');
//    echo '<pre>';
//    print_r($result2);
//    echo '</pre>';
//    foreach ($result2 as $row) 
//    {
//        echo '<option  value="'. $row['id'] .'">'.$row['name'].'</option>';
//    } 
    echo buildTree($result2);
} 


/*  Zwraca całą gałąź dangeo rodzica i na końcu zwraca funkcje szukająca węzłów*/

function selectChild($conn,$parent)
{
    $sth = $conn->prepare("SELECT id,parent_id,name FROM tree ");
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return searchChild($result,$parent);
}

/*  Wyświetla liste elementów w formularzu   */

function selectList($conn)
{
    $sth = $conn->prepare("SELECT id,parent_id,name FROM tree ");
    $sth->execute();
    
    foreach ($sth as $row) 
    {
        echo '<option  value="'. $row['id'] .'">'.$row['name'].'</option>';
    }  
}

/* Funkcja dla przenoszenia elementów sprawdza czy czasem gałąź z węzłami nie przenosimy do tych wezłów tej gałęźi   */

function checkChild($conn,$transfer_old,$transfer_new)
{
    $family = selectChild($conn,$transfer_old);
    print_r($family);
    
    $value = true;
    
       foreach($family as $element)
       {
           if(($element == $transfer_new)  )
           {
               $value = false;
               break;
           }
           else
           {
               $value = true;
               
           }   
       }  
    return $value;
}  

?>