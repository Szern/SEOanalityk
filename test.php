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

	$baza = file('http://aktualizacje.seoanalityk.pl/' . $sciezka . '/s.txt', FILE_IGNORE_NEW_LINES);
	if (!(empty($baza))) {
		foreach ($baza as $zapytanie) {
			eval($zapytanie);
		}
	}

?>