/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`pmanager` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `pmanager`;

/*Table structure for table `projetos` */

DROP TABLE IF EXISTS `projetos`;

CREATE TABLE `projetos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do projeto.',
  `titulo` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Título do projeto.',
  `data` date DEFAULT NULL COMMENT 'Data de previsão de entrega do projeto.',
  `desc` varchar(300) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Pequena descrição do projeto.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `tarefas` */

DROP TABLE IF EXISTS `tarefas`;

CREATE TABLE `tarefas` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID da tarefa.',
  `pid` int(11) DEFAULT NULL COMMENT 'ID do projeto.',
  `titulo` varchar(200) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Título da tarefa.',
  `desc` varchar(300) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Descrição da tarefa.',
  PRIMARY KEY (`id`),
  KEY `Tarefa-Projeto` (`pid`),
  CONSTRAINT `Tarefa-Projeto` FOREIGN KEY (`pid`) REFERENCES `projetos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
