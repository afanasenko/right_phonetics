<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php require_once('app_config.php'); echo APP_NAME; ?></title>
		<link href="css/pagestyle.css" rel="stylesheet" type="text/css" />	
		<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
	</head>		

	<body>
<?php
	if (isset($_GET['type']))
	{
		echo '<h3>' . $_GET['type'] . '</h3>';
	}
	else
	{
		echo '<h3>Error</h3>';	
	}
	
	if (isset($_GET['text']))
	{
		echo '<p>' . $_GET['text'] . '</p>';	
	}
	
	if (isset($_GET['comment']))
	{
		echo '<p>' . $_GET['comment'] . '</p>';	
	}	
?>
	</body>
</html>