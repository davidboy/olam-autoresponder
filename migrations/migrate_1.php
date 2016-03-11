<?php

require_once __DIR__ . '/common.php';

$db_migrations = array(
    "ALTER TABLE `InfResp_subscribers` ADD `IsSubscribed` tinyint(1) NOT NULL default '1'",

    "ALTER TABLE `InfResp_responders` ADD `StartDate` date",

    "ALTER TABLE `InfResp_subscribers` ADD `TimeJoined` int NOT NULL default '0'",
    "ALTER TABLE `InfResp_subscribers` ADD `Real_TimeJoined` int NOT NULL default '0'",

    "ALTER TABLE `InfResp_config` ADD `schema_version` int NOT NULL default '1'",

    "ALTER TABLE `InfResp_config` DROP `random_str_1`",
    "ALTER TABLE `InfResp_config` DROP `random_str_2`"
);

foreach ($db_migrations as $query) {
    $DB->query($query) or die("Error running migration '$query': $DB->error");
}

$result = $DB->query("SELECT admin_pass FROM InfResp_config") or die("Unable to find admin password: '$DB->error'");
$row = $result->fetch_assoc();
$old_password = $row['admin_pass'];

$hash = password_hash($old_password, PASSWORD_BCRYPT);

$DB->query("UPDATE InfResp_config SET admin_pass='$hash'");