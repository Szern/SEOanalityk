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
	<p>Przy pisaniu skryptu SEOAnalityk wykorzystałem:</p>
	<ul>
		<li class="opis">funkcje do sprawdzania PR z bloga <a class="opis" href="http://www.lampdeveloper.co.uk/php/get-google-page-rank-using-php.html">www.lampdeveloper.co.uk</a></li>
		<li class="opis">bibliotekę do rysowania wykresów ze strony <a class="opis" href="http://naku.dohcrew.com/libchart/pages/introduction/">naku.dohcrew.com</a> na licencji GNU General Public License (GPL)</li>
		<li class="opis">skrypt/plugin do jquery <a class="opis" href="http://plugins.jquery.com/project/droppy">Droppy 0.1.2</a> na licencji <a class="opis" href="http://www.opensource.org/licenses/mit-license.php">Open Source Initiative OSI - The MIT License (MIT)</a></li>
		<li class="opis">pomysł na menu (i fragmenty kodu) z bloga Bartłomieja Frydrych o adresie <a class="opis" href="http://www.frycu.com/blog/2009/02/menu-rozwijane-w-jquery/">www.frycu.com</a></li>
		<li class="opis">bibliotekę jquery.min.js (jQuery JavaScript Library v1.5.1) pobieraną z ajax.googleapis.com</li>
	</ul>
	<p>Bardzo dziękuję ich twórcom, za umożliwienie wykorzystania swoich pomysłów i efektów swojej pracy.</p>
<div>

</body>

</html>