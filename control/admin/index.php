<head>
<script type="text/javascript" src="script.js"></script>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('checkauth.php');

$_SESSION['ctrl'] = 0;
if (!isset($_SESSION['search'])){
	$_SESSION['search'] = false;
}
$current_date = date('Y-m-d');

$query="SELECT COUNT(*) AS count FROM message WHERE `read`='0'";
$result = $mysqli->query($query) or die(mysql_error());
$message = $result->fetch_assoc();

echo "<a href='add.php'><img src='/img/add_task.png' title='Добавить поручение'></a>";
echo "<a href='add_with_items.php'><img src='/img/add_task_items.png' title='Добавить поручение с пунктами'></a>";
echo "<a href='performed.php'><img src='/img/performed.png' title='Исполненные поручения'></a>";
echo "<a href='months.php'><img src='/img/calend.png' title='Поручения по месяцам'></a>";
//echo "<a href='http://10.50.10.100/admin/list.php'><img src='/img/edit_person.png' title='Редактировать пользователей'></a>";
echo "<a href='premium.php'><img src='/img/premium.png' title='Расчет премии'></a>";
echo "<a href='actual.php'><img src='/img/actual.png' title='Актуальные поручения'></a>";
echo "<div class='example3'>";
echo "<img src='/img/message.png' title='Сообщения'>";
echo "<div class='example_text' onclick=\"location.href='messages.php';\">";
echo "<span>".$message['count']."</span>";
echo "</div>";
echo "</div>";
echo "<a href='word.php'><img src='/img/word.png' title='Скачать Word'></a>";

		
function RGBToHex($r, $g, $b) {
	//String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
	$hex = "#";
	$hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
	$hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
	$hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
 
	return $hex;
}

if (isset($_POST) && !empty($_POST)){
	if (isset($_POST['button1'])){
		$id = $_POST['id'];
		$spec_id = $_POST['spec_id'];
		$query = "UPDATE `control` SET `spec_id`='$spec_id' WHERE `id`='$id'";
		//$query = "INSERT INTO `control` (`spec_id`) VALUES('spec_id') WHERE `id`='$id'";
		$result = $mysqli->query($query);
		if ($_SESSION['search']){
			$search = $_SESSION['descr'];
			$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`descr` LIKE '%$search%' OR `i`.`descr` LIKE '%$search%' ORDER BY `c`.`date`";
		}
	}
	else if(isset($_POST['button2'])){
		$ctrl = $_POST['ctrl'];
		$id = $_POST['id'];
		$date = $_POST['date'];
		$performed = $_POST['performed'];
		$query = "UPDATE `control` SET `ctrl`='$ctrl',`performed`='$performed',`day_performed`='$current_date' WHERE `id`='$id'";
		$result = $mysqli->query($query);
		if ($_SESSION['search']){
			$search = $_SESSION['descr'];
			$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`descr` LIKE '%$search%' OR `i`.`descr` LIKE '%$search%' ORDER BY `c`.`date`";
		}
	}
	else if (isset($_POST['button3'])){
		$id = $_POST['id'];
		$query = "UPDATE `control` SET `spec_id`='' WHERE `id`='$id'";
		//$query = "INSERT INTO `control` (`spec_id`) VALUES('spec_id') WHERE `id`='$id'";
		$result = $mysqli->query($query);
		if ($_SESSION['search']){
			$search = $_SESSION['descr'];
			$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`descr` LIKE '%$search%' OR `i`.`descr` LIKE '%$search%' ORDER BY `c`.`date`";
		}
	}
	else if (isset($_POST['quickSearch'])){
		$search = $_POST['search'];
		$_SESSION['search'] = true;
		$_SESSION['descr'] = $search;
		$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`descr` LIKE '%$search%' OR `i`.`descr` LIKE '%$search%' ORDER BY `c`.`date`";
	}
	else if (isset($_POST['depSelOpt'])){
		$search = $_POST['depSelOpt'];
		$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`dep_id`='$search' ORDER BY `c`.`date`";
	}
	else if (isset($_POST['nameSelOpt'])){
		$search = $_POST['nameSelOpt'];
		$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`spec_id`='$search' ORDER BY `c`.`date`";
	}
	else if (isset($_POST['dateSearch'])){
		$search = $_POST['date'];
		$_SESSION['search'] = true;
		$_SESSION['date'] = $search;
		$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`date`='$search' ORDER BY `c`.`date`";
	}
	else if (isset($_POST['eraseSearch'])){
		$_SESSION['search'] = false;
		$_SESSION['date'] = '';
		$_SESSION['descr'] = '';
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


//Если была нажата кнопка поиска, тогда выдаем результаты соответствующие поиску 
if (isset($_POST['quickSearch']) or isset($_POST['depSelOpt']) or isset($_POST['nameSelOpt']) or isset($_POST['dateSearch']) or $_SESSION['search']){
	$query = $query_search;
}
else {
	//$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`ctrl`=0 AND `c`.`date` LIKE '$%-$month-$year'";
	$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`ctrl`=0 AND `c`.`date` LIKE '$year-$month-%' ORDER BY `c`.`date`";
}
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
echo "<tr><th>№</th><th width=500>Поручение</th>";


echo "<th>Департамент</th>";


echo "<th valign='top' >Специалист</th>";


echo "<th>Срок</th><th>Исполнено</th><th>Ответ</th><th>Комментарий службы контроля</th></tr>";

$i = 1;
mb_internal_encoding("UTF-8");
while ($row = $result->fetch_assoc()){


	//Вычисляем количество дней просрочки
	//$dates = explode('-',$row['date']);
	//if ($dates[1] < date('m')){
		//$dates[1]=date('m');
		//$dates[0]="01";
	//}
	$date1 = $row['date'];
	//$date1 = $dates[2]."-".$dates[1]."-".$dates[0];
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
		elseif($days_with_symbol<5 || (($days_with_symbol % 10)<5 and $days_with_symbol>15 and $days_with_symbol % 10 != 0)){
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
echo "</table>";


?>
</div>