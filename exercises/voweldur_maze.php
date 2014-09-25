<?php 
	//------------------------------------------------------------------------------
	// Болванка для создания новых упражнений

	require_once('skeleton.php');
	
	//------------------------------------------------------------------------------
	// 1. Выборка данных для формирования содержимого
	function get_working_set()
	{
		$ptn1 = rand() % 2 ;
		$ptn2 = $ptn1 == 1 ? 0 : 1;
		$patterns = array(0 => $ptn1, 1 => $ptn2);
		
		$nwords = 15;		
		$result = array();	
		
		foreach($patterns as $p)
		{
			$query = 'SELECT * FROM `vowel_duration` WHERE `long_vowel`=' . $p . ' ORDER BY RAND() LIMIT ' . $nwords . ';';
		
			$res = mysql_query($query);
			if (!$res)
				return false;
				
			while ($row = mysql_fetch_assoc($res))
			{
				$result[] = $row;
			}
		}
		
		return $result;			
	}
	
	//------------------------------------------------------------------------------
	// 2. Проверка ответа
	function check_result($words)
	{
		$ref_word = $words[0];
		$query = 'SELECT `long_vowel` FROM `vowel_duration` WHERE `id`=' . $ref_word . ';';		
		$res = mysql_query($query);
		if (!$res)
			return false;
			
		$row = mysql_fetch_row($res);
		if ($row)
			$cue = $row[0];
		else
			return false;
		
		$wrong_words = array();

		foreach ($words as $val)		
		{
			$query = 'SELECT * FROM `vowel_duration` WHERE `id`=' . $val . ' AND `long_vowel`=' . $cue . ';';

			$res = mysql_query($query);
			if (!$res)
				return false;
				
			if (!mysql_num_rows($res))
				$wrong_words[] = $val;
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
		function GenerateMaze() {
			var w = []
			var count_positive = 0;
			
			for (var i = 0; i < 25; i++) {
				w.push(0);
			}
			var startField = Math.floor(Math.random() * 5);
			var startX = 0;
			var startY = startField;


			var x = startX;
			var y = startY;
			w[y * 5 + x] = 1;

			var stopCount = 0;
			while (true) {
				var direction = Math.floor(Math.random() * 3);
				var newX = x;
				var newY = y;
				switch (direction) {
					case 0:
						newY--;
						break;
					case 1:
						newX++;
						break;
					case 2:
						newY++;

						break;
				}
				if (stopCount > 50) {
					break;
				}
				if (newY < 0 || newY > 4 || (direction != 1 && newX > 1 && w[newY * 5 + newX - 1] == 1) || w[newY * 5 + newX] == 1) {
					stopCount++;
					continue;
				}


				w[newY * 5 + newX] = 1;
				x = newX;
				y = newY;
				count_positive++;
				if (newX == 4) {
					break;
				}

			}
			return { points: w, start: { x: startX, y: startY }, end: { x: x, y: y}, count: count_positive };
		}		
		
		
		//-----------------------------------------------------------------------------		
		// 4. Обработчик загрузки данных с сервера в результате выполнения запроса action=fetch
		// вражеский код 
		function RP_OnLoadContent(data) {
		
			console.log(data);
		
			var mazeHtml = "<table class='maze_table'><tr>"
			//формирование пути в лабиринте
			var maze = GenerateMaze(5, 5);
			var div = $("#exercise-content");
			
			var npos = 15;
			var nneg = 0;
			
			// заполнение лабиринта словами из запроса
			for (var i = 0; i < 5; i++) {
				for (var j = 0; j < 5; j++) {
					var isPositive = maze.points[i * 5 + j] == 1;
					var style = "";
					if (j == maze.start.x && i == maze.start.y) {
						style = 'style="background-color:#0a0" maze-start="true"';
					} else if (j == maze.end.x && i == maze.end.y) {
						style = 'style="background-color:#0a0"';
					}

					var offset = isPositive ? 0 : 15;
					var text = "";
					var w_id = 0;
					if (isPositive) {
						text = data[npos]['word'];
						w_id = data[npos]['id'];
						npos++;
					}
					else {
						text = data[nneg]['word'];
						w_id = data[nneg]['id'];
						nneg++;					
					}

					mazeHtml += "<td width='20px' " + style + " data-ispositive='" + isPositive + "' word-id='" + w_id + "'>" + text + "</td>";
				}
			mazeHtml += "</tr><tr>";
			}
			mazeHtml = mazeHtml.substr(0, mazeHtml.length - 4);
			mazeHtml += "</table>"
			
			div.append(mazeHtml);
			// обработка нажатия на ячейке
			var onTdClick = function(ev) {
				var item = $(ev.target);
				if (item.attr("data-isselected") == "true") {
					item.removeClass("maze_elem_selected");				
					item.attr("data-isselected", false);
				} else {
					item.addClass("maze_elem_selected");
					item.attr("data-isselected", true);
				}
			}
			div.find("TD").css("cursor", "pointer").click(onTdClick);			
		}
		
		//-----------------------------------------------------------------------------		
		// 5. Проверка результата
		function RP_CheckResult() {
		
			var div = $("#exercise-content");		
			var chosen_items = [];
			div.find("TD").css("cursor", "default").unbind("click").each(function() {			
				var td = $(this);
				if (td.attr("data-isselected") == "true") {
					chosen_items.push(td.attr("word-id"));
				}
				// добавление начального и конечного элементов
				if (td.attr("maze-start") == "true") {
					chosen_items.splice(0, 0, td.attr("word-id"));
					chosen_items.join();
				}				
			});
			console.log(chosen_items);
			
			//TODO: проверить, что путь выстроен
			
			// запрос результата
			$.ajax({
				url: "exercises/<?php echo $internal_name; ?>.php",
				type: "POST",
				data : {"action" : "check", "items" : JSON.stringify(chosen_items)},
				dataType: "json",
				success: function(data) {				

					console.log(data);
					
					// показ текстового сообщения
					$("#answer-feedback").html(data["cheers"])
						.show(100);

					// помечаем все слова как по умолчанию правильные
					//$(".draggable_word").removeClass("wrong_answer");				
						
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
							$("*[word-id="+val+"]").removeClass("maze_elem_selected").addClass("maze_elem_wrong");				
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
			if (isset($_POST['items']))
			{
				$items = json_decode($_POST['items']);
				$errors = check_result($items);
				
				$nerr = count($errors);
				if ($nerr == count($items)-1)
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
