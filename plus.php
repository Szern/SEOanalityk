<?php

$modul = 'dodawanie stron';
include('baza.php');

$sql_conn = mysql_connect($host,$db_user,$db_password);
mysql_select_db($database) or die ("Coś jest nie tak z bazą…");
mysql_query('SET CHARSET utf8');
mysql_query("SET NAMES 'utf8'");

$zapytanie = "SHOW TABLES FROM `$database`";
$wynik = mysql_query($zapytanie);
while ($row = mysql_fetch_row($wynik)) {
	if ((!(substr($row[0], 0, 1) == '_')) && (!(substr($row[0], 0, 2) == '__'))) {
		$strony[] = str_replace('_','.',$row[0]);
	}
}

include('start.php');

if (!(empty($_GET))) {
	if (isset($_GET['kontrolka'])) {
		$test = $_GET['kontrolka'];
	} else {
	$test = 'nic';
	}
} elseif (!(empty($_POST))) {
	if (isset($_POST['kontrolka'])) {
		$test = $_POST['kontrolka'];
	} else {
	$test = 'nic';
	}
} else {
	$test = 'nic';
}

if ($test == 'wypelniony') {

	if (isset($_GET['buforek'])) {
		$buforek = $_GET['buforek'];
	} elseif (isset($_POST['buforek'])) {
		$buforek = $_POST['buforek'];
	}
	$bb = substr($buforek,0,7);
	if ($bb == 'http://') {
		$buf = substr($buforek,7);
	}
	$buf = str_replace('.','_',$buforek); // na wszelki wypadek
	$zapytanie = "CREATE TABLE `$buf` (
id int(8) NOT NULL auto_increment,
data date,
czas datetime,
site int(7),
sitey int(7),
sitem int(7),
pr int(2),
gbl int(9),
ybl int(9),
mbl int(9),
abl int(9),
wbl int(9),
alexa int(10),
alexar int(10),
alexad int(10),
alexabl int(10),
wiek date,
PRIMARY KEY(id)
) DEFAULT CHARSET=utf8";
	$wynik = mysql_query($zapytanie);
	if (!$wynik) {
			die('Błąd: ' . mysql_error());
	} else {
		include('funkcje.php');
		$teraz = date("Y-m-d");
		$buf = str_replace('_','.',$buf);
		include('spiderki.php');
		$buf = str_replace('.','_',$buf);
		$zapytanie = "INSERT INTO `$buf` (`id`, `data`, `czas`, `site`, `sitey`, `sitem`, `pr`, `gbl`, `ybl`, `mbl`, `abl`, `alexa`, `alexar`, `alexad`, `alexabl`) values ('', '$teraz', NOW(), '$siteg', '$sitey', '$sitem', '$pr', '$gbl', '$ybl', '$mbl', '$abl', '$alexa', '$alexar', '$alexad', '$alexal')";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
			die('Błąd: ' . mysql_error());
		} else {
			echo 'Strona <strong>'. $buforek . '</strong> została dodana do bazy i aktualne dane zostały pobrane.';
		}
	}
	
mysql_close($sql_conn);

} else {

	echo '<div class="pudlo3">
	<form enctype="application/x-www-form-urlencoded" action="plus.php" accept-charset="utf-8" method="POST">
		<div class="row">
			<span class="label">Adres strony: </span>
			<span class="formw"><input type="text" name="buforek" /></span>
			<input type="hidden" name="kontrolka" value="wypelniony" />
			<input type="submit" value="Dodaj" />
		</div>
	</form>
</div>';

}

echo '</body>

</html>';

?>
