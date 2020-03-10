<?php

	include('baza.php');
	$sql_conn = mysql_connect($host,$db_user,$db_password);
	mysql_select_db($database) or die ("Coś jest nie tak z bazą");
	mysql_query('SET CHARSET utf8');
	mysql_query("SET NAMES 'utf8'");
	$zapytanie = "SELECT `identyfikator` FROM `_wersja`";
	$wynik = mysql_query($zapytanie);
	$row = mysql_fetch_row($wynik);
	$stara_wersja = $row[0];
	$sciezka = str_replace('.','_',str_replace(' ','_',$stara_wersja));
	$nowa_wersja = file_get_contents('http://aktualizacje.seoanalityk.pl/wersja.txt');

// pytanie o aktualizację

if (!(empty($_POST))) {
	if (isset($_POST['kontrolka'])) {
		$test = $_POST['kontrolka'];
	} else {
	$test = 'nic';
	}
} else {
	$test = 'nic';
}

if ($test == 'aktualizuj') {

// wczytanie plików z serwera
	
	$nowe = file('http://aktualizacje.seoanalityk.pl/' . $sciezka . '/n.txt', FILE_IGNORE_NEW_LINES);
	if (!(empty($nowe))) {
		foreach ($nowe as $plik) {
			copy('http://aktualizacje.seoanalityk.pl/' . $sciezka . '/' . $plik,$plik);
		}
	}
	$zmieniane = file('http://aktualizacje.seoanalityk.pl/' . $sciezka . '/z.txt', FILE_IGNORE_NEW_LINES);
	if (!(empty($zmieniane))) {
		foreach ($zmieniane as $plik) {
			if (file_exists($plik)) {
				$bak = $plik . '.bak';
				rename($plik,$bak);
			}
			copy('http://aktualizacje.seoanalityk.pl/' . $sciezka . '/' . $plik,$plik);
		}
	}
	$usuwane = file('http://aktualizacje.seoanalityk.pl/' . $sciezka . '/u.txt', FILE_IGNORE_NEW_LINES);
	if (!(empty($usuwane))) {
		foreach ($usuwane as $plik) {
			chmod($plik, 0666);
			if (!(unlink($plik))) {
				echo '<p>Nie udało się usunąć pliku. Możesz sprawdzić czy istnieje i usunąć ręcznie następujący plik: ' . $plik . '<p>';
			}
		}
	}

// wczytanie z serwera zapytań SQL i uaktualnienie bazy
	
	$baza = file('http://aktualizacje.seoanalityk.pl/' . $sciezka . '/s.txt', FILE_IGNORE_NEW_LINES);
	if (!(empty($baza))) {
		foreach ($baza as $zapytanie) {
			eval($zapytanie); // zrobic obsługę błedów
		}
	}

	session_start();
	$_SESSION['wersja'] = $nowa_wersja;
	header("Location: przeglad.php");
	exit();	

} elseif ($test == 'nieaktualizuj') {

	header("Location: przeglad.php");
	exit();	

} else {

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>SEOAnalityk - aktualizacja</title>
  <meta name="robots" content="none" />
	<meta name="googlebot" content="none" />
	<meta name="author" content="Marcin Kowol" />
	<meta http-equiv="creation-date" content="Thu, 18 Aug 2011 23:44:43 +0100" />
	<link rel="stylesheet" media="screen" type="text/css" href="leszcz.css" />
</head>
	
<body>
<p>Masz zainstalowaną wersję: ' . $stara_wersja . '<br />Najnowsza wersja dostępna na serwerze to: ' . $nowa_wersja . '<br />Jeśli zrezygnujesz w tej chwili z aktualizacji zostaniesz ponownie o nią zapytany przy następnym logowaniu.</p><p>Najważniesze zmiany wprowadzone w nowej wersji skryptu:' . file_get_contents('http://aktualizacje.seoanalityk.pl/' . $sciezka . '/zmiany.txt') . '<p>Czy zaktualizować teraz Twój skrypt SEOAnalityk?</p>
<div>
	<form enctype="application/x-www-form-urlencoded" action="aktualizacja.php" accept-charset="utf-8" method="POST">
		<div>
			<input type="hidden" name="kontrolka" value="aktualizuj" />
			<input type="submit" value="Aktualizuj" />
		</div>
	</form>
</div>
<div>
	<form enctype="application/x-www-form-urlencoded" action="aktualizacja.php" accept-charset="utf-8" method="POST">
		<div>
			<input type="hidden" name="kontrolka" value="nieaktualizuj" />
			<input type="submit" value="Nie aktualizuj" />
		</div>
	</form>
</div>
</body>
</html>';

}

?>