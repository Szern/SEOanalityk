<?php

$modul = 'usuwanie użytkowników';
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
	$zapytanie = "DELETE FROM `_konta` WHERE `login`='$nazwa'";
	$wynik = mysql_query($zapytanie);
	if (!$wynik) {
			die('Błąd: ' . mysql_error());
	} else {
		echo 'Użytkownik <strong>'. $nazwa . '</strong> został usunięty.';
	}

} else {

	$zapytanie = "SELECT * FROM `_konta`";
	$wynik = mysql_query($zapytanie);
	if (mysql_num_rows($wynik) > 1) {
		while($buforek = mysql_fetch_assoc($wynik)) {
			$userzy[] = $buforek['login'];
		}
		echo '<div class="pudlo3">
	<form enctype="application/x-www-form-urlencoded" action="minus_userzy.php" accept-charset="utf-8" method="POST">
		<div class="row">
			<span class="formw">Wybierz użytkownika do usunięcia<select name="nazwa">';
		foreach ($userzy as $user) {
			echo '<option>' . $user . '</option>';
		}
			echo '</select></span>
		</div>';
		echo '<input type="hidden" name="kontrolka" value="wypelniony" />
			<input type="submit" value="Usuń" />
		</div>
	</form>
</div>';
	} else {
		echo 'Nie można usunąć jedynego użytkownika';
	}
}

mysql_close($sql_conn);
echo '</body>

</html>';

?>