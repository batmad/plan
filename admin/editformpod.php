<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'bd.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");

$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

if (isset($_POST) && !empty($_POST)){
$id = $_POST['id'];
$name = $_POST['name'];
$ruk = $_POST['ruk'];
$position = $_POST['position'];
$phone = $_POST['phone'];
$kans = $_POST['kans'];
$fax = $_POST['fax'];
$email = $_POST['email'];


$query_names = "UPDATE podved SET `name`='$name', `ruk` = '$ruk', `position`='$position',`phone`='$phone', `kans`='$kans', `fax`='$fax', `email`='$email' WHERE `id` = '$id'";
$result = $mysqli->query($query_names);

header("Location: http://10.50.10.100/admin/plist.php");

}


if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];

}


$query = "SELECT name,ruk,position,phone,kans,fax,email FROM podved WHERE `id` = '$id'";
$result = $mysqli->query($query);
$result = $result->fetch_assoc();
$name = $result['name'];
$ruk = $result['ruk'];
$position = $result['position'];
$phone = $result['phone'];
$kans = $result['kans'];
$fax = $result['fax'];
$email = $result['email'];




?>
<a href='/admin/plist.php'><img src='/img/previous.png' title='Вернуться обратно'></a>
<h2>Редактирование сотрудника</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
	<table>
    <tr><td>Наименование предприятия:</td><td>   <input type="text" name="name" value="<?php echo htmlspecialchars($name);?>"></td></tr>
	<tr><td>Должность руководителя:</td><td>  <input type="text" name="position" value="<?php echo $position;?>"></td></tr>
	<tr><td>ФИО руководителя:</td><td>  <input type="text" name="ruk" value="<?php echo $ruk;?>"></td></tr>
	<tr><td>Телефон приемной:</td><td>  <input type="text" name="phone" value="<?php echo $phone;?>"></td></tr>
	<tr><td>Телефон канцелярии:</td><td>  <input type="text" name="kans" value="<?php echo $kans;?>"></td></tr>
	<tr><td>Факс:</td><td>  <input type="text" name="fax" value="<?php echo $fax;?>"></td></tr>
	<tr><td>E-mail:</td><td> <input type="text" name="email" value="<?php echo $email;?>"></td></tr>
	</table>
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<input type="submit">

    </form>
<?php
$mysqli->close();
?>

