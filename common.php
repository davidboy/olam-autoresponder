<?php
// This file is part of Olam Autoresponder.
// Copyright (c) 2004-2007 Aaron Colman and Adaptive Business Design.
// Copyright (c) 2016 Anna Burdette, Benjamin Jobson, and David Reed.
//
// Olam Autoresponder is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, version 2.
//
// Olam Autoresponder is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
//     along with Olam Autoresponder.  If not, see <http://www.gnu.org/licenses/>.

include_once('config.php');

# Check config.php vars
if ($MySQL_server == '') {
    die('$MySQL_server not set in config.php');
}
if ($MySQL_user == '') {
    die('$MySQL_user not set in config.php');
}
if ($MySQL_password == '') {
    die('$MySQL_password not set in config.php');
}
if ($MySQL_database == '') {
    die('$MySQL_database not set in config.php');
}

# Start the session
session_start();

# Load our functions
require_once 'functions.php';

# Set the siteURL
if ((!isset($_SERVER['HTTPS'])) || ((strtolower($_SERVER['HTTPS'])) == "off")) {
    $siteURL = "http://" . $_SERVER['SERVER_NAME'];
} else {
    $siteURL = "https://" . $_SERVER['SERVER_NAME'];
}

# Timezone's don't really matter for this app, but we have to set one since older
#   versions of CentOS often don't provide a default
date_default_timezone_set('America/Los_Angeles');

# Set the responder directory
$directory_array = explode('/', $_SERVER['SCRIPT_NAME']);
if (sizeof($directory_array) <= 2) {
    $ResponderDirectory = "/";
} else {
    $ResponderDirectory = "";
    for ($i = 1; $i < (sizeof($directory_array) - 1); $i++) {
        $ResponderDirectory = $ResponderDirectory . "/" . $directory_array[$i];
    }
    $max_i = sizeof($directory_array) - 1;
    $this_file = $directory_array[$max_i];
}

# Figure out the newline character
if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
    $newline = "\r\n";
} elseif (strtoupper(substr(PHP_OS, 0, 3) == 'MAC')) {
    $newline = "\r";
} else {
    $newline = "\n";
}

# Connect to the DB
$DB_LinkID = 0;
dbConnect();

# Ensure UTF8
$DB->query("SET NAMES 'utf8'");

# Check the table install
include_once('check_install.php');

# Check the config
$query = "SELECT * FROM InfResp_config";
$result = $DB->query($query) or die("Invalid query: " . $DB->error);
if ($result->num_rows < 1) {
    # Grab the vars
    $now = time();

    # Setup the array
    $config['Max_Send_Count'] = '500';
    $config['Last_Activity_Trim'] = '6';
    $config['random_timestamp'] = $now;
    $config['admin_user'] = 'admin';
    $config['admin_pass'] = '';
    $config['charset'] = 'UTF-8';
    $config['autocall_sendmails'] = '0';
    $config['add_sub_size'] = '5';
    $config['subs_per_page'] = '25';
    $config['site_code'] = '';
    $config['check_mail'] = '1';
    $config['check_bounces'] = '1';
    $config['tinyMCE'] = '1';
    $config['daily_limit'] = '10000';
    $config['daily_count'] = '0';
    $config['daily_reset'] = $now;

    # Insert the data
    dbInsertArray('InfResp_config', $config);
} else {
    $config = $result->fetch_assoc();

    # If the admin password hasn't been set yet, assume the the config row hasn't been created.
    # Thus the admin hasn't configured anything yet -- force them to do that now.
    if (($config['admin_pass'] == '') && !isset($editingConfig)) {
        redirectTo('/edit_config.php');
    }

    # If the config row exists but is running an old version of the database, run the migrations
    # TODO: this will have to be more complicated when we add more migrations later
    if (!isset($config['schema_version'])) {
        define('PERFORMING_MIGRATION', true);
        require_once 'migrations/migrate_1.php';
    }
}

# Bad, but useful, hackery
$max_send_count = $config['max_send_count'];
$last_activity_trim = $config['last_activity_trim'];
$charset = $config['charset'];
?>
