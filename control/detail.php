<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");
$result = setlocale(LC_ALL, 'ru_RU.UTF-8');


if (isset($_GET) && !empty($_GET)){
$id = $_GET['id'];

$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`d`.`name` AS `dep_name`, `n`.`name` AS `spec_name` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) WHERE `c`.`id` = '$id'";
$result = $mysqli->query($query);
$row = $result->fetch_assoc();
}


$mysqli->close();
?>

<div style="margin-left:50px;margin-right:50px;text-align:justify;">
<p><a href="http://10.50.10.100/control/performed.php"><img src='/img/previous.png' title='Вернуться обратно'></a></p>
<div>
<p><b>Поручение</b></p>
<p>
<?php echo $row['descr']?>
</p>
</div>

<div>
<p ><b>Ответ</b></p>
<p>
<?php echo nl2br($row['answer']);?>
</p>
</div>
</div>