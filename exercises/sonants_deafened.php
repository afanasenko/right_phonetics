<?php 
	require_once('skeleton.php');

	// Получение набора вариантов на стороне сервера
	function get_working_set()
	{
		$res1 = mysql_query('SELECT * FROM `sonants_deafened` WHERE `deafened`=1 ORDER BY RAND() LIMIT 1;');
		$res2 = mysql_query('SELECT * FROM `sonants_deafened` WHERE `deafened`=0 ORDER BY RAND() LIMIT 2;');
		
		if (!$res1 or !$res2)
			return false;
		
		if (mysql_num_rows($res1) != 1 or mysql_num_rows($res2) != 2)
			return false;
		
		$result = array(rand() => mysql_fetch_assoc($res1),
			rand() => mysql_fetch_assoc($res2),
			rand() => mysql_fetch_assoc($res2));

		// Сортировка по случайному ключу - возможно, не лучший вариант.
		ksort($result);
		return $result;
	}
	
	// Проверка результата на стороне сервера
	function check_result($word_id)
	{
		$res = mysql_query('SELECT `deafened` FROM `sonants_deafened` WHERE `id`=' . $word_id . ';');
		if (!$res)
			return false;
			
		if (mysql_num_rows($res) != 1)
			return false;
		
		$row = mysql_fetch_row($res);
		return $row[0];
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
				
			$("#exercise-content").append("<div id=\"radio\" style=\"margin:2em 0; display:table; width:100%\"></div>");
			
			$.each( data, function( key, val ) {
			
				$("#radio").append("<div id=\"radio"+key+"\" style=\"display:table-cell; padding:0 0.5em;\"></div>");				
				
				$("#radio"+key).append("<input type=\"radio\" id=\""+key+"\" name=\"select_word\"><label for=\""+key+"\">"+val+"</label>");				
			});
		}
		
		//-----------------------------------------------------------------------------		
		// проверка результата
		function RP_CheckResult() {
			var u = $("input:radio:checked").attr("id");
		
			if (typeof u != 'undefined') {
				
				// запрос результата
				$.ajax({
					url: "exercises/<?php echo $internal_name; ?>.php",
					type: "POST",
					data : {"action" : "check", "word" : u},
					dataType: "json",
					success: function(data) {				

						console.log(data);
					
						$("#answer-feedback").html(data["cheers"])
							.show(100);
						
						var s = parseInt(data["success"]);
						
						if (s) {
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
			make_skeleton($internal_name);
			exit();
		}
		elseif (!strcmp($_POST['action'], 'fetch'))
		{
			$res = get_working_set();
			
			if (!$res)
				die('Error getting data');	
			
			$result = array();
			foreach ($res as $entry)
			{
				$id = $entry['id'];
				// звук, который в транскрипции взят в квадратные скобки, выделяется стилем
				$trans = preg_replace('/\[(.)\]/', '<span style="font-weight:800; color:#f00">$1</span>', $entry['transcription']);

				$result[$id] = $entry['word'] . ' /' . $trans . '/';
			}			

			print json_encode($result);						
			exit();
		}
		elseif (!strcmp($_POST['action'], 'check'))
		{
			$word_id = $_POST['word'];
			$succ = intval(check_result($word_id));
			$result = array('success' => $succ, 'cheers' => GetCheers($succ));
			print json_encode($result);			
			exit();
		}
	}
?>