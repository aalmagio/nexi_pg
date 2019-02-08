<?php
/*
V 20190126.1 First release
V 20190204.1 FIRST WORKING RELESE
*/
define( "USE_SANDBOX", 1 ); //1 sito di sviluppo; 0 sito di produzione
define( "DEBUG_AA", 1 ); // Attiva log codice 
define( "REQ_FIELDS_DEFAULT", "nome,cognome,mail,descrizione,periodo" );
define( "DEFAULT_LN", "ITA" ); //Lingua di default
define( "CONTROLLO_ANAGRAFICA", 0 );
define( "INVIO_MAIL", 0 );// Abilita invio mail di ringraziamanto 
define( "FORM_LANG", DEFAULT_LN );
if ( true == USE_SANDBOX ) {//Sviluppo
    define( "PERCORSO_DI_BASE", "/htdocs/public/stage" ); // Produzione 
	$url_di_base = "https://stage.sito.tld"; // Produzione 
} else {//PRODUZIONE
	define( "PERCORSO_DI_BASE", "/htdocs/public/www" ); // Produzione 
	$url_di_base = "https://www.sito.tld"; // Produzione
}
define( "INCLUDE_FOLDER", PERCORSO_DI_BASE . "/inc" );
define( "EMAIL_FOLDER", PERCORSO_DI_BASE . "/email" );
define( "LIB_FOLDER", PERCORSO_DI_BASE . "/lib" );
define( "PAGES_FOLDER", PERCORSO_DI_BASE . "/pages" );
//NEXI - INIZIO
define( "URL", $url_di_base . "/esito.php" );
define( "URL_BACK", $url_di_base . "/annullo.php" );
define( "CURRENCY", "EUR" );
define( "TCONTAB", "C" ); // Modalità di incasso C = Immediata D = Differita 
//NEXI - FINE
define( "CC_POS", "NEXI" );
define( "URLPOST", $url_di_base . "/service.php" );
define ( "FORM", $url_di_base ."/index.php");
?>
?>