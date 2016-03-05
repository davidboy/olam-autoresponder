<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

include_once('config.php');

# ------------------------------------------------

function bouncer_exists($bouncer_id)
{
    global $DB_LinkID;

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
    $result = mysql_query($query, $DB_LinkID) or die("Invalid query: " . mysql_error());
    if (mysql_num_rows($result) > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function bouncer_address_exists($address)
{
    global $DB_LinkID;

    # Grab addy
    $address = trim(strtolower($address));

    # Check for it's existance
    $query = "SELECT * FROM InfResp_Bouncers WHERE EmailAddy = '$address'";
    $result = mysql_query($query, $DB_LinkID) or die("Invalid query: " . mysql_error());
    if (mysql_num_rows($result) > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function unassigned_addy_pulldown()
{
    # Make a hash of currently assigned bouncer addresses
    $assigned = array();
    $query = "SELECT EmailAddy FROM InfResp_Bouncers";
    $DB_result = mysql_query($query) OR die("Invalid query: " . mysql_error());
    while ($data = mysql_fetch_assoc($DB_result)) {
        $addy = strtolower(trim($data['EmailAddy']));
        $assigned[$addy] = TRUE;
    }

    # Compare to the list of addresses in responders
    $unassigned = array();
    $found_some = FALSE;
    $query = "SELECT OwnerEmail,ReplyToEmail FROM InfResp_responders";
    $DB_result = mysql_query($query) OR die("Invalid query: " . mysql_error());
    while ($data = mysql_fetch_assoc($DB_result)) {
        foreach ($data as $key => $value) {
            $addy = strtolower(trim($value));
            if ((!(IsInArray($unassigned, $addy))) && ($assigned[$addy] != TRUE)) {
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

# Get login / pass information.
$X_login = $_REQUEST['login'];
$X_pass = $_REQUEST['pword'];

# Get and verify input
$Responder_ID = MakeSafe($_REQUEST['r_ID']);
$action = MakeSafe($_REQUEST['action']);
if (!(is_numeric($Responder_ID))) {
    $Responder_ID = "";
}

# Check authentication
$Is_Auth = User_Auth();
if ($Is_Auth) {
    # Top template
    include('templates/open.page.php');

    # Cpanel top
    $help_section = "bouncers";
    include('templates/controlpanel.php');

    # Regexps button
    include('templates/bounce_regexps.bouncers.php');

    # Check the bouncer ID
    $bouncer_id = MakeSafe($_REQUEST['b_ID']);
    if ((!(is_numeric($bouncer_id))) || (empty($bouncer_id)) || ($bouncer_id == "")) {
        $bouncer_id = "0";
    }

    if ($action == "create") {
        # Did we pass an email?
        $data['EmailAddy'] = strtolower(MakeSafe($_REQUEST['EmailAddy']));
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
    } elseif (($action == "edit") && (bouncer_exists($bouncer_id))) {
        # Query DB - We already know there's a row for it.
        $query = "SELECT * FROM InfResp_Bouncers WHERE BouncerID = '$bouncer_id'";
        $result = mysql_query($query) OR die("Invalid query: " . mysql_error());
        $data = mysql_fetch_assoc($result);

        # Show the template
        $heading = "Edit a Bouncer";
        $return_action = "list";
        $submit_action = "do_edit";
        include('templates/edit_create.bouncers.php');
        include('templates/back_button.bouncers.php');
    } elseif (($action == "delete") && (bouncer_exists($bouncer_id))) {
        # Query DB - We already know there's a row for it.
        $query = "SELECT * FROM InfResp_Bouncers WHERE BouncerID = '$bouncer_id'";
        $result = mysql_query($query) OR die("Invalid query: " . mysql_error());
        $data = mysql_fetch_assoc($result);

        # Show the template
        $return_action = "list";
        include('templates/delete.bouncers.php');
        include('templates/back_button.bouncers.php');
    } else {
        if (($action == "do_edit") && (bouncer_exists($bouncer_id))) {
            # Grab and clean form data
            $fields = get_db_fields('InfResp_Bouncers');
            foreach ($_REQUEST as $name => $value) {
                $name = strtolower($name);
                if ($fields['hash'][$name] == TRUE) {
                    $form[$name] = MakeSafe($value);
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
            DB_Update_Array('InfResp_Bouncers', $form, "BouncerID = '$bouncer_id'");

            # Done! Take us back...
            print "<p class=\"big_header\">Bouncer changed!</p>\n";
        } elseif ($action == "do_create") {
            # Grab and clean form data
            $fields = get_db_fields('InfResp_Bouncers');
            foreach ($_REQUEST as $name => $value) {
                $name = strtolower($name);
                if ($fields['hash'][$name] == TRUE) {
                    $form[$name] = MakeSafe($value);
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

            if (bouncer_address_exists($form['emailaddy'])) {
                # Done! Take us back...
                print "<p class=\"big_header\">That address is already assigned!</p>\n";
            } else {
                # Insert the row
                DB_Insert_Array('InfResp_Bouncers', $form);

                # Done! Take us back...
                print "<p class=\"big_header\">Bouncer added!</p>\n";
            }
        } elseif (($action == "do_delete") && (bouncer_exists($bouncer_id))) {
            # Delete from the bouncer table
            $query = "DELETE FROM InfResp_Bouncers WHERE BouncerID = '$bouncer_id'";
            $result = mysql_query($query) OR die("Invalid query: " . mysql_error());

            # Done! Take us back...
            print "<p class=\"big_header\">Bouncer deleted!</p>\n";
        }

        # Init vars
        $alt = TRUE;
        $return_action = "list";

        # Show the page
        $query = "SELECT * FROM InfResp_Bouncers";
        $DB_Result = mysql_query($query) or die("Invalid query: " . mysql_error());
        if (mysql_num_rows($DB_Result) > 0) {
            # Top template
            include('templates/list_top.bouncers.php');

            # Run the list
            for ($i = 0; $i < mysql_num_rows($DB_Result); $i++) {
                $data = mysql_fetch_assoc($DB_Result);

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
} else {
    admin_redirect();
}

DB_disconnect();
?>
