-- Start command --
CREATE TABLE IF NOT EXISTS `InfResp_customfields` (
   `fieldID` bigint(32) NOT NULL auto_increment PRIMARY KEY,
   `user_attached` bigint(32) NOT NULL,
   `resp_attached` bigint(32) NOT NULL,
   `email_attached` varchar(255) NOT NULL default '',
   `streetaddress_1` varchar(255) NOT NULL default '',
   `streetaddress_2` varchar(255) NOT NULL default '',
   `city` varchar(255) NOT NULL default '',
   `state` varchar(99) NOT NULL default '',
   `zipcode` varchar(99) NOT NULL default '',
   `homephone` varchar(99) NOT NULL default '',
   `best_contact_time` varchar(255) NOT NULL default '',
   `other_offers` tinyint(1) NOT NULL default '0',
   `full_name` varchar(255) NOT NULL default '',
   `reason` varchar(99) NOT NULL default '',
   `income` varchar(99) NOT NULL default '',
   `interest` varchar (99) NOT NULL default '',
   `hours` varchar (99) NOT NULL default '',
   `source` varchar (255) NOT NULL default '',
   `variation` varchar (255) NOT NULL default ''
) ENGINE=InnoDB CHARACTER SET utf8;
-- End command --

-- Start command --
CREATE TABLE IF NOT EXISTS `InfResp_messages` (
   `MsgID` bigint(32) NOT NULL auto_increment PRIMARY KEY,
   `Subject` varchar(255) NOT NULL default '',
   `SecMinHoursDays` bigint(32) NOT NULL default '0',
   `Months` int NOT NULL default '0',
   `absDay` varchar(255) NOT NULL default '',
   `absMins` int NOT NULL default '0',
   `absHours` int NOT NULL default '0',
   `BodyText` text NOT NULL,
   `BodyHTML` text NOT NULL,
   `attachmentName` varchar(255),
   `attachmentStorageName` varchar(255)
) ENGINE=InnoDB CHARACTER SET utf8;
-- End command --

-- Start command --
CREATE TABLE IF NOT EXISTS `InfResp_responders` (
   `ResponderID` bigint(32) NOT NULL auto_increment PRIMARY KEY,
   `Enabled` tinyint(1) NOT NULL default '1',
   `Name` varchar(255) NOT NULL default '',
   `ResponderDesc` text NOT NULL,
   `OwnerEmail` varchar(100) NOT NULL default '',
   `OwnerName` varchar(255) NOT NULL default '',
   `ReplyToEmail` varchar(100) NOT NULL default '',
   `MsgList` text NOT NULL,
   `OptMethod` varchar(10) NOT NULL default 'Double',
   `OptInRedir` varchar(255) NOT NULL default '',
   `OptOutRedir` varchar(255) NOT NULL default '',
   `OptInDisplay` text NOT NULL,
   `OptOutDisplay` text NOT NULL,
   `NotifyOwnerOnSub` tinyint(1) NOT NULL default '1'
   `StartDate` date
) ENGINE=InnoDB CHARACTER SET utf8;
-- End command --

-- Start command --
CREATE TABLE IF NOT EXISTS `InfResp_subscribers` (
   `SubscriberID` bigint(32) NOT NULL auto_increment PRIMARY KEY,
   `ResponderID` bigint(32) NOT NULL,
   `SentMsgs` text,
   `EmailAddress` varchar(100) NOT NULL default '',
   `TimeJoined` bigint(32) NOT NULL default '0',
   `Real_TimeJoined` bigint(32) NOT NULL default '0',
   `CanReceiveHTML` tinyint(1) NOT NULL default '0',
   `LastActivity` bigint(32) NOT NULL default '0',
   `FirstName` varchar(255) NOT NULL default '',
   `LastName` varchar(255) NOT NULL default '',
   `IP_Addy` varchar(255) NOT NULL default '',
   `ReferralSource` varchar(255) NOT NULL default '',
   `UniqueCode` varchar(255) NOT NULL default '',
   `Confirmed` tinyint(1) NOT NULL default '0'
) ENGINE=InnoDB CHARACTER SET utf8;
-- End command --

-- Start command --
CREATE TABLE IF NOT EXISTS `InfResp_blacklist` (
   `Blacklist_ID` bigint(32) NOT NULL auto_increment PRIMARY KEY,
   `EmailAddress` varchar(100) NOT NULL default ''
) ENGINE=InnoDB CHARACTER SET utf8;
-- End command --

-- Start command --
CREATE TABLE IF NOT EXISTS `InfResp_POP3` (
   `POP_ConfigID` bigint(32) NOT NULL auto_increment PRIMARY KEY,
   `ThisPOP_Enabled` tinyint(1) NOT NULL default '0',
   `Confirm_Join` tinyint(1) NOT NULL default '0',
   `Attached_Responder` bigint(32) NOT NULL default '0',
   `host` text NOT NULL,
   `port` int NOT NULL default '110',
   `username` text,
   `password` text,
   `mailbox` varchar(255) NOT NULL default 'INBOX',
   `HTML_YN` tinyint(1) NOT NULL default '0',
   `Delete_After_Download` tinyint(1) NOT NULL default '0',
   `Spam_Header` varchar(100) NOT NULL default '***SPAM***',
   `Concat_Middle` tinyint(1) NOT NULL default '1',
   `Mail_Type` varchar(20) NOT NULL default 'pop3'
) ENGINE=InnoDB CHARACTER SET utf8;
-- End command --

