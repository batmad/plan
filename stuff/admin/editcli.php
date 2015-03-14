<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");

$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

if (isset($_POST) && !empty($_POST)){
$id = $_POST['id'];
$name = $_POST['name'];
$weight = $_POST['weight'];
$show = $_POST['show'];


$query_names = "UPDATE name SET `name`='$name', `weight` = '$weight', `show_stuff`='$show' WHERE `id` = '$id'";

$result = $mysqli->query($query_names);

header("Location: http://10.50.10.100/stuff/admin/list.php");

}


if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];
	$name = $_GET['name'];
	$weight = $_GET['weight'];
	$id_dep = $_GET['id_dep'];
}

$query = "SELECT id,name FROM department";
$result = $mysqli->query($query);
while ($dep = $result->fetch_assoc()){
	$deps[] = $dep;
}


?>
<a href="/stuff/admin/list.php">Вернуться </a>
<h2>Редактирование сотрудника</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    ФИО: <input type="text" name="name" value="<?php echo $name ?>"><br/>
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
	Приоритет выдачи: <input type="text" name= "weight" value="<?php echo $weight?>"><br/>
   	<input type="hidden" name="id" value="<?php echo $id?>">
	<input type="submit">
    </form>

<?php
$mysqli->close();
?>

