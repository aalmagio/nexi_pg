<?php
/*
V 20190126.1 basic configuration NON WORKING: just set up function without relataion and without any call.
*/
require( 'inc/config.inc.php' );
require( 'inc/data.inc.php' );
define( "LOG_FILE", "./eme_WS.log" ); //Togliere in Produzione
function checkCC( $number ) {
    //https://stackoverflow.com/questions/174730/what-is-the-best-way-to-validate-a-credit-card-in-php
    $number = preg_replace( '/\D/', '', $number );
    $supportedCircuit = "KO";
    $Circuit = "Nessuno";
    if ( substr( $number, 0, 2 ) == 30 || substr( $number, 0, 2 ) == 36 || substr( $number, 0, 2 ) == 38 ) {
        $supportedCircuit = "KO";
        $Circuit = "Diners";
    } elseif ( substr( $number, 0, 2 ) == 34 || substr( $number, 0, 2 ) == 37 ) {
            $supportedCircuit = "OK";
            $Circuit = "American Express";
        }
        /*elseif(substr($number,0,4)==4539){
        	$supportedCircuit = "OK";
        	$Circuit = "CartaSI";
        }*/
    elseif ( substr( $number, 0, 2 ) >= 40 && substr( $number, 0, 2 ) <= 49 ) {
        $supportedCircuit = "OK";
        $Circuit = "CartaSI";
    }
    elseif ( substr( $number, 0, 2 ) >= 51 && substr( $number, 0, 2 ) <= 55 ) {
        $supportedCircuit = "OK";
        $Circuit = "MasterCard";
    }
    elseif ( substr( $number, 0, 4 ) >= 2221 && substr( $number, 0, 4 ) <= 2720 ) {
        $supportedCircuit = "OK";
        $Circuit = "MasterCard";
    }
    else {
        $supportedCircuit = "KO";
        $Circuit = "Circuito non supportato";
    }
    // Set the string length and parity
    $number_length = strlen( $number );
    $parity = $number_length % 2;
    // Loop through each digit and do the maths
    $total = 0;
    for ( $i = 0; $i < $number_length; $i++ ) {
        $digit = $number[ $i ];
        // Multiply alternate digits by two
        if ( $i % 2 == $parity ) {
            $digit *= 2;
            // If the sum is two digits, add them together (in effect)
            if ( $digit > 9 ) {
                $digit -= 9;
            }
        }
        // Total up the digits
        $total += $digit;
    }
    if ( $total % 10 === 0 ) {
        return array( "", $Circuit, $supportedCircuit );
    } else {
        return array( "E027|", $Circuit, $supportedCircuit );
    }
}

