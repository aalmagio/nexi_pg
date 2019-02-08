<?php
/*
V 20190126.1 First release
V 20190204.1 FIRST WORKING RELESE 
*/
//Sviluppo 
if(USE_SANDBOX == true) {
    //NEXI
    define( "ALIAS", "" );
    define( "MAC_KEY", "" );
    define( "GRUPPO", "" );
    define( "MERCHANT", "" );
	define( "PAYMENT_URL", "https://int-ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet" );
	//DB
    define('DB_IP','dbstage.sito.tld');//IP o FQDN del database 
	define('DB_USER','user'); //user mysql 
	define('DB_PASSWORD','password'); //password mysql
	define('DB_DBNAME','nome_dc_sviluppo'); //nome del db
    //EMAIL
	define("EMAIL_SMTPSecure", "tls"); // sets SMTP server secure connection
	define("EMAIL_SMTP_auth", "true"); // sets SMTP server authentication
	define("EMAIL_host","smtp.sito.tld"); // sets the SMTP server
	define("EMAIL_host_port",587); // set the SMTP port for the  server
	define("EMAIL_authenticated_Username","email@sito.tld"); // SMTP account username
	define("EMAIL_authenticated_Psw","nonteladico"); // SMTP account password
	define("EMAIL_display_Name","Nome Cognome"); // Nome visualizzato nella mail
}
//Produzione
else{ 
    //NEXI
    define( "ALIAS", "" );
    define( "MAC_KEY", "" );
    define( "GRUPPO", "" );
    define( "MERCHANT", "" );
	define( "PAYMENT_URL", "https://ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet" );
	//DB
    define('DB_IP','db.sito.tld');//IP o FQDN del database 
	define('DB_USER','user'); //user mysql 
	define('DB_PASSWORD','password'); //password
	define('DB_DBNAME','nome_dc_produzione'); //nome del db
    //EMAIL
	define("EMAIL_SMTPSecure", "tls"); // sets SMTP server secure connection
	define("EMAIL_SMTP_auth", "true"); // sets SMTP server authentication
	define("EMAIL_host","smtp.sito.tld"); // sets the SMTP server
	define("EMAIL_host_port",587); // set the SMTP port for the  server
	define("EMAIL_authenticated_Username","email@sito.tld"); // SMTP account username
	define("EMAIL_authenticated_Psw","nonteladico"); // SMTP account password
	define("EMAIL_display_Name","Nome Cognome"); // Nome visualizzato nella mail
}
?>