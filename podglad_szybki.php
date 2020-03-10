<?php

$modul = 'szybki podgląd';
include('baza.php');

$sql_conn = mysql_connect($host,$db_user,$db_password);
mysql_select_db($database) or die ("Coś jest nie tak z bazą");
mysql_query('SET CHARSET utf8');
mysql_query("SET NAMES 'utf8'");

include('start.php');

if (!(empty($_GET))) {
	$buf = $_GET['stronka'];
} elseif (!(empty($_POST))) {
	$buf = $_POST['stronka'];
} else {
	$buf = 'domena.pl';
}
$bb = substr($buf,0,7);
if ($bb == 'http://') {
	$buf = substr($buf,7);
}

?>

	<div class="pudlo1">
		<form enctype="application/x-www-form-urlencoded" action="podglad_szybki.php" method="POST">
			<div class="row">
				<span class="label">stronka: </span><span
	class="formw"><input type="text" name="stronka" size="30" value="<?php echo $buf; ?>"/>
				<input type="submit" value="sprawdź" /></span>
			</div>
		</form>
	</div>

	<?php

if (!($buf == 'domena.pl')) {

	include('funkcje.php');
	include('spiderki.php');

	echo '<table cellspacing="0" class="tabela2" summary="">
	<tbody>
		<tr>
			<td rowspan="3"><img src="images/google.png" alt="" /></td><td class="prawa">site:&nbsp;</td><td class="lewa">' . $siteg . '</td>
		</tr>
		<tr>
			<td class="prawa">backlinks:&nbsp;</td><td class="lewa">' . $gbl . '</td>
		</tr>
		<tr>
			<td class="prawa">PR:&nbsp;</td><td class="lewa">' . $pr . '</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td rowspan="4"><img src="images/alexa.png" alt="" /></td><td class="prawa">Popularity:&nbsp;</td><td class="lewa">' . $alexa . '</td>
		</tr>
		<tr>
			<td class="prawa">Reach Rank:&nbsp;</td><td class="lewa">' . $alexar . '</td>
		</tr>
		<tr>
			<td class="prawa">Rank Delta:&nbsp;</td><td class="lewa">' . $alexad . '</td>
		</tr>
		<tr>
			<td class="prawa">Links:&nbsp;</td><td class="lewa">' . $alexal . '</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td rowspan="2"><img src="images/yahoo.png" alt="" /></td><td class="prawa">site:&nbsp;</td><td class="lewa">' . $sitey . '</td>
		</tr>
		<tr>
			<td class="prawa">backlinks:&nbsp;</td><td class="lewa">' . $ybl . '</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td rowspan="2"><img src="images/msn.png" alt="" /></td><td class="prawa">site:&nbsp;</td><td class="lewa">' . $sitem . '</td>
		</tr>
		<tr>
			<td class="prawa">backlinks:&nbsp;</td><td class="lewa">' . $mbl . '</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><img src="images/altavista.png" alt="" /></td><td class="prawa">backlinks:&nbsp;</td><td class="lewa">' . $abl . '</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><img src="images/atw.png" alt="" /></td><td class="prawa">backlinks:&nbsp;</td><td class="lewa">' . $wbl . '</td>
		</tr>
	</tbody>
	</table>';
	
}

?>

</body>

</html>
