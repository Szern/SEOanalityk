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
	if (substr($row[0], 0, 2) == '__') {
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

	$grupa = '__' . $_POST['grupa'];
	$wynik = mysql_query("DROP TABLE `$grupa`");
	if (!$wynik) {
		echo '<p>nie można usunąć grupy <strong>' . substr($grupa, 2) . '</strong>. Błąd: ' . mysql_error() . '</p>';
	}	else {
		echo '<p>Usunięto grupę <strong>' . substr($grupa, 2) . '</strong></p>';
	}
	
} else {

	$confirm = 'Wybierz grupę do usunięcia: ';
	if (!(empty($grupy))) {
		echo '<div class="pudlo2">
		<p><strong style="color:red;">UWAGA!</strong> Po naciśnięciu przycisku "usuń" <strong style="color:red;">NIEODWRACALNIE</strong> usuniesz z bazy definicję grupy. Nie będzie powtórnego ostrzeżenia.</p>
		<p>Usunięta zostanie tylko grupa, nie zostaną usunięte strony, które należały do grupy.</p>
		<form enctype="application/x-www-form-urlencoded" action="minus_grupy.php" accept-charset="utf-8" method="POST" name="formularz">
		<div class="row">
			<input type="hidden" name="kontrolka" value="wypelniony" />
		</div>';
		echo ' <div class="row">
			<span class="formw">' . $confirm . '<select name="grupa">';
		foreach ($grupy as $grupa) {
			echo '<option>' . substr($grupa, 2) . '</option>';
		}
		echo '</select></span>
		</div>';
		echo '<input type="submit" value="Usuń" />
		</form>
		</div>';
	} else {
	echo '<p>Brak grup do usunięcia</p>';
	}
}

echo '</body>

</html>';

mysql_close($sql_conn);

?>