/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dump della struttura di tabella anagrafica
CREATE TABLE IF NOT EXISTS `anagrafica` (
  `Id_a` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(80) NOT NULL DEFAULT 'anonimo',
  `cognome` varchar(80) NOT NULL DEFAULT 'anonimo',
  `sesso` enum('M','F') DEFAULT 'M',
  `indirizzo` varchar(255) DEFAULT '',
  `civico` varchar(10) DEFAULT NULL,
  `cap` varchar(8) DEFAULT NULL,
  `citta` varchar(100) DEFAULT NULL,
  `provincia` varchar(2) DEFAULT NULL,
  `stato` varchar(80) DEFAULT NULL,
  `tel` varchar(25) DEFAULT NULL,
  `mail` varchar(120) DEFAULT NULL,
  `codFis` varchar(16) DEFAULT NULL,
  `privacy` enum('Y','N') NOT NULL DEFAULT 'N',
  `IP` varchar(15) DEFAULT NULL,
  `lang` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`Id_a`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- L’esportazione dei dati non era selezionata.
-- Dump della struttura di tabella pagamento
CREATE TABLE IF NOT EXISTS `pagamento` (
  `codTrans` varchar(30) NOT NULL DEFAULT 'P-2019',
  `Id_a` int(11) NOT NULL DEFAULT '0',
  `importo` varchar(9) DEFAULT '000000500',
  `descrizione` varchar(255) DEFAULT NULL,
  `periodo` varchar(255) DEFAULT NULL,
  `esito` enum('OK','KO','WA') NOT NULL DEFAULT 'WA',
  `data` date NOT NULL DEFAULT '2019-01-01',
  `ora` time NOT NULL DEFAULT '00:00:00',
  `IP` varchar(15) NOT NULL DEFAULT '0.0.0.0',
  `nazionalita` varchar(3) DEFAULT NULL,
  `mac` varchar(64) DEFAULT NULL,
  `codAut` varchar(6) DEFAULT NULL,
  `tipoProdotto` varchar(64) DEFAULT NULL,
  `alias` varchar(20) DEFAULT NULL,
  `pan` varchar(24) DEFAULT NULL,
  `brand` varchar(24) DEFAULT NULL,
  `divisa` varchar(18) DEFAULT NULL,
  `scadenza_pan` varchar(6) DEFAULT NULL,
  `codiceEsito` varchar(8) DEFAULT NULL,
  `languageId` varchar(3) DEFAULT NULL,
  `tipoTransazione` varchar(18) DEFAULT NULL,
  `codiceConvenzione` varchar(12) DEFAULT NULL,
  `tipo_richiesta` varchar(4) DEFAULT NULL,
  `TCONTAB` varchar(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- L’esportazione dei dati non era selezionata.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
