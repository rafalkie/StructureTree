<?php
    require("function.php");
    require("config.php");
?>

    <!doctype html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Tree</title>

        <link rel="Stylesheet" type="text/css" href="css/style.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    </head>

    <body>

        <div class="container">
            <div class="col-2">
                <h2>Funkcje struktury drzewiastej</h2>
                <form class="background" method="POST" action="add.php">
                    <input type="hidden" name="action" value="add" />
                    <h3>Dodaj</h3>
                    <div class="block">
                        <a>Podaj nazwe:</a>
                        <input class="place" type="text" name="name" value="" placeholder="  Nazwa" />
                    </div>
                    <br>
                    <div class="block">
                        <a>Wybierz rodzica:</a>
                        <select class="place" name="parent_id">
                    <option  value="0">Brak</option>  

                        <?php
                          selectList($conn);
                        ?>

                    </select>
                    </div>
                    <input class="button" type="submit" name="add" value="Dodaj" />
                </form>


                <form method="POST" action="add.php">
                    <input type="hidden" name="action" value="delete" />
                    <h3>Usuń</h3>
                    <div class="block">
                        <a>Wybierz element: </a>
                        <select class="place" name="delete"> 
                             <?php
                              selectList($conn);
                             ?>
                        </select>
                    </div>
                    <br>
                    <input onclick="myFunction()" class="button" type="button" value="Usuń" />
                    <input class="button" type="submit" name="delete_base" value="Usuń wszystko" />
                    <div id="window_delete">
                        <p class="info ">Informacja</p>
                        <p class=" info-text">Wybierz czy chcesz usunąć całą gałąź z wszystkimi wezłami jeśli posiada, czy tylko ten element ("Rodzica") ??</p>

                        <input class="button_reverse" type="submit" name="del_parent" value="Usuń element" />
                        <input class="button_reverse" type="submit" name="del_family" value="Usuń Gałąź" />
                        <input class="button_alert" type="submit" name="cancel" value="Anuluj" />

                    </div>
                </form>





                <form class="background" method="POST" action="add.php">
                    <input type="hidden" name="action" value="sort" />
                    <h3>Sortuj</h3>
                    <div class="block">
                        <a>Wybierz element: </a>
                        <select class="place" name="sort2"> 
                        <option  value="0">Brak</option>  
                        <?php
                            selectList($conn);
                        ?>      
                    </select>
                    </div>
                    <input class="button" type="submit" name="sort" value="Sortuj" />
                </form>


                <form class="background" method="POST" action="add.php">
                    <input type="hidden" name="action" value="edit" />
                    <h3>Edytuj</h3>
                    <div class="block">
                        <a>Wybierz element: </a>
                        <select class="place" name="edit_id">
                        <?php
                            selectList($conn);
                        ?>
                    </select>
                    </div>
                    <br>
                    <div class="block">
                        <a>Nowa nazwa: </a>
                        <input class="place" type="text" name="edit_name" value="" placeholder="  Nowa nazwa" />
                    </div>
                    <input class="button" type="submit" name="edit" value="Edytuj" />
                </form>


                <form name="transfer" method="POST" action="add.php">
                    <input type="hidden" name="action" value="transfer" />
                    <h3>Przenoszenie elementow:</h3>
                    <div class="block">
                        <a>Wybierz element:</a>
                        <select class="place" name="transfer_old">

                        <?php
                            selectList($conn);
                        ?>
                    </select>
                    </div>
                    <br>
                    <div class="block">
                        <a>Przenieś do: </a>
                        <select class="place" name="transfer_new">
                 <option  value="0">Brak</option>    

                        <?php
                                 selectList($conn);
                        ?>

                    </select>
                    </div>
                    <input class="button" type="submit" name="transfer" value="wybierz" />
                </form>
            </div>



            <div class="col-2 ">
                <h2 class="center">Struktura</h2>
                <div id="tree">
                    <form class="button_tree" method="POST" action="add.php">
                        <input type="hidden" name="action" value="create_example_base" />
                        <input class="button" type="submit" name="create_example_base" value="Wygeneruj przykładowe drzewo" />
                    </form>
                    <?php  
                          if(isset($_GET["sort"]))
                          {  
                              selectStore($conn,($_GET["sort"])) ; 
                          }
                          else
                          {
                              selectStore($conn,0) ;   
                          }
                     ?>

                </div>
            </div>
            <div clas="clear"></div>

            <div id="window_error">
                <form method="POST" action="add.php">
                    <input type="hidden" name="action" value="cancel" />
                    <p class="info ">Informacja</p>
                    <p class=" info-text">Wypełnij pole !!!!</p>
                    <input class="button_reverse" type="submit" name="cancel" value="OK" />
                </form>

            </div>
            <div id="window_error2">
                <form method="POST" action="add.php">
                    <input type="hidden" name="action" value="cancel" />
                    <p class="info ">Informacja</p>
                    <p class=" info-text">Chcesz wykonać niemożliwą operacje przeniesienia !!!</p>
                    <input class="button_reverse" type="submit" name="cancel" value="OK" />
                </form>

            </div>

            <?php

                if(isset($_GET["send"]))
                {   
                 ?>
                <script> document.getElementById("window_error2").style.display = "block"; </script>
                
                <?php 
                    
                }
                
                
                if(isset($_GET["empty"]))
                {   
                 ?>
                <script> document.getElementById("window_error").style.display = "block";</script>

                <?php 
                    
                }
                ?>

                <script>
                    $("ul li a").on("click", function() {
                        if ($(this).parent().hasClass("hasSubMenu")) {

                            if ($(this).parent().find("ul").hasClass("activeSubMenu")) {
                                $(this).parent().find("ul").removeClass("activeSubMenu");
                            } else {
                                $(this).parent().find("ul").addClass("activeSubMenu");
                            }
                        }
                    });


                    function myFunction() {
                        document.getElementById("window_delete").style.display = "block";
                    }

                </script>






        </div>
    </body>

    </html>
