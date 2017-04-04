-- replace wp with actual prefix before running the commands

DROP VIEW IF EXISTS `lwp_iss_students`;
DROP VIEW IF EXISTS `lwp_iss_parents`;
DROP TABLE IF EXISTS `lwp_iss_payment`;
DROP TABLE IF EXISTS `lwp_iss_registration`;
DROP TABLE IF EXISTS `lwp_iss_student`;
DROP TABLE IF EXISTS `lwp_iss_parent`;
DROP TABLE IF EXISTS `lwp_iss_changelog`;

CREATE TABLE IF NOT EXISTS `lwp_iss_changelog` (
  `ChangelogID` int(11) NOT NULL,
  `TableName` varchar(30) NOT NULL,
  `ParentID` int(11) NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `FieldName` varchar(100) NOT NULL,
  `FieldValue` varchar(100) NOT NULL,
  `ChangeSetID` varchar(30) NOT NULL,
  `ModifiedBy` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


ALTER TABLE `lwp_iss_changelog` ADD PRIMARY KEY (`ChangelogID`);
ALTER TABLE `lwp_iss_changelog`  MODIFY `ChangelogID` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS `lwp_iss_parent` (
  `ID` int(11) unsigned NOT NULL,
  `ParentID` int(11) NOT NULL,
  `FatherFirstName` varchar(100) NOT NULL COMMENT 'Father First Name',
  `FatherLastName` varchar(100) NOT NULL COMMENT 'Father Last Name',
  `FatherEmail` varchar(100) DEFAULT NULL COMMENT 'Father Email',
  `FatherWorkPhone` varchar(20) DEFAULT NULL COMMENT 'Father Work Phone',
  `FatherCellPhone` varchar(20) DEFAULT NULL COMMENT 'Father Cell Phone',
  `HomeStreetAddress` varchar(200) DEFAULT NULL COMMENT 'Street Address',
  `HomeCity` varchar(100) DEFAULT NULL COMMENT 'City',
  `HomeZip` int(5) DEFAULT NULL COMMENT 'Zip',
  `HomePhone` varchar(20) NOT NULL COMMENT 'Home Phone',
  `FamilySchoolStartYear` varchar(20) DEFAULT NULL COMMENT 'Family School Start Year',
  `SchoolEmail` varchar(100) DEFAULT NULL,
  `MotherFirstName` varchar(100) NOT NULL COMMENT 'Mother First Name',
  `MotherLastName` varchar(100) NOT NULL COMMENT 'Mother Last Name',
  `MotherStreetAddress` varchar(200) DEFAULT NULL COMMENT 'Mother Street Address',
  `MotherCity` varchar(100) DEFAULT NULL COMMENT 'Mother City',
  `MotherZip` int(5) DEFAULT NULL COMMENT 'Mother Zip',
  `MotherEmail` varchar(100) DEFAULT NULL COMMENT 'Mother Email',
  `MotherHomePhone` varchar(20) DEFAULT NULL COMMENT 'Mother Home Phone',
  `MotherWorkPhone` varchar(20) DEFAULT NULL COMMENT 'Mother Work Phone',
  `MotherCellPhone` varchar(20) DEFAULT NULL COMMENT 'Mother Cell Phone',
  `EmergencyContactName1` varchar(100) DEFAULT NULL COMMENT 'Emergency Contact Name 1',
  `EmergencyContactPhone1` varchar(20) DEFAULT NULL COMMENT 'Emergency Contact Phone 1',
  `EmergencyContactName2` varchar(100) DEFAULT NULL COMMENT 'Emergency Contact Name 2',
  `EmergencyContactPhone2` varchar(20) DEFAULT NULL COMMENT 'Emergency Contact Phone 2',
  `ShareAddress` varchar(5) NOT NULL DEFAULT 'Yes',
  `TakePicture` varchar(5) NOT NULL DEFAULT 'Yes',
  `ParentStatus` varchar(10) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated Date',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Created Date',
  `SpecialNeedNote` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Parents';

ALTER TABLE `lwp_iss_parent`  ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `ParentID` (`ParentID`);
ALTER TABLE `lwp_iss_parent`  MODIFY `ID` int(11) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS `lwp_iss_payment` (
  `PaymentID` int(11) unsigned NOT NULL,
  `ParentID` int(11) NOT NULL,
  `RegistrationYear` varchar(9) NOT NULL DEFAULT '2016-2017',
  `PaymentInstallment1` float(6,2) DEFAULT NULL,
  `PaymentMethod1` varchar(20) DEFAULT NULL,
  `PaymentDate1` date DEFAULT NULL,
  `PaymentInstallment2` float(6,2) DEFAULT NULL,
  `PaymentMethod2` varchar(20) DEFAULT NULL,
  `PaymentDate2` date DEFAULT NULL,
  `TotalAmountDue` float(6,2) DEFAULT NULL,
  `PaidInFull` varchar(3) NOT NULL DEFAULT 'No',
  `RegistrationCode` varchar(100) DEFAULT NULL,
  `RegistrationExpiration` datetime DEFAULT NULL,
  `RegistrationComplete` varchar(10) DEFAULT 'New',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated Date',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Created Date',
  `Comments` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Payments';

ALTER TABLE `lwp_iss_payment`
  ADD PRIMARY KEY (`PaymentID`),
  ADD UNIQUE KEY `ParentID` (`ParentID`,`RegistrationYear`),
  ADD KEY `ISS_Payment_RegistrationYear_FK` (`RegistrationYear`);
ALTER TABLE `lwp_iss_payment` MODIFY `PaymentID` int(11) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `lwp_iss_payment`
  ADD CONSTRAINT `ISS_Registration_ParentID_FK` FOREIGN KEY (`ParentID`) REFERENCES `lwp_iss_parent` (`ParentID`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `lwp_iss_student` (
  `ID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `ParentID` int(11) NOT NULL,
  `StudentBirthDate` date NOT NULL,
  `StudentFirstName` varchar(35) NOT NULL,
  `StudentLastName` varchar(35) NOT NULL,
  `StudentGender` varchar(1) NOT NULL DEFAULT 'M',
  `StudentStatus` varchar(10) NOT NULL DEFAULT 'active',
  `StudentEmail` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `lwp_iss_student`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `StudentID` (`StudentID`),
  ADD UNIQUE KEY `ParentID_StudentID` (`ParentID`,`StudentID`) USING BTREE,
  ADD KEY `ISS_Student_ParentID_FK` (`ParentID`);
ALTER TABLE `lwp_iss_student` MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `lwp_iss_student` ADD CONSTRAINT `ISS_Student_ParentID_FK` FOREIGN KEY (`ParentID`) REFERENCES `lwp_iss_parent` (`ParentID`);

CREATE TABLE IF NOT EXISTS `lwp_iss_registration` (
  `RegistrationID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `RegistrationYear` varchar(10) NOT NULL,
  `RegularSchoolGrade` varchar(2) NOT NULL,
  `ISSGrade` varchar(2) NOT NULL DEFAULT 'KG',
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `lwp_iss_registration`
  ADD PRIMARY KEY (`RegistrationID`),
  ADD UNIQUE KEY `StudentID_RegistrationYear` (`StudentID`,`RegistrationYear`) USING BTREE,
  ADD KEY `ISS_Class_RegistrationYear_FK` (`RegistrationYear`);
ALTER TABLE `lwp_iss_registration` MODIFY `RegistrationID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `lwp_iss_registration` ADD CONSTRAINT `ISS_Class_StudentID_FK` FOREIGN KEY (`StudentID`) REFERENCES `lwp_iss_student` (`StudentID`) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE VIEW `lwp_iss_parents` AS select `lwp_iss_payment`.`PaymentID` AS `ID`,`lwp_iss_parent`.`ParentID` AS `ParentID`,`lwp_iss_parent`.`FatherFirstName` AS `FatherFirstName`,`lwp_iss_parent`.`FatherLastName` AS `FatherLastName`,`lwp_iss_parent`.`FatherEmail` AS `FatherEmail`,`lwp_iss_parent`.`FatherWorkPhone` AS `FatherWorkPhone`,`lwp_iss_parent`.`FatherCellPhone` AS `FatherCellPhone`,`lwp_iss_parent`.`HomeStreetAddress` AS `HomeStreetAddress`,`lwp_iss_parent`.`HomeCity` AS `HomeCity`,`lwp_iss_parent`.`HomeZip` AS `HomeZip`,`lwp_iss_parent`.`HomePhone` AS `HomePhone`,`lwp_iss_parent`.`FamilySchoolStartYear` AS `FamilySchoolStartYear`,`lwp_iss_parent`.`SchoolEmail` AS `SchoolEmail`,`lwp_iss_parent`.`MotherFirstName` AS `MotherFirstName`,`lwp_iss_parent`.`MotherLastName` AS `MotherLastName`,`lwp_iss_parent`.`MotherStreetAddress` AS `MotherStreetAddress`,`lwp_iss_parent`.`MotherCity` AS `MotherCity`,`lwp_iss_parent`.`MotherZip` AS `MotherZip`,`lwp_iss_parent`.`MotherEmail` AS `MotherEmail`,`lwp_iss_parent`.`MotherHomePhone` AS `MotherHomePhone`,`lwp_iss_parent`.`MotherWorkPhone` AS `MotherWorkPhone`,`lwp_iss_parent`.`MotherCellPhone` AS `MotherCellPhone`,`lwp_iss_parent`.`EmergencyContactName1` AS `EmergencyContactName1`,`lwp_iss_parent`.`EmergencyContactPhone1` AS `EmergencyContactPhone1`,`lwp_iss_parent`.`EmergencyContactName2` AS `EmergencyContactName2`,`lwp_iss_parent`.`EmergencyContactPhone2` AS `EmergencyContactPhone2`,`lwp_iss_parent`.`ShareAddress` AS `ShareAddress`,`lwp_iss_parent`.`TakePicture` AS `TakePicture`,`lwp_iss_parent`.`ParentStatus` AS `ParentStatus`,`lwp_iss_parent`.`SpecialNeedNote` AS `SpecialNeedNote`,`lwp_iss_payment`.`RegistrationYear` AS `RegistrationYear`,`lwp_iss_payment`.`PaymentInstallment1` AS `PaymentInstallment1`,`lwp_iss_payment`.`PaymentMethod1` AS `PaymentMethod1`,`lwp_iss_payment`.`PaymentDate1` AS `PaymentDate1`,`lwp_iss_payment`.`PaymentInstallment2` AS `PaymentInstallment2`,`lwp_iss_payment`.`PaymentMethod2` AS `PaymentMethod2`,`lwp_iss_payment`.`PaymentDate2` AS `PaymentDate2`,`lwp_iss_payment`.`TotalAmountDue` AS `TotalAmountDue`,`lwp_iss_payment`.`PaidInFull` AS `PaidInFull`,`lwp_iss_payment`.`RegistrationCode` AS `RegistrationCode`,`lwp_iss_payment`.`RegistrationExpiration` AS `RegistrationExpiration`,`lwp_iss_payment`.`RegistrationComplete` AS `RegistrationComplete`,`lwp_iss_payment`.`Comments` AS `Comments` from (`lwp_iss_parent` join `lwp_iss_payment` on((`lwp_iss_parent`.`ParentID` = `lwp_iss_payment`.`ParentID`)));


CREATE VIEW `lwp_iss_students` AS select `lwp_iss_registration`.`RegistrationID` AS `ID`,`lwp_iss_student`.`StudentID` AS `StudentID`,`lwp_iss_student`.`ParentID` AS `ParentID`,`lwp_iss_student`.`StudentFirstName` AS `StudentFirstName`,`lwp_iss_student`.`StudentLastName` AS `StudentLastName`,`lwp_iss_student`.`StudentBirthDate` AS `StudentBirthDate`,`lwp_iss_student`.`StudentGender` AS `StudentGender`,`lwp_iss_student`.`StudentStatus` AS `StudentStatus`,`lwp_iss_student`.`StudentEmail` AS `StudentEmail`,`lwp_iss_registration`.`RegularSchoolGrade` AS `RegularSchoolGrade`,`lwp_iss_registration`.`ISSGrade` AS `ISSGrade`,`lwp_iss_registration`.`RegistrationYear` AS `RegistrationYear` from (`lwp_iss_student` join `lwp_iss_registration` on((`lwp_iss_student`.`StudentID` = `lwp_iss_registration`.`StudentID`)));


