<?php 
	//------------------------------------------------------------------------------
	// Болванка для создания новых упражнений

	require_once('skeleton.php');
	
	//------------------------------------------------------------------------------
	// 1. Выборка данных для формирования содержимого
	function get_working_set(&$cue_pattern)
	{
		$cue_pattern = 1 + rand() % 2 ;
		$antipattern = $cue_pattern == 1 ? 2 : 1;
	
		$query = 'SELECT * FROM `syllable_choose` WHERE `pattern`=' . $cue_pattern . ' ORDER BY RAND() LIMIT 1;';
		
		$res1 = mysql_query($query);
		
		$query = 'SELECT * FROM `syllable_choose` WHERE `pattern`=' . $antipattern . ' ORDER BY RAND() LIMIT 3;';
		$res2 = mysql_query($query);
		
		if (!$res1 or !$res2)
			return false;
		
		if (mysql_num_rows($res1) != 1 or mysql_num_rows($res2) != 3)
			return false;
		
		$result = array(rand() => mysql_fetch_assoc($res1));
		
		while ($row = mysql_fetch_assoc($res2))
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
		// Проверка результата (в действительности не используется)
		function RP_CheckResult() {
			$("#warning-message-text").html("Just drag a word to the bin!</br>Your answer will be checked automatically!");
			$("#warning-message").dialog( "open" );
		}		
		
		//-----------------------------------------------------------------------------		
		// 4. Проверка результата
		function RP_CheckResult_internal(word_id) {
			// ключ, с которым сравнивается тип выбранного слова
			var u = $("input#cue").val();
		
			if (typeof u != 'undefined') {
				// скрипт проверки результата одинаковый для нескольких упражнений!
				var server_url = "./syllable_choose.php";
				
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
							$("#trashbin").html('<img src="./img/bin_happy.gif">');
						}
						else {
							$("#answer-feedback").removeClass("ui-state-highlight")
												.addClass("ui-state-error");
							$("#trashbin").html('<img src="./img/bin_sad.gif">');
						}
					
					}
				});
			}		
			else {
				alert('Make your choice!');
			}	
		}
		
		//-----------------------------------------------------------------------------		
		// 5. Обработчик загрузки данных с сервера в результате выполнения запроса action=fetch
		function RP_OnLoadContent(data) {
			
			$("#exercise-content").append('<div id="word_sandbox" style="min-height:4em"></div>');
			$("#btn_check").hide();
			
			console.log(data);			
			
			$.each( data, function( key, val ) {

				if (key == "cue")
					$("#exercise-content").append("<input type=\"hidden\" id=\"cue\" value=\""+val+"\"/>");
				else {
					$("#word_sandbox").append("<p id=\""+key+"\">"+val+"</p>")
					$("#"+key).draggable({ containment : "#exercise-body", revert: true })
						.addClass("draggable_word");				
				}
			});
			
			$("#exercise-content").append('<div><div id="trashbin"><img src="./img/bin.gif"></div></div>');
			$( "#trashbin" ).droppable({
				accept : ".draggable_word",
				drop : function(event, ui) {
					var drag = ui.draggable;
					//ui.draggable( "option", "disabled", true );
					drag.hide();
					//drag.appendTo(this);
					RP_CheckResult_internal(drag.attr("id"))						;
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
			$cue = 0;
			$res = get_working_set($cue);
			if (!$res)
				die("error");

			$result = array();
				
			foreach ($res as $val)
			{
				$wid = $val['id'];
				$result[$wid] = $val['word'];
			}	
			
			$result['cue'] = $cue;
			print json_encode($result);
		}
		//-----------------------------------------------------------------------------		
		// 6. Ответ на запрос проверки результата
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