-- Start command --
CREATE TABLE IF NOT EXISTS `InfResp_mail` (
   `Mail_ID` bigint(32) NOT NULL auto_increment PRIMARY KEY,
   `ResponderID` bigint(32) NOT NULL default '0',
   `Closed` tinyint(1) NOT NULL default '0',
   `Subject` varchar(255) NOT NULL default '',
   `TEXT_msg` text NOT NULL,
   `HTML_msg` text NOT NULL,
   `Time_To_Send` bigint(32) NOT NULL default '0',
   `Time_Sent` bigint(32) NOT NULL default '0'
) ENGINE=InnoDB CHARACTER SET utf8;
-- End command --

-- Start command --
CREATE TABLE IF NOT EXISTS `InfResp_mail_cache` (
   `Cache_ID` bigint(32) NOT NULL auto_increment PRIMARY KEY,
   `Mail_ID` bigint(32) NOT NULL default '0',
   `SubscriberID` bigint(32) NOT NULL default '0',
   `Status` varchar(255) NOT NULL default 'queued',
   `LastActivity` bigint(32) NOT NULL default '0'
) ENGINE=InnoDB CHARACTER SET utf8;
-- End command --

-- Start command --
CREATE TABLE IF NOT EXISTS `InfResp_Logs` (
   `Log_ID` bigint(32) NOT NULL auto_increment PRIMARY KEY,
   `TimeStamp` bigint(32) NOT NULL default '0',
   `Activity` varchar(255) NOT NULL default '',
   `Activity_Parameter` text NOT NULL,
   `ID_Parameter` text NOT NULL,
   `Extra_Parameter` text NOT NULL
) ENGINE=InnoDB CHARACTER SET utf8;
-- End command --

-- Start command --
CREATE TABLE IF NOT EXISTS `InfResp_Bouncers` (
   `BouncerID` bigint(32) NOT NULL auto_increment PRIMARY KEY,
   `EmailAddy` varchar(255) NOT NULL default '',
   `Enabled` tinyint(1) NOT NULL default '1',
   `host` varchar(255) NOT NULL default '',
   `port` int NOT NULL default '110',
   `username` varchar(255) NOT NULL default '',
   `password` varchar(255) NOT NULL default '',
   `mailbox` varchar(255) NOT NULL default 'INBOX',
   `mailtype` varchar(20) NOT NULL default 'pop3',
   `DeleteLevel` int NOT NULL default '1',
   `SpamHeader` varchar(100) NOT NULL default '***SPAM***',
   `NotifyOwner` tinyint(1) NOT NULL default '1'
) ENGINE=InnoDB CHARACTER SET utf8;
-- End command --

-- Start command --
CREATE TABLE IF NOT EXISTS `InfResp_BounceRegs` (
   `BounceRegexpID` bigint(32) NOT NULL auto_increment PRIMARY KEY,
   `RegX` text NOT NULL
) ENGINE=InnoDB CHARACTER SET utf8;
-- End command --

-- Start command --
CREATE TABLE IF NOT EXISTS `InfResp_config` (
   `max_send_count` bigint(32) NOT NULL default '500',
   `last_activity_trim` bigint(10) NOT NULL default '6',
   `random_str_1` text NOT NULL,
   `random_str_2` text NOT NULL,
   `random_timestamp` bigint(32) NOT NULL,
   `admin_user` varchar(100) NOT NULL default '',
   `admin_pass` varchar(100) NOT NULL default '',
   `charset` varchar(255) NOT NULL default 'UTF-8',
   `autocall_sendmails` tinyint(1) NOT NULL default '0',
   `add_sub_size` int NOT NULL default '5',
   `subs_per_page` int NOT NULL default '25',
   `site_code` varchar(255) NOT NULL default '',
   `check_mail` tinyint(1) NOT NULL default '1',
   `check_bounces` tinyint(1) NOT NULL default '1',
   `tinyMCE` tinyint(1) NOT NULL default '1',
   `daily_limit` int NOT NULL default '50000',
   `daily_count` int NOT NULL default '0',
   `daily_reset` bigint(32) NOT NULL default '0'
) ENGINE=InnoDB CHARACTER SET utf8;
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('550 5.1.1')
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('550 5.7.1')
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('551 5.1.1')
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('unrouteable mail domain')
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('Unrouteable address')
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('The recipient cannot be verified.')
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('User .* not known.')
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('User .* not listed.')
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('User .* is not defined.')
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('.*: User unknown')
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('.* - User currently disabled.')
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('No thank you rejected: Account Unavailable:')
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('550 .* Recipient address rejected:')
-- End command --

-- Start command --
INSERT INTO `InfResp_BounceRegs` (`RegX`) VALUES ('550 .*: invalid address')
-- End command --
