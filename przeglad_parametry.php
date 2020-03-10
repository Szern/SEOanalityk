<?php

$modul = 'kofiguracja parametrów w przeglądzie';
include('baza.php');

$sql_conn = mysql_connect($host,$db_user,$db_password);
mysql_select_db($database) or die ("Coś jest nie tak z bazą");
mysql_query('SET CHARSET utf8');
mysql_query("SET NAMES 'utf8'");

$parametry = array('Google site', 'Yahoo site', 'MSN site', 'Google backlinks', 'Yahoo backlinks', 'MSN backlinks', 'Altavista backlinks', 'Alexa backlinks', 'PR');


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

$confirm = '';

if ($test == 'wypelniony') {

	$wynik = mysql_query("TRUNCATE TABLE `_przeglad_parametry`");
	foreach ($parametry as $parametr) {
		if ((isset($_POST[str_replace(' ','_',$parametr)])) && (($_POST[str_replace(' ','_',$parametr)]) == 'tak')) {
			$zapytanie = "INSERT INTO `_przeglad_parametry` (`id`, `parametr`) values ('', '$parametr')";
			$wynik = mysql_query($zapytanie);
		}
	}
	$confirm = '<p><strong>Zapisano zmiany</strong></p>';
	
}

$sql = mysql_query("SELECT * FROM `_przeglad_parametry`");
while($porownanie[] = mysql_fetch_array($sql, MYSQL_NUM));

echo '<div class="pudlo2">' . $confirm . '
<form enctype="application/x-www-form-urlencoded" action="przeglad_parametry.php" accept-charset="utf-8" method="POST" name="formularz">
<div class="row">
	<input type="hidden" name="kontrolka" value="wypelniony" />
	<input type="submit" value="zapisz" />
	<input type="button" value="wszystkie" onclick="ptaszek(this.form)">
</div>';
foreach ($parametry as $parametr) {
	echo ' <div class="row">
	<span class="formw"><input type="checkbox" name="' . $parametr . '" value="tak" ';
	foreach ($porownanie as $por) {
		if ($parametr == $por[1]) { echo 'checked '; }
	}
	echo '/></span>
	<span class="label">' . $parametr . '</span>
</div>';
}
echo'	</form>
</div>';

echo '</body>

</html>';

mysql_close($sql_conn);

?>