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

include('common.php');
requireUserToBeLoggedIn();

# ------------------------------------------------

function regexpExists($regexp_id)
{
    global $DB;

    # Bounds check
    if (isEmpty($regexp_id)) {
        return FALSE;
    }
    if ($regexp_id == "0") {
        return FALSE;
    }
    if (!(is_numeric($regexp_id))) {
        return FALSE;
    }

    # Check for it's existance
    $query = "SELECT * FROM InfResp_BounceRegs WHERE BounceRegexpID = '$regexp_id'";
    $result = $DB->query($query) or die("Invalid query: " . $DB->error);
    if ($result->num_rows > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

# ------------------------------------------------

# Get the action var
$action = strtolower(makeSafe($_REQUEST['action']));

# Top template
include('templates/open.page.php');

# Cpanel top
$help_section = "regexps";
include('templates/controlpanel.php');

# Set address
$address = makeSafe($_REQUEST['address']);

# Process actions
if ($action == "add") {
    $regexp = makeSafe($_REQUEST['regx']);
    $query = "SELECT * FROM InfResp_BounceRegs WHERE RegX = '$regexp'";
    $result = $DB->query($query) or die("Invalid query: " . $DB->error);
    if ($result->num_rows > 0) {
        # Print msg
        print "<p class=\"big_header\">That Regexp Already Exists!</p>\n";
    } else {
        $query = "INSERT INTO InfResp_BounceRegs (RegX) VALUES ('$regexp')";
        $result = $DB->query($query) OR die("Invalid query: " . $DB->error);
        $regx_id = $DB->insert_id;

        # Print msg
        print "<p class=\"big_header\">Regexp Added!</p>\n";
    }
} elseif ($action == "remove") {
    $regexp_id = makeSafe($_REQUEST['regx']);
    if (regexpExists($regexp_id)) {
        # Delete from the regexp table
        $query = "DELETE FROM InfResp_BounceRegs WHERE BounceRegexpID = '$regexp_id'";
        $result = $DB->query($query) OR die("Invalid query: " . $DB->error);

        # Print msg
        print "<p class=\"big_header\">Bouncer Regexp Deleted!</p>\n";
    } else {
        # Print msg
        print "<p class=\"big_header\">That Regexp Wasn't Found!</p>\n";
    }
}

print "<p class=\"big_header\">- Bouncer Regexps -</p>\n";
$query = "SELECT * FROM InfResp_BounceRegs";
$DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);
if ($DB_result->num_rows > 0) {
    # Remove regexp box
    print "<center>\n";
    print "<FORM action=\"regexps.php\" method=POST> \n";
    print "<select name=\"regx\" size=\"10\">\n";
    while ($result = $DB_result->fetch_assoc()) {
        print "<option value=\"" . $result['BounceRegexpID'] . "\">" . $result['RegX'] . "</option>\n";
    }
    print "</select>";
    print "<br />\n";
    print "<input type=\"hidden\" name=\"action\" value=\"remove\"> \n";
    print "<input type=\"submit\" name=\"admin\"  value=\"Remove Regexp\" alt=\"Remove Regexp\">  \n";
    print "</FORM> \n";
    print "<br /></center>\n";
} else {
    print "<br /><strong>No Regexps Found!</strong><br /><br />\n";
}

# Template for "add new"
include('templates/add_new.regexps.php');

# Template for "Back to admin"
include('templates/admin_button.regexps.php');

# Template bottom
copyright();
include('templates/close.page.php');
