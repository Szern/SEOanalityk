<?php

$modul = 'dodawanie i usuwanie stron';
include('baza.php');

$sql_conn = mysql_connect($host,$db_user,$db_password);
mysql_select_db($database) or die ("Coś jest nie tak z bazą");
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

	foreach ($strony as $strona) {
		if ((isset($_POST[str_replace(".", "_", $strona)])) && (($_POST[str_replace(".", "_", $strona)]) == 'tak')) {
			$str = str_replace('.','_',$strona);
			$zapytanie = "DROP TABLE `$str`";
			$wynik = mysql_query($zapytanie);
			if (!$wynik) {
				echo 'Nie można usunąć: ' . mysql_error() . '<br />';
			} else {
				echo 'Usunięto bezpowrotnie stronę ' . $strona . ' i jej dane archiwalne<br />';
			}
		}
	}

} else {

	echo '<div class="pudlo3">
		<p><strong style="color:red;">UWAGA!</strong> Po naciśnięciu przycisku "usuń" <strong style="color:red;">NIEODWRACALNIE</strong> usuniesz z bazy zgromadzone dane archiwalne dla zaznaczonych stron. Nie będzie powtórnego ostrzeżenia.</p>
		<form enctype="application/x-www-form-urlencoded" action="minus.php" accept-charset="utf-8" method="POST">';
	foreach ($strony as $strona) {
		echo '    <div class="row">
				<span class="formw"><input type="checkbox" name="' . $strona . '" value="tak" /></span>
				<span class="label">' . $strona . '</span>
			</div>';
	}
	echo '		<div class="row">
			<input type="hidden" name="kontrolka" value="wypelniony" />
			<input type="submit" value="usuń" />
			</div>
		</form>
	</div>';

}

echo '</body>

</html>';

mysql_close($sql_conn);

?>
