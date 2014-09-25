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
		
		$htm = '<input id="gap" maxlength="2" size="2"></input>';		
		$row['word'] = preg_replace('/\[(.)\]/', $htm, $row['word']);
		$result[$row['id']] = $row['word'];
		
		$query = 'SELECT `id`, `word` FROM `vowel_duration` WHERE `long_vowel`=' . $cue . ' ORDER BY RAND() LIMIT 3;';
		$res = mysql_query($query);
		if (!$res or mysql_num_rows($res) != 3)
			return false;				
		

		while ($row = mysql_fetch_assoc($res))
			$result[$row['id']] = $row['word'];
		
		return $result;
	}
	
	//------------------------------------------------------------------------------
	// 2. Проверка ответа
	function check_result($word_id, $ending)
	{
		$query = 'SELECT `replacement`, FROM `vowel_fill` WHERE `id`=' . $word_id . ';';	
		$res = mysql_query($query);		
		
		if (!$res or mysql_num_rows($res) != 1)
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
					$("#exercise-content").append("<input type=\"hidden\" id=\"cue\" value=\""+val+"\"/>");
				else {
					$("#word_sandbox").append("<p id=\""+key+"\">"+val+"</p>")
				}
			});		
		}
		
		//-----------------------------------------------------------------------------		
		// 5. Проверка результата
		function RP_CheckResult() {
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
			$succ = intval(check_result());
			$result = array('success' => $succ, 'cheers' => GetCheers($succ));
			print json_encode($result);			
			exit();
		}
	}
?>
