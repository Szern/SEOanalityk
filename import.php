<?php

$modul = 'dodawanie stron';
include('baza.php');

$sql_conn = mysql_connect($host,$db_user,$db_password);
mysql_select_db($database) or die ("Coś jest nie tak z bazą");
mysql_query('SET CHARSET utf8');
mysql_query("SET NAMES 'utf8'");

include('start.php');

	if (!(empty($_POST))) {
		if (isset($_POST['kontrolka'])) {
		$test = $_POST['kontrolka'];
	} else {
	$test = 'nic';
	}
} else {
	$test = 'nic';
}

if ($test == 'wypelniony') {

	if (isset($_POST['lista'])) {
		$lista = $_POST['lista'];
	} else {
		$lista = '';
	}
	if (isset($_POST['nazwa_grupy'])) {
		$grupa = $_POST['nazwa_grupy'];
		$buforek = '__' . $grupa;
		$zapytanie = "CREATE TABLE `$buforek` (
id int(8) NOT NULL auto_increment,
stronka varchar(255),
PRIMARY KEY(id)
) DEFAULT CHARSET=utf8";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
			die('Błąd 01: ' . mysql_error());
		}
	} else {
		$grupa = '';
	}

	$error = $_FILES['lista']['error'];
	if ($error == UPLOAD_ERR_OK) {
		$plik_tmp = $_FILES['lista']['tmp_name'];
		$plik_nazwa = $_FILES['lista']['name'];
		$plik_rozmiar = $_FILES['lista']['size'];
	} else {
		echo 'Bład 02: pobierania pliku';
	}
	if (!(empty($plik_rozmiar))) {
		if(is_uploaded_file($plik_tmp)) {
			move_uploaded_file($plik_tmp, "tmp-wczytywanie.txt");
			$strony = file('tmp-wczytywanie.txt');
			}
	} else {
		echo 'Błąd 03: pobrano pusty plik';
	}
	foreach ($strony as $strona) {
		$bb = substr($strona,0,7);
		if ($bb == 'http://') {
			$strona = substr($strona,7);
		}
		$strona = trim($strona);
		if (!(empty($grupa))) {
			$zapytanie = "INSERT INTO `$buforek` (`id`, `stronka`) values ('', '$strona')";
			$wynik = mysql_query($zapytanie);
			if (!$wynik) {
				die('Błąd 04: ' . mysql_error());
			}
		}
		$strona = str_replace('.','_', $strona);
		$zapytanie = "CREATE TABLE `$strona` (
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
 				die('Błąd 04: ' . mysql_error());
 		}
	}
	echo 'Zakończono import poprawnie.';

} else {

	echo '<div class="pudlo3">
	<form enctype="multipart/form-data" action="import.php" accept-charset="utf-8" method="POST">
		<div class="row">
			<span class="label">Nazwa grupy: </span>
			<span class="formw"><input type="text" name="nazwa_grupy" size="40" /></span>
			<span class="label">Wczytaj plik z dysku: </span>
			<span class="formw"><input type="file" name="lista" size="20" /></span>
			<input type="hidden" name="kontrolka" value="wypelniony" />
			<input type="submit" value="Wczytaj strony" />
		</div>
	</form>
</div>';

}

echo '</body>

</html>';

mysql_close($sql_conn);

?>