<?php
define( "USE_SANDBOX", 0 ); //1 sito di sviluppo; 0 sito di produzione
define( "DEBUG_AA", 1 ); // Attiva log codice 
define( "REQ_FIELDS_DEFAULT", "nome,cognome,mail,sesso,indirizzo,cap,citta,provincia,stato" );
define( "DEFAULT_LN", "ITA" ); //Lingua di default
define( "LANGUAGE_MANAGER", 0 ); // 0 Spento 1 acceoso:la gestione della lingua viene abilitata solo se l'URL delform di pagametno cambia in funzione della lingua 
if ( 1 == LANGUAGE_MANAGER ) {
    define( "FORM_LANG", "ITA" ); // gestire la lingua. Usa sigle di tre lettere, in maiuscolo.
    if ( USE_SANDBOX == true ) {
        define( "PAYMENT_URL", "https://int-ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet" );
        if ( "ITA" == FORM_LANG ) {
            //$url_di_base = "https://". $_SERVER['HTTP_HOST']."";
            define( "PERCORSO_DI_BASE", "/var/www/" ); // Sviluppo ITA
            $url_di_base = "https://sandbox.domain.tld"; // Sviluppo ITA
        } elseif ( "ENG" == FORM_LANG ) {
            define( "PERCORSO_DI_BASE", "/var/www/" ); // Sviluppo ENG
            $url_di_base = "https://sandbox.domain.tlden"; // Sviluppo ENG
        } else { //Default
            define( "PERCORSO_DI_BASE", "/var/www/" ); // Sviluppo lingua di default
            $url_di_base = "https://sandbox.domain.tlden"; // Sviluppo lingua di default
        }
    } else {
        define( "PAYMENT_URL", "https://ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet" );
        if ( "ITA" == FORM_LANG ) {
            //$url_di_base = "https://". $_SERVER['HTTP_HOST']."";
            define( "PERCORSO_DI_BASE", "/var/www/" ); // Produzione ITA
            $url_di_base = "https://www.sito.tld"; // Produzione ITA
        } elseif ( "ENG" == FORM_LANG ) {
            define( "PERCORSO_DI_BASE", "/var/www/" ); // Produzione ENG
            $url_di_base = "https://en.sito.tld"; // Produzione ENG
        } else { //Default
            define( "PERCORSO_DI_BASE", "/var/www/" ); // Produzione lingua di default
            $url_di_base = "https://en.sito.tld"; // Produzione lingua di default
        }
    }
} else {
    if ( USE_SANDBOX == true ) {
        define( "PERCORSO_DI_BASE", "/var/www/" ); // Produzione 
        $url_di_base = "https://www.sito.tld"; // Produzione 
    }else{
        define( "PERCORSO_DI_BASE", "/var/www/" ); // Produzione 
        $url_di_base = "https://www.sito.tld"; // Produzione
    }
}
define( "INCLUDE_FOLDER", PERCORSO_DI_BASE . "/nexi_ws/inc" );
define( "EMAIL_FOLDER", PERCORSO_DI_BASE . "/nexi_ws/email" );
define( "LIB_FOLDER", PERCORSO_DI_BASE . "/nexi_ws/lib" );
define( "PAGES_FOLDER", PERCORSO_DI_BASE . "/nexi_ws/pages" );
//NEXI - INIZIO
define( "URL" . $url_di_base . "/esito.php" );
define( "URL_BACK", $url_di_base . "/annullo.php" );
define( "CURRENCY", "EUR" );
define( "TCONTAB", "C" ); // Modalità di incasso C = Immediata D = Differita 
//NEXI - FINE
define( "CC_POS", "NEXI" );
define( "URLPOST", $url_di_base . "/nexi_ws/service.php" );
?>