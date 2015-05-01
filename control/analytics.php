<script type="text/javascript">
  /* Данная функция создаёт кроссбраузерный объект XMLHTTP */
  function getXmlHttp() {
    var xmlhttp;
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
    try {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (E) {
      xmlhttp = false;
    }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
      xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
  }
  
  function dep(){
	var depSel = document.getElementById("department");
	var depSelOpt = depSel.options[depSel.selectedIndex].value;

	var xmlhttp2 = getXmlHttp();
	xmlhttp2.open('POST', '/control/depQuery.php', true); // Открываем асинхронное соединение
    xmlhttp2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Отправляем кодировку
    xmlhttp2.send("depSelOpt=" + encodeURIComponent(depSelOpt)); // Отправляем POST-запрос
    xmlhttp2.onreadystatechange = function() { // Ждём ответа от сервера
      if (xmlhttp2.readyState == 4) { // Ответ пришёл
        if(xmlhttp2.status == 200) { // Сервер вернул код 200 (что хорошо)
          document.getElementById("answer").innerHTML = xmlhttp2.responseText; // Выводим ответ сервера
        }
      }
    };
	
	var xmlhttp = getXmlHttp();
	
	xmlhttp.open('POST', '/control/dep_to_names.php', true); // Открываем асинхронное соединение
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Отправляем кодировку
    xmlhttp.send("depSelOpt=" + encodeURIComponent(depSelOpt)); // Отправляем POST-запрос
    xmlhttp.onreadystatechange = function() { // Ждём ответа от сервера
      if (xmlhttp.readyState == 4) { // Ответ пришёл
        if(xmlhttp.status == 200) { // Сервер вернул код 200 (что хорошо)
          document.getElementById("dep").innerHTML = xmlhttp.responseText; // Выводим ответ сервера
        }
      }
    };

  }
  
    function names(){
	var nameSel = document.getElementById("name");
	var nameSelOpt = nameSel.options[nameSel.selectedIndex].value;
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', '/control/depQuery.php', true); // Открываем асинхронное соединение
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Отправляем кодировку
    xmlhttp.send("nameSelOpt=" + encodeURIComponent(nameSelOpt)); // Отправляем POST-запрос
    xmlhttp.onreadystatechange = function() { // Ждём ответа от сервера
      if (xmlhttp.readyState == 4) { // Ответ пришёл
        if(xmlhttp.status == 200) { // Сервер вернул код 200 (что хорошо)
          document.getElementById("answer").innerHTML = xmlhttp.responseText; // Выводим ответ сервера
        }
      }
    };
	
  }
  
</script>


<?php
$_SESSION['analytics'] = true;
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');

$current_date = date('d-m-Y');
		
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
	}
	else if(isset($_POST['button2'])){
		$ctrl = $_POST['ctrl'];
		$id = $_POST['id'];
		$date = $_POST['date'];
		$performed = $_POST['performed'];
		echo $ctrl;
		$query = "UPDATE `control` SET `ctrl`='$ctrl',`performed`='$performed',`day_performed`='$date' WHERE `id`='$id'";
		$result = $mysqli->query($query);
	}
	else if (isset($_POST['button3'])){
		$id = $_POST['id'];
		$query = "UPDATE `control` SET `spec_id`='' WHERE `id`='$id'";
		//$query = "INSERT INTO `control` (`spec_id`) VALUES('spec_id') WHERE `id`='$id'";
		$result = $mysqli->query($query);
	}
	else if (isset($_POST['quickSearch'])){
		$search = $_POST['search'];
		$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`descr` LIKE '%$search%' OR `i`.`descr` LIKE '%$search%'";
	}
	else if (isset($_POST['depSelOpt'])){
		$search = $_POST['depSelOpt'];
		$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`dep_id`='$search' ";
	}
	else if (isset($_POST['nameSelOpt'])){
		$search = $_POST['nameSelOpt'];
		$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`spec_id`='$search' ";
	}
	else if (isset($_POST['dateSearch'])){
		$search = $_POST['date'];
		$query_search = "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`date`='$search' ";
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
if (isset($_POST['quickSearch']) or isset($_POST['depSelOpt']) or isset($_POST['nameSelOpt']) or isset($_POST['dateSearch'])){
	$query = $query_search;
}
else {
	$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`performed`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`short` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`ctrl`=0";
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
echo "<input type='text' name='search' size=70><br/>";
echo "<input type='submit' name='quickSearch' value='Быстрый поиск'>";
echo "</form>";		
echo "</th>";

//Рисуем возможность выбирать Департамент и специалиста из выпадающего списка
echo "<th valign=top>Департамент";
echo "<br/>";
echo "<select id='department' name='department' onchange='javascript:dep();'>";
	foreach ($deps as $dep){
		if ($dep['id'] == $row['dep_id']){
			echo "<option selected value=".$dep['id'].">".$dep['short']."</option>";
		}
		else {
			echo "<option value=".$dep['id'].">".$dep['short']."</option>";
		}
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
echo "<input type='text' name='date' size=11><br/>";
echo "<input type='submit' name='dateSearch' value='Поиск'>";
echo "</th>";
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
	$dates = explode('-',$row['date']);
	if ($dates[1] < date('m')){
		$dates[1]=date('m');
		$dates[0]="01";
	}
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
	echo "</td>";
	echo "<td  valign='top'>".$row['dep_name']."</td>";
	
	//Если ответственный специалист за поручение не назначен, то даем 
	//возможность выбрать специалиста из ответственного Департамента
	if (!empty($row['spec_name'])){
		echo "<td  valign='top'>".$row['spec_name'];
		echo "</td>";
	}
	else {
		echo "<td  valign='top'>";
		echo "Специалист не назначен";
		echo "</td>";
	}
	
	if ($row['ctrl']){
		echo "<td valign='top' bgcolor='#FFF' width='100'>".$row['date']."<br>";
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
		echo "<td bgcolor='$color'  valign='top' width='100'>".$row['date']."<br/>";
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
			echo "<td bgcolor='$color2'  valign='top' width='100'>".$row['date']."<br/>";
			
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
			echo "<td bgcolor='$color2' valign='top' width='100'>".$row['date']."</td>";
		}
	}
	}
	
	//Рисуем возможность изменять Исполнение путем выбора из выпадающего списка
	echo "<td  valign='top'>";
	if ($row['ctrl']){
		echo "Да";
	}
	else {
		echo "Нет";
		//Если количество дней просрочки больше ноля, тогда отправляем
		//количество дней без символа, иначе отправляем ноль
		if ($days_with_symbol>0){
			echo "<input type='hidden' name='performed' value=".$days.">";
		}
		else{
			echo "<input type='hidden' name='performed' value='0'>";
		}
	}
	echo "</td>";
	
	echo "<td width='600'>".$answer."<a href=\"/control/admin/detail.php?id=".$row['id']."\">Подробнее</a><br/></td>";
	echo "<td>".$row['comment']."</td></tr>";
	




	$i++;
}
echo "</table>";


?>
</div>