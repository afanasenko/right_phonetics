<?php
	require_once('../db_routines.php');
	
	if (isset($_POST['internal_name']))		
	{
		$tok = $_POST['internal_name'];
		$stmt = 'SELECT * FROM `exercises` WHERE `name_internal`=\'' . $tok . '\';';

		$res = mysql_query($stmt);
		if (!$res)
		{
			echo mysql_error();
			exit();
		}
		
		$row = mysql_fetch_assoc($res);
		
		if ($row)
		{
			$cur_unit = $row['id_unit'];
			$cur_lesson = $row['id_lesson'];
		}
		else
			echo 'invalid token ' . $tok;
	}
		
	if ($cur_unit and $cur_lesson) 
	{
		$rules = GetRules($cur_unit, $cur_lesson);
		echo '<ol>';
		foreach ($rules as $val)
		{
			// выделяем транскрипции, заключенные между косых черт 
			$modval = preg_replace('/\/(.)\//', '<span style="font-weight:800; color:#f00">$1</span>', $val);		
		
			echo '<li>' . $val . '</li>';
		}
		echo '</ol>';				
	}
?>