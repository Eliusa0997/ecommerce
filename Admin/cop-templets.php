










<?php
/*
=======================================================
== template page
=======================================================
*/





ob_start(); // Output Buffering Start

session_start();

if (isset($_SESSION['Username'])) {

    $pageTitle = '';
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // start manage page

    if ($do == 'Manage') {      // manage page

    }elseif ($do == 'Add') {
    
    }elseif ($do == 'Insert') {

    }elseif ($do == 'Edit') {

    }elseif ($do == 'Update') {    
        
    }elseif ($do == 'Delete') {

    }elseif ($do == 'Activate') {

    }    

    include   $tpl . 'footer.php';
     
}else {

    header("Location: index.php");

    exit();
}

ob_end_flush();
?>    
