 <?php
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
$user = $_GET['login'];
$pwd = $_GET['password'];    
$query = "SELECT password,id,name FROM name WHERE login='$user'";
$results = $mysqli->query($query);
$result = $results->fetch_assoc();     
if (password_verify($pwd,$result['password'])){
	echo "OK-";
	$name = $result['name'];
	$id = $result['id'];
	echo $name."-";
	echo $id;
}
else{
	echo "FAIL-fuck-fuck";
}

?> 
