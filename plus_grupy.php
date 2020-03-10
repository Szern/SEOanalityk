<?php

$modul = 'dodawanie grup';
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
	}
} elseif (!(empty($_POST))) {
	if (isset($_POST['kontrolka'])) {
		$test = $_POST['kontrolka'];
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
	$buf = '__' . $buforek;
	$zapytanie = "CREATE TABLE `$buf` (
id int(8) NOT NULL auto_increment,
stronka varchar(255),
PRIMARY KEY(id)
) DEFAULT CHARSET=utf8";
	$wynik = mysql_query($zapytanie);
	if (!$wynik) {
			die('Błąd: ' . mysql_error());
	} else {
		echo 'Grupa <strong>'. $buforek . '</strong> została dodana.';
	}
	mysql_close($sql_conn);

} else {

	echo '<div class="pudlo3">
	<form enctype="application/x-www-form-urlencoded" action="plus_grupy.php" accept-charset="utf-8" method="POST">
		<div class="row">
			<span class="label">Nazwa nowej grupy (tylko litery i cyfry): </span>
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