<?php 

   function lang ($phrase){

     static $lang = array(  

        'HOME_ADMIN'   => 'home',
        'CATEGORIES'   => 'Categories',
        'ITEMS'        => 'Items',
        'MEMBERS'      =>  'Members',
        'COMMENTS'     => 'Comments',
        'STATISTICS'   => 'Statistics',
        'LOGS'         => 'logs',
        
     );
     return $lang[$phrase];
  }
