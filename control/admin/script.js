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
	xmlhttp2.open('POST', '/control/admin/depQuery_by_actual.php', true); // Открываем асинхронное соединение
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
	xmlhttp.open('POST', '/control/admin/dep_to_names.php', true); // Открываем асинхронное соединение
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
	xmlhttp.open('POST', '/control/admin/depQuery.php', true); // Открываем асинхронное соединение
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
  
 
function delin(){
	alert('Вы уверены?');
	return false;
}

function validForm(f){
	f.submit();
}
 
function postin(){
	var valid = true;
	var msg = [];
	var dep = document.getElementById('dep_id');
	
	if(document.getElementById('date').value == "" || document.getElementById('date').value == null){
		msg.push("Срок поручения");
		document.getElementById("dateSpan").style.color = "red";
		document.getElementById("date").style.color = "red";
		document.getElementById("dateSpan").style.border = "1px solid red"; 
		document.getElementById("date").style.border = "1px solid red"; 
		valid = false;
	}
	else{
		document.getElementById("dateSpan").style.color = "black";
		document.getElementById("date").style.color = "black";
		document.getElementById("dateSpan").style.border = "0px solid black"; 
		document.getElementById("date").style.border = "1px solid #CCC";
	}	
	
	
	if(dep.options[dep.selectedIndex].text == "" || dep.options[dep.selectedIndex].text == null){
		msg.push("Название Департамента");
		document.getElementById("department").style.color = "red";
		document.getElementById("department").style.border = "1px solid red"; 
		document.getElementById("dep_id").style.border = "1px solid red"; 
		valid = false;
	}
	else{
		document.getElementById("department").style.color = "black";
		document.getElementById("dep_id").style.color = "black";
		document.getElementById("department").style.border = "0px solid black"; 
		document.getElementById("dep_id").style.border = "1px solid #CCC";
	}
	
	if(document.getElementById('descr').value == "" || document.getElementById('descr').value == null){
		msg.push("Текст поручения");
		document.getElementById("descrSpan").style.color = "red";
		document.getElementById("descrSpan").style.border = "1px solid red"; 
		document.getElementById("descr").style.border = "1px solid red"; 
		valid = false;
	}
	else{
		document.getElementById("descrSpan").style.color = "black";
		document.getElementById("descr").style.color = "black";
		document.getElementById("descrSpan").style.border = "0px solid black"; 
		document.getElementById("descr").style.border = "1px solid #CCC";
	}
			
	
	
	if (!valid){
		var report ='Не введены или не выбраны:<br/>';
		msg.forEach(function(entry){
			report = report + entry + "<br/>";
		});
		document.getElementById("report").style.visibility = "visible";
		document.getElementById("report").innerHTML = report;
	}


	return valid;
}

