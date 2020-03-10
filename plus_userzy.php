<?php

$modul = 'dodawanie użytkowników';
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

	if (isset($_GET['nazwa'])) {
		$nazwa = $_GET['nazwa'];
	} elseif (isset($_POST['nazwa'])) {
		$nazwa = $_POST['nazwa'];
	}
	if (isset($_GET['haslo'])) {
		$haslo = $_GET['haslo'];
	} elseif (isset($_POST['haslo'])) {
		$haslo = $_POST['haslo'];
	}
	$zapytanie = "INSERT INTO `_konta` (`login`, `haslo`) VALUES ('$nazwa', (MD5('$haslo')))";
	$wynik = mysql_query($zapytanie);
	if (!$wynik) {
			die('Błąd: ' . mysql_error());
	} else {
		echo 'Użytkownik <strong>'. $nazwa . '</strong> został dodany.';
	}

} else {

	echo '<div class="pudlo3">
	<form enctype="application/x-www-form-urlencoded" action="plus_userzy.php" accept-charset="utf-8" method="POST">
		<div class="row">
			<span class="label">Nazwa użytkownika: </span>
			<span class="formw"><input type="text" name="nazwa" /></span>
			<span class="label">Hasło: </span>
			<span class="formw"><input type="text" name="haslo" /></span>
			<input type="hidden" name="kontrolka" value="wypelniony" />
			<input type="submit" value="Dodaj" />
		</div>
	</form>
</div>';

}

mysql_close($sql_conn);
echo '</body>

</html>';

?>