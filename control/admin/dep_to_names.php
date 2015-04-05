<?php
include($_SERVER['DOCUMENT_ROOT'].'bd.php');
$d = $_POST["depSelOpt"];

$query = "SELECT name,id FROM name WHERE id_dep='$d'";
$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()){
	$names[] = $row;
}

echo "<select name='name' id='name' onchange='javascript:names();'>";
	echo "<option value=''></option>";
foreach ($names as $name){
	echo "<option value=".$name['id'].">".$name['name']."</option>";
}
echo "</select>";
?>