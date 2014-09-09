<?php 
	require_once('skeleton.php');
	
	// Получение набора вариантов
	function get_working_set()
	{
		$res = mysql_query('SELECT * FROM `syllable_choose` ORDER BY RAND() LIMIT 1;');

		if (!$res or mysql_num_rows($res) != 1)
			return false;
		
		return mysql_fetch_assoc($res);
	}
	
	// Проверка ответа
	function check_result($word_id, $sylltype)
	{
		$res = mysql_query('SELECT `pattern` FROM `syllable_choose` WHERE `id`=' . $word_id . ';');

		if (!$res or mysql_num_rows($res) != 1)
			return false;
		
		$row = mysql_fetch_row($res);
		return $row[0] == $sylltype;
	}

//*****************************************************************************************************	
	
	if (isset($_POST['action']))
	{
		// передача разметки упражнения
		if (!strcmp($_POST['action'], 'create'))
		{
			$internal_name = $_POST['internal_name'];

			// скрипты обновления содержимого и проверки результата
?>
	<script>
		//Функции размещаются в глобальном пространстве имен
		
		//-----------------------------------------------------------------------------		
		// обработчик загрузки данных с сервера в результате выполнения запроса action=fetch
		function RP_OnLoadContent(data) {
				
			$("#exercise-content").append("<p id=\"word\"></p>");
			$("#exercise-content").append("<input type=\"hidden\" id=\"word_id\"></input>");
			$("#exercise-content").append("<div id=\"radio\" style=\"margin:2em 0; display:table\"></div>");
		
			$.each( data, function( key, val ) {
			
				if (key == "word")
					$("#word").html("<strong>"+val+"</strong>");
				else if (key == "word_id")
					$("#word_id").html(val);
				else {
					$("#radio").append("<div id=\"radio"+key+"\" style=\"display:table-cell; padding:0 0.5em;\"></div>");				
				
					$("#radio"+key).append("<input type=\"radio\" id=\""+key+"\" name=\"select_syllable\"><label for=\""+key+"\">"+val+"</label>");				
				}
			});
		}
		
		//-----------------------------------------------------------------------------		
		// проверка результата
		function RP_CheckResult() {
			var u = $("input:radio:checked").attr("id");
		
			if (typeof u != 'undefined') {
				var word_id = $("#word_id").text();
				
				// запрос результата
				$.ajax({
					url: "exercises/<?php echo $internal_name; ?>.php",
					type: "POST",
					data : {"action" : "check", "word" : word_id, "pattern" : u},
					dataType: "json",
					success: function(data) {				

						console.log(data);
					
						$("#answer-feedback").html(data["cheers"])
							.show(100);
						
						if (data["success"]) {
							$("#answer-feedback").removeClass("ui-state-error")
												.addClass("ui-state-highlight");
						}
						else {
							$("#answer-feedback").removeClass("ui-state-highlight")
												.addClass("ui-state-error");
						}
					
					}
				});
			}		
			else {
				alert('Make your choice!');
			}
		}
	</script>
<?php
			make_skeleton($internal_name);
			exit();
		}
		elseif (!strcmp($_POST['action'], 'fetch'))
		{
			$res = get_working_set();
			if (!$res)
				die("error");

			$result = array('word' => $res['word'], 'word_id' => $res['id']);
			
			$res = mysql_query('SELECT * from `syllable_patterns`;');
			if ($res)
			{
				while ($row = mysql_fetch_assoc($res))
					$result[$row['id']] = $row['pattern'];
			}
			else
			{
				$result['error'] = mysql_error();
			}
			
			print json_encode($result);
			exit();
		}
		elseif (!strcmp($_POST['action'], 'check'))
		{
			if (isset($_POST['word']) and isset($_POST['pattern']))
			{
				$succ = intval(check_result($_POST['word'], $_POST['pattern']));
				$result = array('success' => $succ, 'cheers' => GetCheers($succ));
				print json_encode($result);			
				exit();
			}
			else
				exit();
		}
	}
?>
