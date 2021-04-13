

<?php

    
        /*######################################################
        ** Get Records Function V2.0  
        ** Function To Get All Data  From Database
        */ ####################################################### 
    
        function getAllFrom($field, $table, $where = null, $and = null, $orderfield, $ordering = "DESC"){

            global $con;

            $getAll = $con->prepare("SELECT * FROM $table $where $and ORDER BY $orderfield $ordering");

            $getAll->execute();

            $All = $getAll->fetchAll();

            return $All;
        }


    /*
    ** function to put title to the page dynamiclly
    ** v1
    */

        function getTitle() {

            global $pageTitle;

            if (isset($pageTitle)) {
                
                echo $pageTitle;

            } else {

                echo 'defult';
            }
        }

     /*
    ** Home Redirect Function V2.0
    ** This Function Accept Parametrs
    ** $theMsg = Echo THe Message [ Erorr , Success, Warning ]
    ** $url = The link You Want To Redirect TO
    ** $seconds = Seconds Befor Redirecting
    */

    function redirectHome($theMsg, $url = null, $seconds =3) {

        if ($url === null) {

            $url = 'index.php';

            $link = 'Homepage';
            
        }else {
            
            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

                $url = $_SERVER['HTTP_REFERER'];

                $link = 'Previous Page';

            }else {
                
                $url ='index.php';

                $link = 'Homepage';

            }
            
        }

        echo $theMsg;

        echo "<div class='alert alert-info'>you will be redirect to $link After $seconds Seconds</div>";

        header("refresh:$seconds;url=$url");

        exit();
    }    
      
    /*
    **Chaeck Items Function V1.0
    **Function to Check In Database [ Function Accept Parameters ]
    **Select = The Item to Select [ Example: users, items, categories ]
    **$from = The Table To Select Form [ Examble: users, item, categoris ]
    **$value = The VAlue Of Select [ Example: Osman, Box, Electronics ]
    */   
    
        function checkItem($select, $from, $value){

            global $con;

            $stetment = $con->prepare("SELECT $select FROM $from WHERE $select = ? ");

            $stetment->execute(array($value));

            $count = $stetment->rowCount();

            return $count;
        }


    /*
     **Chaeck Items Function V1.0  
     **
     **
     **
     **
    */
    
        function countItem($item, $table){

            global $con;

            $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

            $stmt2->execute();

            return $stmt2->fetchColumn();
        }


        /*
        ** Get Latest Items Function V1.0  
        ** Function To Get Latest Item From Database [users, items, Comments]
        ** $select = Field To Select From
        ** $table = the tabele to chose from
        ** $limit = number of record to get
        */  
    
        function getLatest($select, $table, $order, $limit = 5){

            global $con;

            $getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit ");

            $getstmt->execute();

            $rows = $getstmt->fetchAll();

            return $rows;
        }