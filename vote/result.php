<?php
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
echo "<a href='/'><img src='/img/previous.png' title='Вернуться обратно'></a><br/><br/>";

$query_names = "SELECT name,id FROM poll ORDER BY `id` DESC LIMIT 1";
$result      = $mysqli->query($query_names);
$row 		 = $result->fetch_assoc();
$id_poll     = $row['id'];
$name_poll   = $row['name'];

$query = "SELECT r.id_variant,
				 COUNT(r.id) AS count,
				 v.name
			FROM result AS r
			LEFT JOIN variant AS v
			ON(r.id_variant = v.id)			
			WHERE r.id_poll ='$id_poll'
			GROUP BY r.id_variant";
			
$result = $mysqli->query($query);
$total = 0;
while($row = $result->fetch_assoc()){
	$rows[] = $row;
	$total = $total + $row['count'];
}
echo "<h2>".$name_poll."</h2>";
echo "Количество проголосовавших: ".$total."<br/>";
foreach ($rows as $r){
	$percent = $r['count']/$total*100;
	echo $r['name']." - ".$r['count']." чел. - ".$percent."%<br/>";
}


