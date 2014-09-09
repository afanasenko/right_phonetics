<?php 
	//------------------------------------------------------------------------------
	// Болванка для создания новых упражнений

	require_once('skeleton.php');
	
	//------------------------------------------------------------------------------
	// 1. Выборка данных для формирования содержимого
	function get_working_set()
	{
		$cue_pattern = 1 + rand() % 2 ;
		$antipattern = $cue_pattern == 1 ? 2 : 1;
	
		$query = 'SELECT * FROM `syllable_choose` WHERE `pattern`=1 ORDER BY RAND() LIMIT 4;';
		
		$res = mysql_query($query);
		if (!$res)
			return false;
			
		$result = array();

		while ($row = mysql_fetch_assoc($res))
		{
			$key = rand();
			while (array_key_exists($key, $result))
				$key = rand();
			$result[$key] = $row;
		}
		
		$query = 'SELECT * FROM `syllable_choose` WHERE `pattern`=2 ORDER BY RAND() LIMIT 4;';
		
		$res = mysql_query($query);
		if (!$res)
			return false;
			
		while ($row = mysql_fetch_assoc($res))
		{
			$key = rand();
			while (array_key_exists($key, $result))
				$key = rand();

			$result[$key] = $row;
		}		

		// Сортировка по случайному ключу - возможно, не лучший вариант.
		ksort($result);
		return $result;	
	}
	
	//------------------------------------------------------------------------------
	// 2. Проверка ответа
	function check_result($word_id, $sylltype)
	{
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
		
			console.log(data);	
			$("#exercise-content").append('<div id="word_sandbox" style="height:8em"></div>');
			
			$.each( data, function( key, val ) {

				$("#word_sandbox").append("<p id=\""+key+"\">"+val+"</p>")
				$("#"+key).draggable({ revert: true })
					.addClass("draggable_word");				
			});			
			
			$("#exercise-content").append("<div class=\"dropbin\" id=\"bin1\" style=\"border: 1px solid; height: 5em\"></div>");			
			$("#exercise-content").append("<div class=\"dropbin\" id=\"bin2\" style=\"border: 1px solid; height: 5em\"></div>");						
			
			$( "div.dropbin" ).droppable({
				accept : ".draggable_word",
				drop : function(event, ui) {
					var drag = ui.draggable;
					//ui.draggable( "option", "disabled", true );
					//drag.hide();
					drag.detach();
					drag.appendTo(this);
					//RP_CheckResult(drag.attr("id"))						;
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

			$result = array();
				
			foreach ($res as $val)
			{
				$wid = $val['id'];
				$result[$wid] = $val['word'];
			}	
			
			print json_encode($result);
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
