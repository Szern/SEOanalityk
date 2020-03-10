<?php

if (!(empty($_POST))) {
	if (isset($_POST['kontrolka'])) {
		$test = $_POST['kontrolka'];
	} else {
		$test = '';
	}
} else {
		$test = '';
}

include('baza.php');

if (($database == 'NAZWA BAZY') && (!($test == 'wypelniony'))) {

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>SEOAnalityk - instalator</title>
  <meta name="robots" content="none" />
	<meta name="googlebot" content="none" />
	<meta name="author" content="Marcin Kowol" />
	<meta http-equiv="creation-date" content="Thu, 18 Aug 2011 23:44:43 +0100" />
	<link rel="stylesheet" media="screen" type="text/css" href="leszcz.css" />
</head>
	
<body>
<div class="pudlo4">
	<p style="color:red;font-weight:bold;text-align:center;">Nie podales danych bazy w pliku baza.php</p><p style="font-weight:bold;text-align:center;font-style:italic;">Plik baza.php musi umożliwiać zapis.<br /><br /></p>
	<form enctype="application/x-www-form-urlencoded" action="index.php" accept-charset="utf-8" method="POST">
		<div class="row1">
			<span class="label">Nazwa bazy danych: </span>
			<span class="formw"><input type="text" name="baza" /></span>
		</div>
		<div class="row1">
			<span class="label">Nazwa użytkownika bazy danych: </span>
			<span class="formw"><input type="text" name="uzytkownik" /></span>
		</div>
		<div class="row1">
			<span class="label">Hasło użytkownika bazy danych: </span>
			<span class="formw"><input type="text" name="haslo" /></span>
		</div>
		<div class="row1">
			<span class="label">Serwer: </span>
			<span class="formw"><input type="text" name="serwer" value="localhost"/></span>
		</div>
		<div class="row1">
			<input type="hidden" name="kontrolka" value="wypelniony" />
			<input type="submit" value="Zapisz" />
		</div>
	</form>
</div>
</body>
</html>';
} elseif (($database == 'NAZWA BAZY') && ($test == 'wypelniony')) {
	if (!(empty($_POST))) {
		if (isset($_POST['baza'])) {
			$baza = $_POST['baza'];
		}	else {
			header("Location: index.php");
			exit();
		}
		if (isset($_POST['uzytkownik'])) {
			$uzytkownik = $_POST['uzytkownik'];
		}	else {
			header("Location: index.php");
			exit();
		}
		if (isset($_POST['haslo'])) {
			$haslo = $_POST['haslo'];
		}	else {
			header("Location: index.php");
			exit();
		}
		if (isset($_POST['serwer'])) {
			$serwer = $_POST['serwer'];
		}	else {
			header("Location: index.php");
			exit();
		}
	}
	$konfiguracja = '<?php

// baza
$db_user = "' . $uzytkownik . '";
$db_password = "' . $haslo . '";
$database = "' . $baza . '";
$host = "' . $serwer . '"; // najczesciej localhost

// ustawienia
date_default_timezone_set("Europe/Warsaw"); // strefa czasowa

$dcg = "74.125.77.99"; // datacenter Google
$dcy = "74.6.239.80"; // datacenter Yahoo
$dcm = "www.bing.com"; // datacenter MSN
$dca = "data.alexa.com"; // datacenter Alexa
$dcw = "77.252.2.40"; // datacenter whois.domaintools.com

?>';
	chmod('baza.php', 0666);
	$fp = fopen('baza.php','wb');
	fwrite($fp, $konfiguracja);
	fclose($fp);
	chmod('generated', 0666);
	header("Location: index.php");
	exit();
} else {

	$sql_conn = mysql_connect($host,$db_user,$db_password);
	mysql_select_db($database) or die ("Coś jest nie tak z bazą");
	mysql_query('SET CHARSET utf8');
	mysql_query("SET NAMES 'utf8'");

	$zapytanie = "SHOW TABLES FROM `$database`";
	$wynik = mysql_query($zapytanie);
	while ($row = mysql_fetch_row($wynik)) {
		if ((!(substr($row[0], 0, 1) == '_')) && (!(substr($row[0], 0, 2) == '__'))) {
			$strony_a[] = $row[0];
			$strony[] = str_replace('_','.',$row[0]);
		} elseif (substr($row[0], 0, 2) == '__') {
			$grupy[] = $row[0];
		}
		$wszystko[] = $row[0];
	}
	$instalka = count($wszystko);
	if ($instalka == 0) {
		$sql_conn = mysql_connect($host,$db_user,$db_password);
		mysql_select_db($database) or die ("Coś jest nie tak z bazą…");
		mysql_query('SET CHARSET utf8');
		mysql_query("SET NAMES 'utf8'");
		$zapytanie = "CREATE TABLE `_konta` (
	id int(3) NOT NULL auto_increment,
	login varchar(255),
	haslo varchar(255),
	PRIMARY KEY(id)
	) DEFAULT CHARSET=utf8";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
			die('Błąd tworzenia tabeli: ' . mysql_error());
		}
		$zapytanie = "INSERT INTO `_konta` (`login`, `haslo`) VALUES ('seoanalityk', (MD5('seoanalityk')))";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
		die('Błąd zapisu do tabeli: ' . mysql_error());
		}
		
		$zapytanie = "CREATE TABLE `_przeglad_strony` (
	id int(4) NOT NULL auto_increment,
	stronka varchar(255),
	PRIMARY KEY(id)
	) DEFAULT CHARSET=utf8";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
			die('Błąd tworzenia tabeli: ' . mysql_error());
		}
		$zapytanie = "CREATE TABLE `_przeglad_parametry` (
	id int(4) NOT NULL auto_increment,
	parametr varchar(255),
	PRIMARY KEY(id)
	) DEFAULT CHARSET=utf8";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
			die('Błąd tworzenia tabeli: ' . mysql_error());
		}
		$zapytanie = "INSERT INTO `_przeglad_parametry` (`parametr`) VALUES ('Google site')";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
		die('Błąd zapisu do tabeli: ' . mysql_error());
		}
		$zapytanie = "CREATE TABLE `_zakres` (
	id int(1) NOT NULL auto_increment,
	dni int(4),
	PRIMARY KEY(id)
	) DEFAULT CHARSET=utf8";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
			die('Błąd tworzenia tabeli: ' . mysql_error());
		}
		$zapytanie = "INSERT INTO `_zakres` (`dni`) VALUES (7)";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
			die('Błąd zapisu do tabeli: ' . mysql_error());
		}
		$zapytanie = "CREATE TABLE `_wykresy` (
	id int(4) NOT NULL auto_increment,
	wykres varchar(255),
	PRIMARY KEY(id)
	) DEFAULT CHARSET=utf8";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
			die('Błąd tworzenia tabeli: ' . mysql_error());
		}
		$zapytanie = "INSERT INTO `_wykresy` (`wykres`) VALUES ('wolne')";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
		die('Błąd zapisu do tabeli: ' . mysql_error());
		}
		$zapytanie = "CREATE TABLE `_wersja` (
	id int(4) NOT NULL auto_increment,
	identyfikator varchar(255),
	PRIMARY KEY(id)
	) DEFAULT CHARSET=utf8";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
			die('Błąd tworzenia tabeli: ' . mysql_error());
		}
		$zapytanie = "INSERT INTO `_wersja` (`identyfikator`) VALUES ('1.0.0')";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
		die('Błąd zapisu do tabeli: ' . mysql_error());
		}
		
		mysql_close($sql_conn);
		header("Location: plus.php",false);
		exit();
	}
	$instalka = count($strony);
	if ($instalka == 0) {
		header("Location: plus.php");
		exit();
	} else {
		header("Location: przeglad.php");
		exit();	
	}
}

?>
