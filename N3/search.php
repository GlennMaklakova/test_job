<?php
function setAnySymbol($str){
  $newstr_array=explode("*",$str);
  $newstr="";
  for ($i=0; $i<(count($newstr_array)-1); $i++){
     $newstr=$newstr.$newstr_array[$i]."%";
  }
  $newstr=$newstr.$newstr_array[count($newstr_array)-1];
  return $newstr;
}
function setOneSymbol($str){
  $newstr_array=explode("?",$str);
  $newstr="";
  for ($i=0; $i<(count($newstr_array)-1); $i++){
     $newstr=$newstr.$newstr_array[$i]."_";
  }
  $newstr=$newstr.$newstr_array[count($newstr_array)-1];
  return $newstr;
}
function shieldUnderscore($str){
    $newstr_array=explode("_",$str);
    $newstr="";
    for ($i=0; $i<(count($newstr_array)-1); $i++){
     $newstr=$newstr.$newstr_array[$i]."|_";
  }
  $newstr=$newstr.$newstr_array[count($newstr_array)-1];
  return $newstr;
}
function shieldPercent($str){
    $newstr_array=explode("%",$str);
    $newstr="";
    for ($i=0; $i<(count($newstr_array)-1); $i++){
     $newstr=$newstr.$newstr_array[$i]."|%";
  }
  $newstr=$newstr.$newstr_array[count($newstr_array)-1];
  return $newstr;
}
echo "<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\"/>
</head>
<body>
<form method=\"POST\"> 
 <div id=\"forma\">
 <h3>Форма поиска</h3>
 <p>В ключевой фразе Вы можете использовать специальные символы: '*'
 - заменяет любую комбинацию символов; '?' - заменяет один символ. Таким образом на
 слово 'газ*' найдется 'газ', 'газета', 'газированный' и т.д. на 'баннер?' - 'баннера', 'баннеру',
 но не 'баннер' и 'баннерный'.</p>
 <input type=\"text\" name=\"keyword\">
 <input type=\"submit\" name=\"send\" value=\"Найти\">
 </div>
 <div id=\"res\">";
 $dbDatabase = "test"; 

 $con = mysql_connect("localhost", "root", "") or die(mysql_error());
 mysql_select_db($dbDatabase) or die(mysql_error());
 echo "По вашему запросу:";

 if (isset($_POST['keyword'])) {$keyword = $_POST['keyword'];}

 $keyword = trim($keyword); // убираются лишние пробелы из начала и конца строки
 $keyword = stripslashes($keyword); //удаляет экранирование символов
 $keyword = htmlspecialchars($keyword); // заменяет html теги на коды
 echo "<b>$keyword</b><br />";

 $shU="";
 $shP="";
 
 $shU= shieldUnderscore($keyword);
 $keyword = $shU;
 $shP= shieldPercent($keyword);
 $keyword = $shP;
 $keyword = setAnySymbol($keyword);
 $keyword = setOneSymbol($keyword);

 if (!($shU || $shP)) {
     $search_query = "SELECT * FROM books WHERE book_name LIKE '".strtoupper($keyword)."'"; 
 }
 else{
     $search_query = "SELECT * FROM books WHERE book_name LIKE '".strtoupper($keyword)."' ESCAPE '|'";
 }
 $query = mysql_query($search_query); // Здесь непосредственно происходит поиск

 if(!$query)
 {
 echo "Поиск не осуществлен. Код ошибки:<br />";
 echo exit(mysql_error());
 }
 if (mysql_num_rows($query) > 0)
 {
 $myrow = mysql_fetch_array($query);

 do
 {
 echo "-->".$myrow["book_name"]."<br />"; 
 }while ($myrow = mysql_fetch_array($query));
 
 } else echo "Ничего не найдено.<br/>";
 echo "</div>
 </form>
 </body>"
?>