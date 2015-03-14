<?php
include('bd.php');

//$date = date('Y-m-d');
//$query = "INSERT INTO `test` (`date`) VALUES('$date')";
//$result = $mysqli->query($query);

$query = "SELECT date,tdate FROM test WHERE date BETWEEN '2014-12-02' AND '2014-12-09'";
$result = $mysqli->query($query); 
while ($row = $result->fetch_assoc()){
	echo "Дата - ".$row['date']."<br/>";
}

  function export_csv(
        $table, 		// Имя таблицы для экспорта
        $afields, 		// Массив строк - имен полей таблицы
        $filename, 	 	// Имя CSV файла для сохранения информации
                    // (путь от корня web-сервера)
        $delim=',', 		// Разделитель полей в CSV файле
        $enclosed='"', 	 	// Кавычки для содержимого полей
        $escaped='\\', 	 	// Ставится перед специальными символами
        $lineend='\\r\\n'){  	// Чем заканчивать строку в файле CSV

    $q_export = 
    "SELECT ".implode(',', $afields).
    "   INTO OUTFILE '".$_SERVER['DOCUMENT_ROOT'].$filename."' ".
    "FIELDS TERMINATED BY '".$delim."' ENCLOSED BY '".$enclosed."' ".
    "    ESCAPED BY '".$escaped."' ".
    "LINES TERMINATED BY '".$lineend."' ".
    "FROM ".$table
    ;

        // Если файл существует, при экспорте будет выдана ошибка
        if(file_exists($_SERVER['DOCUMENT_ROOT'].$filename)) 
            unlink($_SERVER['DOCUMENT_ROOT'].$filename); 
        return mysql_query($q_export);
    }
	$afields = array("date","tdate");
	export_csv("test",$afields,"/admin/download/test.csv");
	
	  function import_csv(
        $table, 		// Имя таблицы для импорта
        $afields, 		// Массив строк - имен полей таблицы
        $filename, 	 	// Имя CSV файла, откуда берется информация 
                    // (путь от корня web-сервера)
        $delim=',',  		// Разделитель полей в CSV файле
        $enclosed='"',  	// Кавычки для содержимого полей
        $escaped='\\', 	 	// Ставится перед специальными символами
        $lineend='\\r\\n',   	// Чем заканчивается строка в файле CSV
        $hasheader=FALSE){  	// Пропускать ли заголовок CSV

    if($hasheader) $ignore = "IGNORE 1 LINES ";
    else $ignore = "";
    $q_import = 
    "LOAD DATA INFILE '".
        $_SERVER['DOCUMENT_ROOT'].$filename."' INTO TABLE ".$table." ".
    "FIELDS TERMINATED BY '".$delim."' ENCLOSED BY '".$enclosed."' ".
    "    ESCAPED BY '".$escaped."' ".
    "LINES TERMINATED BY '".$lineend."' ".
    $ignore.
    "(".implode(',', $afields).")"
    ;
        return mysql_query($q_import);
    }

?>