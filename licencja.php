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

	<p>SEOAnalityk jest moim autorskim skryptem. SEOAnalityk jest wolnym oprogramowaniem; możesz go rozprowadzać dalej i/lub modyfikować na warunkach Powszechnej Licencji Publicznej GNU, wydanej przez Fundację Wolnego Oprogramowania - według wersji trzeciej tej Licencji lub (według twojego wyboru) którejś z późniejszych wersji.</p>
	<p>SEOAnalityk rozpowszechniany jest z nadzieją, iż będzie on użyteczny - jednak BEZ JAKIEJKOLWIEK GWARANCJI, nawet domyślnej gwarancji PRZYDATNOŚCI HANDLOWEJ albo PRZYDATNOŚCI DO OKREŚLONYCH ZASTOSOWAŃ. W celu uzyskania bliższych informacji sięgnij do Powszechnej Licencji Publicznej GNU.</p>
	<p>Z pewnością wraz ze skryptem SEOAnalityk otrzymałeś też egzemplarz Powszechnej Licencji Publicznej GNU (GNU General Public License); jeśli nie - napisz do Free Software Foundation, Inc., 59 Temple Place, Fifth Floor, Boston, MA  02110-1301  USA</p>
	<br /><br />
	<pre><?php include('gpl.txt'); ?></pre>

<div>

</body>

</html>


