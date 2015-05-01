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
	$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`dep_id` = '$id_dep' AND `c`.`ctrl`=0 ORDER BY `c`.`date`";
}
else{
	$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`spec_id` = '$id' AND `c`.`ctrl`=0 ORDER BY `c`.`date`";
}
$result = $mysqli->query($query);

$query="SELECT name,id FROM name WHERE id_dep='$id_dep' AND head<>1";
$res = $mysqli->query($query);
while ($spec = $res->fetch_assoc()){
	$specs[] = $spec;
}




echo "<a href='/control.php'><img src='/img/previous.png' title='Вернуться обратно'></a><br/><br/>";
echo "<a href='performed.php'><img src='/img/performed.png' title='Исполненные поручения'></a>";
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
	
	//Если ответственный специалист за поручение не назначен, то даем руководителю 
	//возможность выбрать специалиста из его Департамента и сбрасывать назначенного специалиста
	if (!empty($row['spec_name'])){
		if ($head or $zam_head){
			echo "<td  valign='top'>".$row['spec_name'];
			echo "<form action=\"";
			$_SERVER['PHP_SELF'];
			echo "\" method='post'>";
			echo "<input type='hidden' name='id' value=".$row['id'].">";
			echo "<input type='submit' name='button3' value='Cбросить'>";
			echo "</form>";
			echo "</td>";
		}
		else{
			echo "<td  valign='top'>".$row['spec_name']."</td>";
		}
	}
	else {
		echo "<td  valign='top'>";
		echo "<form action=\"";
		$_SERVER['PHP_SELF'];
		echo "\" method='post'>";
		echo "<select name='spec_id'>";
		echo "<option value=''></option>";
		foreach ($specs as $spec){
			echo "<option value='".$spec['id']."'>".$spec['name']."</option>";
		}
		echo "</select><br/>";
		echo "<input type='hidden' name='id' value=".$row['id'].">";
		echo "<input type='submit' name='button2'>";
		echo "</form>";

	}
	
	//Если дата с просрочкой, то пишем сколько дней просрочено
	if ($days_with_symbol>0){
		echo "<td bgcolor='$color'  valign='top' width='100'>".$date1."<br/>";
		echo "<b>Просрочено на ".$days; 
		if ($days_with_symbol==1 || (($days_with_symbol % 10)==1 and $days_with_symbol!=11)){
			echo " день";
		}
		elseif($days_with_symbol<5 || (($days_with_symbol % 10)<5 and $days_with_symbol>15 )){
			echo " дня";
		}
		else{
			echo " дней";
		}
		echo "</b></td>";
	}
	//иначе если осталось 5 дней или менеe до конца срока, то пишем сколько дней осталось
	else {
		if ($days<=5){
			echo "<td bgcolor='$color2'  valign='top' width='100'>".$date1."<br/>";
			
			if ($days==0){
				echo "<b>ПОСЛЕДНИЙ ДЕНЬ "; 
			}
			elseif ($days==1){
				echo "<b>Остался ".$days; 
				echo " день";
			}
			elseif($days<5 ){
				echo "<b>Осталось ".$days; 
				echo " дня";
			}
			else{
				echo "<b>Осталось ".$days; 
				echo " дней";
			}
			echo "</b></td>";
		}
		else {
			echo "<td bgcolor='$color2' valign='top' width='100'>".$date1."</td>";
		}
	}
		
		echo "<td  valign='top'>".$ctrl."</td>";
		echo "<td width='600'>".$answer."<a href=\"/control/edit.php?id=".$row['id']."\"><br>Редактировать</a></td>";
		echo "<td>".$row['comment']."</td></tr>";
	$i++;
}
echo "</table>";


?>