<?php

$modul = 'zmiana zakresu podglądu';
include('baza.php');

$sql_conn = mysql_connect($host,$db_user,$db_password);
mysql_select_db($database) or die ("Coś jest nie tak z bazą…");
mysql_query('SET CHARSET utf8');
mysql_query("SET NAMES 'utf8'");

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
	$buforek = (int)$buforek;
	$zapytanie = "UPDATE `_zakres` SET `dni` = '$buforek' WHERE `id` = '1'";
	$wynik = mysql_query($zapytanie);
	if (!$wynik) {
		die('Błąd zapisu do tabeli: ' . mysql_error());
	}	else {
		echo 'Zakres prezentowanych danych został zmieniony na <strong>'. $buforek . '</strong> dni.';
	}

} else {

	echo '<div class="pudlo3">
	<form enctype="application/x-www-form-urlencoded" action="dni.php" accept-charset="utf-8" method="POST">
		<div class="row">
			<span class="label">Liczba dni: </span>
			<span class="formw"><input type="text" name="buforek"  value="7" /></span>
			<input type="hidden" name="kontrolka" value="wypelniony" />
			<input type="submit" value="Zmień" />
		</div>
	</form>
</div>';

}

echo '</body>

</html>';

mysql_close($sql_conn);

?>