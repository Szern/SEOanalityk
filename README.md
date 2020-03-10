# SEOanalityk
SEOAnalityk jest moim autorskim skryptem. Funkcje, jakie obecnie spełnia to:

- codzienne sprawdzanie parametrów stron www i zapisywanie ich w bazie,
- przeglądanie archiwalnych parametrów stron www (zapisanych w bazie) w formie tabel i wykresów,
- porównanie archiwalnych parametrów kilku (obecnie od dwu do sześciu) stron www w formie tabel i wykresów,
- doraźne sprawdzanie parametrów strony www.

Parametry stron sprawdzane obecnie przez skrypt to:

- Google site,
- Google backlinks,
- Google PageRank,
- MSN site,
- MSN backlinks,
- Yahoo site,
- Yahoo backlinks,
- Alexa Popularity,
- Alexa Reach Rank,
- Alexa Rank Delta,
- Alexa backlinks,
- Altavista backlinks,
- AllTheWeb backlinks.

Skrypt jest we wczesnej fazie rozwojowej. Wszelkie uwagi, a także pomoc w jego rozwoju są mile widziane na tym forum.

SEOAnalityk jest moim autorskim skryptem. SEOAnalityk jest wolnym oprogramowaniem; możesz go rozprowadzać dalej i/lub modyfikować na warunkach Powszechnej Licencji Publicznej GNU, wydanej przez Fundację Wolnego Oprogramowania - według wersji trzeciej tej Licencji lub (według twojego wyboru) którejś z późniejszych wersji.

Przy pisaniu skryptu SEOAnalityk wykorzystałem:

- funkcje do sprawdzania PR z bloga http://fusionswift.com
- bibliotekę do rysowania wykresów ze strony http://naku.dohcrew.com na licencji GNU General Public License (GPL)
- bibliotekę do rysowania wykresów ze strony http://www.amcharts.com/ - freeware (pod warunkiem zamieszczenia linku na wykresie)
- skrypt/plugin do jquery Droppy 0.1.2 na licencji Open Source Initiative OSI - The MIT License (MIT)
- pomysł na menu (i fragmenty kodu) z bloga Bartłomieja Frydrych o adresie www.frycu.com
- bibliotekę jquery.min.js (jQuery JavaScript Library v1.5.1) pobieraną z ajax.googleapis.com

Bardzo dziękuję ich twórcom, za umożliwienie wykorzystania swoich pomysłów i efektów swojej pracy.

Instalacja:

1) tworzymy bazę SQL
2) w pliku baza.php wpisujemy dane własnej bazy:

$db_user='uzytkownik';
$db_password='haslo';
$database='nazwa bazy';
$host='localhost'; // jeśli inny niż localhost, należy go zmienić
$grafy = 'wolne'; // 'szybkie' lub 'wolne' - dwa rodzaje wykresów

3) kopiujemy zawartość folderu "skrypt" na serwer
4) dla folderu "generated" ustawiamy prawa do zapisu 777
5) dodajemy do CRON plik "site.php" np. w ten sposób (zamieniając "domena.pl" na adres, pod którym instalujemy skrypt):

wget -O /dev/null http://domena.pl/mwt/site.php 2>&1

Skrypt uruchamiamy w CRON co najmniej raz dziennie, ale w przypadku dużej ilosci stron i obciążonego serwera mozna go uruchomić kilkakrotnie.
6) uruchamiamy skrypt poprzez wpisanie adresu skryptu w przeglądarce
7) dodajemy stronę wybierając z menu "konfig" -> "dodawanie stron"

To wszystko, skrypt powinien działać.
