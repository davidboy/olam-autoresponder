<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

$silent = $_REQUEST['silent'];
if ($silent == "1") {
    $silent = TRUE;
} else {
    $silent = FALSE;
}

include('common.php');

# Reset some variables
$DB_ResponderID = 0;
$DB_ResponderName = "";
$DB_OwnerEmail = "";
$DB_OwnerName = "";
$DB_ReplyToEmail = "";
$DB_MsgList = "";
$DB_ResponderDesc = "";

# Passed stuff
$Responder_ID = makeSafe($_REQUEST['r_ID']);
$action = makeSafe($_REQUEST['action']);
$HandleHTML = makeSafe($_REQUEST['h']);

# Bounds check
if ($HandleHTML != 1) {
    $HandleHTML = 0;
}
if (!(is_numeric($Responder_ID))) {
    $Responder_ID = 0;
}

# Logged in?
if ($Is_Auth = userAuth()) {
    # Top template
    if ($silent == FALSE) {
        include('templates/open.page.php');
    }

    # Process actions
    if ($action == "list") {
        $help_section = "editresps1";
        include('templates/controlpanel.php');

        $query = "SELECT * FROM InfResp_responders ORDER BY ResponderID";
        $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());

        # Top template
        $alt = TRUE;
        include('templates/list_top.responders.php');

        # Loop thru the list
        if (mysql_num_rows($DB_result) > 0) {
            $i = 0;
            while ($query_result = mysql_fetch_assoc($DB_result)) {
                $Responder_ID = $query_result['ResponderID'];
                $DB_ResponderID = $query_result['ResponderID'];
                $DB_ResponderName = $query_result['Name'];
                $DB_ResponderDesc = $query_result['ResponderDesc'];
                $DB_OwnerEmail = $query_result['OwnerEmail'];
                $DB_OwnerName = $query_result['OwnerName'];
                $DB_ReplyToEmail = $query_result['ReplyToEmail'];
                $DB_MsgList = $query_result['MsgList'];
                $DB_RespEnabled = $query_result['Enabled'];
                $DB_OptMethod = $query_result['OptMethod'];
                $DB_OptInRedir = $query_result['OptInRedir'];
                $DB_OptOutRedir = $query_result['OptOutRedir'];
                $DB_OptInDisplay = $query_result['OptInDisplay'];
                $DB_OptOutDisplay = $query_result['OptOutDisplay'];
                $DB_NotifyOnSub = $query_result['NotifyOwnerOnSub'];

                # Row template
                $alt = (!($alt));
                include('templates/list_row.responders.php');

                # Next!
                $i++;
            }
        } else {
            print "<br> \n";
            print "<strong>No responders exist. Click 'New' to create one.</strong><br>\n";
            print "<br> \n";
        }

        # Bottom template
        include('templates/list_bottom.responders.php');
    } elseif ($action == "create") {
        # Display template
        include('templates/create.responders.php');
    } elseif ($action == "update") {
        if (!(responderExists($Responder_ID))) {
            adminRedirect();
        }
        getResponderInfo();

        # Display template
        include('templates/update_top.responders.php');

        # Resp msg anchor
        print "<a name=\"responder_msgs\">&nbsp;</a>\n";

        # Messages start here.
        $DB_MsgList = trim($DB_MsgList, ",");
        $DB_MsgList = trim($DB_MsgList);
        $MsgList_Array = explode(',', trim($DB_MsgList));
        $Max_Index = sizeof($MsgList_Array);

        # Explode likes to treat NULL as an element. :/
        if (trim($DB_MsgList) == NULL) {
            $Max_Index = 0;
        }
        if ($DB_MsgList == "") {
            $Max_Index = 0;
        }
        if ($Max_Index == 0) {
            # No msgs found!
            include('templates/no_responder_msgs.responders.php');
        } else {
            # Msg list header
            $alt = TRUE;
            include('templates/msg_list_top.responders.php');

            for ($i = 0; $i <= $Max_Index - 1; $i++) {
                $M_ID = trim($MsgList_Array[$i]);
                getMsgInfo($M_ID);

                # gmp_mod and fmod aren't working on my host for some reason. :-(
                $T_minutes = intval($DB_MsgSeconds / 60);
                $T_seconds = $DB_MsgSeconds - ($T_minutes * 60);
                $T_hours = intval($T_minutes / 60);
                $T_minutes = $T_minutes - ($T_hours * 60);
                $T_days = intval($T_hours / 24);
                $T_hours = $T_hours - ($T_days * 24);
                $T_weeks = intval($T_days / 7);
                $T_days = $T_days - ($T_weeks * 7);
                $T_months = $DB_MsgMonths;

                # Display message row
                $alt = (!($alt));
                include('templates/msg_list_row.responders.php');
            }

            # Msg list footer
            include('templates/msg_list_bottom.responders.php');
        }

        # Display new msg template
        include('templates/new_msg.responders.php');
    } elseif ($action == "erase") {
        if (!(responderExists($Responder_ID))) {
            adminRedirect();
        }
        getResponderInfo();

        # Display template
        include('templates/erase.responders.php');
    } elseif ($action == "do_create") {

        $Resp_Enabled = '1';
        $Resp_Name = makeSemiSafe($_REQUEST['Resp_Name']);
        $Resp_Desc = makeSemiSafe($_REQUEST['Resp_Desc']);
        $Reply_To = makeSafe($_REQUEST['Reply_To']);
        $Owner_Name = makeSafe($_REQUEST['Owner_Name']);
        $Owner_Email = makeSafe($_REQUEST['Owner_Email']);
        $OptMethod = makeSafe($_REQUEST['OptMethod']);
        $OptInRedir = makeSafe($_REQUEST['OptInRedir']);
        $OptOutRedir = makeSafe($_REQUEST['OptOutRedir']);
        $OptInDisp = makeSemiSafe($_REQUEST['OptInDisplay']);
        $OptOutDisp = makeSemiSafe($_REQUEST['OptOutDisplay']);
        $NotifyOwner = makeSemiSafe($_REQUEST['NotifyOwner']);
        if ($NotifyOwner != "1") {
            $NotifyOwner = "0";
        }
        if ($OptMethod != "Double") {
            $OptMethod = "Single";
        }
        $Msg_List = '';

        $query = "INSERT INTO InfResp_responders (Name, Enabled, ResponderDesc, OwnerEmail, OwnerName, ReplyToEmail, MsgList, OptMethod, OptInRedir, OptOutRedir, OptInDisplay, OptOutDisplay, NotifyOwnerOnSub)
              VALUES('$Resp_Name', '$Resp_Enabled', '$Resp_Desc', '$Owner_Email', '$Owner_Name', '$Reply_To', '$Msg_List', '$OptMethod', '$OptInRedir', '$OptOutRedir', '$OptInDisp', '$OptOutDisp', '$NotifyOwner')";
        $DB_result = mysql_query($query)
        or die("Invalid query: " . mysql_error());

        # Done!
        print "<H3 style=\"color : #003300\">Responder Added!</H3> \n";
        print "<font size=4 color=\"#660000\">Return to list. <br></font> \n";

        # Print back button
        $return_action = "list";
        include('templates/back_button.responders.php');
    } elseif ($action == "do_update") {
        if (!(responderExists($Responder_ID))) {
            adminRedirect();
        }

        $Resp_Name = makeSemiSafe($_REQUEST['Resp_Name']);
        $Resp_Desc = makeSemiSafe($_REQUEST['Resp_Desc']);
        $Reply_To = makeSafe($_REQUEST['Reply_To']);
        $Owner_Name = makeSafe($_REQUEST['Owner_Name']);
        $Owner_Email = makeSafe($_REQUEST['Owner_Email']);
        $OptMethod = makeSafe($_REQUEST['OptMethod']);
        $OptInRedir = makeSafe($_REQUEST['OptInRedir']);
        $OptOutRedir = makeSafe($_REQUEST['OptOutRedir']);
        $OptInDisp = makeSemiSafe($_REQUEST['OptInDisplay']);
        $OptOutDisp = makeSemiSafe($_REQUEST['OptOutDisplay']);
        $NotifyOwner = makeSemiSafe($_REQUEST['NotifyOwner']);
        if ($OptMethod != "Double") {
            $OptMethod = "Single";
        }
        if ($NotifyOwner != "1") {
            $NotifyOwner = "0";
        }

        $query = "UPDATE InfResp_responders
              SET Name = '$Resp_Name', 
                  ResponderDesc = '$Resp_Desc', 
                  OwnerEmail = '$Owner_Email', 
                  OwnerName = '$Owner_Name', 
                  ReplyToEmail = '$Reply_To',
                  OptMethod = '$OptMethod',
                  OptInRedir = '$OptInRedir',
                  OptOutRedir = '$OptOutRedir',
                  OptInDisplay = '$OptInDisp',
                  OptOutDisplay = '$OptOutDisp',
                  NotifyOwnerOnSub = '$NotifyOwner'
              WHERE ResponderID = '$Responder_ID'";
        $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());

        # Done!
        print "<H3 style=\"color : #003300\">Responder Saved!</H3> \n";
        print "<font size=4 color=\"#660000\">Return to list. <br></font> \n";

        # Print back button
        $return_action = "list";
        include('templates/back_button.responders.php');
    } elseif ($action == "do_erase") {
        if (!(responderExists($Responder_ID))) {
            adminRedirect();
        }
        getResponderInfo();

        $DB_MsgList = trim($DB_MsgList, ",");
        $DB_MsgList = trim($DB_MsgList);

        $query = "DELETE FROM InfResp_responders WHERE ResponderID = '$Responder_ID'";
        $DB_result = mysql_query($query)
        or die("Invalid query: " . mysql_error());

        $query = "DELETE FROM InfResp_POP3 WHERE Attached_Responder = '$Responder_ID'";
        $DB_result = mysql_query($query)
        or die("Invalid query: " . mysql_error());

        $MsgList_Array = explode(',', $DB_MsgList);
        $Max_Index = sizeof($MsgList_Array);

        # Explode likes to treat NULL as an element. :/
        if (trim($DB_MsgList) == NULL) {
            $Max_Index = 0;
        }
        if ($DB_MsgList == "") {
            $Max_Index = 0;
        }

        for ($i = 0; $i <= $Max_Index - 1; $i++) {
            $Temp_ID = trim($MsgList_Array[$i]);
            $query = "DELETE FROM InfResp_messages WHERE MsgID = '$Temp_ID'";
            $DB_result = mysql_query($query)
            or die("Invalid query: " . mysql_error());
        }

        $query = "DELETE FROM InfResp_subscribers WHERE ResponderID = '$Responder_ID'";
        $DB_result = mysql_query($query)
        or die("Invalid query: " . mysql_error());

        # Done!
        print "<H3 style=\"color : #003300\">Responder Deleted!</H3> \n";
        print "<font size=4 color=\"#660000\">Return to list.</font> <br>\n";

        # Print back button
        $return_action = "list";
        include('templates/back_button.responders.php');
    } elseif ($action == "POP3") {
        # Check if there's a DB entry
        # If no, create one and set variables
        # If yes, just set variables
        # Print pop3 screen
        # Save button, back button

        if (!(responderExists($Responder_ID))) {
            adminRedirect();
        }
        getResponderInfo();

        $query = "SELECT * FROM InfResp_POP3 WHERE Attached_Responder = '$Responder_ID' LIMIT 1";
        $DB_POP3_Result = mysql_query($query) or die("Invalid query: " . mysql_error());

        if (mysql_num_rows($DB_POP3_Result) < 1) {
            # POP3 defaults.
            $DB_Attached_Responder = $Responder_ID;
            $DB_POP3_host = 'localhost';
            $DB_POP3_port = '110';
            $DB_POP3_username = 'username';
            $DB_POP3_password = 'password';
            $DB_POP3_mailbox = 'INBOX';
            $DB_Pop_Enabled = 0;
            $DB_Confirm_Join = 1;
            $DB_HTML_YN = 0;
            $DB_DeleteYN = 0;
            $DB_SpamHeader = '***SPAM***';
            $DB_ConcatMid = '1';
            $DB_Mail_Type = 'pop3';

            $insertquery = "INSERT INTO InfResp_POP3 (ThisPOP_Enabled, Confirm_Join, Attached_Responder, host, port, username, password, mailbox, HTML_YN, Delete_After_Download, Spam_Header, Concat_Middle, Mail_Type)
                       VALUES('$DB_Pop_Enabled','$DB_Confirm_Join','$DB_Attached_Responder','$DB_POP3_host','$DB_POP3_port','$DB_POP3_username','$DB_POP3_password','$DB_POP3_mailbox','$DB_HTML_YN','$DB_DeleteYN','$DB_SpamHeader','$DB_ConcatMid','$DB_Mail_Type')";
            $DB_POP3_Insert_Result = mysql_query($insertquery)
            or die("Invalid query: " . mysql_error());
            if (mysql_affected_rows() > 0) {
                $DB_POP_ConfID = mysql_insert_id();
            }
        } else {
            $POP3_Result = mysql_fetch_assoc($DB_POP3_Result);
            $DB_POP_ConfID = $POP3_Result['POP_ConfigID'];
            $DB_Pop_Enabled = $POP3_Result['ThisPOP_Enabled'];
            $DB_Confirm_Join = $POP3_Result['Confirm_Join'];
            $DB_Attached_Responder = $POP3_Result['Attached_Responder'];
            $DB_POP3_host = $POP3_Result['host'];
            $DB_POP3_port = $POP3_Result['port'];
            $DB_POP3_username = $POP3_Result['username'];
            $DB_POP3_password = $POP3_Result['password'];
            $DB_POP3_mailbox = $POP3_Result['mailbox'];
            $DB_HTML_YN = $POP3_Result['HTML_YN'];
            $DB_DeleteYN = $POP3_Result['Delete_After_Download'];
            $DB_SpamHeader = $POP3_Result['Spam_Header'];
            $DB_ConcatMid = $POP3_Result['Concat_Middle'];
            $DB_Mail_Type = $POP3_Result['Mail_Type'];
        }

        # Show template
        include('templates/pop3.responders.php');

        # Print back button
        $return_action = "update";
        include('templates/back_button.responders.php');
    } elseif ($action == "custom_stuff") {

        $query = "SELECT * FROM InfResp_customfields WHERE resp_attached = '$Responder_ID'";
        $DB_result = mysql_query($query)
        or die("Invalid query: " . mysql_error());

        print "<br>\n";

        if (mysql_num_rows($DB_result) > 0) {
            $i = 0;
            while ($DBarray = mysql_fetch_assoc($DB_result)) {
                foreach ($DBarray as $key => $value) {
                    print "$key: $value <br>\n";
                }
                $i++;
                print "<br>\n";
            }
            print "<br>\n";
            print "<FORM action=\"responders.php\" method=POST>\n";
            print "<input type=\"hidden\" name=\"action\"     value=\"custom_stuff_csv\">\n";
            print "<input type=\"hidden\" name=\"r_ID\"       value=\"$Responder_ID\">\n";
            print "<input type=\"hidden\" name=\"silent\"     value=\"1\">\n";
            print "<input type=\"submit\" name=\"submit\"     value=\"Print as CSV\">\n";
            print "</FORM>\n";
        } else {
            print "<br>\nNo custom data found.<br>\n";
        }

        # Print back button
        $return_action = "update";
        include('templates/back_button.responders.php');
    } elseif ($action == "custom_stuff_csv") {
        $filename = time();
        header("Content-Disposition: attachment; filename=$filename.csv");
        header("Content-Type: application/octet-stream");
        header("Pragma: no-cache");
        header("Expires: 0");

        $query = "SELECT * FROM InfResp_customfields WHERE resp_attached = '$Responder_ID'";
        $DB_result = mysql_query($query)
        or die("Invalid query: " . mysql_error());

        $CustomFieldsArray = getFieldNames('InfResp_customfields');
        $fieldstr = "";
        foreach ($CustomFieldsArray as $key => $value) {
            $fieldstr .= "$value,";
        }
        $fieldstr = trim(trim($fieldstr), ",");
        print "$fieldstr\n";

        while ($DBarray = mysql_fetch_assoc($DB_result)) {
            $datastr = "";
            foreach ($CustomFieldsArray as $key => $value) {
                $datastr .= $DBarray[$value] . ",";
            }
            $datastr = trim(trim($datastr), ",");
            print "$datastr\n";
        }
    } elseif ($action == "do_POP3") {

        # Test variable passing
        if (!(responderExists($Responder_ID))) {
            adminRedirect();
        }
        getResponderInfo();

        $user = makeSafe($_REQUEST['pop3_user']);
        $pass = makeSafe($_REQUEST['pop3_pw']);
        $Mbox = makeSafe($_REQUEST['pop3_box']);
        $host = makeSafe($_REQUEST['pop3_host']);
        $port = makeSafe($_REQUEST['pop3_port']);
        $spam = makeSafe($_REQUEST['pop3_spam']);
        $cmid = makeSafe($_REQUEST['pop3_cmid']);
        $type = strtolower(makeSafe($_REQUEST['pop3_type']));
        $POP3_ID = makeSafe($_REQUEST['pop3_ID']);

        # $HandleHTML, $deletemsgs, $confirmjoin, $enabled

        $deletemsgs = makeSafe($_REQUEST['pop3_deletemsgs']);
        $confirmjoin = makeSafe($_REQUEST['pop3_confirmjoin']);
        $enabled = makeSafe($_REQUEST['pop3_enabled']);
        if ($deletemsgs == 1) {
        } else {
            $deletemsgs = 0;
        }
        if ($confirmjoin == 1) {
        } else {
            $confirmjoin = 0;
        }
        if ($enabled == 1) {
        } else {
            $enabled = 0;
        }
        if ($cmid != 1) {
            $cmid = 0;
        }
        if (($type != "imap") && ($type != "pop3") && ($type != "nntp")) {
            $type = "pop3";
        }

        $query = "UPDATE InfResp_POP3
             SET ThisPOP_Enabled = '$enabled',
                 Confirm_Join = '$confirmjoin',
                 host = '$host',
                 port = '$port',
                 username = '$user',
                 password = '$pass',
                 mailbox = '$Mbox',
                 HTML_YN = '$HandleHTML',
                 Delete_After_Download = '$deletemsgs',
                 Spam_Header = '$spam',
                 Concat_Middle = '$cmid',
                 Mail_Type = '$type'
             WHERE Attached_Responder = '$Responder_ID'";
        $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());

        # Done!
        print "<H3 style=\"color : #003300\">POP3 changes saved!</H3> \n";
        print "<font size=4 color=\"#660000\">Return to Responder.</font> <br>\n";

        # Print back button
        $return_action = "update";
        include('templates/back_button.responders.php');
    } else {
        adminRedirect();
    }

    # Bottom template
    if ($silent == FALSE) {
        copyright();
        include('templates/close.page.php');
    }
} else {
    adminRedirect();
}

dbDisconnect();
?>