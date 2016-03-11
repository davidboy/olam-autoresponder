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

# Handle emails that have been blacklisted and should not be let into the database
include('common.php');
requireUserToBeLoggedIn();

# Grab passed
$Responder_ID = makeSafe(@$_REQUEST['r_ID']);
$Message_ID = makeSafe(@$_REQUEST['m_ID']);
$action = strtolower(makeSafe($_REQUEST['action']));


# Top template
include('templates/open.page.php');

# Cpanel top
$help_section = "blacklist";
include('templates/controlpanel.php');

# Set address
$address = makeSafe($_REQUEST['address']);

# Process actions
if (($action == "add") && (isEmail($address))) {
    $query = "SELECT * FROM InfResp_blacklist WHERE EmailAddress = '$address'";
    $DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);
    if ($DB_result->num_rows > 0) {
        print "<br /><strong>That address is already in the blacklist!</strong>\n";
    } else {
        $query = "INSERT INTO InfResp_blacklist (EmailAddress) VALUES ('$address')";
        $DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);
        print "<br /><strong>Address added!</strong>\n";

        # Remove from subscriber and custom fields tables
        $query = "DELETE FROM InfResp_subscribers WHERE EmailAddress = '$address'";
        $DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);
        $query = "DELETE FROM InfResp_customfields WHERE email_attached = '$address'";
        $DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);
    }

    # Back button
    $return_action = "list";
    include('templates/back_button.blacklist.php');
} elseif (($action == "remove") && (isEmail($address))) {
    # Delete from blacklist
    $query = "DELETE FROM InfResp_blacklist WHERE EmailAddress = '$address'";
    $DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);

    # Print msg
    print "<br /><strong>Address deleted!</strong>\n";

    # Back button
    $return_action = "list";
    include('templates/back_button.blacklist.php');
} else {
    print "<p class=\"big_header\">Blacklist controls</p>\n";

    $query = "SELECT * FROM InfResp_blacklist";
    $DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);
    if ($DB_result->num_rows > 0) {
        # Remove address box
        print "<center>\n";
        print "<FORM action=\"blacklist.php\" method=POST> \n";
        print "<select name=\"address\" size=\"10\">\n";
        while ($result = $DB_result->fetch_assoc()) {
            $addy = $result['EmailAddress'];
            print "<option value=\"$addy\">$addy</option>\n";
        }
        print "</select>";
        print "<br />\n";
        print "<input type=\"hidden\" name=\"action\" value=\"remove\"> \n";
        print "<input type=\"submit\" name=\"remove\" value=\"Remove Address\" alt=\"Remove Address\">  \n";
        print "</FORM> \n";
        print "<br /></center>\n";
    } else {
        print "<br /><strong>No addresses listed!</strong><br /><br />\n";
    }

    # Add new address
    print "<center><br />\n";
    print "<FORM action=\"blacklist.php\" method=POST> \n";
    print "<input name=\"address\" size=40 maxlength=250 value=\"\" class=\"fields\">\n";
    print "<input type=\"hidden\" name=\"action\" value=\"add\"> \n";
    print "<input type=\"submit\" name=\"add\"    value=\"Add Address\" alt=\"Add Address\">  \n";
    print "</FORM></center>\n";

    # Back to admin button
    print "<br /><br />\n";
    print "<FORM action=\"admin.php\" method=POST> \n";
    print "<input type=\"hidden\" name=\"action\" value=\"list\"> \n";
    print "<input type=\"submit\" name=\"admin\"  value=\"<< To Admin\" alt=\"<< To Admin\">  \n";
    print "</FORM> \n";
}

# Template bottom
copyright();
include('templates/close.page.php');
