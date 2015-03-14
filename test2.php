<?php
include('bd_sakha.gov.ru.php');


$query = "SELECT name,id FROM name";
$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()){
	$names[] = $row;
}

echo "<select name='name' id='name' onchange='javascript:names();'>";
foreach ($names as $name){
	echo "<option value=".$name['id'].">".$name['name']."</option>";
}
echo "</select>";
?>