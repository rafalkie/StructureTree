<?php

$host = '55.mysql.ideo' ;   // nazwa hosta 
$user = 'root' ;  // użytkownik
$password = 'root' ;  // hasło 
$db = 'praktykant5';  // baza danych

// ## Połączenie php PDO MySql i prosty zapis wiersza danych 

try {  
     
     $conn = new \PDO("mysql:host={$host};dbname={$db}", $user, $password,
                    array(
                          PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8",
                          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                          )
                    );

     

   } catch (PDOException $e) {  

 echo "Wystąpił błąd PDO";  

}

?>


<!--
**Jak jest pusta baza zeby błedów niewyrzucało przy klikaniu w przyciski


**Zabezpieczyc przed pustym polem:
    -dodawanie
    -edycja

**Sortowanie

**Ostylować

**Zabezpieczyc kod przed SQL injection




-->