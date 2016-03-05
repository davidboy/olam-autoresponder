<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

include_once('config.php');

$Is_Auth = User_Auth();
if (empty($config['admin_pass'])) {
    $auto_auth = TRUE;
    $Is_Auth = TRUE;
}

if (($Is_Auth) || ($auto_auth)) {
    # Get the absolute directory info
    $abs_directory_array = explode('/', $_SERVER['SCRIPT_FILENAME']);
    if (sizeof($abs_directory_array) <= 2) {
        $abs_directory = "/";
    } else {
        $abs_directory = "";
        for ($i = 1; $i < (sizeof($abs_directory_array) - 1); $i++) {
            $abs_directory = $abs_directory . "/" . $abs_directory_array[$i];
        }
        $max_i = sizeof($abs_directory_array) - 1;
        $abs_file = $abs_directory_array[$max_i];
    }

    # Top template
    include('templates/open.page.php');

    # Save data?
    print "<br>\n";
    if ($_REQUEST['action'] == "save") {
        # Clean the data
        $config_fields = get_db_fields('InfResp_config');
        foreach ($_REQUEST as $name => $value) {
            $name = strtolower($name);
            if ($config_fields['hash'][$name] == TRUE) {
                $form[$name] = MakeSafe($value);
            }
        }

        if (!(is_numeric($form['add_sub_size']))) {
            $form['add_sub_size'] = 5;
        }
        if (!(is_numeric($form['subs_per_page']))) {
            $form['subs_per_page'] = 25;
        }
        if (!(is_numeric($form['last_activity_trim']))) {
            $form['last_activity_trim'] = 6;
        }
        if ($form['last_activity_trim'] > 120) {
            $form['last_activity_trim'] = 0;
        }

        # Save the data
        DB_Update_Array('InfResp_config', $form);

        # Grab the new data
        $query = "SELECT * FROM InfResp_config";
        $result = mysql_query($query) or die("Invalid query: " . mysql_error());
        $config = mysql_fetch_assoc($result);

        # Prep the data
        $max_send_count = $config['max_send_count'];
        $last_activity_trim = $config['last_activity_trim'];
        $charset = $config['charset'];

        # Fix the session
        $_SESSION['l'] = md5(WebEncrypt($config['admin_user'], $config['random_str_1']));
        $_SESSION['p'] = md5(WebEncrypt($config['admin_pass'], $config['random_str_2']));

        # Done!
        print "<center><H2>Changes Saved!</H2></center>\n";
    }

    # If our password is empty print an warning message!
    if (empty($config['admin_pass'])) {
        print "<center><H2>Warning: Your admin password is not set!</H2></center>\n";
    }

    # Display config template
    include('templates/edit.config.php');

    # Display back to admin button
    include('templates/back_button.config.php');

    # Display the bottom template
    copyright();
    include('templates/close.page.php');
} else {
    admin_redirect();
}

DB_disconnect();
?>
