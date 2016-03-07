<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

include('common.php');
include('templates/open.page.php');

$Responder_ID = makeSafe($_REQUEST['r_ID']);
$action = makeSafe($_REQUEST['action']);

# ----------------------------------------------------------------------------------
# Anti-spam phrase. It's added to the end of all email addressed to make it more
# difficult for spammers to harvest the addresses.
# $antispam = "";      # To disable anti-spam.
#
$antispam = "@nospam";
#
# ----------------------------------------------------------------------------------

if ($action == "subscribe") {
    # --------------------------------------------------------------------------------
    print "<br><font color=\"#000066\">\n";
    print "<center>\n";
    print "<table cellspacing=\"10\" bgcolor=\"#CCCCCC\" style=\"border: 1px solid #000000;\"><tr><td>\n";
    print "<form action=\"$siteURL$ResponderDirectory/s.php\" method=GET>\n";
    print "<strong><font color=\"#660000\">Your name (First, Last):</font></strong><br>\n";
    print "<input type=\"text\" name=\"f\" style=\"background-color : #FFFFFF\" size=11 maxlength=40>\n";
    print " <input type=\"text\" name=\"l\" style=\"background-color : #FFFFFF\" size=11 maxlength=40>\n";
    print "<br><br>\n";
    print "<strong><font color=\"#000066\">Email address:</font></strong><br>\n";
    print "<input type=\"text\" name=\"e\" style=\"background-color : #FFFFFF\" size=20 maxlength=50>\n";
    print "<input type=\"image\" src=\"$siteURL$ResponderDirectory/images/go-button.gif\" name=\"submit\" value=\"Submit\"><br>\n";
    print "<input type=\"hidden\" name=\"r\" value=\"$Responder_ID\">\n";
    print "<input type=\"hidden\" name=\"a\" value=\"sub\">\n";
    print "<br>\n";
    print "<font color=\"#003300\">HTML: <input type=\"RADIO\" name=\"h\" value=\"1\">Yes &nbsp;\n";
    print "<input type=\"RADIO\" name=\"h\" value=\"0\" checked=\"checked\">No<br> \n";
    print "</font></form>\n";
    print "</td></tr></table>\n";
    print "</center>\n";
    # --------------------------------------------------------------------------------
    print "<br><br>\n";
    print "<strong>Back to Responder List.</strong><br>\n";
    print "<FORM action=\"list.php\" method=POST> \n";
    print "<input type=\"hidden\" name=\"action\" value=\"list\"> \n";
    print "<input type=\"submit\" name=\"Back\" value=\"<< Back\" alt=\"<< Back\">  \n";
    print "</FORM> \n";
    print "</font>\n";
    # --------------------------------------------------------------------------------
} else {
    $query = "SELECT * FROM InfResp_responders ORDER BY ResponderID";
    $DB_result = mysql_query($query)
    or die("Invalid query: " . mysql_error());

    if (mysql_num_rows($DB_result) > 0) {

        print "<br>\n";
        print "<center><font color=\"#003300\" size=\"5\">List of Responders</font></center>\n";

        $i = 0;
        while ($query_result = mysql_fetch_assoc($DB_result)) {
            $DB_ResponderID = $query_result['ResponderID'];
            $DB_RespEnabled = $query_result['Enabled'];
            $DB_ResponderName = $query_result['Name'];
            $DB_ResponderDesc = $query_result['ResponderDesc'];
            $DB_OwnerEmail = $query_result['OwnerEmail'];
            $DB_OwnerName = $query_result['OwnerName'];
            $DB_ReplyToEmail = $query_result['ReplyToEmail'];
            $DB_MsgList = $query_result['MsgList'];
            $DB_OptMethod = $query_result['OptMethod'];
            $DB_OptInRedir = $query_result['OptInRedir'];
            $DB_OptOutRedir = $query_result['OptOutRedir'];
            $DB_OptInDisplay = $query_result['OptInDisplay'];
            $DB_OptOutDisplay = $query_result['OptOutDisplay'];
            $DB_NotifyOnSub = $query_result['NotifyOwnerOnSub'];

            # Show responder row
            include('templates/list.list.php');
        }
    }
}

# Template bottom
copyright();
include('templates/close.page.php');

dbDisconnect();
?>
