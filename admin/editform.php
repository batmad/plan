<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'bd.php');
include('date.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");

$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

if (isset($_POST) && !empty($_POST)){
$id = $_POST['id'];
$name = $_POST['id_name'];
$descr = $_POST['descr'];
$date = $_POST['date'];
$nextweek = $_POST['nextweek'];

$query_names = "UPDATE todo SET id_name='$name', date = '$date' ,descr = '$descr', id_name = '$name' WHERE id = '$id'";

$result = $mysqli->query($query_names);
if ($nextweek=='yes'){
	header("Location: http://10.50.10.100/admin/index.php?plan=nextweek");
}
else{
	header("Location: http://10.50.10.100/admin");
}
}


if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];
	$nextweek = $_GET['nextweek'];
	
	$query = "SELECT descr,date,id_name FROM todo WHERE id='$id'";
	$res = $mysqli->query($query);
	
	$todo = $res->fetch_assoc();

}



$query_names = "SELECT name,id FROM name";

$result = $mysqli->query($query_names);

while ($row = $result->fetch_assoc()){
	$rows[] = $row;
}




?>
<a href="/admin/index.php">Вернуться </a>
<h2>Редактирование мероприятия</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    ФИО: <select name="id_name">
	
	<?php 
	foreach($rows as $row){
		if ($row['id'] == $todo['id_name']){
			echo "<option selected value=".$row['id'].">".$row['name']."</option>";	
		}
		else {
			echo "<option value=".$row['id'].">".$row['name']."</option>";	
		}
	}
	echo "</select>";
	?>
	Дата: <input type="text" name="date" value="<?php echo $todo['date']?>"><br/>
    Мероприятие: <br/><textarea name="descr" cols="100" rows="50"><?php echo $todo['descr'] ?></textarea><br/>
	<input type="hidden" name="id" value="<?php echo $id?>">
	<input type="hidden" name="nextweek" value="<?php echo $nextweek ?>">
	<input type="submit">
    </form>

<?php
$mysqli->close();
?>

