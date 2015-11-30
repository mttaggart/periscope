<?php

$table_queries = ["CREATE TABLE IF NOT EXISTS `Activities` (
    `ACT_ID` int(11) NOT NULL AUTO_INCREMENT,
    `U_ID` int(11) NOT NULL,
    `Text` varchar(255) DEFAULT NULL,
    `Rank` tinyint(4) NOT NULL DEFAULT '0',
    PRIMARY KEY (`ACT_ID`),
    KEY `U_ID` (`U_ID`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2266 ;",
    
    "CREATE TABLE IF NOT EXISTS `Assessments` (
    `ASS_ID` int(11) NOT NULL AUTO_INCREMENT,
    `U_ID` int(11) NOT NULL,
    `Text` varchar(255) DEFAULT NULL,
    `AT_ID` int(11) NOT NULL,
    `Rank` tinyint(4) NOT NULL DEFAULT '0',
    PRIMARY KEY (`ASS_ID`),
    KEY `U_ID` (`U_ID`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1783 ;",
    
   "CREATE TABLE IF NOT EXISTS `AssessmentTypes` (
   `AT_ID` int(11) NOT NULL AUTO_INCREMENT,
   `AT_Text` varchar(30) NOT NULL,
    PRIMARY KEY (`AT_ID`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;",
    
   "CREATE TABLE IF NOT EXISTS `Content` (
   `CON_ID` int(11) NOT NULL AUTO_INCREMENT,
   `U_ID` int(11) NOT NULL,
   `Text` varchar(255) NOT NULL,
   `Rank` tinyint(4) NOT NULL DEFAULT '0',
   PRIMARY KEY (`CON_ID`),
   KEY `U_ID` (`U_ID`)
   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2001 ;",
   
   "CREATE TABLE IF NOT EXISTS `EssentialQuestions` (
   `EQ_ID` int(11) NOT NULL AUTO_INCREMENT,
   `U_ID` int(11) NOT NULL,
   `Text` varchar(255) DEFAULT NULL,
   `Rank` tinyint(4) NOT NULL,
   PRIMARY KEY (`EQ_ID`),
   KEY `U_ID` (`U_ID`)
   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2456 ;",
    
  "CREATE TABLE IF NOT EXISTS `GradeLevels` (
  `GL_ID` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(2) CHARACTER SET utf8 NOT NULL,
  `longname` varchar(30) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`GL_ID`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;",
  
  "CREATE TABLE IF NOT EXISTS `Resources` (
  `RSC_ID` int(11) NOT NULL AUTO_INCREMENT,
  `U_ID` int(11) NOT NULL,
  `Text` varchar(255) DEFAULT NULL,
  `Rank` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`RSC_ID`),
  KEY `U_ID` (`U_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1598 ;",
    
  "CREATE TABLE IF NOT EXISTS `Skills` (
  `SKL_ID` int(11) NOT NULL AUTO_INCREMENT,
  `U_ID` int(11) NOT NULL,
  `Text` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `Rank` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`SKL_ID`),
  KEY `U_ID` (`U_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1962 ;",
    
"CREATE TABLE IF NOT EXISTS `Standards` (
  `STD_ID` int(10) NOT NULL AUTO_INCREMENT,
  `STD_I_ID` int(10) NOT NULL,
  `U_ID` int(10) NOT NULL,
  `Rank` tinyint(4) NOT NULL,
  `Text` text NOT NULL,
  PRIMARY KEY (`STD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;",
    
"CREATE TABLE IF NOT EXISTS `Standards` (
  `STD_ID` int(10) NOT NULL AUTO_INCREMENT,
  `STD_I_ID` int(10) NOT NULL,
  `U_ID` int(10) NOT NULL,
  `Rank` tinyint(4) NOT NULL,
  `Text` text NOT NULL,
  PRIMARY KEY (`STD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;",
    
"CREATE TABLE IF NOT EXISTS `StandardsCategories` (
  `STD_C_ID` int(10) NOT NULL AUTO_INCREMENT,
  `STD_L_ID` int(10) NOT NULL,
  `Label` varchar(100) NOT NULL,
  PRIMARY KEY (`STD_C_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;",
    
"CREATE TABLE IF NOT EXISTS `StandardsLibraries` (
  `STD_L_ID` int(30) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  PRIMARY KEY (`STD_L_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;",
    
"CREATE TABLE IF NOT EXISTS `StandardsListItems` (
  `STD_I_ID` int(10) NOT NULL AUTO_INCREMENT,
  `STD_SC_ID` int(10) NOT NULL,
  `STD_C_ID` int(10) NOT NULL,
  `STD_L_ID` int(10) NOT NULL,
  `Text` text NOT NULL,
  `Label` varchar(30) NOT NULL,
  PRIMARY KEY (`STD_I_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1495 ;",
    
"CREATE TABLE IF NOT EXISTS `StandardsSubCategories` (
  `STD_SC_ID` int(10) NOT NULL AUTO_INCREMENT,
  `STD_C_ID` int(10) NOT NULL,
  `STD_L_ID` int(10) NOT NULL,
  `Label` varchar(256) NOT NULL,
  PRIMARY KEY (`STD_SC_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=159 ;",
    
"CREATE TABLE IF NOT EXISTS `Subjects` (
  `S_ID` int(11) NOT NULL AUTO_INCREMENT,
  `shortname` varchar(30) CHARACTER SET latin1 NOT NULL,
  `longname` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`S_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14; ",
    
"CREATE TABLE IF NOT EXISTS `Units` (
  `U_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USR_ID` int(30) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `GradeLevel_id` int(11) NOT NULL,
  `Subject_id` int(11) NOT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `Comments` text NOT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`U_ID`),
  KEY `GradeLevel_id` (`GradeLevel_id`),
  KEY `Subject_id` (`Subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2134 ;",
    
"CREATE TABLE IF NOT EXISTS `Users` (
  `USR_ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`USR_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;",
    
"ALTER TABLE `Activities`
  ADD CONSTRAINT `Activities_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `Units` (`U_ID`);",

"ALTER TABLE `Assessments`
  ADD CONSTRAINT `Assessments_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `Units` (`U_ID`);",
    
"ALTER TABLE `Content`
  ADD CONSTRAINT `Content_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `Units` (`U_ID`);",
    
"ALTER TABLE `EssentialQuestions`
  ADD CONSTRAINT `EssentialQuestions_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `Units` (`U_ID`);",
    
"ALTER TABLE `Resources`
  ADD CONSTRAINT `Resources_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `Units` (`U_ID`);",
    
"ALTER TABLE `Skills`
  ADD CONSTRAINT `Skills_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `Units` (`U_ID`);",
    
"ALTER TABLE `Units`
  ADD CONSTRAINT `Units_ibfk_1` FOREIGN KEY (`GradeLevel_id`) REFERENCES `GradeLevels` (`GL_ID`),
  ADD CONSTRAINT `Units_ibfk_2` FOREIGN KEY (`GradeLevel_id`) REFERENCES `GradeLevels` (`GL_ID`),
  ADD CONSTRAINT `Units_ibfk_3` FOREIGN KEY (`Subject_id`) REFERENCES `Subjects` (`S_ID`);"
     
  
];

?>