<?php
	require_once('./db_routines.php');
	
	if (isset($_POST['unit']))
		$cur_unit = $_POST['unit'];
	else
		$cur_unit = 0;
		
	if (isset($_POST['lesson']))
		$cur_lesson = $_POST['lesson'];	
	else
		$cur_lesson = 0;		

	if (isset($_POST['exercise']))
		$cur_ex = $_POST['exercise'];	
	else
		$cur_ex = 0;			
		
	if ($cur_unit and $cur_lesson) 
	{
		echo '<h4>' . GetLessonName($cur_unit, $cur_lesson) . '</h4>';
		
		$ex = GetExercises($cur_unit, $cur_lesson);
		echo '<ul style="list-style: none">';
		foreach ($ex as $key => $val)
		{
			$exercise_title = str_replace(' ', '&nbsp;', $key . '. ' . $val['title']);
			
			$sel_class = $key == $cur_ex ? ' selected' : '';
			echo '<li id="' . $val['token'] . '" class="exercise-handle lesson-menu' . $sel_class . '" style="display:inline; padding:0 0.8em 0 0;" href=# onclick="FetchExercise(\'' . $val['token'] . '\');">' . $exercise_title . '</li>';
		}
		echo '</ul>';				
		echo '<hr></hr>';
	}
	else
		echo '<h4>Choose something on the left</h4>';
?>