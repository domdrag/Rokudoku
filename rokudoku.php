<?php
	session_start();
	if( isset($_SESSION['pokusaj']) ){
		session_unset();
        session_destroy();
	}
?>


<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta charset="utf-8" />
        <title>Rokudoku</title>
</head>
<body>
	<h1> ROKUDOKU !</h1>
	<br>
        <form action="Rokudoku_obradi.php" method="POST">
		Unesi svoje ime:
		<input type="text" name="ime" />
		<select name="ploca">
       			<option name="ploca1" value="1">Version 1</option>
				<option name="ploca2" value="2">Version 2</option>
		 </select>
		<button type="submit">Zapocni igru!</button>
		
	</form>
    
</body>
</html>
