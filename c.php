
<?php
$month = date('m');
$year = date('Y');


header('Content-type: text/html; charset=utf-8');
include('bd.php');
echo "<a href='/'><img src='/img/previous.png' title='Вернуться обратно'></a><br/><br/>";
echo "<a href='/control'><img src='/img/cabinet.png' title='Личный кабинет'></a><br/><br/>";


$query = "SELECT name,id FROM department";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()){
	$deps[] = $row;
}

$query = "SELECT name,id,id_dep FROM name";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()){
	$names[] = $row;
}


$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`answer`,`c`.`performed`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`comment`,`d`.`name` AS `dep_name`, `n`.`name` AS `spec_name`,`i`.`descr` AS `item_descr` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) LEFT JOIN `control_item` AS `i` ON (`c`.`control_id`=`i`.`id`) WHERE `c`.`date` LIKE '%-$month-$year'";
$result = $mysqli->query($query);

?>

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
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'http://10.50.10.100/test2.php', true); // Открываем асинхронное соединение
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Отправляем кодировку
    xmlhttp.send("depSelOpt=" + encodeURIComponent(depSelOpt)); // Отправляем POST-запрос
    xmlhttp.onreadystatechange = function() { // Ждём ответа от сервера
      if (xmlhttp.readyState == 4) { // Ответ пришёл
        if(xmlhttp.status == 200) { // Сервер вернул код 200 (что хорошо)
          document.getElementById("dep").innerHTML = xmlhttp.responseText; // Выводим ответ сервера
        }
      }
    };
	var xmlhttp2 = getXmlHttp();
	xmlhttp2.open('POST', 'http://10.50.10.100/control/admin/index.php', true); // Открываем асинхронное соединение
    xmlhttp2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Отправляем кодировку
    xmlhttp2.send("depSelOpt=" + encodeURIComponent(depSelOpt)); // Отправляем POST-запрос
    xmlhttp2.onreadystatechange = function() { // Ждём ответа от сервера
      if (xmlhttp2.readyState == 4) { // Ответ пришёл
        if(xmlhttp2.status == 200) { // Сервер вернул код 200 (что хорошо)
          document.getElementById("answer").innerHTML = xmlhttp2.responseText; // Выводим ответ сервера
        }
      }
    };
  }
  
    function names(){
	var nameSel = document.getElementById("name");
	var nameSelOpt = nameSel.options[nameSel.selectedIndex].value;
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'http://10.50.10.100/control/admin/index.php', true); // Открываем асинхронное соединение
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

<div>
<table border=1>
<tr>
<td valign=top>
Департамент:<br/>
<select id='department' name='department' onchange="javascript:dep();">
<?php
	foreach ($deps as $dep){
		if ($dep['id'] == $row['dep_id']){
			echo "<option selected value=".$dep['id'].">".$dep['name']."</option>";
		}
		else {
			echo "<option value=".$dep['id'].">".$dep['name']."</option>";
		}
	}
?>
</select>
</td>
<input type="button" value="Выбрать" onclick="dep()" />

<td valign=top>
<p>Специалист: <br/>
<span id="dep"></span></p>
</td>
</tr>
</table>

</div>
<div id ='answer'>

</div>