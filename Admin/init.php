<?php
    include 'connect.php';
   
// Route
    $eng     =  'includes/languages/';         // languages ditectory
    $fanc    =  'includes/function/';         // function ditectory
    $tpl     =  'includes/templates/';       // templated ditectory
    $css     =  'layout/css/';              // css ditectory
    $css     =  'layout/css/';             // css ditectory
    $js      =  'layout/js/' ;           // js ditectory
  


// include the important files
    include   $fanc . 'function.php';
    include   $eng.'english.php';
    include   $tpl.'header.php';
    include   $tpl.'footer.php';
 
// include navbar in all pages expect the one with nonavbar varubale
    if (!isset($noNavbar)) {

        include $tpl . 'navbar.php';
        
    }
    