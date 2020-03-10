<?php

include('baza.php');
include('funkcje.php');

// połączenie z bazą

$sql_conn = mysql_connect($host,$db_user,$db_password);
mysql_select_db($database) or die ("Coś jest nie tak z bazą");
mysql_query('SET CHARSET utf8');
mysql_query("SET NAMES 'utf8'");

// tabela ze sprawdzanymi stronami

$zapytanie = "SHOW TABLES FROM `$database`";
$wynik = mysql_query($zapytanie);
while ($row = mysql_fetch_row($wynik)) {
	if ((!(substr($row[0], 0, 1) == '_')) && (!(substr($row[0], 0, 2) == '__'))) {
		$stronki[] = $row[0];
	}
}

$teraz = date("Y-m-d");

foreach ($stronki as $buforek) {

	$zapytanie = "SELECT MAX(id) FROM `$buforek`";
	$wynik = mysql_query($zapytanie);
	$row = mysql_fetch_row($wynik);
	$maxident = $row[0];
	$zapytanie = "SELECT `data` FROM `$buforek` WHERE id=$maxident";
	$wynik = mysql_query($zapytanie);
	$row = mysql_fetch_row($wynik);
	$ostatnia = $row[0];
	if (!($ostatnia == $teraz)) {
		$buf = str_replace('_','.',$buforek);
		include('spiderki.php');

	// dodanie aktualnych danych do bazy
		$zapytanie = "INSERT INTO `$buforek` (`id`, `data`, `czas`, `site`, `sitey`, `sitem`, `pr`, `gbl`, `ybl`, `mbl`, `abl`, `alexa`, `alexar`, `alexad`, `alexabl`) values ('', '$teraz', NOW(), '$siteg', '$sitey', '$sitem', '$pr', '$gbl', '$ybl', '$mbl', '$abl', '$alexa', '$alexar', '$alexad', '$alexal')";
		$wynik = mysql_query($zapytanie);
	}
//	sleep(rand(15,25));
}

mysql_close($sql_conn);

// codzienne czyszczenie foderu z wykresów

$sciezka = './generated/';
$katalog = opendir($sciezka);
while ( $file = readdir($katalog) )
{
  if ( ($file != '.') && ($file != '..') )
  unlink($sciezka.$file);
}
closedir($katalog);

?>
