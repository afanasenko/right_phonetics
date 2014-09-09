<?php 
	require('./header.php'); 
	require_once('./db_routines.php');
	session_start();
?>

<body>
	<script src="js/jquery.cookie.js"></script>	
	<script>
	$(function() {

		$('ul#activity_menu ul').each(function(index) {
			$(this).prev().addClass('collapsible').click(function() {
				if ($(this).next().css('display') == 'none') {
					$(this).next().slideDown(100, function () {
						$(this).prev().removeClass('collapsed').addClass('expanded');
						$(this).prev().css('list-style','square');						
					});
				}
				else {
					$(this).next().slideUp(100, function () {
						$(this).prev().removeClass('expanded').addClass('collapsed');
						$(this).prev().css('list-style','inherit');						
						$(this).find('ul').each(function() {
							$(this).hide().prev().removeClass('expanded').addClass('collapsed');
						});
					});
				}
				return false;
			});
		});
		
		var lesson = $.cookie('display_lesson');
		var unit = $.cookie('display_unit');
		if (lesson && unit)
			SelectLesson(unit, lesson);

	});
	
	function FetchExercise(token) {
		console.log('FetchExercise: ' + token);
	
		// запрос результата
		$.ajax({
			url: './exercises/' + token + '.php',
			type: "POST",
			data : {"action" : "create", "internal_name" : token},
			dataType: "html",
			success: function(data) {		
				$('#exercise-body').html(data);
				$("#"+token).addClass('selected');
				$("li.lesson-menu").not("#"+token).removeClass('selected');				
			}
		});
	}

	function SelectLesson(idu, idl) {
		idu = parseInt(idu);
		idl = parseInt(idl);
		
		console.log('SelectLesson: ' + idu + '.' + idl);
		var url = './create_lesson.php';
		$.post(url, { "unit" : idu, "lesson" : idl }, function(response){
			
			$("#lesson-header").html(response);			
			
			/*
			var hdr = $("#lesson-header");
			var parent = hdr.parent();
			hdr.detach();
			hdr.html(response);
			parent.append(hdr);
			*/
			
			var id = 100*idu + idl;
			$('#'+id).addClass('selected');
			$('li.exercise-handle').not('#'+id).removeClass('selected');
					
			$.cookie('display_unit', idu);
			$.cookie('display_lesson', idl);
			
			$('#exercise-body').html('');	
		});			
	}

	</script>
	
	<div id="container" class="ui-widget">		

		<!-- Title -->
		<div class="ui-widget" style="text-align:left; margin-top:0;">
		
		<table><tr><td><img src="./img/pudding-2.jpg" height="48px"></td><td>
<?php
			echo('<h3>' . APP_NAME . '</h3>');
?>
		</td></tr></table>
		</div>	
		
		<div id="left-panel" class="ui-widget">
	
<?php
		echo '<div class="ui-widget-header ui-corner-all ui-helper-clearfix" style="padding:0.4em;"><span>Course contents</span></div>';
		echo '<div>';

			$result = mysql_query('SELECT id_unit, name_unit FROM units;');
			if (!$result)
				handle_db_error();
				
			echo '<ul id="activity_menu">';
			while ($row = mysql_fetch_row($result))
			{
				$idu = $row[0];
			
				echo '<li><span>' . $row[1] . '</span>';
				
					$result2 = mysql_query('SELECT id_lesson, name_lesson FROM lessons WHERE id_unit=' . $idu . ';');
					if (!$result2)
						handle_db_error();				

					echo '<ul>';						
					while ($row2 = mysql_fetch_row($result2))
					{			
						$idl = $row2[0];
						# сомнительный способ задания идентификатора...						
						$iditem = (100*$idu+$idl);
						$itemclass = 'exercise-handle';
						
						if (isset($_SESSION['unit_lesson_selection']))
						{
							if ($_SESSION['unit_lesson_selection'] == $iditem)
								$itemclass .= ' selected';
						}
						
						echo '<li class="' . $itemclass . '" id="' . $iditem . '" href=# onclick="SelectLesson(' . $idu . ', ' . $idl . ');">' . $row2[1] . '</li>';
					}
					echo '</ul>';
				
				echo '</li>';
			}
			echo '</ul>';
			
		echo '</div>';

?>
		</div> <!-- left-panel -->
		
		<div id="content">	

			<!-- блок с меню по упражнениям -->
			<div id="lesson-header">
			</div>

			<!-- блок с содержимым упражнения -->
			<div id="exercise-body">
			</div>
			
		</div>	

		<div id="footer">
<?php 
			require_once('./app_config.php'); 
			
			echo '<div style="display:table;">';
			echo '<div style="display:table-cell;"><a href="' . PMA_LINK . '" target="_blank">Manage course database (PHPMyAdmin)</a></div>';

			#echo '<div style="display:table-cell;">' . APP_NAME . '&copy 2014</div>'; 
			echo '</div>';
?>
		</div>			
	</div>
</body>

<?php
	require('./footer.php'); 	
?>
