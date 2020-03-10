<?php
// funkcja do sprawdzania PR
// http://www.lampdeveloper.co.uk/php/get-google-page-rank-using-php.html

//www.lampdeveloper.co.uk/
/*
 * convert a string to a 32-bit integer
 */
function StrToNum($Str, $Check, $Magic)
{
    $Int32Unit = 4294967296;  // 2^32

    $length = strlen($Str);
    for ($i = 0; $i < $length; $i++) {
        $Check *= $Magic; 	
        //If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31), 
        //  the result of converting to integer is undefined
        //  refer to http://www.php.net/manual/en/language.types.integer.php
        if ($Check >= $Int32Unit) {
            $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
            //if the check less than -2^31
            $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
        }
        $Check += ord($Str{$i}); 
    }
    return $Check;
}

/* 
 * Genearate a hash for a url
 */
function HashURL($String)
{
    $Check1 = StrToNum($String, 0x1505, 0x21);
    $Check2 = StrToNum($String, 0, 0x1003F);

    $Check1 >>= 2; 	
    $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
    $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
    $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);	
	
    $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
    $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
	
    return ($T1 | $T2);
}

/* 
 * genearate a checksum for the hash string
 */
function CheckHash($Hashnum)
{
    $CheckByte = 0;
    $Flag = 0;

    $HashStr = sprintf('%u', $Hashnum) ;
    $length = strlen($HashStr);
	
    for ($i = $length - 1;  $i >= 0;  $i --) {
        $Re = $HashStr{$i};
        if (1 === ($Flag % 2)) {              
            $Re += $Re;     
            $Re = (int)($Re / 10) + ($Re % 10);
        }
        $CheckByte += $Re;
        $Flag ++;	
    }

    $CheckByte %= 10;
    if (0 !== $CheckByte) {
        $CheckByte = 10 - $CheckByte;
        if (1 === ($Flag % 2) ) {
            if (1 === ($CheckByte % 2)) {
                $CheckByte += 9;
            }
            $CheckByte >>= 1;
        }
    }

    return '7'.$CheckByte.$HashStr;
}

function getpagerank($url) {

$fp = fsockopen("toolbarqueries.google.com", 80, $errno, $errstr, 30);
if (!$fp) {
   echo "$errstr ($errno)<br />\n";
} else {
 $out = "GET /search?client=navclient-auto&ch=".CheckHash(HashURL($url))."&features=Rank&q=info:".$url."&num=100&filter=0 HTTP/1.1\r\n";
$out .= "Host: toolbarqueries.google.com\r\n";
$out .= "User-Agent: Mozilla/4.0 (compatible; GoogleToolbar 2.0.114-big; Windows XP 5.1)\r\n";
$out .= "Connection: Close\r\n\r\n";

   fwrite($fp, $out);
   
   //$pagerank = substr(fgets($fp, 128), 4);
   //echo $pagerank;
   while (!feof($fp)) {
	$data = fgets($fp, 128);
	$pos = strpos($data, "Rank_");
	if($pos === false){} else{
		$pagerank = substr($data, $pos + 9);
		return $pagerank;
	}
   }
   fclose($fp);
   
   }

}

// funkcja sprawdzania tabel

function table_exists ($table, $db) {
	$zapytanie = "SHOW TABLES FROM `$db`";
	$tables = mysql_query($zapytanie);
	while (list ($temp) = mysql_fetch_array ($tables)) {
		if ($temp == $table) {
			return TRUE;
		}
	}
	return FALSE;
}

// spider

