<?php
	$siteg = spiderek('search?q=site%3A', $buf, $dcg, 'www.google.com', '', "About ", 6, " results");
	$sitey = spiderek('advsearch?p=', $buf, $dcy, 'siteexplorer.search.yahoo.com', '', "Pages (", 7, ")");
	$sitem = spiderek('search?q=site%3A', $buf, $dcm, 'www.bing.com', '', "of ", 3, " results");

	$gbl = spiderek('search?q=link%3A', $buf, $dcg, 'www.google.com', '', "About ", 6, " results");
	$ybl = spiderek('advsearch?p=', $buf, $dcy, 'siteexplorer.search.yahoo.com', '&bwm=i&bwmo=d&bwmf=s', "Inlinks (", 9, ")");
	$mbl = spiderek('search?q=link%3A', $buf, $dcm, 'www.bing.com', '', "of ", 3, " results");
	$abl = spiderek('advsearch?p=', $buf, $dcy, 'siteexplorer.search.yahoo.com', '&bwm=i&bwmf=u&bwms=p&fr=altavista', "Inlinks (", 9, ")");
	$wbl = spiderek('advsearch?p=', $buf, $dcy, 'siteexplorer.search.yahoo.com', '&bwm=i&bwmf=u&bwms=p&fr=alltheweb', "Inlinks (", 9, ")");
	$pr = getpagerank($buf);
	$alexa = spiderek('data?cli=10&dat=snbamz&url=', $buf, $dca, 'data.alexa.com', '', 'TEXT="', 6, '"/>');
	$alexar = spiderek('data?cli=10&dat=snbamz&url=', $buf, $dca, 'data.alexa.com', '', 'RANK="', 6, '"/>');
	$alexad = spiderek('data?cli=10&dat=snbamz&url=', $buf, $dca, 'data.alexa.com', '', 'DELTA="', 7, '"/>');
	$alexal = spiderek('data?cli=10&dat=snbamz&url=', $buf, $dca, 'data.alexa.com', '', 'NUM="', 5, '"/>');
//	$wiek = spiderek('', $buf, $dcw, 'whois.domaintools.com', '', 'since', 5, '</span>');
?>