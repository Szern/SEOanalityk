<?php

$modul = 'edycja grupy';
include('baza.php');

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

	$grupa = $_POST['grupa'];
	$wynik = mysql_query("TRUNCATE TABLE `_przeglad_strony`");
	$wynik = mysql_query("TRUNCATE TABLE `$grupa`");
	foreach ($strony_a as $strona) {
		if ((isset($_POST[$strona])) && (($_POST[$strona]) == 'tak')) {
			$strona = str_replace("_", ".", $strona);
			$zapytanie = "INSERT INTO `_przeglad_strony` (`id`, `stronka`) values ('', '$strona')";
			$wynik = mysql_query($zapytanie);
			$zapytanie = "INSERT INTO `$grupa` (`id`, `stronka`) values ('', '$strona')";
			$wynik = mysql_query($zapytanie);
		}
	}
	echo '<p>Zapisano nową zawartość grupy <strong>' . substr($grupa, 2) . '</strong></p>';
	
} elseif ($test == 'edycja') {

	$grupa = '__' . $_POST['grupa'];

	$confirm = 'Edycja zawartości grupy '. substr($grupa, 2);
	
	$sql = mysql_query("SELECT * FROM `$grupa`");
	while($porownanie[] = mysql_fetch_array($sql, MYSQL_NUM));

	echo '<div class="pudlo2">' . $confirm . '<form enctype="application/x-www-form-urlencoded" action="przeglad_strony.php" accept-charset="utf-8" method="POST" name="formularz">
	<div class="row">
		<input type="hidden" name="kontrolka" value="wypelniony" />
		<input type="hidden" name="grupa" value="' . $grupa . '" />
		<input type="submit" value="zapisz" />
		<input type="button" value="wszystkie/wyczyść" onclick="ptaszek(this.form)">
	</div>';
	foreach ($strony as $strona) {
		echo ' <div class="row">
		<span class="formw"><input type="checkbox" name="' . $strona . '" value="tak" ';
		foreach ($porownanie as $por) {
			if ($strona == $por[1]) { echo 'checked '; }
		}
		echo '/></span>
		<span class="label">' . $strona . '</span>
	</div>';
	}
	echo'	</form>
	</div>';

} else {

	$confirm = 'Wybierz grupę do edycji: ';
	if (!(empty($grupy))) {
		echo '<div class="pudlo2">
		<form enctype="application/x-www-form-urlencoded" action="przeglad_strony.php" accept-charset="utf-8" method="POST" name="formularz">
		<div class="row">
			<input type="hidden" name="kontrolka" value="edycja" />
		</div>';
		echo ' <div class="row">
			<span class="formw">' . $confirm . '<select name="grupa">';
		foreach ($grupy as $grupa) {
			echo '<option>' . substr($grupa, 2) . '</option>';
		}
		echo '</select></span>
		</div>';
		echo '<input type="submit" value="Edytuj" />
		</form>
		</div>';
	} else {
	echo '<p>Brak grup do edycji</p>';
	}
}

echo '</body>

</html>';

mysql_close($sql_conn);

?>