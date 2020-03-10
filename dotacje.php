<?php

$modul = 'licencja';
include('baza.php');

$sql_conn = mysql_connect($host,$db_user,$db_password);
mysql_select_db($database) or die ("Coś jest nie tak z bazą");
mysql_query('SET CHARSET utf8');
mysql_query("SET NAMES 'utf8'");

include('start.php');

mysql_close($sql_conn);

?>

<div class='opisy'>
	<p>Na wstępie zaznaczam, że SEOAnalityk jest całkowicie darmowy i można dowolnie z niego korzystać bez żadnych opłat. Informacje poniżej zamieszczam na wyraźne żądania użytkowników.</p>
	<p>Jeśli z jakiegoś powodu (albo bez powodu) chcielibyście nagrodzić robotę autora skryptu, możecie to zrobić w następujący sposób:</p>
	<ul>
		<li class="opis"><a class="opis" href="http://bitcoin.org/">bitcoin:</a> 18VpBUjeTWcScUGzwkwTj2PivkV65wCocW</li>
		<li class="opis"><a class="opis" href="http://www.paypal.pl/pl">paypal:</a> marcin@kowol.pl</li>
		<li class="opis">przelew PLN: Marcin Kowol Citi handlowy 85 1030 0019 0109 8518 0372 7857</a></li>
	</ul>
	<p>Gwoli wyjaśnienia, dotacje skierowane na powyższe konta nie mają bezpośredniego wpływu na tempo ani kierunek rozwoju skryptu. Przeznaczone zostaną raczej na piwo i inne drobne przyjemności.</p>
<div>

</body>

</html>