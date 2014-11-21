<?php
	require_once('app_config.php');

	# Проверить комбинацию пользователь/пароль
	function CheckUser($name, $pwd)
	{
		$stmt = 'SELECT * FROM `users` WHERE `username`="' . $name . '";';
		
		$res = mysql_query($stmt);
		if (!$res)
			handle_db_error();
		
		if (mysql_num_rows($res))
		{
			$row = mysql_fetch_assoc($res);
			return !strcmp($row['password'], $pwd);			
		}
		else
		{
			return !(strcmp($name, suser_name) and strcmp($pwd, suser_pwd));
		}
	}	
	
	# Получить название "урока"
	function GetLessonName($unit, $lesson)
	{
		$stmt = 'SELECT `name_lesson` FROM `lessons` WHERE `id_unit`=' . $unit . ' AND `id_lesson`=' . $lesson . ';';
		
		$res = mysql_query($stmt);
		if (!$res)
			handle_db_error();
		
		$row = mysql_fetch_row($res);
		return $row[0];
	}

	# Получить текст задания
	function GetExerciseTask($internal_name)
	{
		$stmt = 'SELECT `description` FROM `exercises` WHERE `name_internal`=\'' . $internal_name . '\';';	
		$res = mysql_query($stmt);
		if (!$res)
			return mysql_error();		
			
		if (mysql_num_rows($res))
		{
			$row = mysql_fetch_row($res);
			return $row[0];
		}
		else
			return '';
	}

	# Получить ругательно-поощрительное сообщение в зависимости от успеха/неудачи
	function GetCheers($success)
	{
		$stmt = 'SELECT `cheers_text` FROM `cheers` WHERE `success`=' . $success . ' ORDER BY RAND() LIMIT 1;';	
		$res = mysql_query($stmt);
		if (!$res)
			return mysql_error();		
			
		if (mysql_num_rows($res))
		{
			$row = mysql_fetch_row($res);
			return $row[0];
		}
		else
			return '';
	}
	
	# Получить описания всех упражнений из заданного урока
	function GetExercises($unit, $lesson)
	{
		$stmt = 'SELECT * FROM `exercises` WHERE `id_unit`=' . $unit . ' AND `id_lesson`=' . $lesson . ';';		
		$res = mysql_query($stmt);
		if (!$res)
			handle_db_error($stmt);		
			
		$items = array();
			
		while ($row = mysql_fetch_assoc($res))
		{
			$idex = $row['id_exercise'];
			$items[$idex] = array('title' => $row['name_exercise'], 
				'desc' => $row['description'], 
				'token' => $row['name_internal'],
				'uid' => $idex + 100*$lesson + 10000*$unit); //уникальный идентификатор (FIXME:name_internal)
				
			# при отсутствии у упражнения собственного названия ...
			if (empty($row['name_exercise']))
				$items[$idex]['title'] = 'No name';
		}		
		
		return $items;
	}

	# Полное дерево упражнений
	function MakeCourseTree()
	{
		// вытаскиваем все разделы
		$stmt = 'SELECT `id_unit`, `name_unit` FROM `units`;';
		$res1 = mysql_query($stmt);
		if (!$res1)
			handle_db_error($stmt);	
			
		// перебор разделов
		$result	= array();
		while ($row1 = mysql_fetch_row($res1))
		{
			$unit = $row1[0];
			// вытаскиваем все уроки
			$stmt = 'SELECT `id_lesson`, `name_lesson` FROM `lessons` WHERE `id_unit`=' . $unit . ';';
			$res2 = mysql_query($stmt);
			if (!$res2)
				handle_db_error($stmt);							
				
			// перебор уроков
			$accum = array();
			while ($row2 = mysql_fetch_row($res2))			
			{
				$lesson = $row2[0];			
				$accum[$lesson] = array('title' => $row2[1], 'content' => GetExercises($unit, $lesson));
			}
			
			$result[$unit] = array('title' => $row1[1], 'content' => $accum);
		}
		
		return $result;
	}
	
	# Получить правила для заданного "урока"
	function GetRules($unit, $lesson)
	{
		$stmt = "SELECT `rule_text` FROM `rules` WHERE `rule_unit`={$unit} AND `rule_lesson`={$lesson} ORDER BY `rule_number`;";
		
		$res = mysql_query($stmt);
		if (!$res)
			return mysql_error();	
		
		$result = array();
		while($row = mysql_fetch_row($res))
		{
			array_push($result, $row[0]);
		}
		return $result;
	}
	
	function handle_db_error($stmt = '')
	{
		$url = './error_page.php?type=Ошибка при обращении к базе данных&text=' .  mysql_error();
		if (!empty($stmt))
			$url .= '&comment=' . $stmt;
		header('Location: ' . $url);
	}

	# ==================================================================
	# Установка соединения с базой
	
	mysql_connect(DBHOST, DBUSER, DBPASSWORD) 
		or handle_db_error('Ошибка подключения к базе данных');

	mysql_select_db(DBNAME) 
		or handle_db_error('Ошибка при выборе базы данных');
		
	mysql_query("SET NAMES 'utf8';"); //Задаем кодировку	
?>