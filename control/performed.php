<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');
function RGBToHex($r, $g, $b) {
	//String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
	$hex = "#";
	$hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
	$hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
	$hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
 
	return $hex;
}

if (isset($_POST) && !empty($_POST)){
	if (isset($_POST['button2'])){
		$id = $_POST['id'];
		$spec_id = $_POST['spec_id'];
		$query = "UPDATE `control` SET `spec_id`='$spec_id' WHERE `id`='$id'";
		//$query = "INSERT INTO `control` (`spec_id`) VALUES('spec_id') WHERE `id`='$id'";
		$result = $mysqli->query($query);
	}
	else if (isset($_POST['button3'])){
		$id = $_POST['id'];
		$query = "UPDATE `control` SET `spec_id`='' WHERE `id`='$id'";
		$result = $mysqli->query($query);
	}
}

$id = $_SESSION['id'];
$query = "SELECT `head`,`id_dep`,`name`,`id`,`zam_head` FROM `name` WHERE `id`='$id'";
$result = $mysqli->query($query);
$person = $result->fetch_assoc();
$head = $person['head'];
$zam_head = $person['zam_head'];
$id_dep = $person['id_dep'];


//Если сотрудник является руководителем или заместителем руководителя департамента, тогда высвечиваем все поручения по департаменту
//Если специалист, то высвечиваем все поручения для данного сотрудника
if ($head or $zam_head){
	$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`c`.`performed`,`c`.`day_performed`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`dep_id` = '$id_dep' AND `c`.`ctrl`=1";
}
else{
	$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`c`.`performed`,`c`.`day_performed`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`spec_id` = '$id' AND `c`.`ctrl`=1";
}
$result = $mysqli->query($query);

$query="SELECT name,id FROM name WHERE id_dep='$id_dep' AND head<>1";
$res = $mysqli->query($query);
while ($spec = $res->fetch_assoc()){
	$specs[] = $spec;
}
echo "<a href='/control/'><img src='/img/previous.png' title='Вернуться обратно'></a><br/><br/>";
echo "<table border='1'>";
echo "<tr><th>№</th><th width=500>Поручение</th><th>Департамент</th><th>Специалист</th><th>Срок</th><th>Исполнено</th><th>Ответ</th><th>Комментарии</th></tr>";
$i = 1;
mb_internal_encoding("UTF-8");
while ($row = $result->fetch_assoc()){

	$dates = explode('-',$row['date']);
	$date1 = $dates[2]."-".$dates[1]."-".$dates[0];
	$date2 = date('Y-m-d');
	$datetime1 = new DateTime($date1);
	$datetime2 = new DateTime($date2);
	$interval = $datetime1->diff($datetime2);
	$days_with_symbol = $interval->format('%R%a');
	$days = $interval->format('%a');
	
	//Вычисляем цвет, чем больше просрочка тем краснее цвет.
	//Если просрочка слишком большая и голубой и зеленый цвет в
	//диапазоне RGB не ушли на отрицательное число приравниваем
	//$gb = 0; (минимальный цвет для Green и Blue)
	$gb = 210 - 10*$interval->format('%a%');
	if ($gb<0){
		$gb = 0;
	}
	$color = rgbtohex(255,$gb,$gb);
	
	//Чем меньше дней осталось до конца исполнения поручения, 
	//тем желтее делаем цвет поручения
	$b = 50*$days;
	if ($b>255){
		$b = 255;
	}
	$g = 170 + 20*$days;
	if ($g>255){
		$g = 255;
	}
	$color2 = rgbtohex(255,$g,$b);
	
	
	if (strlen($row['answer'])>400){
		$answer = mb_substr($row['answer'],0,400);
		$answer = $answer."...";
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
	
	
	echo "<tr><td  valign='top'>$i</td>";
	
	//Если поручение было с пунктами (item_descr не пустой),
	//тогда вставляем описание поручения (item_descr) и пункт поручения.
	//Иначе выводим только пункт (получается поручение из одного пункта)
	if (!empty($row['item_descr'])){
		echo "<td width='320' valign='top' >".$row['item_descr']."<br/>".$row['descr'];
	}
	else {
		echo "<td width='320' valign='top' >".$row['descr'];
	}
	
	echo "<td  valign='top'>".$row['dep_name']."</td>";
	
	//Выводим ответственного специалиста за поручение
	echo "<td  valign='top'>".$row['spec_name']."</td>";
	
	//Выводим дату
	echo "<td  valign='top' width='120'>".$row['date']."<br/>";
	
	
		//echo "<td valign='top' bgcolor='#CCC' width='100'>".$row['date']."<br>";
		if ($row['performed']){
			echo "Просрочено на ".$row['performed'];
			if ($row['performed']==1 || (($row['performed'] % 10)==1 and $row['performed']!=11)){
				echo " день";
			}
			elseif($row['performed']<5 || (($row['performed'] % 10)<5 and $row['performed']>15 )){
				echo " дня";
			}
			else{
				echo " дней";
			}
		}
		else{
			echo "В срок";
		}
		echo "</td>";
	
	
	
	
	
		
	echo "<td  valign='top'>".$row['day_performed']."</td>";
	echo "<td width='600' valign='top'>".$answer."<a href='detail.php?id=".$row['id']."'>Подробнее</a></td>";
	echo "<td>".$row['comment']."</td></tr>";
	$i++;
}
echo "</table>";


?>