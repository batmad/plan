<html>
<head>
<title>Подведомственные предприятия Министерства ЖКХ и энергетики РС(Я)</title>
</head>
<body>
<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');


setlocale(LC_TIME,"ru_RU");
$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

echo "<a href='index.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";

$query_names = "SELECT name,id,ruk,phone,kans,fax,position,email FROM podved";
$result = $mysqli->query($query_names);
while ($row = $result->fetch_assoc()){
	$rows[] = $row;
}

echo "<h2>Подведомственные предприятия</h2>";
echo "<table border=1><th>Наименование предприятия</th><th width='10'>ФИО руководителя</th><th>Приемная</th><th>Канцелярия</th><th>Факс</th><th>e-mail</th>";
foreach ($rows as $row){
	echo "<tr><td valign='top'>".$row['name']."</td>";
	echo "<td valign='top'>".$row['position']."<br/>".$row['ruk']."</td>";
	echo "<td valign='top'>".$row['phone']."</td>";
	echo "<td valign='top'>".$row['kans']."</td>";
	echo "<td valign='top'>".$row['fax']."</td>";
	echo "<td valign='top'>".$row['email']."</td>";
}

	


$mysqli->close();
?>

