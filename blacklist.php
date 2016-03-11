<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

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
    $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());
    if (mysql_num_rows($DB_result) > 0) {
        print "<br /><strong>That address is already in the blacklist!</strong>\n";
    } else {
        $query = "INSERT INTO InfResp_blacklist (EmailAddress) VALUES ('$address')";
        $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());
        print "<br /><strong>Address added!</strong>\n";

        # Remove from subscriber and custom fields tables
        $query = "DELETE FROM InfResp_subscribers WHERE EmailAddress = '$address'";
        $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());
        $query = "DELETE FROM InfResp_customfields WHERE email_attached = '$address'";
        $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());
    }

    # Back button
    $return_action = "list";
    include('templates/back_button.blacklist.php');
} elseif (($action == "remove") && (isEmail($address))) {
    # Delete from blacklist
    $query = "DELETE FROM InfResp_blacklist WHERE EmailAddress = '$address'";
    $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());

    # Print msg
    print "<br /><strong>Address deleted!</strong>\n";

    # Back button
    $return_action = "list";
    include('templates/back_button.blacklist.php');
} else {
    print "<p class=\"big_header\">Blacklist controls</p>\n";

    $query = "SELECT * FROM InfResp_blacklist";
    $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());
    if (mysql_num_rows($DB_result) > 0) {
        # Remove address box
        print "<center>\n";
        print "<FORM action=\"blacklist.php\" method=POST> \n";
        print "<select name=\"address\" size=\"10\">\n";
        while ($result = mysql_fetch_assoc($DB_result)) {
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

dbDisconnect();
?>
