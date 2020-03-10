<?php

if (!(empty($_POST))) {
	if (isset($_POST['uzytkownik'])) {
		$userek = $_POST['uzytkownik'];
		if (isset($_POST['haslo'])) {
			$haslo = $_POST['haslo'];
			include('baza.php');
			$sql_conn = mysql_connect($host,$db_user,$db_password);
			mysql_select_db($database) or die ("Coś jest nie tak z bazą");
			mysql_query('SET CHARSET utf8');
			mysql_query("SET NAMES 'utf8'");
			$zapytanie = mysql_query("SELECT * FROM `_konta` WHERE `login` = '" . $userek . "' ");
			$wynik = mysql_fetch_array($zapytanie);
			mysql_close($sql_conn);
			if ($wynik) {
				if ((md5($haslo)) == ($wynik['haslo'])) {
					session_start();
					$_SESSION['auth'] = true;
					header("Location: przeglad.php");
					exit();
				} else {
						echo 'błąd hasła';
					header("Location: login.php"); // błąd hasła
					exit();
				}
			} else {
				header("Location: login.php"); // błąd użytkownika
				exit();
			}
		}
	}
} else {
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>SEOAnalityk - wersja - logowanie</title>
  <meta name="robots" content="none" />
	<meta name="googlebot" content="none" />
	<meta name="author" content="Marcin Kowol" />
	<meta http-equiv="creation-date" content="Thu, 18 Aug 2011 23:44:43 +0100" />
	<link rel="stylesheet" media="screen" type="text/css" href="leszcz.css" />
</head>
	
<body>

<div class="pudlo5">
<span class="seoanalityk">SEOAnalityk</span>
<form enctype="application/x-www-form-urlencoded" action="logowanie.php" accept-charset="utf-8" method="POST">
		<div class="row">
			<span class="label1">użytkownik: </span>
			<span class="formw1"><input type="text" name="uzytkownik" /></span>
		</div>
		<div class="row">
			<span class="label1">hasło: </span>
			<span class="formw1"><input type="password" name="haslo" /></span>
		</div>
		<div class="send">
			<input type="hidden" name="kontrolka" value="wypelniony" />
			<span class="sub1"><input type="submit" value="zaloguj się" /></span>
		</div>
</form>
</div>
</body>
</html>';
}

?>