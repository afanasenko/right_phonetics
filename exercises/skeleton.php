<?php
	require_once('../db_routines.php');
 
	function make_skeleton($internal_name)
	{
		# Текст задания
		echo '<p id="exercise-task">' . GetExerciseTask($internal_name) . '</p>';
		# блок для отображения результата
		echo '<div id="answer-feedback"></div>';
		# блок для размещения содержимого 
		echo '<div id="exercise-content"></div>';
		# блок для размещения кнопок 		
		echo '<div id="control-buttons" style="padding:0.4em">';

			echo '<button id="btn_check">Check</button>';
			echo '<button id="btn_procceed">Next</button>';		
			echo '<button id="btn_showrule">Help</button>';		
		
		echo '</div>';
		
		# блок для помощи
		# Вопрос: заполнять этот блок правилами сразу или ждать нажатия Help, как сделано сейчас?
		echo '<div id="rule_dialog" title="Summary of the rules"></div>';
		
		echo '<div id="warning-message" title="Warning"><p id="warning-message-text"><span class="ui-icon ui-icon-comment" style="float:left; margin:0 7px 50px 0;"></span></p></div>';
		
		# базовый скрипт для управления кнопками
?>		
	<script>
		(function ($) {	
		
			function GetContent() {
			
				// удаляем старое содержимое и прячем до окончания загрузки
				$("#exercise-task").hide(0);
				$("#answer-feedback").hide(0);
				$("#exercise-content").empty();
				$("#control-buttons").hide(0);
				
				// запрос данных из базы
				$.ajax({
					url: "exercises/<?php echo $internal_name; ?>.php",
					type: "POST",
					data : {"action" : "fetch"},
					dataType: "json",
					success: function(data) {
					
						RP_OnLoadContent(data);

						// показываем
						$("#exercise-task").show(0);
						$("#control-buttons").show(0);
				   }
				});
			}
		
			GetContent();		
		
			$( "#btn_check" ).button()
			.click( RP_CheckResult );
			
			$( "#btn_procceed" ).button()
			.click( GetContent );

			// при щелчке по кнопке помощи запрашиваются правила для данного упражнения
			// В принципе, правила можно загрузить сразу, без последующего запроса к fetch_help.php. 
			$( "#btn_showrule" ).button()
			.click(function( event ) {

				$('#rule_dialog').html('Help is coming...');
			
				$.post('exercises/fetch_help.php', { "internal_name" : "<?php echo $internal_name; ?>" }, function(response){
				
					var dlg = $('#rule_dialog');
					// заполняем диалог текстом
					dlg.html( response );	
					// сдвигаем его, т.к. размер мог измениться
					dlg.dialog('option', 'position', 'center');
				});
			
				$("#rule_dialog").dialog( "open" );
				event.preventDefault();
			});		
			
			// модальный диалог с правилами
			$("#rule_dialog").dialog({
				autoOpen: false,
				resizable: false,
				//draggable: false,
				modal: true,
				width: 400,
				buttons: [
					{
						text: "Ok",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				]
			});	

			//модальное окно для вывода предупреждений при неправильных действиях
			$( "#warning-message" ).dialog({
				autoOpen: false,
				resizable: false,
				modal: true,
				buttons: {
					Ok: function() {
						$( this ).dialog( "close" );
					}
				}
			});			

		})(jQuery);
	</script>
<?php
	}
?>
