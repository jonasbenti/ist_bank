/*
SQLyog Enterprise v12.13 (64 bit)
MySQL - 5.7.20 : Database - ist
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ist` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `ist`;

/*Table structure for table `contas` */

DROP TABLE IF EXISTS `contas`;

CREATE TABLE `contas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pessoas_id` int(11) unsigned NOT NULL COMMENT 'id pessoa',
  `numero_conta` varchar(6) NOT NULL,
  `qtd_movimentacoes` int(11) DEFAULT '0',
  `saldo` decimal(20,2) DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_pessoas_id` (`pessoas_id`),
  CONSTRAINT `fk_pessoas_id` FOREIGN KEY (`pessoas_id`) REFERENCES `pessoas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `movimentacoes` */

DROP TABLE IF EXISTS `movimentacoes`;

CREATE TABLE `movimentacoes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `contas_id` int(11) unsigned DEFAULT NULL,
  `valor` decimal(20,2) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_contas_id` (`contas_id`),
  CONSTRAINT `fk_contas_id` FOREIGN KEY (`contas_id`) REFERENCES `contas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Table structure for table `pessoas` */

DROP TABLE IF EXISTS `pessoas`;

CREATE TABLE `pessoas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL DEFAULT '',
  `cpf` varchar(11) NOT NULL DEFAULT '',
  `endereco` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/* Trigger structure for table `movimentacoes` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `atualiza_dados_conta_i` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `atualiza_dados_conta_i` AFTER INSERT ON `movimentacoes` FOR EACH ROW BEGIN
    
    DECLARE total int(11);  
    DECLARE total_transacoes decimal(10,2); 
    
    SELECT SUM(valor), COUNT(*) into total_transacoes, total 
    FROM ist.movimentacoes WHERE contas_id = NEW.contas_id;
   
    
	UPDATE 
	  ist.contas 
	SET
	   saldo = total_transacoes,
	   qtd_movimentacoes = total
	WHERE id = NEW.contas_id ;
	END */$$


DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
