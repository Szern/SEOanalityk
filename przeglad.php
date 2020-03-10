<?php

$modul = 'historia';
include('baza.php');

if ($database == 'NAZWA BAZY') {
	header("Location: index.php");
	exit();
}

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
	$wszystko[] = $row[0];
}
// mysql_free_result($wynik);
$instalka = count($wszystko);
if ($instalka == 0) {
	header("Location: instalacja.php");
	exit();
}
$instalka = count($strony);
if ($instalka == 0) {
	header("Location: plus.php");
	exit();
}

$zamiennik = array('Google site'=>'site', 'Yahoo site'=>'sitey', 'MSN site'=>'sitem', 'Google backlinks'=>'gbl', 'Yahoo backlinks'=>'ybl', 'MSN backlinks'=>'mbl', 'Altavista backlinks'=>'abl', 'Alexa backlinks'=>'alexa', 'PR'=>'pr');

$tekst_parametrow = array('Google site', 'Yahoo site', 'MSN site', 'Google backlinks', 'Yahoo backlinks', 'MSN backlinks', 'Altavista backlinks', 'Alexa backlinks', 'PR');

$sql = mysql_query("SELECT * FROM `_przeglad_parametry`");
while($str[] = mysql_fetch_array($sql, MYSQL_NUM));
foreach ($str as $st) {
	if (!(empty($st))) {
		$parametry_do_wyswietlenia[] = $st[1]; // pozostałe kolumny do wyświetlenia (dane)
	}
}

foreach ($parametry_do_wyswietlenia as $pdw) {
	switch ($pdw) {
		case 'Google site':
			$parametry_do_wczytania[] = 'site';
		break;
		case 'Yahoo site':
			$parametry_do_wczytania[] = 'sitey';
		break;
		case 'MSN site':
			$parametry_do_wczytania[] = 'sitem';
		break;
		case 'Google backlinks':
			$parametry_do_wczytania[] = 'gbl';
		break;
		case 'Yahoo backlinks':
			$parametry_do_wczytania[] = 'ybl';
		break;
		case 'MSN backlinks':
			$parametry_do_wczytania[] = 'mbl';
		break;
		case 'Altavista backlinks':
			$parametry_do_wczytania[] = 'abl';
		break;
		case 'Alexa backlinks':
			$parametry_do_wczytania[] = 'alexa';
		break;
		case 'PR':
			$parametry_do_wczytania[] = 'pr';
		break;
	}
}

$sql = mysql_query("SELECT * FROM `_przeglad_strony`");
while($stronki[] = mysql_fetch_array($sql, MYSQL_NUM));
foreach ($strony as $strona) {
	foreach ($stronki as $stronka) {
		if ($strona == $stronka[1]) {
			$strony_do_wyswietlenia[] = $strona; // pierwsza kolumna do wyświetlenia
		}
	}
}

$tester = count($strony_do_wyswietlenia);
// ob_start();
if ($tester > 0) {

	include('start.php');

	// nagłówek tabeli

	$naglowek_tabeli = '<div id="t_index">
	<table cellspacing="0" class="tabela4">
	<tbody>
	';
	$naglowek_tabeli .=  '<tr>
	<td class="cztery">
	<form name="grupy">';
	if (isset($_SESSION['grupa'])) {
		$naglowek_tabeli .= 'grupa: <strong>' . $_SESSION['grupa'] . '</strong>&nbsp;&nbsp;';
	}
	$naglowek_tabeli .= '<select name="grupy" onChange="javascript:changePage()">
	<option>zmień na grupę:&nbsp;&nbsp;</option>';
	foreach ($grupy as $grupa) {
		$naglowek_tabeli .= '<option value="' . substr($grupa, 2) . '">' . substr($grupa, 2) . '&nbsp;&nbsp;</option>
';
		}
	$naglowek_tabeli .= '</select>
	</form>
	</td>
	';
	foreach ($parametry_do_wyswietlenia as $pdw) {
		$naglowek_tabeli .= '<td class="cztery"><a class="lwykresy" href="przeglad.php?parametr=' . $zamiennik[$pdw] . '">' . $pdw . '</a></td>
	';
	}
	$naglowek_tabeli .= '<td class="cztery">aktualizacja</td>
	<td></td>
	</tr>
	';

	// zamknięcie tabeli

	$koniec_tabeli = '</tbody>
	</table>
	</div>';

	// wiersze

	if (!(empty($_GET))) {
		$ten_parametr = $_GET['parametr'];
	} else {
		$ten_parametr = 'brak';
	}

	foreach ($strony_do_wyswietlenia as $sdw) {
		$wiersz = '<tr>
	<td class="abrakadabra1"><a class="lwykresy" target="_blank" href="http://' . $sdw . '">' . $sdw . '</a></td>
	';
		$sdwt = str_replace('.','_',$sdw);
		foreach ($parametry_do_wczytania as $pdw) {
			$zapytanie = "SELECT `$pdw` FROM `$sdwt` ORDER BY data DESC LIMIT 1";
			$wynik = mysql_query($zapytanie);
			if (!$wynik) {
				die('Błąd: ' . mysql_error());
			}	else {
				$row = mysql_fetch_row($wynik);
				$dane = $row[0];
				$wiersz .= '<td class="abrakadabra2">' . $dane . '</td>
	';
				if ($pdw == $ten_parametr) {
					$wartosc_parametru = $dane;
				} elseif ($ten_parametr == 'brak') {
					$wartosc_parametru = $sdw;
				}
			}
		}
		$wiersz .= '<td class="abrakadabra2">';
		$zapytanie = "SELECT `czas` FROM `$sdwt` ORDER BY data DESC LIMIT 1";
		$wynik = mysql_query($zapytanie);
		if (!$wynik) {
			die('Błąd: ' . mysql_error());
		}	else {
			$row = mysql_fetch_row($wynik);
			$dane = $row[0];
			$wiersz .= $dane . '</td>
	<td class="abrakadabra2">';
		}
		$wiersz .= '<a class="lwykresy" href="archiwum.php?domena=' . $sdw . '">wykresy</a></td>
	</tr>';
		if (isset($wiersze[$wartosc_parametru])) {
			$wiersze[$wartosc_parametru] .= $wiersz;
		} else {
			$wiersze[$wartosc_parametru] = $wiersz;
		}
	}

	if ((!(isset($_SESSION['porzadek']))) || (!($_SESSION['porzadek'] == true))) {
		ksort($wiersze);
		$_SESSION['porzadek'] = true;
	} else {
		krsort($wiersze);
		$_SESSION['porzadek'] = false;
	}
	echo $naglowek_tabeli;
	$szary = true;
	foreach ($wiersze as $wiersz) {
		if ($szary == true) {
			echo str_replace('abrakadabra1', 'jedenszary', str_replace('abrakadabra2', 'dwaszary', $wiersz));
			$szary = false;
		} else {
			echo str_replace('abrakadabra1', 'jeden', str_replace('abrakadabra2', 'dwa', $wiersz));
			$szary = true;
		}
	}

} else {
	header("Location: przeglad_strony.php");
	exit();
}
	
echo $koniec_tabeli;
// ob_end_flush();

?>