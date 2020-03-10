<?php

$modul = 'historia';
include('baza.php');

$sql_conn = mysql_connect($host,$db_user,$db_password);
mysql_select_db($database) or die ("Coś jest nie tak z bazą");
mysql_query('SET CHARSET utf8');
mysql_query("SET NAMES 'utf8'");

// wczytanie stron aktywnej grupy

$zapytanie = "SHOW TABLES FROM `$database`";
$wynik = mysql_query($zapytanie);
while ($row = mysql_fetch_row($wynik)) {
	if ((!(substr($row[0], 0, 1) == '_')) && (!(substr($row[0], 0, 2) == '__'))) {
		$strony[] = str_replace('_','.',$row[0]);
	}
}

// wybor domeny do wyswietlania

if (!(empty($_GET))) {
	$d = $_GET['domena'];
} elseif (!(empty($_POST))) {
	$d = $_POST['domena'];
} else {
	$d = str_replace('_','.',$strony[0]);
}

// pobranie zakresu dni do wyswietlenia

$zapytanie = "SELECT `dni` FROM `_zakres`";
$wynik = mysql_query($zapytanie);
$row = mysql_fetch_row($wynik);
$dni = $row[0];

// dopasywanie maksymalnej szerokosci ekranu

//     document.body.offsetWidth // dla IE
//    window.innerWidth // dla normalnych

if (isset($_GET['res'])) {
  $resolution = (int)$_GET['res'];
} else {
  echo "<script language='javascript'>\n";
  echo "  location.href=\"${_SERVER['SCRIPT_NAME']}?${_SERVER['QUERY_STRING']}"
            . "&res=\" + window.innerWidth;\n";
  echo "</script>\n";
  exit();
}

// var_dump($resolution);

	$szerokosc = 130+($dni*40); //przeliczenie szerokości tabeli
//	echo 'ekran: ' . $resolution . '<br />szerokosc: ' . $szerokosc . '<br />';
	if ($szerokosc > $resolution) {
		$szerokosc = $resolution;
	}
	$szerokosc_wykresu = ($dni*70); //przeliczenie szerokości wykresu
//	echo 'szerokosc wykresu: ' . $szerokosc_wykresu . '<br />';
	if ($szerokosc_wykresu > $resolution) {
		$szerokosc_wykresu = $resolution;
	}
//	echo 'szerokosc po: ' . $szerokosc . '<br />szerokosc wykresu po: ' . $szerokosc_wykresu . '<br />';

function arrayReverse(&$arr) { // odwracanie kolejności w tablicy
	$c = count($arr);
  for($i=$c-1;$i>=0;$i--) {
		$arr[$c+$i] = $arr[$i];
	}
	$arr = array_slice($arr,$c,$c*2);
}

