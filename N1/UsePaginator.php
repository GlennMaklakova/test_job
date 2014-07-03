<?php
  include "paginator_class.php";
    
  $newPaginator = new Paginator ("localhost", "root", "", "test");
  $table = "wp_options";
  
  //$newPaginator->setDefaults();
  $newPaginator->setNumOnPage(15);
  $newPaginator->setNeighbours(4);
  $newPaginator->setPage(7);
  $newPaginator->setStyle("style/style1.css");
  
  $newPaginator->displayPaginator($table);
?>