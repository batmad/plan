<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
if (isset($_POST['depSelOpt'])){
		$search = $_POST['depSelOpt'];
		$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`dep_id`='$search' AND `c`.`ctrl`=0 ORDER BY `c`.`date`";
}
else if (isset($_POST['nameSelOpt'])){
		$search = $_POST['nameSelOpt'];
		if ($_SESSION['ctrl'] == 1){
			$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`spec_id`='$search' AND `c`.`ctrl`=1 ORDER BY `c`.`date`";
		}
		else {
			$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`spec_id`='$search' AND `c`.`ctrl`=0 ORDER BY `c`.`date`";
		}
}
else if (isset($_POST['dateSelOpt'])){
		$search = $_POST['dateSelOpt'];
		$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`date`='$search' ORDER BY `c`.`date`";
}

$current_date = date('Y-m-d');
$query = $query_search;
$result = $mysqli->query($query);

function RGBToHex($r, $g, $b) {
	//String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
	$hex = "#";
	$hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
	$hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
	$hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
 
	return $hex;
}

echo "<table border='1'>";
echo "<tr><th>№</th><th width=500>Поручение</th>";


echo "<th>Департамент</th>";


echo "<th valign='top' >Специалист</th>";


echo "<th>Срок</th><th>Исполнено</th><th>Ответ</th><th>Комментарий службы контроля</th></tr>";

$i = 1;
mb_internal_encoding("UTF-8");
while ($row = $result->fetch_assoc()){


	//Вычисляем количество дней просрочки
	$dates = explode('-',$row['date']);
	//if ($dates[1] < date('m')){
		//$dates[1]=date('m');
		//$dates[0]="01";
	//}
	$date1 = $row['date'];
	$date2 = date('Y-m-d');
	$datetime1 = new DateTime($date1);
	$datetime2 = new DateTime($date2);
	$interval = $datetime1->diff($datetime2);
	$days_with_symbol = $interval->format('%R%a');
	$days = $interval->format('%a');
	
	$dates = explode('-',$row['date']);
	$rdate = $dates[2]."-".$dates[1]."-".$dates[0];
	
	//Вычисляем цвет, чем больше просрочка тем краснее цвет.
	//Если просрочка слишком большая и голубой и зеленый цвет в
	//диапазоне RGB не ушли на отрицательное число приравниваем
	//$gb = 0; (минимальный цвет для Green и Blue)
	$gb = 210 - 10*$days;
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
	$query="SELECT name,id FROM name WHERE id_dep=".$row['dep_id'];
		$res = $mysqli->query($query);
		while ($spec = $res->fetch_assoc()){
		$specs[] = $spec;
	}
		
		
		
	//Начинаем рисовать таблицу
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
	echo "<br/><a href=\"edit.php?id=".$row['id']."\">Редактировать</a></td>";
	echo "<td  valign='top'>".$row['dep_name']."</td>";
	
	//Если ответственный специалист за поручение не назначен, то даем 
	//возможность выбрать специалиста из ответственного Департамента
	if (!empty($row['spec_name'])){
		echo "<td  valign='top'>".$row['spec_name'];
		echo "<form action=\"";
		$_SERVER['PHP_SELF'];
		echo "\" method='post'>";
		echo "<input type='hidden' name='id' value=".$row['id'].">";
		echo "<input type='submit' name='button3' value='Cбросить'>";
		echo "</form>";
		echo "</td>";
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
		echo "<input type='submit' name='button1'>";
		echo "</form>";
		echo "</td>";
	}
	
	if ($row['ctrl']){
		echo "<td valign='top' bgcolor='#CCC' width='100'>".$rdate."<br>";
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
	}
	else{
	//Если дата с просрочкой, то пишем сколько дней просрочено
	if ($days_with_symbol>0){
		echo "<td bgcolor='$color'  valign='top' width='100'>".$rdate."<br/>";
		echo "<b>Просрочено на ".$days; 
		if ($days_with_symbol==1 || (($days_with_symbol % 10)==1 and $days_with_symbol!=11)){
			echo " день";
		}
		elseif($days_with_symbol<5 || (($days_with_symbol % 10)<5 and $days_with_symbol>15 and $days_with_symbol % 10 != 0 )){
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
			echo "<td bgcolor='$color2'  valign='top' width='100'>".$rdate."<br/>";
			
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
			echo "<td bgcolor='$color2' valign='top' width='100'>".$rdate."</td>";
		}
	}
	}
	//Рисуем возможность изменять Исполнение путем выбора из выпадающего списка
	echo "<td  valign='top'>";
	if ($row['ctrl']){
		echo "Да";
	}
	else {
		echo "<form action=\"";
		$_SERVER['PHP_SELF'];
		echo "\" method='post'>";
		echo "<select name='ctrl'>";
		echo "<option value=1>Да</option>";
		echo "<option selected value=0>Нет</option>";
		echo "</select><br/>";
		echo "<input type='hidden' name='id' value=".$row['id'].">";
		echo "<input type='hidden' name='date' value=".$current_date.">";
		//Если количество дней просрочки больше ноля, тогда отправляем
		//количество дней без символа, иначе отправляем ноль
		if ($days_with_symbol>0){
			echo "<input type='hidden' name='performed' value=".$days.">";
		}
		else{
			echo "<input type='hidden' name='performed' value='0'>";
		}
		echo "<input type='submit' name='button2'>";
		echo "</form>";		
	}
	echo "</td>";
	
	echo "<td width='600'>".$answer."<a href=\"/control/admin/detail.php?id=".$row['id']."\">Подробнее</a><br/></td>";
	echo "<td>".$row['comment']."<br/><a href='add_comment.php?id=".$row['id']."'>Добавить </a></td></tr>";
	




	$i++;
}
?>