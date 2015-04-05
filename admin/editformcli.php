<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'bd.php');
include('date.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");

$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

if (isset($_POST) && !empty($_POST)){
$id = $_POST['id'];
$name = $_POST['name'];
$weight = $_POST['weight'];
$show = $_POST['show'];
$id_dep = $_POST['id_dep'];
$iptel = $_POST['iptel'];
$head = $_POST['head'];


$query_names = "UPDATE name SET `name`='$name', `weight` = '$weight', `show_plan`='$show',`id_dep`='$id_dep', `iptel`='$iptel', `head`='$head' WHERE `id` = '$id'";
$result = $mysqli->query($query_names);

header("Location: http://10.50.10.100/admin/list.php");

}


if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];

}

$query = "SELECT id,name FROM department";
$result = $mysqli->query($query);
while ($dep = $result->fetch_assoc()){
	$deps[] = $dep;
}

$query = "SELECT name,weight,show_plan,id_dep,iptel,head FROM name WHERE `id` = '$id'";
$result = $mysqli->query($query);
$result = $result->fetch_assoc();
$name = $result['name'];
$weight = $result['weight'];
$show = $result['show_plan'];
$id_dep = $result['id_dep'];
$iptel = $result['iptel'];
$head = $result['head'];




?>
<a href="/admin/list.php">Вернуться </a>
<h2>Редактирование сотрудника</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    ФИО: <input type="text" name="name" value="<?php echo $name ?>"><br/>
	Приоритет выдачи: <input type="text" name="weight" value="<?php echo $weight?>"><br/>
	Показывать: <select name="show">
	<?php 
	if ($show==1){
		echo '<option selected value="1">Да</option><option value="0">Нет</option>';
	}
	else {
		echo '<option value="1">Да</option><option selected value="0">Нет</option>';
	}
	?>
	</select><br/>
	Руководитель:
	<?php
	if ($head){
		echo "Да<input type='radio' name='head' value='1' checked>Нет<input type='radio' name='head' value='0'>";
	}
	else {
		echo "Да<input type='radio' name='head' value='1'>Нет<input type='radio' name='head' value='0' checked>";
	}
	?>
	<br/>
	Департамент: <select name="id_dep">
	<?php 
	foreach ($deps as $dep){
		if ($id_dep==$dep['id']){
			echo "<option selected value=".$dep['id'].">".$dep['name']."</option>";
		}
		else {
			echo "<option value=".$dep['id'].">".$dep['name']."</option>";
		}
	}
	?>
	</select><br/>
	IP телефон: <input type="text" name="iptel" value="<?php echo $iptel?>"><br/>
   	<input type="hidden" name="id" value="<?php echo $id?>">
	<input type="submit">
	</form>
	<a href="changepwd.php?id=<?php echo $id?>">Изменить логин и пароль</a>

<?php
$mysqli->close();
?>

