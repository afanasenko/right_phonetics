<?php 
	//------------------------------------------------------------------------------
	// Болванка для создания новых упражнений

	require_once('skeleton.php');
	
	//------------------------------------------------------------------------------
	// 1. Выборка данных для формирования содержимого
	function get_working_set()
	{
		$cue = rand() % 2;
		$result = array();

		$query = 'SELECT `id`, `word` FROM `vowel_fill` WHERE `long_vowel`=' . $cue . ' ORDER BY RAND() LIMIT 1;';
		$res = mysql_query($query);		
		
		if (!$res or mysql_num_rows($res) != 1)
			return false;		
			
		$row = mysql_fetch_assoc($res);
		
		$htm = '<input type="text" id="gap" maxlength="2" size="2"></input>';	
		//одна или больше букв в квадратных скобках
		$fixpart = preg_replace('/\[(.+)\]/', '', $row['word']);		
		$wid = $row['id'];
		$row['word'] = preg_replace('/\[(.+)\]/', $htm, $row['word']);
		$result[$wid] = $row['word'];
		
		// с условием на несовпадение с ключевым словом и неповторение индексов
		$query = 'SELECT `id`, `word` FROM `vowel_duration` WHERE `long_vowel`=' . $cue . ' AND SUBSTRING(`word`,0,' . count($fixpart) . ') <> "'. $fixpart . '" AND `id` <> ' . $wid . ' ORDER BY RAND() LIMIT 3;';
		$res = mysql_query($query);
		if (!$res or mysql_num_rows($res) != 3)
			return false;				
		

		while ($row = mysql_fetch_assoc($res))
			$result[$row['id']] = $row['word'];
			
		$result['cue'] = $wid;
		
		return $result;
	}
	
	//------------------------------------------------------------------------------
	// 2. Проверка ответа
	function check_result($word_id, $ending, &$corr)
	{
		$query = 'SELECT `word` FROM `vowel_fill` WHERE `id`=' . $word_id . ';';	
		$res = mysql_query($query);		
		
		if (!$res or mysql_num_rows($res) != 1)
			return false;	

		$row = mysql_fetch_row($res);
			
		$valid = array();
		if (!preg_match_all('/(\w+)/', $row[0], $valid))
			return false;
		
		$corr = $valid[0] ;		
		
		foreach($valid[0] as $token)
		{
			if (!strcmp($token, $ending))
				return true;
		}
		return false;
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
		
			$("#exercise-content").append('<div id="word_sandbox" style="min-height:4em"></div>');
			console.log(data);			
			
			$.each( data, function( key, val ) {

				if (key == "cue")
					$("#exercise-content").append("<input type=\"hidden\" id=\"word_id\" value=\""+val+"\"/>");
				else {
					$("#word_sandbox").append("<p id=\""+key+"\">"+val+"</p>")
				}
			});	

			// фокус ввода
			$('#gap').focus();
		}
		
		//-----------------------------------------------------------------------------		
		// 5. Проверка результата
		function RP_CheckResult() {
			var answer = $('#gap').val();
			var word_id = $("#word_id").val();
			console.log(word_id);
			console.log(answer);
			
			if (answer) {
				// запрос результата
				$.ajax({
					url: "exercises/<?php echo $internal_name; ?>.php",
					type: "POST",
					data : {"action" : "check", "word_id" : word_id, "ending" : answer},
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
				$("#warning-message-text").html("Please, type something!");
				$("#warning-message").dialog( "open" );						
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

			print json_encode($res);
			exit();
		}
		//-----------------------------------------------------------------------------		
		// 6. Ответ на запрос проверки результата
		elseif (!strcmp($_POST['action'], 'check'))
		{
			$word_id = $_POST['word_id'];
			$ending = $_POST['ending'];
			$corr = array();
		
			$succ = intval(check_result($word_id, $ending, $corr));
			$result = array('success' => $succ, 'cheers' => GetCheers($succ), 'correction' => $corr);
			print json_encode($result);			
			exit();
		}
	}
?>
