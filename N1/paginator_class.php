<?php
include "mysql/mysql.php";

class Paginator extends sql_db
{
    var $numOnPage;
	var $neighbours;
	var $currentPage;
	var $style;

function setDefaults () 
  {
    $this->numOnPage = 10;
	$this->neighbours = 3;
	$this->style="style/style1.css";
  }
function setNumOnPage ($num)  // количество рядов на странице
  {
    $this->numOnPage = $num;
  }
function setNeighbours ($num)  //показываемое количество страниц возле текущей 
  {
	$this->neighbours = $num;
  }
  
function setPage ($pagenum)
  {
	$this->currentPage=$pagenum;
  }
function setStyle ($path) // задание css стиля
  {
    $this->style = $path; 
  }
	
function displayPaginator($tableName)
  {  
    print "<head><link rel=\"stylesheet\" type=\"text/css\" href=\"".$this->style."\"/></head><body>";  
    if (isset($_GET['page']))
	{
	  $page = intval($_GET['page']);
	}
	elseif (isset($this->currentPage))
	{
	  $page = $this->currentPage;
	}
	else
    {
      $page = 1;
    }
    
	//$tableName="wp_options";
    $query = "SELECT COUNT(*) AS `counter` FROM `".$tableName."`"; //подсчитать кол-во элементов всего
    $rowset = mysql_query($query) or die(mysql_error());
    $row = mysql_fetch_assoc($rowset);
    $numElements = $row['counter'];

    $pages = ceil($numElements/$this->numOnPage); // количество страниц

    if ($page < 1)  //текущая страница не меньше 1 и не больше последней страницы
    {
        $page = 1;
    }
    elseif ($page > $pages) 
    {
      $page = $pages;
    }

    $start = ($page-1)*$this->numOnPage; //с какой записи начинаем делать выборку
  
    if ($start < 0) $start = 0; // когда в таблице нет записей

    $query = "SELECT * FROM `wp_options` LIMIT {$start}, {$this->numOnPage}";
    $rowset = mysql_query($query) or die(mysql_error());

    while ($row = mysql_fetch_assoc($rowset)) // вывод записей из базы
    {
        print $row[option_id]." ".$row[option_name]." ".$row[option_value]." ".$row[autoload]."<br />";    
    }

  
 //листалка:
                     
    $left_neighbour = $page - $this->neighbours;  // какие страницы соседние
    if ($left_neighbour < 1) $left_neighbour = 1;

    $right_neighbour = $page + $this->neighbours;
    if ($right_neighbour > $pages) $right_neighbour = $pages;

  //вывести листалку
   if ($page == 1) { print ' <b>[1]</b>';} else {print ' <a href="?page=1">1</a>';}
   if ($page > $this->neighbours + 2)                     
   {
      print ' ... '; 
   }
    if ($page < $this->neighbours + 2)
    {
        $startPage=2;
	    $endPage=$right_neighbour;
    }
    elseif ($page >= $pages - $this->neighbours)
    {
        $startPage=$left_neighbour;
	    $endPage=$pages - 1;
    }
    else
    {
        $startPage=$left_neighbour;
	    $endPage=$right_neighbour;
    }
  
    for ($i=$startPage; $i<=$endPage; $i++) 
    {
      if ($i != $page) 
	  {
          print ' <a href="?page=' . $i . '">' . $i . '</a> ';
      }
      else 
	  {
          print ' <b>[' . $i . ']</b> '; // выбранная страница
      }
    }

    if ($page < $pages - $this->neighbours - 1 ) 
    {
      print ' ... ';  
    }
  
    if ($page == $pages) { print ' <b>['. $pages .']</b>';} 
       else {print ' <a href="?page='. $pages .'">'. $pages .'</a>';}
	print "</body>";
  }
	 
}

?>