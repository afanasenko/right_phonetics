<?php 
	//------------------------------------------------------------------------------
	// Болванка для создания новых упражнений

	require_once('skeleton.php');
	
	//------------------------------------------------------------------------------
	// 1. Выборка данных для формирования содержимого
	function get_working_set()
	{
		$res = mysql_query('SELECT * FROM `sonants_forming` ORDER BY RAND() LIMIT 1;');
		
		if (!$res)
			return false;
		
		if (mysql_num_rows($res) != 1)
			return false;
		
		return mysql_fetch_assoc($res);
	}
	
	//------------------------------------------------------------------------------
	// 2. Проверка ответа
	function check_result($word_id, $syll_count)
	{
		$res = mysql_query('SELECT * FROM `sonants_forming` WHERE `id`=' . $word_id . ';');
		
		if (!$res)
			return false;
		
		if (mysql_num_rows($res) != 1)
			return false;
		
		$row = mysql_fetch_assoc($res);
		$arr = explode('|', $row['transcription']);
		return count($arr) == $syll_count;
	}

//*****************************************************************************************************	
	
	if (isset($_POST['action']))
	{
		//------------------------------------------------------------------------------
		// 3. Ответ на запрос создания упражнения (передача разметки)
		if (!strcmp($_POST['action'], 'create'))
		{
			$internal_name = $_POST['internal_name'];

			// скрипты обновления содержимого и проверки результата
?>
	<script>
		//Функции размещаются в глобальном пространстве имен
		
		//-----------------------------------------------------------------------------		
		// 4. Обработчик загрузки данных с сервера в результате выполнения запроса action=fetch
		function RP_OnLoadContent(data) {
		
			$("#exercise-content").append("<p id=\"word\"></p>");
			$("#exercise-content").append("<input type=\"hidden\" id=\"word_id\"></input>");
			$("#exercise-content").append("<div id=\"radio\" style=\"margin:2em 0; display:table\"></div>");

			for (i = 1; i <= 3; ++i) {
				$("#radio").append("<div id=\"radio"+i+"\" style=\"display:table-cell; padding:0 0.5em;\"></div>");				
				$("#radio"+i).append("<input type=\"radio\" id=\""+i+"\" name=\"count_syllables\"><label for=\""+i+"\">"+i+"</label>");		
			}			

			$("#word").html("<strong>"+data['word']+"</strong> /"+data['transcription']+"/");
			$("#word_id").html(data['word_id']);
		}
		
		//-----------------------------------------------------------------------------		
		// 5. Проверка результата
		function RP_CheckResult() {
			var u = $("input:radio:checked").attr("id");
		
			if (typeof u != 'undefined') {
				var word_id = $("#word_id").text();
				
				// запрос результата
				$.ajax({
					url: "exercises/<?php echo $internal_name; ?>.php",
					type: "POST",
					data : {"action" : "check", "word" : word_id, "count" : u},
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
				$("#warning-message-text").html("Please, make your choice!");
				$("#warning-message").dialog( "open" );			
				return;
			}
		}
	</script>
<?php
			// общая разметка + обработка вызова справки
			make_skeleton($internal_name);
			exit();
		}
		//-----------------------------------------------------------------------------		
		// 6. Ответ на запрос содержимого
		elseif (!strcmp($_POST['action'], 'fetch'))
		{
			$res = get_working_set();
			if (!$res)
				die("error");

			$trans = str_replace('|', '', $res['transcription']);				
				
			$result = array('word_id' => $res['id'], 'word' => $res['word'], 'transcription' => $trans);
			print json_encode($result);
			exit();
		}
		//-----------------------------------------------------------------------------		
		// 6. Ответ на запрос проверки результата
		elseif (!strcmp($_POST['action'], 'check'))
		{
			if (isset($_POST['word']) and isset($_POST['count']))
			{
				$succ = intval(check_result($_POST['word'], $_POST['count']));
				$result = array('success' => $succ, 'cheers' => GetCheers($succ));
				print json_encode($result);			
			}
			exit();
		}
	}
?>