function blokowisko($cotojest, $zmienne, $opisy, $dni, $d, $szerokosc, $szerokosc_wykresu) { // funkcja generująca wykresy

	$ilosc = count($zmienne);
	$teraz = date("Y-m-d");

// przygotowanie tablicy z datami
	
	$daty[] = date('Y-m-d', strtotime("now")); 
	for($i = 1; $i < $dni; $i++) {
		$daty[] = date('Y-m-d', strtotime("-" . $i . " day"));
	}
	arrayReverse($daty);

// wygenerowanie danych do tabeli

// pierwsza kolumna

	$t[0] = '<td class="inny"></td>';
	for($i = 1; $i < $ilosc; $i++) {
		$t[$i] = '<td class="inny"><i>' . $opisy[$i] . '</i></td>';
		$old[$i] = 0;
	}

// pozostałe kolumny
	
	foreach ($daty as $key => $data) {
		$zapytanie = "SELECT `" . $zmienne[1] . "`";
		for($i = 2; $i < $ilosc; $i++) {
			$zapytanie .= " ,`" . $zmienne[$i] . "`";
		}
		$e = str_replace('.','_',$d);
		$zapytanie .= " FROM `$e` WHERE data='$data'";
		$wynik = mysql_query($zapytanie);
		$row = mysql_fetch_row($wynik);
		if (!($row[0] == null)) {
			$t[0] .= '<td class="daty">' . substr($data, 8) . '</td>';
			for($i = 0; $i < $ilosc-1; $i++) {
				$wykres[$i+1][$data] = $row[$i];
				if ($old[$i+1]<$row[$i]) {
					$t[$i+1] .= '<td style="color:#000066;">';
				} elseif ($old[$i+1]>$row[$i]) {
					$t[$i+1] .= '<td style="color:#CC3333;">';
				} else {
					$t[$i+1] .= '<td>';
				}
				$t[$i+1] .= $row[$i] . '</td>';
				$old[$i+1] = $row[$i];
			}
		} else {
			unset($daty[$key]);
		}
	}

	$tabelka = '<table style="width:' . $szerokosc . 'px;margin-left:auto;margin-right:auto;margin-top:20px;margin-bottom:10px;" class="tabela1">
<tbody><tr>' . $t[0];
	for($i = 1; $i < $ilosc; $i++) {
		$tabelka .= '</tr><tr>' . $t[$i];
	}
	$tabelka .= '</tr></tbody>
</table>';
	echo $tabelka;

// tworzenie wykresu
	
	$zapytanie = "SELECT `wykres` FROM `_wykresy`";
	$wynik = mysql_query($zapytanie);
	$row = mysql_fetch_row($wynik);
	$grafy = $row[0];
	
	if ($grafy == 'szybkie') {

		$obrazek = 'generated/' . $teraz . '-' . $cotojest . '-' . $d . '-dni-' . $dni . '.png';

		if (!(file_exists($obrazek))) {

			include "libchart/classes/libchart.php";
			
	//	header("Content-type: image/png");

			$chart = new LineChart($szerokosc_wykresu,250);
			
			switch ($ilosc) {
				case 2:
					$chart->getPlot()->getPalette()->setLineColor(array(
						new Color(255, 0, 0)
						));
				break;
				
				case 3:
					$chart->getPlot()->getPalette()->setLineColor(array(
						new Color(255, 0, 0),
						new Color(102, 204, 0)
						));
				break;
				
				case 4:
					$chart->getPlot()->getPalette()->setLineColor(array(
						new Color(255, 0, 0),
						new Color(102, 204, 0),
						new Color(51, 0, 153)
						));
				break;
				
				case 5:
					$chart->getPlot()->getPalette()->setLineColor(array(
						new Color(255, 0, 0),
						new Color(102, 204, 0),
						new Color(51, 0, 153),
						new Color(153, 0, 0)
						));
				break;

				case 6:
					$chart->getPlot()->getPalette()->setLineColor(array(
						new Color(255, 0, 0),
						new Color(102, 204, 0),
						new Color(51, 0, 153),
						new Color(153, 0, 0),
						new Color(53, 153, 0)
						));
				break;

				case 7:
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
			
			for($i = 1; $i < $ilosc; $i++) {
				$serie[$i] = new XYDataSet();
				$licznik = 0;
				foreach ($wykres[$i] as $dane) {
					if (isset($daty[$licznik])) {
						$serie[$i]->addPoint(new Point($daty[$licznik], $dane));
					} else {
						while (!(isset($daty[$licznik]))) {
							++$licznik;
						}
						$serie[$i]->addPoint(new Point($daty[$licznik], $dane));
					}
					++$licznik;
				}
				$linia[$i] = $opisy[$i] . ' ' . $d;
			}

			$dataSet = new XYSeriesDataSet();
				for($i = 1; $i < $ilosc; $i++) {
					$dataSet->addSerie($linia[$i], $serie[$i]);
				}
				$chart->setDataSet($dataSet);
				$chart->getPlot()->setGraphCaptionRatio(0.62);
				$chart->setTitle($cotojest);
				$chart->render($obrazek);
		}

		echo '<p><img class="wykres" src="' . $obrazek . '" alt="wykres" /></p>';

	} elseif ($grafy == 'wolne') {
	
		$kolor = array('FF0000', '66CC00', '330099', '990000', '359900', '000066'); // kolory linii na wykresie
	
	// dane dla flasha
		
		$dane_dla_flasha = '';
//		$j = 0;
		foreach ($daty as $data) {
			$dane_dla_flasha .= $data;
//			if (isset($wykres[1][$j])) {
			if (isset($wykres[1][$data])) {
				for($i = 1; $i < $ilosc; $i++) {
//					$dane_dla_flasha .=  ';' . $wykres[$i][$j];
					$dane_dla_flasha .=  ';' . $wykres[$i][$data];
				}
				$dane_dla_flasha .= '\n';
			} else {
				$dane_dla_flasha = substr($dane_dla_flasha,0,-10);
			}
//			$j++;
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
			<div id="chartdiv-<?php echo $cotojest; ?>" style="width:<?php echo $szerokosc_wykresu; ?>px;height:400px;background-color:#FFFFFF;margin-left:auto;margin-right:auto;"></div>

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
for($i = 1; $i < $ilosc; $i++) {
	$j = $i - 1;
	echo "<graph gid='" . $j . "'><title>" . $opisy[$i] . ' ' . $d . "</title><color>" . $kolor[$j] . "</color><color_hover>FF0F00</color_hover><selected>0</selected><line_width>2</line_width></graph>";
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

include('start.php');

echo '<h1 style="text-align:center;">' . $d . '</h1>
<h2 style="text-align:center;"> ostatnie ' . $dni . ' dni</h2>';

$cotojest = 'site';

$zmienne[0] = 'data';
$zmienne[1] = 'site';
$zmienne[2] = 'sitey';
$zmienne[3] = 'sitem';

$opisy[0] = 'dzień';
$opisy[1] = 'Google site';
$opisy[2] = 'Yahoo site';
$opisy[3] = 'MSN site';

blokowisko($cotojest, $zmienne, $opisy, $dni, $d, $szerokosc, $szerokosc_wykresu);

unset($zmienne);
unset($opisy);

$cotojest = 'backlinki';

$zmienne[0] = 'data';
$zmienne[1] = 'gbl';
$zmienne[2] = 'ybl';
$zmienne[3] = 'mbl';
$zmienne[4] = 'abl';
$zmienne[5] = 'alexabl';

$opisy[0] = 'dzień';
$opisy[1] = 'Google backlinks';
$opisy[2] = 'Yahoo backlinks';
$opisy[3] = 'MSN backlinks';
$opisy[4] = 'Altavista backlinks';
$opisy[5] = 'Alexa backlinks';

blokowisko($cotojest, $zmienne, $opisy, $dni, $d, $szerokosc, $szerokosc_wykresu);

unset($zmienne);
unset($opisy);

$cotojest = 'PageRank';

$zmienne[0] = 'data';
$zmienne[1] = 'pr';

$opisy[0] = 'dzień';
$opisy[1] = 'PR';

blokowisko($cotojest, $zmienne, $opisy, $dni, $d, $szerokosc, $szerokosc_wykresu);

echo '</body>

</html>';

mysql_close($sql_conn);

?>
