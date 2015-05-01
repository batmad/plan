<?php
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
$d = $_POST["nameSelOpt"];

$month = date('m');
$year = date('Y');
$query = "SELECT id,date FROM control WHERE spec_id='$d' AND date LIKE '%-$month-$year'";
$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()){
	$dates[] = $row;
}




echo "<select name='dates' id='dates' onchange='javascript:date2();'>";
foreach ($dates as $date){
	echo "<option value=".$date['id'].">".$date['date']."</option>";
}
echo "</select>";
?>