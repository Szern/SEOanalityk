<?php

$modul = 'o skrypcie';
include('baza.php');

$sql_conn = mysql_connect($host,$db_user,$db_password);
mysql_select_db($database) or die ("Co¶ jest nie tak z baz±");
mysql_query('SET CHARSET utf8');
mysql_query("SET NAMES 'utf8'");

include('start.php');

mysql_close($sql_conn);

?>

<div class='opisy'>

	<p>SEOAnalityk jest moim autorskim skryptem. Jest całkowicie darmowy: <a class="opis" href="licencja.php">licencja</a>.</p>
		<p>Funkcje, jakie obecnie spełnia SEOAnalityk to:</p>
	<ul>
		<li class="opis">codzienne sprawdzanie parametrów stron www i zapisywanie ich w bazie,</li>
		<li class="opis">przeglądanie archiwalnych parametrów stron www (zapisanych w bazie) w formie tabel i wykresów,</li>
		<li class="opis">porównanie archiwalnych parametrów kilku (obecnie od dwu do sześciu) stron www w formie tabel i wykresów,</li>
		<li class="opis">doraźne sprawdzanie parametrów strony www.</li>
	<ul>
	<p>Parametry stron sprawdzane obecnie przez skrypt to:</p>
	<ul>
		<li class="opis">Google site,</li>
		<li class="opis">Google backlinks,</li>
		<li class="opis">Google PageRank,</li>
		<li class="opis">MSN site,</li>
		<li class="opis">MSN backlinks,</li>
		<li class="opis">Yahoo site,</li>
		<li class="opis">Yahoo backlinks,</li>
		<li class="opis">Alexa Popularity,</li>
		<li class="opis">Alexa Reach Rank,</li>
		<li class="opis">Alexa Rank Delta,</li>
		<li class="opis">Alexa backlinks,</li>
		<li class="opis">Altavista backlinks,</li>
		<li class="opis">AllTheWeb backlinks.</li>
	</ul>
	<p>Skrypt jest we wczesnej fazie rozwojowej. Wszelkie uwagi, a także pomoc w jego rozwoju są mile widziane na <a class="opis" href="http://seo.poznan.pl/topic4-seoanalityk.html" title="SEO Analityk">forum SEOAnalityk</a>.</p>

</div>

</body>

</html>


