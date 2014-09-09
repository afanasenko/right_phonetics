<?php 
	require('./header.php'); 
	require_once('./db_routines.php');
	session_start();
	
	$cur_user = '';
	$login_error = 0;
	
	if (isset($_SESSION['username']))
		header('Location: ./course.php'); 
			
	# если форму засабмитили
	if ($_POST['login'])
	{
		$cur_user = $_POST['username'];
		
		if (!CheckUser($cur_user, $_POST['password']))
			$login_error = 1;		
		else
		{
			$_SESSION['username'] = $cur_user;
			header('Location: ./course.php'); 
		}
	}
?>

<body id="loginform">
	
	<div id="container-login">		
		<div id="content-login">

			<!-- js disabled -->
			<noscript>
				<div class="ui-widget">
					<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
						<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
						<strong>Alert:</strong> Enable JavaScript in Your browser to get full functionality.</p>
					</div>
				</div>
			</noscript>			
			<script>
				$(function() {
					$( "#input_login" ).button({
						icons: {
							primary: "ui-icon-gear",
						}					
					});
						
					$( "#input_username" ).focus();
				});
			</script>

			<!-- Title -->
			<div class="ui-widget" style="text-align:center; margin-top:2em;">
				<img src="./img/logo.jpg" width="20%">
				<h3>Wish to improve your pronounciation?</h3>				
<?php
				echo('<h3>Welcome to <span style="font-weight:bold; color:#4297D7">' . APP_NAME . '</span>!</h3>');
?>
			</div>
			
			<!-- Login form -->
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" >
				<fieldset class="ui-widget" style="padding-top: 1em;">
					<legend>Log in</legend>
					<div class="field">
						<label for="username">Username:</label>
						<input type="text" name="username" id="input_username" value="<?php echo $cur_user ?>">				
					</div>
					<div class="field">
						<label for="password">Password:</label>
						<input type="password" name="password" id="input_password">				
					</div>				
				</fieldset>
				
				<fieldset class="ui-widget button_pane">
					<div style="text-align:right;">
						<input type="submit" name="login" id="input_login" value="Go on">				
					</div>				
				</fieldset>			
			</form>
			
<?php
			if ($login_error)
			{
				echo '<!-- Error box -->';
				echo '<div class="ui-widget" style="margin-top:1em;">';
					echo '<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">';
						echo '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';
						
						if ($login_error)
						{
							$msg = 'You should enter valid username and password to continue';
							echo '<p>' . $msg . '</p>';						
						}
						
					echo '</div>';
				echo '</div>';
			}
?>			
		</div><!-- .content-->
	</div><!-- .container-->
</body>

<?php
	require('./footer.php'); 	
?>