function curl_get_data($url, $datacenter, $host) {

// tabela z useragentami

	$user_agents = array(
		"Science Traveller International 1X/1.0",
		"Mozilla/3.0 (compatible)",
		"amaya/9.52 libwww/5.4.0 ",
		"amaya/9.51 libwww/5.4.0",
		"amaya/9.1 libwww/5.4.0",
		"amaya/6.2 libwww/5.3.1",
		"AmigaVoyager/3.4.4 (MorphOS/PPC native)",
		"xChaos_Arachne/5.1.89;GPL,386+",
		"Ubuntu APT-HTTP/1.3 (0.7.23.1ubuntu2)",
		"Ubuntu APT-HTTP/1.3",
		"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.3 (Change: 287 c9dfb30)",
		"Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.2 (Change: 0 )",
		"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser;
	Avant Browser; .NET CLR 1.0.3705; .NET CLR 1.1.4322; 
	Media Center PC 4.0; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
		"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322; FDM)",
		"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; Avant Browser [avantbrowser.com]; Hotbar 4.4.5.0)",
		"Amiga-AWeb/3.5.07 beta",
		"Mozilla/6.0; (Spoofed by Amiga-AWeb/3.5.07 beta)",
		"MSIE/6.0; (Spoofed by Amiga-AWeb/3.4APL)",
		"gnome-vfs/2.12.0 neon/0.24.7",
		"bluefish 0.6 HTML editor",
		"Mozilla/4.61 [en] (X11; U; ) - BrowseX (2.0.0 Windows)",
		"Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en; rv:1.8.1.14) Gecko/20080409 Camino/1.6 (like Firefox/2.0.0.14)",
		"Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en; rv:1.8.1.6) Gecko/20070809 Camino/1.5.1",
		"Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; rv:1.8.0.1) Gecko/20060118 Camino/1.0b2+",
		"Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.5b) Gecko/20030917 Camino/0.7+",
		"Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US; rv:1.0.1) Gecko/20021104 Chimera/0.6",
		"Mozilla/4.08 (Charon; Inferno)",
		"Mozilla/2.0 compatible; Check&Get 1.14 (Windows NT)",
		"Chimera/2.0alpha",
		"Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.1 (KHTML, like Gecko) Chrome/5.0.322.2 Safari/533.1",
		"Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_2; en-US) AppleWebKit/532.9 (KHTML, like Gecko) Chrome/5.0.307.11 Safari/532.9",
		"Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/532.5 (KHTML, like Gecko) Chrome/4.0.249.78 Safari/532.5",
		"Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/532.4 (KHTML, like Gecko) Chrome/4.0.233.0 Safari/532.4",
		"Mozilla/5.0 (X11; U; Linux i686 (x86_64); en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/3.0.198.0 Safari/532.0",
		"Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; Valve Steam GameOverlay; ) AppleWebKit/532.1 (KHTML, like Gecko) Chrome/3.0.195.24 Safari/532.1",
		"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13",
		"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/3.0.195.33 Safari/532.0",
		"Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.53 Safari/525.19",
		"Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_7; en-US) AppleWebKit/531.0 (KHTML, like Gecko) Chrome/3.0.183 Safari/531.0",
		"Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.53 Safari/525.19",
		"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.36 Safari/525.19",
		"Mozilla/5.0 (Linux; U; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13",
		"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13",
		"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13",
		"Contiki/1.0 (Commodore 64; http://dunkels.com/adam/contiki/)",
		"Contiki/1.0 (Commodore 64; http://dunkels.com/adam/contiki/)",
		"curl/7.7.2 (powerpc-apple-darwin6.0) libcurl 7.7.2 (OpenSSL 0.9.6b)",
		"Democracy/0.8.1 (http://www.participatoryculture.org)",
		"Dillo/0.8.6",
		"Dillo/2.0",
		"Dillo/0.8.5-i18n-misc",
		"Dillo/0.8.5-pre",
		"Dillo/0.8.3",
		"Dillo/0.8.2",
		"Dillo/0.8.2",
		"Dillo/0.6.6",
		"DocZilla/1.0 (Windows; U; WinNT4.0; en-US; rv:1.0.0) Gecko/20020804",
		"edbrowse/2.2.10",
		"ELinks/0.12~pre2.dfsg0-1ubuntu1-lite (textmode; Debian; Linux 2.6.32-4-jolicloud i686; 143x37-2)",
		"ELinks/0.12pre5.GIT (textmode; CYGWIN_NT-6.1 1.7.1(0.218/5/3) i686; 80x24-2)",
		"ELinks/0.11.3-5ubuntu2-lite (textmode; Debian; Linux 2.6.24-19-generic i686; 126x37-2)",
		"ELinks/0.11.4-2 (textmode; Debian; GNU/kFreeBSD 6.3-1-486 i686; 141x21-2)",
		"ELinks (0.4.3; NetBSD 3.0.2_PATCH sparc64; 141x19)",
		"ELinks/0.10.4-7ubuntu1-debian (textmode; Linux 2.6.12-10-k7-smp i686; 80x24-2)",
		"ELinks/0.10.5 (textmode; CYGWIN_NT-5.0 1.5.18(0.132/4/2) i686; 143x51-2)",
		"ELinks (0.4.2; Linux; )",
		"Emacs-W3/4.0pre.46 URL/p4.0pre.46 (i686-pc-linux; X11)",
		"Mozilla/5.0 (X11; U; Linux i686; en-us) AppleWebKit/531.2+ (KHTML, like Gecko) Safari/531.2+ Epiphany/2.29.5",
		"Mozilla/5.0 (X11; U; Linux i686; en; rv:1.9.0.11) Gecko/20080528 Epiphany/2.22 Firefox/3.0",
		"Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/420+ (KHTML, like Gecko)",
		"Mozilla/5.0 (X11; U; Linux x86_64; c) AppleWebKit/525.1+ (KHTML, like Gecko, Safari/525.1+) epiphany",
		"Mozilla/5.0 (X11; U; Linux x86_64; en; rv:1.8.1.4) Gecko/20061201 Epiphany/2.18 Firefox/2.0.0.4 (Ubuntu-feisty)",
		"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.5) Gecko/20060731 Epiphany/2.14 Firefox/1.5.0.5",
		"Mozilla/5.0 (X11; U; Linux i686; en; rv:1.8.1) Gecko/20061203 Epiphany/2.16 Firefox/2.0",
		"Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.8.0.1) Gecko/Debian-1.8.0.1-5 Epiphany/1.8.5",
		"Mozilla/5.0 (X11; U; Linux i686; cs-CZ; rv:1.7.13) Gecko/20060418 Epiphany/1.8.2 (Ubuntu) (Ubuntu package 1.0.8)",
		"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.3) Gecko/20040924 Epiphany/1.4.4 (Ubuntu)",
		"Mozilla/5.0 (X11; U; FreeBSD i386; en-US; rv:1.7) Gecko/20040628 Epiphany/1.2.6",
		"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4.1) Gecko/20031030 Epiphany/1.0.8",
		"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4.1) Gecko/20031114 Epiphany/1.0.4",
		"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4) Gecko/20030704 Epiphany/0.9.2",
		"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4) Gecko/20030703 Epiphany/0.8.4",
		"fetch libfetch/2.0",
		"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8) Gecko/20051111 Firefox/1.5 BAVM/1.0.0",
		"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8 ",
		"Mozilla/5.0 (X11; U; Linux armv61; en-US; rv:1.9.1b2pre) Gecko/20081015 Fennec/1.0a1",
		"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.2pre) Gecko/20100225 Ubuntu/9.10 (karmic) Namoroka/3.6.2pre",
		"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 ",
		"Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.1.8) Gecko/20100215 Solaris/10.1 (GNU) Superswan/3.5.8 (Byte/me)",
		"Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10.5; en-US; rv:1.9.0.3) Gecko/2008092414 Firefox/3.0.3 ",
		"Mozilla/5.0 (X11; U; OpenBSD i386; en-US; rv:1.8.1.14) Gecko/20080821 Firefox/2.0.0.14",
		"Mozilla/5.0 (X11; U; Darwin Power Macintosh; en-US; rv:1.8.0.12) Gecko/20070803 Firefox/1.5.0.12 Fink Community Edition",
		"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.13) Gecko/20060410 Firefox/1.0.8",
		"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.3) Gecko/20041002 Firefox/0.10.1",
		"Mozilla/5.0 (X11; U; SunOS sun4m; en-US; rv:1.4b) Gecko/20030517 Mozilla Firebird/0.6",
		"Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.3a) Gecko/20021207 Phoenix/0.5",
		"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.2b) Gecko/20020923 Phoenix/0.1",
		"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.3) Gecko/2008100716 Firefox/3.0.3 Flock/2.0",
		"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.4) Gecko/20060612 Firefox/1.5.0.4 Flock/0.7.0.17.1",
		"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8b5) Gecko/20051019 Flock/0.4 Firefox/1.0+",
	//		"", Galeon
	);

	$headers = array(
		"GET " . $url . " HTTP/1.1",
		"Host: " . $host,
		"User-Agent: " . $user_agents[rand(0, count($user_agents)-1)],
		"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
		"Accept-Language: pl,en-us;q=0.7,en;q=0.3",
		"Accept-Encoding: gzip,deflate",
		"Accept-Charset: ISO-8859-2,utf-8;q=0.7,*;q=0.7",
		"Connection: keep-alive",
	);

	$ch  = curl_init("http://" . $datacenter . '/' . $url);
	if ($datacenter == 'whois.domaintools.com') { echo "http://" . $datacenter . '/' . $url; }	
	curl_setopt($ch, CURLOPT_TIMEOUT, 0);
	curl_setopt($ch, CURLOPT_HEADER, $headers);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$file = 'ip.txt';
  if (file_exists($file)) {
		$fp = fopen($file,'rb');
		$ip = trim(fgets($fp));
		fclose($fp);
		curl_setopt($ch, CURLOPT_INTERFACE, $ip);
	}
	$res = curl_exec($ch) ;
	curl_close($ch) ;
	return $res;

}

function StrContains($haystack, $needle) {
	return(strpos($haystack,$needle)!==false);
}

function spiderek($pre, $url, $dc, $host, $end, $s1, $s1l, $s2) {

	$site = curl_get_data($pre . $url . $end, $dc, $host);
	if (($pre == '') && ($host == '77.252.2.40')) { echo $site; }	
	if ((StrContains($site, "No results found for")) || (StrContains($site, "No Results"))) {
		return 0;
	} else {
	
		$site = substr($site, (strpos($site, $s1) + $s1l));

		$site = substr($site, 0, (strpos($site, $s2)));
		$site = str_replace(',', '', $site);
		$site = (int)$site;
		return $site;
	}

}
?>