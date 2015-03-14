<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");

echo "<a href='/admin/plist.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";
$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

if (isset($_POST) && !empty($_POST)){
$name = $_POST['name'];
$ruk = $_POST['ruk'];
$position = $_POST['position'];
$phone= $_POST['phone'];
$kans= $_POST['kans'];
$fax= $_POST['fax'];
$email= $_POST['email'];

$query_names = "INSERT INTO podved (`name`,`ruk`,`position`,`phone`,`kans`,`fax`,`email`) VALUES ('$name','$ruk','$position','$phone','$kans','$fax','$email')";


$result = $mysqli->query($query_names);


header("Location: http://10.50.10.100/admin/plist.php");

}

?>
<h2>Добавление подведомственного предприятия</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
	<table>
    <tr><td>Наименование предприятия:</td><td>   <input type="text" name="name"></td></tr>
	<tr><td>Должность руководителя:</td><td>  <input type="text" name="position"></td></tr>
	<tr><td>ФИО руководителя:</td><td>  <input type="text" name="ruk"></td></tr>
	<tr><td>Телефон приемной:</td><td>  <input type="text" name="phone"></td></tr>
	<tr><td>Телефон канцелярии:</td><td>  <input type="text" name="kans"></td></tr>
	<tr><td>Факс:</td><td>  <input type="text" name="fax"></td></tr>
	<tr><td>E-mail:</td><td> <input type="text" name="email"></td></tr>
	</table>
	<input type="submit">

    </form>

<?php
$mysqli->close();
?>

