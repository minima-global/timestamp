CREATE TABLE IF NOT EXISTS `timestampdata` (
  `idtimestampdata` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(128) NOT NULL,
  `datecreated` datetime DEFAULT current_timestamp(),
  `datahash` varchar(128) NOT NULL,
  `roottreehash` varchar(128) DEFAULT '0x00',
  `proof` varchar(10000) DEFAULT '0x00',
  `hasbeenadded` int(11) DEFAULT 0,
  `datestamped` datetime DEFAULT NULL,
  PRIMARY KEY (`idtimestampdata`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
