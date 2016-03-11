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

# Flag to prevent an infinite redirect loop
$editingConfig = true;

include_once('common.php');

if (userIsLoggedIn() || $config['admin_pass'] == '') {
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
        $config_fields = dbGetFields('InfResp_config');
        foreach ($_REQUEST as $name => $value) {
            $name = strtolower($name);
            if ($config_fields['hash'][$name] == TRUE) {
                $form[$name] = makeSafe($value);
            }
        }

        $form['admin_pass'] = password_hash($form['admin_pass'], PASSWORD_BCRYPT);

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
        dbUpdateArray('InfResp_config', $form);

        # Grab the new data
        $query = "SELECT * FROM InfResp_config";
        $result = $DB->query($query) or die("Invalid query: " . $DB->error);
        $config = $result->fetch_assoc();

        # Prep the data
        $max_send_count = $config['max_send_count'];
        $last_activity_trim = $config['last_activity_trim'];
        $charset = $config['charset'];

        # Log the user in with their newly created account
        createLoginSession($config['admin_user'], $config['admin_pass']);

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
    redirectTo('/login.php');
}
