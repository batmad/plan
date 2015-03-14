<head>
<script type="text/javascript" src="script.js"></script>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<?php


header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('checkauth.php');

echo "<a href='index.php'>Назад</a><br/><br/>";
echo "<a href='add.php'>Добавить поручение</a><br/><br/>";


$_SESSION['search'] = false;
$_SESSION['date'] = '';
$_SESSION['descr'] = '';
$_SESSION['ctrl'] = 1;

function RGBToHex($r, $g, $b) {
	//String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
	$hex = "#";
	$hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
	$hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
	$hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
 
	return $hex;
}

if (isset($_POST) && !empty($_POST)){
	if (isset($_POST['spec'])){
		$id = $_POST['id'];
		$spec_id = $_POST['buton1'];
		print_r($_POST);
		$query = "UPDATE `control` SET `spec_id`='$spec_id' WHERE `id`='$id'";
		//$query = "INSERT INTO `control` (`spec_id`) VALUES('spec_id') WHERE `id`='$id'";
		$result = $mysqli->query($query);
	}
	else if(isset($_POST['button2'])){
		$ctrl = $_POST['ctrl'];
		$id = $_POST['id'];
		$performed = $_POST['performed'];
		echo $ctrl;
		$query = "UPDATE `control` SET `ctrl`='$ctrl',`performed`='$performed' WHERE `id`='$id'";
		$result = $mysqli->query($query);
	}
	else {
        echo 'ошибка';
    }
}

if (isset($_GET) && !empty($_GET)){
	$month = $_GET['month'];
	$year = date('Y');
	$month = date('m',mktime(1,1,1,$month,1,$year));
}
else{
	$month = date('m');
	$year = date('Y');
}
$query = "SELECT short,id FROM department";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()){
	$deps[] = $row;
}


$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`day_performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`d`.`name` AS `dep_name`, `n`.`name` AS `spec_name` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) WHERE `c`.`ctrl`=1 AND `c`.`date` LIKE '$year-$month-%'";
$result = $mysqli->query($query);




echo "<table border=1>";
echo "<th>№</th>";

//Быстрый поиск
echo "<th width=500 valign=top>";
echo "Поручение";
echo "<form action=\"";
$_SERVER['PHP_SELF'];
echo "\" method='post'>";
echo "<input type='text' name='search' value='";
if (!empty($_SESSION['descr'])){
echo $_SESSION['descr'];
}
echo "' size=70><br/>";
echo "<input type='submit' name='quickSearch' value='Быстрый поиск'>";
echo "</form>";		
echo "</th>";

//Рисуем возможность выбирать Департамент и специалиста из выпадающего списка
echo "<th valign=top>Департамент";
echo "<br/>";
echo "<select id='department' name='department' onchange='javascript:dep();'>";
	echo "<option selected></option>";
	foreach ($deps as $dep){
		echo "<option value=".$dep['id'].">".$dep['short']."</option>";
	}
echo "</select>";
echo "</th>";
echo "<th valign='top' width=135 >Специалист";
echo "<br/><span id='dep'></span>";
echo "</th>";

//быстрый поиск по дате
echo "<th valign='top' >Дата";
echo "<form action=\"";
$_SERVER['PHP_SELF'];
echo "\" method='post'>";
echo "<input type='text' name='date' value='";
if (!empty($_SESSION['date'])){
	echo $_SESSION['date'];
}
echo "'size=11><br/>";
echo "<input type='submit' name='dateSearch' value='Поиск'>";
echo "</th>";


echo "<th valign=top>Сбросить поиск</br></br>";
echo "<form action=\"";
$_SERVER['PHP_SELF'];
echo "\" method='post'>";
echo "<input type='submit' name='eraseSearch' value='Сбросить'></th>";
echo "</table>";


echo "<div id='answer'>";

echo "<table border='1'>";
echo "<tr><th>№</th><th>Поручение</th><th>Департамент</th><th>Специалист</th><th>Ответ</th><th>Срок</th><th>Дата исполнения</th><th>Количество дней просрочки</th><th>Исполнено</th></tr>";
$i = 1;
mb_internal_encoding("UTF-8");
while ($row = $result->fetch_assoc()){

	$date1 =$row['date'];
	$date2 = date('Y-m-d');
	$datetime1 = new DateTime($date1);
	$datetime2 = new DateTime($date2);
	$interval = $datetime1->diff($datetime2);
	
	$dates = explode('-',$row['day_performed']);
	$rdateperf = $dates[2]."-".$dates[1]."-".$dates[0];
	
	$dates = explode('-',$row['date']);
	$rdate = $dates[2]."-".$dates[1]."-".$dates[0];
	
	//Вычисляем цвет, чем больше просрочка тем краснее цвет.
	//Если просрочка слишком большая и голубой и зеленый цвет в
	//диапазоне RGB не ушли на отрицательное число приравниваем
	//$gb = 0; (минимальный цвет для Green и Blue)
	$gb = 210 - 10*$interval->format('%a%');
	if ($gb<0){
		$gb = 0;
	}
	
	$color = rgbtohex(255,$gb,$gb);
	
	$flag = 0;
	if (strlen($row['answer'])>400){
		$answer = mb_substr($row['answer'],0,400);
		$answer = $answer."...";
		$flag = 1;
	}
	else{
		$answer = nl2br($row['answer']);
	}
	if ($row['ctrl']){
		$ctrl = "Да";
	}
	else{
		$ctrl = "Нет";
	}
	
	$specs =array();
	$query="SELECT name,id FROM name WHERE id_dep=".$row['dep_id']." AND head<>1";
		$res = $mysqli->query($query);
		while ($spec = $res->fetch_assoc()){
		$specs[] = $spec;
	}
		
		
		
	//Начинаем рисовать таблицу
	echo "<tr><td  valign='top'>$i</td>";
	echo "<td width='320' valign='top' >".$row['descr']."<br/><a href=\"edit.php?id=".$row['id']."\">Редактировать</a></td>";
	echo "<td  valign='top'>".$row['dep_name']."</td>";
	
	//Выводим ответственного специалиста за поручение
	echo "<td  valign='top'>".$row['spec_name']."</td>";
	
	//Выводим ответ специалиста
	echo "<td width='600'>".$answer."<a href=\"/control/admin/detail.php?id=".$row['id']."\">Подробнее</a></td>";
	
	//Выводим дату
	echo "<td  valign='top' width='120'>".$rdate."</td>";
	
	//Выводим дату исполнения
	echo "<td  valign='top' width='120'>".$rdateperf."</td>";
	
	//Выводим количество просроченных дней
	echo "<td  valign='top' width='120'>".$row['performed']."</td>";
	
	//Рисуем возможность изменять Исполнение путем выбора из выпадающего списка
	echo "<td  valign='top'>";
	if (!$row['ctrl']){
		echo "Нет";
	}
	else {
		echo "<form action=\"";
		$_SERVER['PHP_SELF'];
		echo "\" method='post'>";
		echo "<select name='ctrl'>";
		echo "<option selected value=1>Да</option>";
		echo "<option  value=0>Нет</option>";
		echo "</select><br/>";
		echo "<input type='hidden' name='id' value=".$row['id'].">";
		echo "<input type='hidden' name='performed' value=".$interval->format('%a%').">";
		echo "<input type='submit' name='button2'>";
		echo "</form>";		
	}
	echo "</td>";
	
	echo "</tr>";
	$i++;
}
echo "</table>";
echo "</div>";

?>