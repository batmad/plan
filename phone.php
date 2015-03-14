<html>
<head>
<title>Справочник IP-телефонов Министерства ЖКХ и энергетики РС(Я)</title>
</head>
<body>
<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
echo "<br/><a href='/index.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";



$query = "SELECT name,id,iptel FROM name WHERE `id_dep`='1' ORDER BY weight";
$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()){
	$ruks[] = $row;
}

$query = "SELECT name,id,iptel FROM name WHERE `id_dep`='2' ORDER BY weight";
$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()){
	$dkk[] = $row;
}

$query = "SELECT name,id,iptel FROM name WHERE `id_dep`='3' ORDER BY weight";
$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()){
	$def[] = $row;
}

$query = "SELECT name,id,iptel FROM name WHERE `id_dep`='4' ORDER BY weight";
$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()){
	$djp[] = $row;
}

$query = "SELECT name,id,iptel FROM name WHERE `id_dep`='5' ORDER BY weight";
$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()){
	$de[] = $row;
}
	
$mysqli->close();
?>

<div align=center>


<p><b>Руководство</b></p>
<table border=1>
<?php
foreach ($ruks as $ruk){
	echo "<tr><td align=center>".$ruk['name']."</td><td>".$ruk['iptel']."</td></tr>";
}
?>
</table>

<p><b>Департамент жилищной политики и административной работы</b></p>
<table border=1>
<?php
foreach ($djp as $djp){
	echo "<tr><td align=center>".$djp['name']."</td><td>".$djp['iptel']."</td></tr>";
}
?>
</table>




<table border=1>
<p><b>Департамент коммунального комплекса и стратегического развития</b></p>
<?php
foreach ($dkk as $dkk){
	echo "<tr><td align=center>".$dkk['name']."</td><td>".$dkk['iptel']."</td></tr>";
}
?>
</table>

<table border=1>
<p><b>Департамент энергетики и энергосбережения</b></p>
<?php
foreach ($de as $de){
	echo "<tr><td align=center>".$de['name']."</td><td>".$de['iptel']."</td></tr>";
}
?>
</table>

<table border=1>
<p><b>Департамент экономики, финансов, имущественных вопросов и информатизации</b></p>
<?php
foreach ($def as $def){
	echo "<tr><td align=center>".$def['name']."</td><td>".$def['iptel']."</td></tr>";
}
?>
</table>

</div>

