<?php 
	require('./header.php'); 
	require_once('./db_routines.php');
	session_start();
	
	$course_tree = MakeCourseTree();	
?>

<body>
	<script src="js/jquery.cookie.js"></script>	
	<script>
	$(function() {

		$('ul#activity-menu ul').each(function(index) {
			$(this).prev().addClass('collapsible-unit').click(function() {
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
		/*
		$('ul.unit-menu ul').each(function(index) {
			$(this).prev().addClass('collapsible-lesson').click(function() {
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
		*/
		var last_exercise = $.cookie('display_exercise');
		if (last_exercise)
			FetchExercise(last_exercise);
	});
	
	// Загрузка упражнения
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
				$("li.exercise-handle").not("#"+token).removeClass('selected');			
				$.cookie('display_exercise', token, {expires : 7});				
			}
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

			// список верхнего уровня с названиями разделов
			echo '<ul id="activity-menu">';
		
			foreach ($course_tree as $unit => $udata)
			{
				// список второго уровня с названиями уроков
				echo '<li><span>' . $udata['title'] . '</span><ul class="unit-menu">';
				
				foreach ($udata['content'] as $lesson => $ldata)
				{
					// список третьего уровня с названиями упражнений
					echo '<li><span>' . $ldata['title'] . '</span><ul class="lesson-menu">';
					
					foreach ($ldata['content'] as $exer => $edata)
					{
						echo '<li class="exercise-handle" id="' . $edata['token'] . '" onclick="FetchExercise(\'' . $edata['token'] . '\');">' . $edata['title'] . '</li>';
					}
					
					echo '</ul></li>';
				}
				
				echo '</ul></li>';
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
