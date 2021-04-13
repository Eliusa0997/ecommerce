



<?php
    ini_set('display_errors', 'on');
    error_reporting(E_ALL);

    include 'Admin/connect.php';

    $sessionUser = '';

    if (isset($_SESSION['user'])) {
        $sessionUser = $_SESSION['user'];
        
        
    }

// Route
    $eng     =  'includes/languages/';         // languages ditectory
    $fanc    =  'includes/function/';         // function ditectory
    $tpl     =  'includes/templates/';       // templated ditectory
    $css     =  'layout/css/';              // css ditectory
    $css     =  'layout/css/';             // css ditectory
    $js      =  'layout/js/' ;           // js ditectory


// include the important files
    include   $fanc . 'function.php';
    include   $eng  .'english.php';
    include   $tpl  .'header.php';
