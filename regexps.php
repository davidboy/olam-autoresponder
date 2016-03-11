<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

include('common.php');
requireUserToBeLoggedIn();

# ------------------------------------------------

function regexpExists($regexp_id)
{
    global $DB_LinkID;

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
    $result = mysql_query($query, $DB_LinkID) or die("Invalid query: " . mysql_error());
    if (mysql_num_rows($result) > 0) {
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
    $result = mysql_query($query) or die("Invalid query: " . mysql_error());
    if (mysql_num_rows($result) > 0) {
        # Print msg
        print "<p class=\"big_header\">That Regexp Already Exists!</p>\n";
    } else {
        $query = "INSERT INTO InfResp_BounceRegs (RegX) VALUES ('$regexp')";
        $result = mysql_query($query) OR die("Invalid query: " . mysql_error());
        $regx_id = mysql_insert_id();

        # Print msg
        print "<p class=\"big_header\">Regexp Added!</p>\n";
    }
} elseif ($action == "remove") {
    $regexp_id = makeSafe($_REQUEST['regx']);
    if (regexpExists($regexp_id)) {
        # Delete from the regexp table
        $query = "DELETE FROM InfResp_BounceRegs WHERE BounceRegexpID = '$regexp_id'";
        $result = mysql_query($query) OR die("Invalid query: " . mysql_error());

        # Print msg
        print "<p class=\"big_header\">Bouncer Regexp Deleted!</p>\n";
    } else {
        # Print msg
        print "<p class=\"big_header\">That Regexp Wasn't Found!</p>\n";
    }
}

print "<p class=\"big_header\">- Bouncer Regexps -</p>\n";
$query = "SELECT * FROM InfResp_BounceRegs";
$DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());
if (mysql_num_rows($DB_result) > 0) {
    # Remove regexp box
    print "<center>\n";
    print "<FORM action=\"regexps.php\" method=POST> \n";
    print "<select name=\"regx\" size=\"10\">\n";
    while ($result = mysql_fetch_assoc($DB_result)) {
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