function ValidaAnagrafica( $anagrafica ) {
    $req_fields = explode( ",", $anagrafica->req_fields );
    //Gestione variabili obbligatorie 
    //Nel db i campi che non possono essere NULL sono: Id_a, privacy, data_ins, tipo_ana 
    $errore = 0;
    $messaggio_errore = "";
    if ( $anagrafica->sesso != "S" && ( !isset( $anagrafica->nome ) || trim( $anagrafica->nome ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M001|";
    }
    //elseif (!preg_match("/^[a-zA-Z ]*$/",$anagrafica->nome)) { $errore++ ; $messaggio_errore .= "E001|";} 
    if ( $anagrafica->sesso != "S" && ( !isset( $anagrafica->cognome ) || trim( $anagrafica->cognome ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M002|";
    }
    //elseif (!preg_match("/^[a-zA-Z ]*$/",$anagrafica->cognome)) { $errore++ ; $messaggio_errore .= "E002|";} 
    if ( $anagrafica->sesso == "S" && ( !isset( $anagrafica->ragioneSociale ) || trim( $anagrafica->ragioneSociale ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M003|";
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
    if ( ( $anagrafica->pay_method == "SP" || $anagrafica->pay_method == "SD" ) && ( !isset( $anagrafica->codFis ) || trim( $anagrafica->codFis ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M014|";
    } //Togliere se CF non obbligatorio per SDD
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
    if ( in_array( "id_fonte", $req_fields ) && ( !isset( $anagrafica->id_fonte ) || trim( $anagrafica->id_fonte ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M034|";
    }
    if ( in_array( "id_campagna", $req_fields ) && ( !isset( $anagrafica->id_campagna ) || trim( $anagrafica->id_campagna ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M035|";
    }
    if ( !isset( $anagrafica->IP ) || trim( $anagrafica->IP ) == "" ) {
        $errore++;
        $messaggio_errore .= "M057|";
    }
    if ( !isset( $anagrafica->tipo_ana ) || trim( $anagrafica->tipo_ana ) == "" ) {
        $errore++;
        $messaggio_errore .= "M019|";
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
    //Imposto a vuoto i campi eventualmente NON presnti nel form - INIZIO
    if ( !isset( $anagrafica->codFis ) || trim( $anagrafica->codFis ) == "" ) {
        $anagrafica->codFis = "";
    }
    if ( !isset( $anagrafica->PIVA ) || trim( $anagrafica->PIVA ) == "" ) {
        $anagrafica->PIVA = "";
    }
    if ( !isset( $anagrafica->cap ) || trim( $anagrafica->cap ) == "" ) {
        $anagrafica->cap = "";
    }
    if ( !isset( $anagrafica->citta ) || trim( $anagrafica->citta ) == "" ) {
        $anagrafica->citta = "";
    }
    if ( !isset( $anagrafica->provincia ) || trim( $anagrafica->provincia ) == "" ) {
        $anagrafica->provincia = "";
    }
    if ( !isset( $anagrafica->indirizzo ) || trim( $anagrafica->indirizzo ) == "" ) {
        $anagrafica->indirizzo = "";
    }
    if ( !isset( $anagrafica->civico ) || trim( $anagrafica->civico ) == "" ) {
        $anagrafica->civico = "";
    }
    if ( !isset( $anagrafica->tel ) || trim( $anagrafica->tel ) == "" ) {
        $anagrafica->tel = "";
    }
    if ( !isset( $anagrafica->stato ) || trim( $anagrafica->stato ) == "" ) {
        $anagrafica->stato = "";
    }
    //Imposto a vuoto i campi NON eventualmente presnti nel form - FINE
    //
    //Imposto con valore di default i campi eventualmente NON presnti nel form - INIZIO
    if ( !in_array( "sesso", $req_fields ) && ( !isset( $anagrafica->sesso ) || trim( $anagrafica->sesso ) == "" ) ) {
        $anagrafica->sesso = "X";
    } //X =Daverificare 
    if ( !isset( $anagrafica->privacy ) || trim( $anagrafica->privacy ) == "" ) {
        $anagrafica->privacy = "Y";
    } //TOGLIERE IN PRODUZIONE
    if ( !in_array( "id_fonte", $req_fields ) && ( !isset( $anagrafica->id_fonte ) || trim( $anagrafica->id_fonte ) == "" ) )$anagrafica->id_fonte = ID_FONTE_DEFAULT;
    if ( !in_array( "id_campagna", $req_fields ) && ( !isset( $anagrafica->id_campagna ) || trim( $anagrafica->id_campagna ) == "" ) )$anagrafica->id_campagna = ID_CAMPAGNA_DEFAULT;
    if ( !isset( $anagrafica->IP ) || trim( $anagrafica->IP ) == "" )$anagrafica->IP = $_SERVER[ 'REMOTE_ADDR' ];
    if ( !isset( $anagrafica->tipo_ana ) || trim( $anagrafica->tipo_ana ) == "" )$anagrafica->tipo_ana = "09";
    if ( !isset( $anagrafica->lang ) || trim( $anagrafica->lang ) == "" )$anagrafica->lang = "it";
    //Se Tessera
    if ( $anagrafica->centro == TESSERA_COD ) { //TESSERA X SE
        $anagrafica->tipo_ana = "10";
    }
    //Imposto a valore di default i campi eventualmente NON presnti nel form - FINE
    $chk_anagrafica = call_user_func_array( 'ValidaAnagrafica', array( $anagrafica ) ); // Vlaido l'anagrafica prima di scrivere in mysql
    if ( $chk_anagrafica[ 0 ] <> 0 ) {
        return array( $chk_anagrafica[ 1 ], "" );
    } else {
        // connetto al db
        $connection = mysqli_connect( E_DB_IP, E_DB_USER, E_DB_PASSWORD, E_DB_DBNAME );
        if ( $connection->connect_errno ) {
            trigger_error( "Connessione al server mySQL fallita: (" . $connection->connect_errno . ") " . $connection->connect_error, E_USER_ERROR );
        }
        // preparo lo statement
        if ( !( $stmt = $connection->prepare( "INSERT INTO Anagrafica (nome, cognome, ragioneSociale, sesso, indirizzo, civico, cap, citta, provincia, stato, tel, mail, codFis, datanascita, privacy, id_fonte, id_campagna, IP, tipo_ana, operazione,lang ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,? )" ) ) ) {
            trigger_error( "Prepare failed: (" . $connection->errno . ") " . $connection->error, E_USER_ERROR );
        }
        // associo i parametri ai placeholder
        if ( !$stmt->bind_param( 'sssssssssssssssisssss', $anagrafica->nome, $anagrafica->cognome, $anagrafica->ragioneSociale, $anagrafica->sesso, $anagrafica->indirizzo, $anagrafica->civico, $anagrafica->cap, $anagrafica->citta, strtoupper( $anagrafica->provincia ), $anagrafica->stato, $anagrafica->tel, $anagrafica->mail, $anagrafica->codFis, $anagrafica->mysqldatanascita, $anagrafica->privacy, $anagrafica->id_fonte, $anagrafica->id_campagna, $anagrafica->IP, $anagrafica->tipo_ana, $anagrafica->tipo_pagamento, $anagrafica->lang ) ) {
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
        //Inserire codice con desinenze specifiche //DA SVILIUPPARE 
        if ( $pagamento->centro == TESSERA_COD ) { //TESSERA X SE
            $pagamento->codTrans = "D-" . $date . substr( $date_array[ 0 ], 2, 2 ) . "-DT";
            $pagamento->tessera = "Y";
        } else {
            $pagamento->codTrans = "D-" . $date . substr( $date_array[ 0 ], 2, 2 ) . "-DD";
        }
    }
    if ( !in_array( "causale", $req_fields ) && ( !isset( $pagamento->causale ) || trim( $pagamento->causale ) == "" ) ) {
        $pagamento->causale = "pagamento Libera";
    }
    if ( !isset( $pagamento->tessera ) || trim( $pagamento->tessera ) == "" ) {
        $pagamento->tessera = "N";
    }
    if ( !isset( $pagamento->esito ) || trim( $pagamento->esito ) == "" ) {
        $pagamento->esito = "WA";
    }
    if ( !in_array( "centro", $req_fields ) && ( !isset( $pagamento->centro ) || trim( $pagamento->centro ) == "" ) ) {
        $pagamento->centro = CENTRO_DEFAULT;
    }
    //Se il campo e' vuoto posso valorizzarlo con un valore di defalut
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
    } elseif ( !preg_match( "/^[0-9]*$/", $pagamento->importo ) ) {
        $errore++;
        $messaggio_errore .= "E021|";
    }
    if ( $pagamento->tipo_pagamento == "oneoff" && defined( 'IMPORTO_MINIMO_ONE' ) && $pagamento->importo < IMPORTO_MINIMO_ONE ) {
        $errore++;
        $messaggio_errore .= "E021|";
    }
    if ( $pagamento->tipo_pagamento == "regular" ) {
        $importo_annuo = 12 / $pagamento->frequenza * $pagamento->importo; //Importo Annuo
        if ( defined( 'IMPORTO_MINIMO_REG' ) && $importo_annuo < IMPORTO_MINIMO_REG ) {
            $errore++;
            $messaggio_errore .= "E021|";
        }
    }
    //Id_a e importo sono sempre obbligatori per la doanzione per cui non verifico nemmeno se sono in reqfield
    if ( in_array( "pay_method", $req_fields ) && ( !isset( $pagamento->pay_method ) || trim( $pagamento->pay_method ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M056|";
    }
    if ( isset( $pagamento->pay_method ) && $pagamento->pay_method == "CC" ) { //Verifico la carta di credito
        $verifyCard = checkCC( $pagamento->cartan );
        if ( !isset( $pagamento->cartan ) || trim( $pagamento->cartan ) == "" ) {
            $errore++;
            $messaggio_errore .= "M027|";
        } elseif ( $verifyCard[ 0 ] != "" ) {
                $errore++;
                $messaggio_errore .= "E027|";
            }
            //if ($verifyCard[0]=="" && $verifyCard[2] =="KO") {$errore++; $messaggio_errore .= "Circuito NON supportato";}
        if ( !isset( $pagamento->exp_mm ) || trim( $pagamento->exp_mm ) == "" ) {
            $errore++;
            $messaggio_errore .= "M028|";
        } elseif ( !preg_match( "/^(0[1-9]|1[012])$/", $pagamento->exp_mm ) ) {
            $errore++;
            $messaggio_errore .= "E028|";
        }
        if ( !isset( $pagamento->exp_yy ) || trim( $pagamento->exp_yy ) == "" ) {
            $errore++;
            $messaggio_errore .= "M029|";
        } elseif ( !preg_match( "/^[0-9]{2}$/", $pagamento->exp_yy ) ) {
            $errore++;
            $messaggio_errore .= "E029|";
        }
    }
    if ( in_array( "causale", $req_fields ) && ( !isset( $pagamento->causale ) || trim( $pagamento->causale ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M026|";
    }
    if ( in_array( "nota", $req_fields ) && ( !isset( $pagamento->nota ) || trim( $pagamento->nota ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M022|";
    }
    if ( in_array( "centro", $req_fields ) && ( !isset( $pagamento->centro ) || trim( $pagamento->centro ) == "" ) ) {
        $errore++;
        $messaggio_errore .= "M024";
    }
    if ( $errore > 0 ) {
        return $messaggio_errore;
    }
    //
    else {
        // connetto al db
        $connection = mysqli_connect( E_DB_IP, E_DB_USER, E_DB_PASSWORD, E_DB_DBNAME );
        if ( $connection->connect_errno ) {
            trigger_error( "Connessione al server mySQL fallita: (" . $connection->connect_errno . ") " . $connection->connect_error, E_USER_ERROR );
        }
        // preparo lo statement
        if ( !( $stmt = $connection->prepare( "INSERT INTO pagamento (codTrans,Id_a,importo,pay_method,causale,nota,tessera,tipotessera,esito,centro,tipo) VALUES (?,?,?,?,?,?,?,?,?,?,?)" ) ) ) {
            trigger_error( "Prepare failed: (" . $connection->errno . ") " . $connection->error, E_USER_ERROR );
        }
        // associo i parametri ai placeholder
        if ( !$stmt->bind_param( 'sidssssssis', $pagamento->codTrans, $pagamento->Id_a, $pagamento->importo, $pagamento->pay_method, $pagamento->causale, $pagamento->nota, $pagamento->tessera, $pagamento->tipoTessera, $pagamento->esito, $pagamento->centro, $pagamento->tipo_pagamento ) ) {
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

function aggiornaAnagrafica_mysql( $anagrafica ) {
    //connetto al db
    if ( $anagrafica->centro == TESSERA_COD ) { //TESSERA X SE
        $anagrafica->tipo_ana = "10";
    } else {
        $anagrafica->tipo_ana = "09";
    }
    $connection = mysqli_connect( E_DB_IP, E_DB_USER, E_DB_PASSWORD, E_DB_DBNAME );
    if ( $connection->connect_errno ) {
        trigger_error( "Connessione al server mySQL fallita: (" . $connection->connect_errno . ") " . $connection->connect_error, E_USER_ERROR );
    }
    // preparo lo statement
    if ( !( $stmt = $connection->prepare( "UPDATE Anagrafica SET ID_Mentor=?, tipo_ana=?, sesso=? WHERE Id_a=?;" ) ) ) {
        trigger_error( "Prepare failed: (" . $connection->errno . ") " . $connection->error, E_USER_ERROR );
    }
    // associo i parametri ai placeholder
    if ( !$stmt->bind_param( 'sssi', $anagrafica->codiceAnagraficaMentor, $anagrafica->tipo_ana, $anagrafica->SessoMentor, $anagrafica->Id_a ) ) {
        trigger_error( "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error, E_USER_ERROR );
    }
    // eseguo la query e chiudo
    if ( !$stmt->execute() ) {
        trigger_error( "Execute failed: (" . $stmt->errno . ") " . $stmt->error, E_USER_ERROR );
    }
    $stmt->close();
}

function aggiornapagamentoCodMentor_mysql( $pagamento ) {
    // connetto al db
    $connection = mysqli_connect( E_DB_IP, E_DB_USER, E_DB_PASSWORD, E_DB_DBNAME );
    if ( $connection->connect_errno ) {
        trigger_error( "Connessione al server mySQL fallita: (" . $connection->connect_errno . ") " . $connection->connect_error, E_USER_ERROR );
    }
    // preparo lo statement
    if ( !( $stmt = $connection->prepare( "UPDATE pagamento SET CodiceMentor=? WHERE codTrans=?;" ) ) ) {
        trigger_error( "Prepare failed: (" . $connection->errno . ") " . $connection->error, E_USER_ERROR );
    }
    // associo i parametri ai placeholder
    if ( !$stmt->bind_param( 'ss', $pagamento->codicepagamentoMentor, $pagamento->codTrans ) ) {
        trigger_error( "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error, E_USER_ERROR );
    }
    // eseguo la query e chiudo
    if ( !$stmt->execute() ) {
        trigger_error( "Execute failed: (" . $stmt->errno . ") " . $stmt->error, E_USER_ERROR );
    }
    $stmt->close();
}

function LeggiDati_mysql( $richiesta ) {
    if ( isset( $richiesta->codTrans ) && trim( $richiesta->codTrans ) != "" && preg_match( "/^[A-Z]{1}-[0-9]{17}-[A-Z]{2}/", $richiesta->codTrans ) ) { // Query su codice transazione
        $connection = mysqli_connect( E_DB_IP, E_DB_USER, E_DB_PASSWORD, E_DB_DBNAME )or trigger_error( mysqli_error(), E_USER_ERROR );
        //mysql_select_db(E_DB_DBNAME, $connection);
        $query_pagamento = sprintf( "SELECT Anagrafica.*, pagamento.importo, pagamento.pay_method, pagamento.tessera, pagamento.tipotessera, pagamento.esito, pagamento.`data`, pagamento.tipo FROM pagamento LEFT JOIN Anagrafica ON pagamento.Id_a = Anagrafica.Id_a WHERE pagamento.codTrans = '%s'", $richiesta->codTrans );
        $pagamento = mysqli_query( $connection, $query_pagamento )or die( mysqli_error() );
        $row_pagamento = mysqli_fetch_assoc( $pagamento );
        $totalRows_pagamento = mysqli_num_rows( $pagamento );
        $answer_pagamento = ( object )array();
        foreach ( $row_pagamento as $key => $value ) {
            if ( $key != "" && $key != NULL && $value != "" && $value != NULL ) {
                $answer_pagamento->$key = $value;
            }
        }
        return ( $answer_pagamento );
    } elseif ( isset( $richiesta->Id_a ) && trim( $richiesta->Id_a ) != "" && is_numeric( $richiesta->Id_a ) ) { // Query su codice anagrafica
        $connection = mysqli_connect( E_DB_IP, E_DB_USER, E_DB_PASSWORD, E_DB_DBNAME )or trigger_error( mysqli_error(), E_USER_ERROR );
        //mysql_select_db(E_DB_DBNAME, $connection);
        $query_anagrafica = sprintf( "SELECT * FROM Anagrafica WHERE Id_a = %s", $richiesta->Id_a );
        $anagrafica = mysqli_query( $connection, $query_anagrafica )or die( mysqli_error() );
        $row_anagrafica = mysqli_fetch_assoc( $anagrafica );
        $totalRows_anagrafica = mysqli_num_rows( $anagrafica );
        $answer_pagamento = ( object )array();
        foreach ( $row_anagrafica as $key => $value ) {
            if ( $key != "" && $key != NULL && $value != "" && $value != NULL ) {

                $answer_pagamento->$key = $value;
            }
        }
        $query_pagamento = sprintf( "SELECT pagamento.codTrans, pagamento.importo, pagamento.pay_method, pagamento.esito, pagamento.`data` FROM pagamento WHERE pagamento.Id_a = '%s'", $row_anagrafica[ 'Id_a' ] );
        $pagamento = mysqli_query( $connection, $query_pagamento )or die( mysqli_error() );
        $row_pagamento = mysqli_fetch_assoc( $pagamento );
        $totalRows_pagamento = mysqli_num_rows( $pagamento );
        if ( $totalRows_pagamento == 1 ) {
            foreach ( $row_pagamento as $key => $value ) {
                if ( $key != "" && $key != NULL && $value != "" && $value != NULL ) {
                    $answer_pagamento->$key = $value;
                }
            }
        }
        return ( $answer_pagamento );
    }
}

function GoToPOS( $ordine ) {
    //$codTrans = "TESTPS_" . date('YmdHis');
    //$importo = 5000;
    // Calcolo MAC
    if ( 1 == LANGUAGE_MANAGER ) {
        $languageId = FORM_LANG;
    } elseif ( isset( $ordine->ln )AND "" != trim( $ordine->ln ) ) {
        $languageId = $ordine->ln;
    }
    else {
        $languageId = DEFAULT_LN;
    }
    $mac = sha1( 'codTrans=' . $ordine->codTrans . 'divisa=' . CURRENCY . 'importo=' . $ordine->importo . MAC_KEY );
    // Parametri obbligatori
    $obbligatori = array(
        'alias' => ALIAS,
        'importo' => $ordine->importo,
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
    $requiredParams = array( 'codTrans', 'esito', 'importo', 'divisa', 'data', 'orario', 'codAut', 'mac' );
    foreach ( $requiredParams as $param ) {
        if ( !isset( $_REQUEST[ $param ] ) ) {
            echo 'Paramentro mancante ' . $field;
            header( '500 Internal Server Error', true, 500 );
            exit;
        }
    }
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
        echo 'Errore MAC: ' . $macCalculated . ' non corrisponde a ' . $_REQUEST[ 'mac' ];
        header( '500 Internal Server Error', true, 500 );
        exit;
    }
    // Nel caso in cui non ci siano errori gestisco il parametro esito
    if ( $_REQUEST[ 'esito' ] == 'OK' ) {
        header( 'OK, pagamento avvenuto, preso riscontro', true, 200 );
    } else {
        header( 'KO, pagamento non avvenuto, preso riscontro', true, 200 );
    }
}
?>