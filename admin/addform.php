<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('date.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");

$result = setlocale(LC_ALL, 'ru_RU.UTF-8');


if ($_GET['nextweek']=='yes'){
	echo "<a href='/admin/index.php?nextweek=yes'><img src='/img/previous.png' title='Вернуться обратно'></a>";
}
else{
	echo "<a href='/admin'><img src='/img/previous.png' title='Вернуться обратно'></a>";
}



if (isset($_POST) && !empty($_POST)){
	$name = $_POST['name'];
	$descr = $_POST['descr'];
	$date = $_POST['date'];
	$nextweek = $_POST['nextweek'];


	$query_names = "INSERT INTO todo(id_name,date,descr) VALUES ('$name','$date','$descr')";
	$result = $mysqli->query($query_names);
	
	if ($nextweek=='yes'){
		header("Location: http://$_SERVER[SERVER_ADDR]/admin/index.php?nextweek=yes");
	}
	else{
		header("Location: http://$_SERVER[SERVER_ADDR]/admin");
	}
}

if(isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];
	$gl_date = $_GET['date'];
	$nextweek = $_GET['nextweek'];
}




$query_names = "SELECT name,id FROM name";

$result = $mysqli->query($query_names);

while ($row = $result->fetch_assoc()){
	$rows[] = $row;
}




?>
<h2>Добавление мероприятия</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    ФИО: <select name="name">
	
	<?php 
	foreach($rows as $row){
		if ($row['id'] == $id){
			echo "<option selected value=".$row['id'].">".$row['name']."</option>";	
		}
		else {
			echo "<option value=".$row['id'].">".$row['name']."</option>";	
		}
    }
	echo "</select>";
	?>
	Дата: <input type="text" name="date" value="<?php if(isset($gl_date)){echo $gl_date;} ?>"><br/>
    Мероприятие: <br/><textarea name="descr" cols="100" rows="50"></textarea><br/>
	<input type="hidden" name="nextweek" value="<?php echo $nextweek ?>">
	<input type="submit">
    </form>

<?php
$mysqli->close();
?>

