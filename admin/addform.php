<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include($_SERVER['DOCUMENT_ROOT'].'/date.php');
include('checkauth.php');

$myDate = new mDate();

setlocale(LC_TIME,"ru_RU");

$result = setlocale(LC_ALL, 'ru_RU.UTF-8');






if (isset($_POST) && !empty($_POST)){
	$name = $_POST['name'];
	$descr = $_POST['descr'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	$nextweek = $_POST['nextweek'];
	$datetime = $myDate->datetime($date,$time);
	$responsible = $_POST['responsible'];
	$place = $_POST['place'];

	foreach($_POST['add'] as $add){
		$query_names = "INSERT INTO todo(id_name,date,descr,responsible,place) VALUES ('$add','$datetime','$descr','$responsible','$place')";
		$result = $mysqli->query($query_names);
	}

	$query_names = "INSERT INTO todo(id_name,date,descr,responsible,place) VALUES ('$name','$datetime','$descr','$responsible','$place')";
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
	if(isset($_GET['nextweek'])){
		$nextweek = $_GET['nextweek'];
	}
	else{
		$nextweek = "no";
	}
}


if ($nextweek=='yes'){
	echo "<a href='/admin/index.php?nextweek=yes'><img src='/img/previous.png' title='Вернуться обратно'></a>";
}
else{
	echo "<a href='/admin'><img src='/img/previous.png' title='Вернуться обратно'></a>";
}

$query_names = "SELECT name,id FROM name";

$result = $mysqli->query($query_names);

while ($row = $result->fetch_assoc()){
	$rows[] = $row;
}


$query_names = "SELECT `name`,`id` FROM `name` WHERE `id_dep` = '1' ";
$result = $mysqli->query($query_names);
while ($row = $result->fetch_assoc()){
	$zams[] = $row;
}

$query_names = "SELECT `name`,`id` FROM `name` WHERE `head` = '1'";
$result = $mysqli->query($query_names);
while ($row = $result->fetch_assoc()){
	$ruks[] = $row;
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
	Дата: <input type="text" name="date" value="<?php if(isset($gl_date)){echo $gl_date;} ?>">
	Время: <input type="time" name="time" value=""><br/>



	<div>
	<div style="float:left">
	<fieldset class="shest1">

	<?php	
	$i = 0;
	foreach($zams as $row){		
		if ($row['id'] != $id){
			echo "<input type='checkbox' name='add[".$i++."]' value=".$row['id'].">".$row['name']."<br/>";	
		}
    }
    echo "</fieldset></div>";
    echo "<div style='position:relative; width:200px; margin-left:200px'><fieldset width=320px class='shest1'> ";
    foreach($ruks as $row){
		if ($row['id'] != $id){
			echo "<input type='checkbox' name='add[".$i++."]' value=".$row['id'].">".$row['name']."<br/>";	
		}
    }
    ?>
    </fieldset>

	</div>
	</div>
    <br/>Мероприятие: <br/><textarea name="descr" cols="100" rows="10"></textarea><br/>
    Место: <input type="text" name="place" value=""><br/>
    Ответственный: <input type="text" name="responsible" value=""><br/>
	<input type="hidden" name="nextweek" value="<?php echo $nextweek ?>">
	<input type="submit">
    </form>

<?php
$mysqli->close();
?>
