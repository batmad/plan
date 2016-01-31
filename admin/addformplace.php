<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");

echo "<a href='/admin/list.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";
$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

if (isset($_POST) && !empty($_POST)){
	$name = $_POST['name'];
	$query_names = "INSERT INTO place(`name`) VALUES ('$name')";
	$result = $mysqli->query($query_names);
	header("Location: http://$_SERVER[SERVER_ADDR]/admin/placelist.php");
}


?>
<h2>Добавление места проведения совещания: </h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    Место:            <input type="text" name="name"><br/>

	
	<input type="submit">
    </form>

<?php
$mysqli->close();
?>

