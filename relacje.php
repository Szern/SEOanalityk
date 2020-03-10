<?php

$modul = 'porównania';
include('baza.php');

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
	}
}

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

$zapytanie = "SELECT `dni` FROM `_zakres`";
$wynik = mysql_query($zapytanie);
$row = mysql_fetch_row($wynik);
$dni = $row[0];

$parametry = array('Google site', 'Yahoo site', 'MSN site', 'Google backlinks', 'Yahoo backlinks', 'MSN backlinks', 'Altavista backlinks', 'Alexa backlinks', 'PR');

if ($test == 'wypelniony') {

	function arrayReverse(&$arr)
	{
			$c = count($arr);
			for($i=$c-1;$i>=0;$i--) {
				$arr[$c+$i] = $arr[$i];
			}
			$arr = array_slice($arr,$c,$c*2);
	}

	function multiblokowisko($cotojest, $zmienne, $opisy, $dni, $d) {

		$ilosc = count($d);
		$iloscz = count($zmienne);
		$teraz = date("Y-m-d");

		$dd = date('Y-m-d', strtotime("now"));
		$zapytanie = "SELECT `" . $zmienne[1] . "` FROM `$d[0]` WHERE data='$dd'";
		$wynik = mysql_query($zapytanie);
		$row = mysql_fetch_row($wynik);
// jeśli bład - zmniejszyć datę o jeden dzień (pętla aż zadziała)
		if (!(empty($row))) {
			$daty[] = date('Y-m-d', strtotime("now"));
		}
		for($i = 1; $i < $dni; $i++) {
			$daty[] = date('Y-m-d', strtotime("-" . $i . " day"));
		}
		arrayReverse($daty);
		
		$t[0] = '<td class="inny"><i>dzień</i></td>';
		for($i = 0; $i < $ilosc; $i++) {
			$t[] = '<td class="inny"><i>' . str_replace('_','.',$d[$i]) . '</i></td>';
		}
		foreach ($daty as $data) {
			$t[0] .= '<td class="daty">' . substr($data, 8) . '</td>';
		}
	
	$i = 1;
	foreach ($d as $strona) {
		$old[$i] = 0;
		foreach ($daty as $data) {
			$zapytanie = "SELECT `" . $zmienne[1] . "`";
			for($j = 2; $j < $iloscz; $i++) {
				$zapytanie .= " ,`" . $zmienne[$j] . "`";
			}
			$zapytanie .= " FROM `$strona` WHERE data='$data'";
			$wynik = mysql_query($zapytanie);
			$row = mysql_fetch_row($wynik);
			for ($k = 0 ; $k < $iloscz-1 ; $k++) {
				$wykres[$i][] = $row[$k];
				if ($old[$i]<$row[$k]) {
					$t[$i] .= '<td style="color:#000066;">';
				} elseif ($old[$i]>$row[$k]) {
					$t[$i] .= '<td style="color:#CC3333;">';
				} else {
					$t[$i] .= '<td>';
				}
				$t[$i] .= $row[$k] . '</td>';
				$old[$i] = $row[$k];
			}
		}
		$i++;
	}
	$szerokosc = 130+($dni*40);
	if ($szerokosc > 1050) {
		$szerokosc = 1050;
	}
	$tabelka = '<table style="width:' . $szerokosc . 'px;margin-left:auto;margin-right:auto;margin-top:20px;margin-bottom:10px;" class="tabela3">
<tbody><tr>' . $t[0];
	for($i = 1; $i <= $ilosc; $i++) {
		$tabelka .= '</tr><tr>' . $t[$i];
	}
	$tabelka .= '</tr></tbody>
</table>';
	echo $tabelka;
	
	$zapytanie = "SELECT `wykres` FROM `_wykresy`";
	$wynik = mysql_query($zapytanie);
	$row = mysql_fetch_row($wynik);
	$grafy = $row[0];
	
	if ($grafy == 'szybkie') {
	
		$obrazek = 'generated/' . $teraz . '-porownanie-' . $cotojest;
			foreach ($d as $strona) {
				$obrazek .=  '-' . str_replace('_','.',$strona);
			}
			$obrazek .=  '-dni-' . $dni . '.png';

			if (!(file_exists($obrazek))) {

				include "libchart/classes/libchart.php";
				
		//	header("Content-type: image/png");

				$chart = new LineChart(950,250);
				
				switch (count($d)) {
					case 1:
						$chart->getPlot()->getPalette()->setLineColor(array(
							new Color(255, 0, 0)
							));
					break;
					
					case 2:
						$chart->getPlot()->getPalette()->setLineColor(array(
							new Color(255, 0, 0),
							new Color(102, 204, 0)
							));
					break;
					
					case 3:
						$chart->getPlot()->getPalette()->setLineColor(array(
							new Color(255, 0, 0),
							new Color(102, 204, 0),
							new Color(51, 0, 153)
							));
					break;
					
					case 4:
						$chart->getPlot()->getPalette()->setLineColor(array(
							new Color(255, 0, 0),
							new Color(102, 204, 0),
							new Color(51, 0, 153),
							new Color(153, 0, 0)
							));
					break;

					case 5:
						$chart->getPlot()->getPalette()->setLineColor(array(
							new Color(255, 0, 0),
							new Color(102, 204, 0),
							new Color(51, 0, 153),
							new Color(153, 0, 0),
							new Color(53, 153, 0)
							));
					break;

					case 6:
						$chart->getPlot()->getPalette()->setLineColor(array(
							new Color(255, 0, 0),
							new Color(102, 204, 0),
							new Color(51, 0, 153),
							new Color(153, 0, 0),
							new Color(53, 153, 0),
							new Color(0, 0, 102)
							));
					break;
				}
				
				for($i = 1; $i <= count($d); $i++) {
					$serie[$i] = new XYDataSet();
					$licznik = 0;
					foreach ($daty as $data) {
	//					echo 'licznik=' . $licznik . ' i=' . $i . ' data' . $data . ' *** ' . $wykres[$i][$licznik] . '<br />'; 
						$serie[$i]->addPoint(new Point($data, $wykres[$i][$licznik]));
						++$licznik;
					}
					$linia[$i] = $d[$i-1];
				}

				$dataSet = new XYSeriesDataSet();
					for($i = 1; $i <= count($d); $i++) {
						$dataSet->addSerie($linia[$i], $serie[$i]);
					}
					$chart->setDataSet($dataSet);
					$chart->getPlot()->setGraphCaptionRatio(0.62);
					$chart->setTitle($cotojest);
					$chart->render($obrazek);
			}

			echo '<p><img class="wykres" src="' . $obrazek . '" alt="wykres" /></p>';
			
	} elseif ($grafy == 'wolne') {
	
		$kolor = array('FF0000', '66CC00', '330099', '990000', '359900', '000066');
	
	// dane dla flasha
		
		$dane_dla_flasha = '';
		$j = 0;
		foreach ($daty as $data) {
			$dane_dla_flasha .= $data;
			if (isset($wykres[1][$j])) {
				for($i = 1; $i <= $ilosc; $i++) {
					$dane_dla_flasha .=  ';' . $wykres[$i][$j];
				}
				$dane_dla_flasha .= '\n';
			} else {
				$dane_dla_flasha = substr($dane_dla_flasha,0,-10);
			}
			$j++;
		}
		$dane_dla_flasha = substr($dane_dla_flasha,0,-2);
		
//	echo '<br />dane: ' . $dane_dla_flasha . '*<br />'

	?>
			<!-- swf object (version 2.2) is used to detect if flash is installed and include swf in the page -->
			<script type="text/javascript" src="amcharts/flash/swfobject.js"></script>

			<!-- following scripts required for JavaScript version. The order is important! -->
			<script type="text/javascript" src="amcharts/javascript/amcharts.js"></script>
			<script type="text/javascript" src="amcharts/javascript/amfallback.js"></script>
			<script type="text/javascript" src="amcharts/javascript/raphael.js"></script>

					<!-- chart is placed in this div. if you have more than one chart on a page, give unique id for each div -->
			<div id="chartdiv-<?php echo $cotojest; ?>" style="width:600px;height:400px;background-color:#FFFFFF;margin-left:auto;margin-right:auto;"></div>

				<script type="text/javascript">
				
							var params = {
									bgcolor:"#FFFFFF"
									};

					var flashVars = {
							path: "amcharts/flash/",

					/* in most cases settings and data are loaded from files, but, as this require
					 all the files to be upladed to web server, we use inline data and settings here.*/
					 
							// settings_file: "../sampleData/column_settings.xml",
							// data_file: "../sampleData/column_data.xml"
							
	chart_data: "<?php echo $dane_dla_flasha; ?>",
	chart_settings:"<settings><thousands_separator>.</thousands_separator><hide_bullets_count>18</hide_bullets_count><data_type>csv</data_type><plot_area><margins><left>95</left><right>40</right><top>55</top><bottom>30</bottom></margins></plot_area><scroller><enabled>false</enabled></scroller><grid><x><alpha>10</alpha><approx_count>8</approx_count></x><y_left><alpha>10</alpha><?php
if ($cotojest == 'PageRank') {
	echo '<min>0</min><max>10</max><strict_min_max>true</strict_min_max>';
}
?></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><help><button><color>FCD202</color><text_color>000000</text_color><text_color_hover>FF0F00</text_color_hover></button><balloon><color>FCD202</color><text_color>000000</text_color></balloon></help><graphs><?php 
for($i = 1; $i <= $ilosc; $i++) {
	$j = $i - 1;
	echo "<graph gid='" . $j . "'><title>" . str_replace('_','.',$d[$j]) . "</title><color>" . $kolor[$j] . "</color><color_hover>FF0F00</color_hover><selected>0</selected><line_width>2</line_width></graph>";
}
	?></graphs><labels><label lid='0'><text><![CDATA[<b><?php echo $cotojest; ?></b>]]></text><y>15</y><text_size>13</text_size><align>center</align></label></labels></settings>"
				};

				// change == to != to test flash version
					if(AmCharts.recommended() == "js"){
					var amFallback = new AmCharts.AmFallback();
					// amFallback.settingsFile = flashVars.settings_file;  		// doesn't support multiple settings files or additional_chart_settins as flash does
					// amFallback.dataFile = flashVars.data_file;
					amFallback.chartSettings = flashVars.chart_settings;
					amFallback.pathToImages = "amcharts/javascript/images/";
					amFallback.chartData = flashVars.chart_data;
					amFallback.type = "line";
					amFallback.write("chartdiv-<?php echo $cotojest; ?>");
				}
				else{
									swfobject.embedSWF("amcharts/flash/amline.swf", "chartdiv-<?php echo $cotojest; ?>", "600", "400", "8.0.0", "amcharts/flash/expressInstall.swf", flashVars, params);
							}

			</script>

<?php

	}

}
	
	foreach ($strony_a as $strona) {
		if ((isset($_POST[str_replace(".", "_", $strona)])) && (($_POST[str_replace(".", "_", $strona)]) == 'tak')) {
		$d[] = $strona;
		}
	}
	
	$opisy[0] = 'dzień';
	$opisy[1] = $_POST['parametr'];
	
	$cotojest = $opisy[1];

	$zmienne[0] = 'data';
	foreach ($opisy as $opis) {
		switch ($opis) {

		case 'Google site':
		$zmienne[] = 'site';
		break;
		
		case 'Yahoo site':
		$zmienne[] = 'sitey';
		break;
		
		case 'MSN site':
		$zmienne[] = 'sitem';
		break;
		
		case 'Google backlinks':
		$zmienne[] = 'gbl';
		break;

		case 'Yahoo backlinks':
		$zmienne[] = 'ybl';
		break;
		
		case 'MSN backlinks':
		$zmienne[] = 'mbl';
		break;	

		case 'Altavista backlinks':
		$zmienne[] = 'abl';
		break;

		case 'Alexa backlinks':
		$zmienne[] = 'alexabl';
		break;

		case 'PR':
		$zmienne[] = 'pr';
		break;

		}
	}

	multiblokowisko($cotojest, $zmienne, $opisy, $dni, $d);
	
} else {

	echo '<div class="pudlo2">
	<form enctype="application/x-www-form-urlencoded" action="relacje.php" accept-charset="utf-8" method="POST" name="formularz">
	<div class="row">
		<span class="formw">
			<select name="parametr">';
	foreach ($parametry as $parametr) {
		echo '<option>' . $parametr . '</option>';
	}
		echo '	</select>
		<input type="hidden" name="kontrolka" value="wypelniony" />
		<input type="submit" value="porównaj" />
		<input type="button" value="wszystkie" onclick="ptaszek(this.form)">
	</div>';
	foreach ($strony as $strona) {
		echo ' <div class="row">
		<span class="formw"><input type="checkbox" name="' . $strona . '" value="tak" /></span>
		<span class="label">' . $strona . '</span>
	</div>';
	}
	echo'	</form>
</div>';
}

echo '</body>

</html>';

mysql_close($sql_conn);

?>
