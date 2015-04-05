<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'bd.php');
include('date.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");

echo "<a href='/admin/list.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";
$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

if (isset($_POST) && !empty($_POST)){
$name = $_POST['name'];
$weight= $_POST['weight'];
$show= $_POST['show'];
$id_dep= $_POST['id_dep'];
$iptel= $_POST['iptel'];

$query_names = "INSERT INTO name(`name`,`weight`,`show_plan`,`id_dep`,`iptel`) VALUES ('$name','$weight','$show','$id_dep','$iptel')";


$result = $mysqli->query($query_names);


header("Location: http://10.50.10.100/admin/list.php");

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
	IP телефон: <input type="text" name="iptel"><br/>
	Показывать: <select name="show">
	<option value="1">Да</option>
	<option value="0">Нет</option>
	</select><br/>
	<input type="submit">
    </form>

<?php
$mysqli->close();
?>

