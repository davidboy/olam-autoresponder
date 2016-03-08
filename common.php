<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

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

# Include the includes
include('evilness-filter.php');
include('functions.php');

# Set the siteURL
if ((isEmpty($_SERVER['HTTPS'])) || ((strtolower($_SERVER['HTTPS'])) == "off")) {
    $siteURL = "http://" . $_SERVER['SERVER_NAME'];
} else {
    $siteURL = "https://" . $_SERVER['SERVER_NAME'];
}

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
mysql_query("SET NAMES 'utf8'");

# Check the table install
include_once('check_install.php');

# Check the config
$query = "SELECT * FROM InfResp_config";
$result = mysql_query($query) or die("Invalid query: " . mysql_error());
if (mysql_num_rows($result) < 1) {
    # Grab the vars
    $now = time();
    $str1 = generateRandomBlock();
    $str2 = generateRandomBlock();

    # Setup the array
    $config['Max_Send_Count'] = '500';
    $config['Last_Activity_Trim'] = '6';
    $config['random_str_1'] = $str1;
    $config['random_str_2'] = $str2;
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
    $config = mysql_fetch_assoc($result);

    # If the admin password hasn't been set yet, assume the the config row hasn't been created.
    # Thus the admin hasn't configured anything yet -- force them to do that now.
    if (($config['admin_pass'] == '') && !isset($editingConfig)) {
        redirectTo('/edit_config.php');
    }
}

# Bad, but useful, hackery
$max_send_count = $config['max_send_count'];
$last_activity_trim = $config['last_activity_trim'];
$charset = $config['charset'];
?>
