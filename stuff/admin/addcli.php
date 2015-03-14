<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");

echo "<a href='/stuff/admin/list.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";
$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

if (isset($_POST) && !empty($_POST)){
$name = $_POST['name'];
$weight= $_POST['weight'];
$id_dep= $_POST['id_dep'];


$query_names = "INSERT INTO name(`name`,`weight`,`id_dep`) VALUES ('$name','$weight','$id_dep')";
$result = $mysqli->query($query_names);


header("Location: http://10.50.10.100/stuff/admin/list.php");
}


$query = "SELECT id,name FROM department";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()){
	$rows[] = $row;
}

?>
<h2>Добавление сотрудника</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    ФИО:            <input type="text" name="name"><br/>
	Департамент: <select name="id_dep">
<?php
	foreach ($rows as $row){
		echo "<option value=".$row['id'].">".$row['name']."</option>";
	}
?>
	</select><br/>
	Приоритет выдачи: <input type="text" name="weight"><br/>
	<input type="submit">
    </form>

<?php
$mysqli->close();
?>

