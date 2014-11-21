<?php 
	//------------------------------------------------------------------------------
	// Болванка для создания новых упражнений

	require_once('skeleton.php');
	
	//------------------------------------------------------------------------------
	// 1. Выборка данных для формирования содержимого
	function get_working_set()
	{
		$nwords = 10;		
	
		$query = 'SELECT * FROM `long_words` WHERE `stress_count`=2 ORDER BY RAND() LIMIT ' . $nwords . ';';
		
		$res = mysql_query($query);
		if (!$res)
			return false;
			
		$result = array();

		while ($row = mysql_fetch_assoc($res))
		{
			$result[$row['id']] = $row['word'];
		}

		return $result;	
	}
	
	//------------------------------------------------------------------------------
	// 2. Проверка ответа
	function check_result($items)
	{
		$wrong_words = array();

		foreach ($items as $key => $val)		
		{
			$query = 'SELECT `pattern` FROM `long_words` WHERE `id`=' . $key . ';';
			$res = mysql_query($query);
			if (!$res)
				return false;

			$row = mysql_fetch_row($res);
			if ($row)
			{
				$valid = array();
				if (!preg_match_all('/[_^]/', $row[0], $valid))
					return false;		

				$corr = $valid[0];
					
				$str = '';
				foreach($corr as $tok)
					$str .= $tok;
					
				//$wrong_words[] = $str;
					
				
				if (strcmp($str, $val))
					$wrong_words[] = $key;
			}
			else
			{
				//нештатная ситуация
				$wrong_words[] = $key;
			}
		}

		return $wrong_words;
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
		
			//console.log(data);	
			$("#exercise-content").append('<div id="word-sandbox" style="min-height:8em"></div>');
			
			$.each( data, function( key, val ) {

				$("#word-sandbox").append("<p id=\""+key+"\">"+val+"</p>")
				$("#"+key).draggable({
					helper: "clone",
					revert: "invalid",
					opacity : 0.5
				})
					.addClass("draggable_word");				
			});			
			
			$("#exercise-content").append("<table border='1' cellspacing='0' cellpadding='0' style='width:18em'><tr><td width='50%'>, '</td><td width='50%'>' ,</td></tr><tr><td height='2em'><div class='dropbin_wrap'><div class='dropbin' stress-pattern='_^'></div></div></td><td height='2em'><div class='dropbin_wrap'><div class='dropbin' stress-pattern='^_'></div></div></td></tr></table>");			
			
			$( "div.dropbin" ).droppable({
				accept : ".draggable_word",
				drop : function(event, ui) {
					var drag = ui.draggable;
					//ui.draggable( "option", "disabled", true );
					//drag.hide();
					drag.detach();
					drag.appendTo(this);
				}
			});			
		}

		//-----------------------------------------------------------------------------		
		// 5. Проверка результата
		function RP_CheckResult() {
			var chosen_items = {};
			//число слов в корзинах
			var cnt = 0;			
			$("#exercise-content").find("div.dropbin").each(function() {
			
				var container = $(this);
				var pattern = container.attr("stress-pattern");
				container.children().each(function() {
					cnt++;
					var w = $(this);
					var wid = w.attr("id");
					chosen_items[wid] = pattern;
				});
			});

			//console.log(chosen_items);				
			
			//сравнение с общим числом слов
			if (cnt != $(".draggable_word").length) {
			
				$("#warning-message-text").html("Please, drag all words first!");
				$("#warning-message").dialog( "open" );			
				return;
			}
	
			// запрос результата
			$.ajax({
				url: "exercises/<?php echo $internal_name; ?>.php",
				type: "POST",
				data : {"action" : "check", "items" : JSON.stringify(chosen_items)},
				dataType: "json",
				success: function(data) {				

					console.log(data);
					
					// сомнительный способ убрать пустое место
					$("#word-sandbox").css("min-height", "0");					
					
					// показ текстового сообщения
					$("#answer-feedback").html(data["cheers"])
						.show(100);

					// помечаем все слова как по умолчанию правильные
					$(".draggable_word").removeClass("wrong_answer");				
						
					if (data["success"] == 1) {
						$("#answer-feedback").removeClass("ui-state-error")
											.addClass("ui-state-highlight");
					}
					else {
						$("#answer-feedback").removeClass("ui-state-highlight")
											.addClass("ui-state-error");
					}
					
					// подсветка неправильных ответов
					if (data["correction"]) {
						console.log(data["correction"]);				
						$.each( data["correction"], function( key, val ) {
							$("#"+val).addClass("wrong_answer");				
						});	
					}					
				}
			});
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
			$result = get_working_set();
			if (!$result)
				die("error");

			print json_encode($result);
		}
		//-----------------------------------------------------------------------------		
		// 7. Ответ на запрос проверки результата
		elseif (!strcmp($_POST['action'], 'check'))
		{
			if (isset($_POST['items']))
			{
				// true для преобразования объекта в array, функции check_result это безразлично,
				// но у объекта не посчитать число элементов count($items)			
				$items = json_decode($_POST['items'], true);
				$errors = check_result($items);
				
				$nerr = count($errors);
				if ($nerr == count($items))
					$succ = 0;
				elseif ($nerr == 0)
					$succ = 1;
				else
					$succ = 2;
			}				
			else
			{
				$errors = array();
				$succ = 0;				
			}
				
			$result = array('success' => $succ, 'cheers' => GetCheers($succ), 'correction' => $errors);
			print json_encode($result);			
			exit();
		}
	}
?>
