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

include_once('common.php');
requireUserToBeLoggedIn();

# ------------------------------------------------

function bouncerExists($bouncer_id)
{
    global $DB;

    # Bounds check
    if (isEmpty($bouncer_id)) {
        return FALSE;
    }
    if ($bouncer_id == "0") {
        return FALSE;
    }
    if (!(is_numeric($bouncer_id))) {
        return FALSE;
    }

    # Check for it's existance
    $query = "SELECT * FROM InfResp_Bouncers WHERE BouncerID = '$bouncer_id'";
    $result = $DB->query($query) or die("Invalid query: " . $DB->error);
    if ($result->num_rows > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function bouncerAddressExists($address)
{
    global $DB;

    # Grab addy
    $address = trim(strtolower($address));

    # Check for it's existance
    $query = "SELECT * FROM InfResp_Bouncers WHERE EmailAddy = '$address'";
    $result = $DB->query($query) or die("Invalid query: " . $DB->error);
    if ($result->num_rows > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function unassignedAddressPulldown()
{
    # Make a hash of currently assigned bouncer addresses
    $assigned = array();
    $query = "SELECT EmailAddy FROM InfResp_Bouncers";
    $DB_result = $DB->query($query) OR die("Invalid query: " . $DB->error);
    while ($data = $DB_result->fetch_assoc()) {
        $addy = strtolower(trim($data['EmailAddy']));
        $assigned[$addy] = TRUE;
    }

    # Compare to the list of addresses in responders
    $unassigned = array();
    $found_some = FALSE;
    $query = "SELECT OwnerEmail,ReplyToEmail FROM InfResp_responders";
    $DB_result = $DB->query($query) OR die("Invalid query: " . $DB->error);
    while ($data = $DB_result->fetch_assoc()) {
        foreach ($data as $key => $value) {
            $addy = strtolower(trim($value));
            if ((!(isInArray($unassigned, $addy))) && ($assigned[$addy] != TRUE)) {
                $found_some = TRUE;
                $unassigned[] = $addy;
            }
        }
    }

    # Make the pulldown
    if ($found_some == TRUE) {
        print "<select name=\"EmailAddy\" class=\"fields\">\n";
        foreach ($unassigned as $key => $value) {
            print "<option value=\"$value\">$value</option>\n";
        }
        print "<option value=\"other\">Other Address</option>\n";
        print "</select>\n";
    } else {
        print "<select name=\"EmailAddy\" class=\"fields\">\n";
        print "<option value=\"\">No Unassigned</option>\n";
        print "</select>\n";
    }
}

# ------------------------------------------------

# Get and verify input
$Responder_ID = makeSafe(@$_REQUEST['r_ID']);
$action = makeSafe($_REQUEST['action']);
if (!(is_numeric($Responder_ID))) {
    $Responder_ID = "";
}


# Top template
include('templates/open.page.php');

# Cpanel top
$help_section = "bouncers";
include('templates/controlpanel.php');

# Regexps button
include('templates/bounce_regexps.bouncers.php');

# Check the bouncer ID
$bouncer_id = makeSafe($_REQUEST['b_ID']);
if ((!(is_numeric($bouncer_id))) || (empty($bouncer_id)) || ($bouncer_id == "")) {
    $bouncer_id = "0";
}

if ($action == "create") {
    # Did we pass an email?
    $data['EmailAddy'] = strtolower(makeSafe($_REQUEST['EmailAddy']));
    if (!(isEmail($data['EmailAddy']))) {
        $data['EmailAddy'] = "user@domain";
    }
    if ((isEmpty($data['EmailAddy'])) || ($data['EmailAddy'] == "other")) {
        $data['EmailAddy'] = "user@domain";
    }

    # Init vars
    $data['Enabled'] = 1;
    $data['host'] = "localhost";
    $data['port'] = 110;
    $data['username'] = "user";
    $data['password'] = "pass";
    $data['mailbox'] = "INBOX";
    $data['mailtype'] = "pop3";
    $data['DeleteLevel'] = 1;
    $data['SpamHeader'] = "***SPAM***";
    $data['NotifyOwner'] = 1;

    # Show the template
    $heading = "Create a Bouncer";
    $return_action = "list";
    $submit_action = "do_create";
    include('templates/edit_create.bouncers.php');
    include('templates/back_button.bouncers.php');
} elseif (($action == "edit") && (bouncerExists($bouncer_id))) {
    # Query DB - We already know there's a row for it.
    $query = "SELECT * FROM InfResp_Bouncers WHERE BouncerID = '$bouncer_id'";
    $result = $DB->query($query) OR die("Invalid query: " . $DB->error);
    $data = $result->fetch_assoc();

    # Show the template
    $heading = "Edit a Bouncer";
    $return_action = "list";
    $submit_action = "do_edit";
    include('templates/edit_create.bouncers.php');
    include('templates/back_button.bouncers.php');
} elseif (($action == "delete") && (bouncerExists($bouncer_id))) {
    # Query DB - We already know there's a row for it.
    $query = "SELECT * FROM InfResp_Bouncers WHERE BouncerID = '$bouncer_id'";
    $result = $DB->query($query) OR die("Invalid query: " . $DB->error);
    $data = $result->fetch_assoc();

    # Show the template
    $return_action = "list";
    include('templates/delete.bouncers.php');
    include('templates/back_button.bouncers.php');
} else {
    if (($action == "do_edit") && (bouncerExists($bouncer_id))) {
        # Grab and clean form data
        $fields = dbGetFields('InfResp_Bouncers');
        foreach ($_REQUEST as $name => $value) {
            $name = strtolower($name);
            if ($fields['hash'][$name] == TRUE) {
                $form[$name] = makeSafe($value);
            }
        }
        unset($form['bouncerid']);

        # Bounds checking
        if ($form['enabled'] != 1) {
            $form['enabled'] = 0;
        }

        if (!(is_numeric($form['port']))) {
            $form['port'] = 110;
        }

        $form['mailtype'] = strtolower($form['mailtype']);
        if (($form['mailtype'] != "imap") && ($form['mailtype'] != "nntp")) {
            $form['mailtype'] = "pop3";
        }

        if (($form['deletelevel'] != 0) && ($form['deletelevel'] != 2)) {
            $form['deletelevel'] = 1;
        }

        if ($form['notifyowner'] != 1) {
            $form['notifyowner'] = 0;
        }

        # Check for empty addy fields
        if (isEmpty($form['emailaddy'])) {
            $form['emailaddy'] = "user@domain";
        }

        if ($form['emailaddy'] == "user@domain") {
            $form['enabled'] = 0;
        }

        # Update the row
        dbUpdateArray('InfResp_Bouncers', $form, "BouncerID = '$bouncer_id'");

        # Done! Take us back...
        print "<p class=\"big_header\">Bouncer changed!</p>\n";
    } elseif ($action == "do_create") {
        # Grab and clean form data
        $fields = dbGetFields('InfResp_Bouncers');
        foreach ($_REQUEST as $name => $value) {
            $name = strtolower($name);
            if ($fields['hash'][$name] == TRUE) {
                $form[$name] = makeSafe($value);
            }
        }
        unset($form['bouncerid']);

        # Bounds checking
        if ($form['enabled'] != 1) {
            $form['enabled'] = 0;
        }

        if (!(is_numeric($form['port']))) {
            $form['port'] = 110;
        }

        $form['mailtype'] = strtolower($form['mailtype']);
        if (($form['mailtype'] != "imap") && ($form['mailtype'] != "nntp")) {
            $form['mailtype'] = "pop3";
        }

        if (($form['deletelevel'] != 0) && ($form['deletelevel'] != 2)) {
            $form['deletelevel'] = 1;
        }

        if ($form['notifyowner'] != 1) {
            $form['notifyowner'] = 0;
        }

        # Check for empty addy fields
        if (isEmpty($form['emailaddy'])) {
            $form['emailaddy'] = "user@domain";
        }

        if ($form['emailaddy'] == "user@domain") {
            $form['enabled'] = 0;
        }

        if (bouncerAddressExists($form['emailaddy'])) {
            # Done! Take us back...
            print "<p class=\"big_header\">That address is already assigned!</p>\n";
        } else {
            # Insert the row
            dbInsertArray('InfResp_Bouncers', $form);

            # Done! Take us back...
            print "<p class=\"big_header\">Bouncer added!</p>\n";
        }
    } elseif (($action == "do_delete") && (bouncerExists($bouncer_id))) {
        # Delete from the bouncer table
        $query = "DELETE FROM InfResp_Bouncers WHERE BouncerID = '$bouncer_id'";
        $result = $DB->query($query) OR die("Invalid query: " . $DB->error);

        # Done! Take us back...
        print "<p class=\"big_header\">Bouncer deleted!</p>\n";
    }

    # Init vars
    $alt = TRUE;
    $return_action = "list";

    # Show the page
    $query = "SELECT * FROM InfResp_Bouncers";
    $DB_Result = $DB->query($query) or die("Invalid query: " . $DB->error);
    if ($DB_result->num_rows > 0) {
        # Top template
        include('templates/list_top.bouncers.php');

        # Run the list
        while($data = $DB_Result->fetch_assoc()) {

            # Show the template
            include('templates/list_row.bouncers.php');

            # Alternate colors
            $alt = (!($alt));
        }

        # Bottom template - Add new / back
        include('templates/list_bottom.bouncers.php');
    } else {
        print "<p class=\"big_header\">No bouncers exist. Create one?</p>\n";
    }

    # Add new button
    include('templates/add_new.bouncers.php');

    # Back to admin button
    include('templates/admin_button.bouncers.php');
}

# Template bottom
copyright();
include('templates/close.page.php');

