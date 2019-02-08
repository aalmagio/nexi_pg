<?php
/*
V 20190126.1 basic configuration NOT WORKING: just set up functions without relataions and without any call.
V 20190204.1 FIRST WORKING RELESE.
V 20190204.2 FIRST WORKING RELESE: decimal separator bug correction and security SMTP configuation within config.inc.php
V 20190208.1 WORKING RELESE: Added Language managment
*/
require( 'inc/config.inc.php' );
require( 'inc/data.inc.php' );
require( 'lib/CodiceFiscale.php' );
define( "LOG_FILE", "./NEXI_WS.log" ); //Togliere in Produzione

function ValidaAnagrafica( $anagrafica ) {
    $req_fields = explode( ",", $anagrafica->req_fields );
    //Gestione variabili obbligatorie 
    //Nel db i campi che non possono essere NULL sono: Id_a, privacy, data_ins, tipo_ana 
    $errore = 0;
    $messaggio_errore = "";
    if ( !isset( $anagrafica->nome ) || trim( $anagrafica->nome ) == "" ) {
        $errore++;
        $messaggio_errore .= "M001|";
    }
    if ( !isset( $anagrafica->cognome ) || trim( $anagrafica->cognome ) == "" ) {
        $errore++;
        $messaggio_errore .= "M002|";
    }

    if ( in_array( "mail", $req_fields ) && ( !isset( $anagrafica->mail ) || trim( $anagrafica->mail ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M005|";
    }
    if ( in_array( "mail", $req_fields ) && !filter_var( $anagrafica->mail, FILTER_VALIDATE_EMAIL ) ) {
        $errore++;
        $messaggio_errore .= "E005|";
    }
    if ( in_array( "sesso", $req_fields ) && ( !isset( $anagrafica->sesso ) || trim( $anagrafica->sesso ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M004|";
    }
    if ( in_array( "indirizzo", $req_fields ) && ( !isset( $anagrafica->indirizzo ) || trim( $anagrafica->indirizzo ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M007|";
    }
    if ( in_array( "civico", $req_fields ) && ( !isset( $anagrafica->civico ) || trim( $anagrafica->civico ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M008|";
    }
    if ( in_array( "cap", $req_fields ) && ( !isset( $anagrafica->cap ) || trim( $anagrafica->cap ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M009|";
    }
    if ( in_array( "cap", $req_fields ) && $anagrafica->stato == "I" && !preg_match( "/^[0-9]{5}$/", $anagrafica->cap ) ) {
        $errore++;
        $messaggio_errore .= "E009|";
    }
    if ( in_array( "citta", $req_fields ) && ( !isset( $anagrafica->citta ) || trim( $anagrafica->citta ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M010|";
    }
    if ( $anagrafica->stato == "I" ) {
        if ( in_array( "provincia", $req_fields ) && ( !isset( $anagrafica->provincia ) || trim( $anagrafica->provincia ) == "" ) ) {
            $errore++;
            $messaggio_errore .= "M011|";
        }
        //if(in_array("provincia", $req_fields) && $anagrafica->stato =="I" && !preg_match("/^[a-zA-Z]{2}$/",$anagrafica->provincia) ) {$errore++ ; $messaggio_errore .= "E011|";} 
        $province = array( "AG", "AL", "AN", "AO", "AQ", "AR", "AP", "AT", "AV", "BA", "BT", "BL", "BN", "BG", "BI", "BO", "BZ", "BS", "BR", "CA", "CL", "CB", "CI", "CE", "CT", "CZ", "CH", "CO", "CS", "CR", "KR", "CN", "EN", "FM", "FE", "FI", "FG", "FC", "FR", "GE", "GO", "GR", "IM", "IS", "SP", "LT", "LE", "LC", "LI", "LO", "LU", "MC", "MN", "MS", "MT", "VS", "ME", "MI", "MO", "MB", "NA", "NO", "NU", "OG", "OT", "OR", "PD", "PA", "PR", "PV", "PG", "PU", "PE", "PC", "PI", "PT", "PN", "PZ", "PO", "RG", "RA", "RC", "RE", "RI", "RN", "RM", "RO", "SA", "SS", "SV", "SI", "SR", "SO", "TA", "TE", "TR", "TO", "TP", "TN", "TV", "TS", "UD", "VA", "VE", "VB", "VC", "VR", "VV", "VI", "VT" );
        if ( !in_array( strtoupper( $anagrafica->provincia ), $province ) && trim( $anagrafica->provincia ) != "" && $anagrafica->stato == "I" ) {
            $errore++;
            $messaggio_errore .= "E011|";
        }
    }
    if ( in_array( "stato", $req_fields ) && ( !isset( $anagrafica->stato ) || trim( $anagrafica->stato ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M012|";
    }
    if ( in_array( "lang", $req_fields ) && ( !isset( $anagrafica->lang ) || trim( $anagrafica->lang ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M065|";
    }
    if ( in_array( "tel", $req_fields ) && ( !isset( $anagrafica->tel ) || trim( $anagrafica->tel ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M006|";
    }
    if ( in_array( "codFis", $req_fields ) && ( !isset( $anagrafica->codFis ) || trim( $anagrafica->codFis ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M014|";
    }
    $cf = new CodiceFiscale();
    if ( trim( $anagrafica->codFis ) != "" && $anagrafica->stato == "I" && !$cf->ValidaCodiceFiscale( $anagrafica->codFis ) ) {
        $errore++;
        $messaggio_errore .= "E014|";
    }
    if ( in_array( "PIVA", $req_fields ) && ( !isset( $anagrafica->PIVA ) || trim( $anagrafica->PIVA ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M013|";
    }
    if ( in_array( "PIVA", $req_fields ) && $anagrafica->stato == "I" && !preg_match( "/^[0-9]{11}$/", $anagrafica->PIVA ) ) {
        $errore++;
        $messaggio_errore .= "E013|";
    }
    if ( !isset( $anagrafica->IP ) || trim( $anagrafica->IP ) == "" ) {
        $errore++;
        $messaggio_errore .= "M057|";
    }
    if ( !isset( $anagrafica->privacy ) || trim( $anagrafica->privacy ) == "" ) {
        $errore++;
        $messaggio_errore .= "M036|";
    }
    return array( $errore, $messaggio_errore );
}

function ScriviAnagrafica_mysql( $anagrafica ) { // Scrivo l'anagrafica in mySQL 
    //verifico le variabili
    //Valorizzazione default delle varibaili (eventulamnte anche quelle obbligatorie nel database)
    $req_fields = explode( ",", $anagrafica->req_fields );
    //Imposto con valore di default i campi eventualmente NON presnti nel form - INIZIO
    if ( !in_array( "sesso", $req_fields ) && ( !isset( $anagrafica->sesso ) || "" == trim( $anagrafica->sesso ) ) ) {
        $anagrafica->sesso = "X";
    } //X =NON Definito 
    if ( !isset( $anagrafica->IP ) || "" == trim( $anagrafica->IP ) )$anagrafica->IP = $_SERVER[ 'REMOTE_ADDR' ];
    if ( !isset( $anagrafica->lang ) || "" == trim( $anagrafica->lang ) )$anagrafica->lang = DEFAULT_LN;
    if ( !isset( $anagrafica->privacy ) || "" == trim( $anagrafica->privacy ) )$anagrafica->privacy = 'Y';
    //Imposto a valore di default i campi eventualmente NON presnti nel form - FINE
    if ( 1 == CONTROLLO_ANAGRAFICA ) {
        $chk_anagrafica = call_user_func_array( 'ValidaAnagrafica', array( $anagrafica ) ); // Vlaido l'anagrafica prima di scrivere in mysql
    } else {
        $chk_anagrafica[ 0 ] = 0;
    }
    if ( $chk_anagrafica[ 0 ] <> 0 ) {
        return array( $chk_anagrafica[ 1 ], "" );
    } else {
        // connetto al db
        $connection = mysqli_connect( DB_IP, DB_USER, DB_PASSWORD, DB_DBNAME );
        if ( $connection->connect_errno ) {
            trigger_error( "Connessione al server mySQL fallita: (" . $connection->connect_errno . ") " . $connection->connect_error, E_USER_ERROR );
        }
        // preparo lo statement
        if ( !( $stmt = $connection->prepare( "INSERT INTO Anagrafica (nome, cognome, sesso, indirizzo, civico, cap, citta, provincia, stato, tel, mail, codFis,  privacy, IP, lang ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )" ) ) ) {
            trigger_error( "Prepare failed: (" . $connection->errno . ") " . $connection->error, E_USER_ERROR );
        }
        // associo i parametri ai placeholder
        if ( !$stmt->bind_param( 'sssssssssssssss', $anagrafica->nome, $anagrafica->cognome, $anagrafica->sesso, $anagrafica->indirizzo, $anagrafica->civico, $anagrafica->cap, $anagrafica->citta, strtoupper( $anagrafica->provincia ), $anagrafica->stato, $anagrafica->tel, $anagrafica->mail, $anagrafica->codFis, $anagrafica->privacy, $anagrafica->IP, $anagrafica->lang ) ) {
            trigger_error( "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error, E_USER_ERROR );
        }
        // eseguo la query e chiudo
        if ( !$stmt->execute() ) {
            trigger_error( "Execute failed: (" . $stmt->errno . ") " . $stmt->error, E_USER_ERROR );
        }
        $codice_anagrafica = $stmt->insert_id;
        $stmt->close();
        if ( !is_numeric( $codice_anagrafica ) ) {
            return array( "Errore di scrittura in mysql", "" );
        } else {
            return array( $codice_anagrafica, "" );
        }
    }
}

function Scrivipagamento_mysql( $pagamento ) {
    //verifico le variabili
    //Gestione variabili obbligatorie 
    //Nel db i campi che non possono essere NULL sono: codTrans,Id_a,importo,pay_method,tessera,esito,data
    //Valorizzazione default delle varibaili (eventualmente anche quelle obbligatorie nel database)
    $req_fields = explode( ",", $pagamento->req_fields );
    if ( !isset( $pagamento->codTrans ) || trim( $pagamento->codTrans ) == "" ) {
        $micro_date = microtime();
        $date_array = explode( " ", $micro_date );
        $date = date( "YmdwHis", $date_array[ 1 ] );
        $pagamento->codTrans = "T-" . $date . substr( $date_array[ 0 ], 2, 2 ) . "-PP";
    }
    if ( !isset( $pagamento->esito ) || trim( $pagamento->esito ) == "" ) {
        $pagamento->esito = "WA";
    }
    $errore = 0;
    $messaggio_errore = "";
    //codTrans, il codice di transazione lo genero nella funzione anziche' nel form ma se mi viene passato 
    //if(!isset($pagamento->codTrans) || trim($pagamento->codTrans) =="") {$errore++ ; $messaggio_errore .= "M020";}
    if ( !isset( $pagamento->Id_a ) || trim( $pagamento->Id_a ) == "" ) {
        $errore++;
        $messaggio_errore .= "M058|";
    }
    if ( !isset( $pagamento->importo ) || trim( $pagamento->importo ) == "" ) {
        $errore++;
        $messaggio_errore .= "M021|";
    } elseif ( !preg_match( "/^[0-9]+[0-9,\.]*$/", $pagamento->importo ) ) {
        $errore++;
        $messaggio_errore .= "E021|";
    }
    if ( in_array( "descrizione", $req_fields ) && trim( $pagamento->descrizione ) == "" ) {
        $errore++;
        $messaggio_errore .= "M026|";
    }
        if ( in_array( "periodo", $req_fields ) && trim( $pagamento->periodo ) == "" ) {
        $errore++;
        $messaggio_errore .= "E086|";
    }
    if (!isset($_REQUEST['data']) || "" == trim($_REQUEST['data'])){
        $data_trans = date("Y-m-d");   
    } 
    else{ 
        $data_trans= $_REQUEST['data'];
    }
    if (!isset($_REQUEST['ora']) || "" == trim($_REQUEST['ora'])){
        $ora_trans = date("H:i:s");   
    } 
    else{ 
        $ora_trans= $_REQUEST['ora'];
    }
    
    if ( $errore > 0 ) {
        return $messaggio_errore;
    }
    
    //
    else {
        //Importo da autorizzare espresso in centesimi di euro senza separatore, i primi 2 numeri a destra rappresentano gli euro cent, es.: 5000 corrisponde a 50,00 €
        if( "," ==substr($pagamento->importo, -3, 1) || "."==substr($pagamento->importo, -3, 1) ){
        $decimal_thousand_separator = array(",",".");
        $importo = str_replace($decimal_thousand_separator, "", $pagamento->importo);
        }
        elseif( "," ==substr($pagamento->importo, -2, 1) || "."==substr($pagamento->importo, -2, 1) ){
        $decimal_thousand_separator = array(",",".");
        $importo = str_replace($decimal_thousand_separator, "", $pagamento->importo)."0";
        } 
        else{
            $decimal_thousand_separator = array(",",".");
            $importo = str_replace($decimal_thousand_separator, "", $pagamento->importo);
            $importo = $importo ."00";
            
        }
        // connetto al db
        $connection = mysqli_connect( DB_IP, DB_USER, DB_PASSWORD, DB_DBNAME );
        if ( $connection->connect_errno ) {
            trigger_error( "Connessione al server mySQL fallita: (" . $connection->connect_errno . ") " . $connection->connect_error, E_USER_ERROR );
        }
        // preparo lo statement
        if ( !( $stmt = $connection->prepare( "INSERT INTO pagamento (codTrans,Id_a,importo,descrizione,periodo,esito, data, ora, IP) VALUES (?,?,?,?,?,?,?,?,?)" ) ) ) {
            trigger_error( "Prepare failed: (" . $connection->errno . ") " . $connection->error, E_USER_ERROR );
        }
        // associo i parametri ai placeholder
        if ( !$stmt->bind_param( 'sisssssss', $pagamento->codTrans, $pagamento->Id_a, $importo, $pagamento->descrizione, $pagamento->periodo, $pagamento->esito,  $data_trans, $ora_trans,  $pagamento->IP ) ) {
            trigger_error( "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error, E_USER_ERROR );
        }
        // eseguo la query e chiudo
        if ( !$stmt->execute() ) {
            trigger_error( "Execute failed: (" . $stmt->errno . ") " . $stmt->error, E_USER_ERROR );
        }
        $stmt->close();
        return $pagamento->codTrans;
    }
}

function GoToPOS( $ordine ) {
    // Calcolo MAC
    if( "," ==substr($ordine->importo, -3, 1) || "."==substr($ordine->importo, -3, 1) ){
        $decimal_thousand_separator = array(",",".");
        $importo = str_replace($decimal_thousand_separator, "", $ordine->importo);
    }
    elseif( "," ==substr($ordine->importo, -2, 1) || "."==substr($ordine->importo, -2, 1) ){
        $decimal_thousand_separator = array(",",".");
        $importo = str_replace($decimal_thousand_separator, "", $ordine->importo)."0";
        } 
    else{
        $decimal_thousand_separator = array(",",".");
        $importo = str_replace($decimal_thousand_separator, "", $ordine->importo);
        $importo = $importo ."00";

    }
    if ( 1 == LANGUAGE_MANAGER ) {
        $languageId = FORM_LANG;
    } elseif ( isset( $ordine->ln )AND "" != trim( $ordine->ln ) ) {
        $languageId = $ordine->ln;
    }
    else {
        if (isset($_REQUEST['lang'])){
            if ("ITALIANO"==$_REQUEST['lang']) {
                $languageId = "ITA";
            }elseif ("ENGLISH"==$_REQUEST['lang']) {
                $languageId = "ENG";
            }
            else{
                $languageId = DEFAULT_LN;   
            }
        }
        else{
            $languageId = DEFAULT_LN;
        }
    }
    $mac = sha1( 'codTrans=' . $ordine->codTrans . 'divisa=' . CURRENCY . 'importo=' . $importo . MAC_KEY );
    // Parametri obbligatori
    //Importo da autorizzare espresso in centesimi di euro senza separatore, i primi 2 numeri a destra rappresentano gli euro cent, es.: 5000 corrisponde a 50,00 €
    
    $obbligatori = array(
        'alias' => ALIAS,
        'importo' => $importo ,
        'divisa' => CURRENCY,
        'codTrans' => $ordine->codTrans,
        'url' => URL,
        'url_back' => URL_BACK,
        'mac' => $mac,
    );
    // Parametri facoltativi
    $facoltativi = array(
        'languageId' => $languageId,
        'urlpost' => URLPOST,
        'mail' => $ordine->mail,
        'descrizione' => $ordine->descrizione,
        'Note1' => $ordine->Note1,
        'OPTION_CF' => $ordine->codFis,
        'TCONTAB' => TCONTAB,
    );
    $requestParams = array_merge( $obbligatori, $facoltativi );
    $aRequestParams = array();
    foreach ( $requestParams as $param => $value ) {
        $aRequestParams[] = $param . "=" . $value;
    }
    $stringRequestParams = implode( "&", $aRequestParams );
    $redirectUrl = PAYMENT_URL . "?" . $stringRequestParams;
    return ( $redirectUrl );
}

function NexiNotification( $notifica ) {
    // Controllo che ci siano tutti i parametri di ritorno obbligatori per calcolare il MAC
    $requiredParams = array( 'codTrans', 'esito', 'importo', 'divisa', 'codAut', 'mac' );
    foreach ( $requiredParams as $param ) {
        if ( !isset( $_REQUEST[ $param ] ) ) {
            //echo 'Paramentro mancante ' . $field;
            header( "500 Internal Server Error", true, 500 );
            exit;
        }
    }
    // Nel caso in cui non ci siano errori gestisco il parametro esito
    if ( "OK" == $_REQUEST[ 'esito' ] || "KO" == $_REQUEST[ 'esito' ] ) {
        $connection = mysqli_connect( DB_IP, DB_USER, DB_PASSWORD, DB_DBNAME );
        $query_pagamento = sprintf( "SELECT anagrafica.*, pagamento.importo, pagamento.esito, pagamento.`data` FROM pagamento LEFT JOIN anagrafica ON pagamento.Id_a = anagrafica.Id_a WHERE pagamento.codTrans = '%s'", $_REQUEST[ 'codTrans' ] );
        echo $query_pagamento;
        $pagamento = mysqli_query( $connection, $query_pagamento );
        $row_pagamento = mysqli_fetch_assoc( $pagamento );
        $totalRows_pagamento = mysqli_num_rows( $pagamento );
        $answer_pagamento = ( object )array();
        foreach ( $row_pagamento as $key => $value ) {
            if ( $key != "" && $key != NULL && $value != "" && $value != NULL ) {
                $answer_pagamento->$key = $value;
            }
        }
        // connetto al db
        $connection = mysqli_connect( DB_IP, DB_USER, DB_PASSWORD, DB_DBNAME );
        if ( $connection->connect_errno ) {
            trigger_error( "Connessione al server mySQL fallita: (" . $connection->connect_errno . ") " . $connection->connect_error, E_USER_ERROR );
        }
        // preparo lo statement

        if ( !( $stmt = $connection->prepare( "UPDATE pagamento SET esito=?, data=?, nazionalita=?, mac=?, codAut=?, tipoProdotto=?, alias=?, pan=?, brand=?, ora=?, divisa=?, scadenza_pan=?, codiceEsito=?, languageId=?, tipoTransazione=?, codiceConvenzione=?, tipo_richiesta=?, TCONTAB=?  WHERE codTrans=?;" ) ) ) {
            trigger_error( "Prepare failed: (" . $connection->errno . ") " . $connection->error, E_USER_ERROR );
        }
        $data_trans = $_REQUEST[ 'data' ];
        $ora_trans = $_REQUEST[ 'orario' ];  
        // associo i parametri ai placeholder
        if ( !$stmt->bind_param( 'sssssssssssssssssss', $_REQUEST[ 'esito' ], $data_trans, $_REQUEST[ 'nazionalita' ], $_REQUEST[ 'mac' ], $_REQUEST[ 'codAut' ], $_REQUEST[ 'tipoProdotto' ], $_REQUEST[ 'alias' ], $_REQUEST[ 'pan' ], $_REQUEST[ 'brand' ], $ora_trans, $_REQUEST[ 'divisa ' ], $_REQUEST[ 'scadenza_pan' ], $_REQUEST[ 'codiceEsito' ], $_REQUEST[ 'languageId' ], $_REQUEST[ 'tipoTransazione' ], $_REQUEST[ 'codiceConvenzione' ], $_REQUEST[ 'tipo_richiesta' ], $_REQUEST[ 'TCONTAB' ], $_REQUEST[ 'codTrans' ] ) ) {
            trigger_error( "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error, E_USER_ERROR );
        }
        // eseguo la query e chiudo
        if ( !$stmt->execute() ) {
            trigger_error( "Execute failed: (" . $stmt->errno . ") " . $stmt->error, E_USER_ERROR );

        }
        $stmt->close();
        // INIVIO MAIL - INIZIO
        if ("1"==INVIO_MAIL){
            if ($row_pagamento['mail']){ 
                $recipient = $row_pagamento['mail']; //recipient
            }
            else  { $recipient = EMAIL_authenticated_Username; } //recipient
            if ("OK"==$_REQUEST[ 'esito' ] ){
                $subject ="La transazione ha avuto esito postivo";
            } else{
                $subject ="La transazione ha avuto esito negativo";
            }
            $header = "From: ". EMAIL_display_Name . " <" . EMAIL_authenticated_Username . ">\r\n"; //optional headerfields
            $testo_mail = file_get_contents(EMAIL_FOLDER."/".FORM_LANG."/esito_pagamento.html");
            $patterns = array();
            $patterns[0] = '/IMPORTO/';
            $patterns[1] = '/CODTRANS/';
            $patterns[2] = '/CODAUT/';
            $patterns[3] = '/DATI_PERSONALI/';
            $patterns[4] = '/CAUSALE/';
            $patterns[5] = '/NOME/';
            $replacements = array();
            $replacements[0] = $row_pagamento['importo']/100;
            $replacements[1] = $_REQUEST[ 'codTrans' ];
            $replacements[2] = $_REQUEST[ 'codAut' ];

            $replacements[3] =ucwords(utf8_decode(stripslashes($row_pagamento['nome'])))." ". ucwords(utf8_decode(stripslashes($row_pagamento['cognome'])));

            $replacements[4] = "CASA / DESCRIZIONE : " . utf8_decode(stripslashes($row_pagamento['descrizione'])) . "<br>Periodo: " . utf8_decode(stripslashes($row_pagamento['periodo']));
            $replacements[5] =  ucwords(utf8_decode($row_pagamento['nome']));
            $testo_mail_modificato = preg_replace($patterns, $replacements, $testo_mail);
            require_once('class/PHPMailerAutoload.php');
            // Nuova gestione e-mail
            $mail             = new PHPMailer();
            //$mail->SingleTo = true;
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                                       // 1 = errors and messages
                                                       // 2 = messages only
            $mail->SMTPSecure = EMAIL_SMTPSecure; 
            $mail->SMTPAuth   = EMAIL_SMTP_auth;                  // enable SMTP authentication
            $mail->Host       = EMAIL_host; // sets the SMTP server
            $mail->Port       = EMAIL_host_port;                    // set the SMTP port for the GMAIL server
            $mail->Username   = EMAIL_authenticated_Username; // SMTP account username
            $mail->Password   = EMAIL_authenticated_Psw;        // SMTP account password
            $mail->SetFrom(EMAIL_authenticated_Username, EMAIL_display_Name);
            $mail->AddReplyTo(EMAIL_authenticated_Username,EMAIL_display_Name);
            //$mail->AddCC("",$Name);
            //$mail->AddBCC("");
            $mail->Subject    = $subject;
            $mail->IsHTML(true); // send as HTML
            $mail->AltBody= "Se non leggi clicca qui".
            $mail->Body = $testo_mail_modificato;
            $mail->AddAddress($recipient, "");// Produzione
            /*
            $mail->AddAttachment("images/phpmailer.gif");      // attachment
            $mail->AddAttachment("images/phpmailer_mini.gif"); // attachment*/
            //$mail->AddAttachment("formiscrizioni.JPG");
            $mail->Send();        
            /*if(!$mail->Send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
             } else {
                echo "Message has been sent";
             }*/
            // INVIO MAIL - FINE
        }
        return ( $_REQUEST[ 'esito' ] );
    }
}

//Inizio script 
if ( DEBUG_AA == true ) {
    error_log( date( '[Y-m-d H:i e] ' ) . "JSON chiamata : " . $query_json . PHP_EOL, 3, LOG_FILE ); //DEBUG_AA
}
/*
// Per l'uso con JSON
$query_stream = json_decode( $query_json, true ); 
$query_action = (object) array(); // Azione richiesta la WebService
foreach($query_stream as $key => $value){
    if ($key!="" && $key!= NULL && $value!="" && $value != NULL){$query_action->$key=$value ;}
}
*/
$query_data = ( object )array();
//foreach($query_action->data as $key => $value){//Per l'uso con JSON 
foreach ( $_REQUEST as $key => $value ) { // Per l'uso con GET/POST
    if ( $key != "" && $key != NULL && $value != "" && $value != NULL ) {
        $query_data->$key = trim( utf8_decode( $value ) );
    }
}

if ( !isset( $query_data->req_fields ) || trim( $query_data->req_fields ) == "" ) {
    $query_data->req_fields = REQ_FIELDS_DEFAULT; // Se non sono imposti i campi richiesti li valorizza con quelli di defult
}
//METODI

if ( $query_data->operation == "do" && $query_data->param == "transaction" ) {
    if ( DEBUG_AA == true ) {
        error_log( date( '[Y-m-d H:i e] ' ) . "REQUEST pagamento ONEOFF" . PHP_EOL, 3, LOG_FILE ); //DEBUG_AA
    }
    //1 Scrivo l'anagrafica in mysql
    $id_anagrafica = call_user_func_array( 'scriviAnagrafica_mysql', array( $query_data ) ); // Scrivo l'anagrafica in mysql
    if ( is_numeric( $id_anagrafica[ 0 ] ) ) {
        $_return_query[ 'anagrafica' ] = $id_anagrafica[ 0 ];
        $_return_query[ 'anagrafica_esito' ] = "OK";
        $risposta_ana[ 'Esito_mysql' ] = "OK";
        $risposta_ana[ 'Messaggio_mysql' ] = "&Egrave; stata scritta in MYSQL l'anagrafica " . $id_anagrafica[ 0 ];
        $risposta_ana[ 'id_anagrafica_mysql' ] = $id_anagrafica[ 0 ];
        $query_data->Id_a = $id_anagrafica[ 0 ]; // Aggiungo l'id mysql dell'anagrafia a query_data
        //2 Scrivo pagamento in mysql con id anagrfica (1) (Se ho scritto l'anagrafica)
        $id_pagamento = call_user_func_array( 'Scrivipagamento_mysql', array( $query_data ) );
        if ( preg_match( "/^[A-Z]{1}-[0-9]{17}-[A-Z]{2}/", $id_pagamento ) ) { //Verifico codice transazione (formato T-20170701608524665-PP)
            $_return_query[ 'pagamento' ] = $id_pagamento;
            $_return_query[ 'pagamento_esito' ] = "OK";
            $risposta_pag[ 'Esito_mysql' ] = "OK";
            $risposta_pag[ 'Messaggio_mysql' ] = "&Egrave; stata scritta in MYSQL il pagamento " . $id_pagamento;
            $risposta_pag[ 'codTrans' ] = $id_pagamento;
            $query_data->codTrans = $id_pagamento; // Aggiungo il codice di transazione a query_data
            if ( $query_data->pay_method === "CC" ) { //Pagamanto con carta di credito
                //3 Effettuo la transazione su NEXI con il codice transazione (2) e i dati del donatore (1)
                $redirect_NEXI = call_user_func_array( 'GoToPOS', array( $query_data ) );
               // echo $redirect_NEXI;
                header( "Location: $redirect_NEXI" );
                exit;
                //echo $redirect_NEXI."<br>";
            } elseif ( $query_data->pay_method === "PP" ) { //pagamento con PayPal
                // PER SVILUPPO FUTURO	
            }
            else {
                $risposta_trans[ 'Esito_trans' ] = "Altro";
                $risposta_trans[ 'Messaggio_trans' ] = "Il sistema di pagamnto non &grave; supportato ";
                $risposta_trans[ 'URL_trans' ] = "";
                $risposta[ 'Transazione' ] = $risposta_trans;
            }
        } else { //errore scrittura promessa di pagamento in mysql
            $_return_query[ 'pagamento' ] = $id_pagamento;
            $_return_query[ 'pagamento_esito' ] = "KO";
            $risposta_pag[ 'Esito_mysql' ] = "KO";
            $risposta_pag[ 'Messaggio_mysql' ] = "Si &egrave; verificato un errore nella scrittura in MYSQL " . $id_pagamento;
            $risposta_pag[ 'CodTrans' ] = $id_pagamento;
        }
        $risposta[ 'pagamento' ] = $risposta_pag;
        //
    } else { //errore scrittura anagrafica in mysql
        $_return_query[ 'anagrafica_esito' ] = "KO";
        $risposta_ana[ 'Esito_mysql' ] = "KO";
        $risposta_ana[ 'Messaggio_mysql' ] = "Si &egrave; verificato un errore nella scrittura in MYSQL " . $id_anagrafica[ 0 ];
        $risposta_ana[ 'id_anagrafica_mysql' ] = $id_anagrafica[ 0 ];
    }
    $risposta[ 'Anagrafica' ] = $risposta_ana;
    $risposta_string = json_encode( $risposta );
    $url_error = FORM . "?error=Y";
    foreach ( $_return_query as $key => $value ) {
        if ( $key != "" && $key != NULL && $value != "" && $value != NULL ) {
            $url_error .= "&" . $key . "=" . $value;
        }
    }
    header( "Location: $url_error" );
    exit;
    //ESITO - FINE
    //Codice del WS - FINE
} 
elseif(isset($query_data->mac) && (isset($query_data->alias) &&  ALIAS == $query_data->alias ) ){ //NOTIFICATION NEXI
    // Calcolo MAC con i parametri di ritorno
    $macCalculated = sha1( 'codTrans=' . $_REQUEST[ 'codTrans' ] .
        'esito=' . $_REQUEST[ 'esito' ] .
        'importo=' . $_REQUEST[ 'importo' ] .
        'divisa=' . $_REQUEST[ 'divisa' ] .
        'data=' . $_REQUEST[ 'data' ] .
        'orario=' . $_REQUEST[ 'orario' ] .
        'codAut=' . $_REQUEST[ 'codAut' ] . MAC_KEY
    );
    // Verifico corrispondenza tra MAC calcolato e MAC di ritorno
    if ( $macCalculated != $_REQUEST[ 'mac' ] ) {
        //echo 'Errore MAC: ' . $macCalculated . ' non corrisponde a ' . $_REQUEST[ 'mac' ];
        header( '500 Internal Server Error', true, 500 );
        exit;
    } else {
        $transaction = call_user_func_array( 'NexiNotification', array( $query_data ) ); // Scrivo l'anagrafica in mysql
        if ( $transaction ) { //esito notifica
            header( $transaction . ', pagamento avvenuto, preso riscontro', true, 200 );
        }

    }
}
else {
    //Resto del mondo
}

?>