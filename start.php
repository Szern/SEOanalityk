<?php
session_start();
if ((!(isset($_SESSION['auth']))) && (!($_SESSION['auth'] == true))) {
	header("Location: logowanie.php");
	exit();
}

if (!(isset($_SESSION['wersja']))) {
	$zapytanie = "SELECT `identyfikator` FROM `_wersja`";
	$wynik = mysql_query($zapytanie);
	$row = mysql_fetch_row($wynik);
	$_SESSION['wersja'] = $row[0];
	if (file_get_contents('http://aktualizacje.seoanalityk.pl/wersja.txt')) {
		$serwerek = file_get_contents('http://aktualizacje.seoanalityk.pl/wersja.txt');
		if (!($_SESSION['wersja'] == $serwerek)) {
			header("Location: aktualizacja.php");
			exit();
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>SEOAnalityk - wersja <?php echo $_SESSION['wersja'] . ' - moduł ' . $modul; ?></title>
  <meta name="robots" content="none" />
	<meta name="googlebot" content="none" />
	<meta name="author" content="Marcin Kowol" />
	<meta http-equiv="creation-date" content="Thu, 18 Aug 2011 23:44:43 +0100" />
	<link rel="stylesheet" media="screen" type="text/css" href="leszcz.css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
	<script type="text/javascript" src="jquery.droppy.js"></script>
	<script type="text/javascript" src="menu.js"></script>
	<script type="text/javascript">
var check=false;
function ptaszek(thisForm){
a=document.formularz.elements;
	if(check==false){
	for (i = 0; i < a.length; i++){
	if(a[i].type.toLowerCase()=="checkbox"){ a[i].checked=true; }
	}
	check=true;
	}else{
	for (i = 0; i < a.length; i++){
	if(a[i].type.toLowerCase()=="checkbox"){ a[i].checked=false; }
	}
	check=false;
	}
}
	</script>
	<script type="text/javascript">
function changePage()
{
   selectedValue = document.forms['grupy'].grupy.value;
   newLocation = "zmiana_grupy.php?kontrolka=zmiana&grupa="+selectedValue;
   window.location = newLocation;
}
	</script> 
</head>
	
<body>

<div id="header">
	<div class="menu">
		<img src="images/menu-left.png" alt="" class="left" />
		<ul id="nav">
			<li class="act"><a class="ccc" href="przeglad.php">przegląd</a></li>
			<li class="act"><img src="images/menu-podzial.png" alt=""/></li>
			<li class="act"><a class="ccc" href="podglad_szybki.php">sprawdzenie</a></li>
			<li class="act"><img src="images/menu-podzial.png" alt=""/></li>
			<li class="act"><a class="ccc" href="relacje.php">porównanie</a></li>
			<li class="act"><img src="images/menu-podzial.png" alt=""/></li>
			<li class="act"><a class="ccc" href="#">konfiguracja</a>
				<ul class="back">
					<li class="toppodmenu"><a class="short" href="plus.php">dodawanie stron</a></li>
					<!-- <li class="podmenu"><a class="short" href="import.php">import stron</a></li> -->
					<li class="podmenu"><a class="short" href="minus.php">usuwanie stron</a></li>
					<li class="podmenu"><a class="short" href="plus_grupy.php">dodawanie grup</a></li>
					<li class="podmenu"><a class="short" href="przeglad_strony.php">edycja grup</a></li>
					<li class="podmenu"><a class="short" href="minus_grupy.php">usuwanie grup</a></li>
					<li class="podmenu"><a class="short" href="plus_userzy.php">dodawanie użytkowników</a></li>
					<li class="podmenu"><a class="short" href="minus_userzy.php">usuwanie użytkowników</a></li>
					<li class="podmenu"><a class="short" href="zmiana_grupy.php">wyświetlana grupa</a></li>
					<li class="podmenu"><a class="short" href="przeglad_parametry.php">wyświetlane parametry</a></li>
					<li class="podmenu"><a class="short" href="dni.php">ilość wyświetlanych dni</a></li>
					<li class="podmenu"><a class="short" href="wykresy.php">wygląd wykresów</a></li>
				</ul>
			</li>
			<li class="act"><img src="images/menu-podzial.png" alt=""/></li>
			<li class="act"><a class="ccc" href="seoanalityk.php">SEOAnalityk</a>
				<ul class="back">
					<li class="toppodmenu"><a class="short" href="out.php">wylogowanie</a></li>
					<li class="podmenu"><a class="short" href="seoanalityk.php">opis skryptu</a></li>
					<li class="podmenu"><a class="short" href="licencja.php">licencja</a></li>
					<li class="podmenu"><a class="short" href="thanks.php">składniki</a></li>
					<li class="podmenu"><a class="short" href="http://seo.poznan.pl/forum7-seoanalityk.html">forum skryptu</a></li>
					<li class="podmenu"><a class="short" href="http://seoanalityk.pl/">witryna skryptu</a></li>
					<li class="podmenu"><a class="short" href="dotacje.php">dotacje</a></li>
				</ul>
			</li>
			<li class="rog"><img src="images/menu-right.png" alt="" /></li>
		</ul>
	</div>
</div>