<?php
//Sviluppo
if(USE_SANDBOX == true) {
    define( "ALIAS", "" );
    define( "MAC_KEY", "" );
    define( "GRUPPO", "" );
    define( "MERCHANT", "" );
	//DB
    define('DB_IP','');//Produzione e Sviluppo: IP o FQDN del database 
	define('DB_USER',''); // Sviluppo: user mysql 
	define('DB_PASSWORD',''); // Sviluppo: password
	define('DB_DBNAME',''); // Sviluppo: nome del db
    //EMAIL
	define("EMAIL_SMTP_auth",true); // sets the SMTP server
	define("EMAIL_host",""); // sets the SMTP server
	define("EMAIL_host_port",""); // set the SMTP port for the  server
	define("EMAIL_authenticated_Username",""); // SMTP account username
	define("EMAIL_authenticated_Psw",""); // SMTP account password
}
//Produzione
else{ 
    define( "ALIAS", "" );
    define( "MAC_KEY", "" );
    define( "GRUPPO", "" );
    define( "MERCHANT", "" );
	//DB
    define('DB_IP','');//Produzione e Sviluppo: IP o FQDN del database 
	define('DB_USER',''); // Sviluppo: user mysql 
	define('DB_PASSWORD',''); // Sviluppo: password
	define('DB_DBNAME',''); // Sviluppo: nome del db
    //EMAIL
	define("EMAIL_SMTP_auth",true); // sets the SMTP server
	define("EMAIL_host",""); // sets the SMTP server
	define("EMAIL_host_port",""); // set the SMTP port for the  server
	define("EMAIL_authenticated_Username",""); // SMTP account username
	define("EMAIL_authenticated_Psw",""); // SMTP account password
}
?>