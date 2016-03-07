<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

include('common.php');

# Grab the data
$Responder_ID = MakeSafe($_REQUEST['r_ID']);
$M_ID = MakeSafe($_REQUEST['MSG_ID']);
$action = MakeSafe($_REQUEST['action']);

if (!(is_numeric($Responder_ID))) {
    # A small bit of magic to filter out any screwy crackerness of the RespID
    $Responder_ID = NULL;
}
if (!(is_numeric($M_ID))) {
    # Same with Message ID.
    $M_ID = NULL;
}

if ($Is_Auth = User_Auth()) {
    # Template top
    include('templates/open.page.php');
    include_once('popup_js.php');

    # Check responder ID
    if (!(ResponderExists($Responder_ID))) {
        admin_redirect();
    }

    # Action processing
    if ($action == "create") {
        # Init vars
        $DB_absDay = "";
        $DB_absHours = 0;
        $DB_absMins = 0;

        # Display template
        include('templates/create.messages.php');
    } elseif ($action == "update") {
        GetMsgInfo($M_ID);

        # Do the math
        $T_minutes = intval($DB_MsgSeconds / 60);
        $T_seconds = $DB_MsgSeconds - ($T_minutes * 60);
        $T_hours = intval($T_minutes / 60);
        $T_minutes = $T_minutes - ($T_hours * 60);
        $T_days = intval($T_hours / 24);
        $T_hours = $T_hours - ($T_days * 24);
        $T_weeks = intval($T_days / 7);
        $T_days = $T_days - ($T_weeks * 7);
        $T_months = $DB_MsgMonths;

        # Select the correct absDay
        if ($DB_absDay == "Sunday") {
            $absday['Sunday'] = " SELECTED";
        } else {
            $absday['Sunday'] = "";
        }
        if ($DB_absDay == "Monday") {
            $absday['Monday'] = " SELECTED";
        } else {
            $absday['Monday'] = "";
        }
        if ($DB_absDay == "Tuesday") {
            $absday['Tuesday'] = " SELECTED";
        } else {
            $absday['Tuesday'] = "";
        }
        if ($DB_absDay == "Wednesday") {
            $absday['Wednesday'] = " SELECTED";
        } else {
            $absday['Wednesday'] = "";
        }
        if ($DB_absDay == "Thursday") {
            $absday['Thursday'] = " SELECTED";
        } else {
            $absday['Thursday'] = "";
        }
        if ($DB_absDay == "Friday") {
            $absday['Friday'] = " SELECTED";
        } else {
            $absday['Friday'] = "";
        }
        if ($DB_absDay == "Saturday") {
            $absday['Saturday'] = " SELECTED";
        } else {
            $absday['Saturday'] = "";
        }

        # Debug info
        # print "  MsgID   $DB_MsgID<br>\n";
        # print "  MsgSub  $DB_MsgSub<br>\n";
        # print "  MsgSec  $DB_MsgSeconds<br>\n";
        # print "  Months  $DB_MsgMonths<br>\n";
        # print "  AbsDay  $DB_absDay<br>\n";
        # print "  AbsMin  $DB_absMins<br>\n";
        # print "  AbsHour $DB_absHours<br>\n";
        # print "  MsgBody $DB_MsgBodyText<br>\n";
        # print "  MsgHTML $DB_MsgBodyHTML<br>\n";

        # Display template
        include('templates/update.messages.php');
    } elseif ($action == "delete") {
        GetMsgInfo($M_ID);

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

        # Display template
        include('templates/delete.messages.php');
    } elseif ($action == "do_create") {
        # Prep data
        $P_subj = MakeSemiSafe($_REQUEST['subj']);
        $P_bodytext = MakeSemiSafe($_REQUEST['bodytext']);
        $P_bodyhtml = MakeSemiSafe($_REQUEST['bodyhtml']);
        $P_months = MakeSafe($_REQUEST['months']);
        $P_weeks = MakeSafe($_REQUEST['weeks']);
        $P_days = MakeSafe($_REQUEST['days']);
        $P_hours = MakeSafe($_REQUEST['hours']);
        $P_min = MakeSafe($_REQUEST['min']);
        $P_absday = MakeSafe($_REQUEST['abs_day']);
        $P_abshours = MakeSafe($_REQUEST['abs_hours']);
        $P_absmin = MakeSafe($_REQUEST['abs_min']);

        if (!(is_numeric($P_months))) {
            $P_months = 0;
        }
        if (!(is_numeric($P_weeks))) {
            $P_weeks = 0;
        }
        if (!(is_numeric($P_days))) {
            $P_days = 0;
        }
        if (!(is_numeric($P_hours))) {
            $P_hours = 0;
        }
        if (!(is_numeric($P_min))) {
            $P_min = 0;
        }
        if (!(is_numeric($P_abshours))) {
            $P_abshours = 0;
        }
        if (!(is_numeric($P_absmin))) {
            $P_absmin = 0;
        }
        if (($P_absday != "Monday") && ($P_absday != "Tuesday") && ($P_absday != "Wednesday") && ($P_absday != "Thursday") && ($P_absday != "Friday") && ($P_absday != "Saturday") && ($P_absday != "Sunday")) {
            $P_absday = "";
        }

        GetResponderInfo();

        $TempDay_Seconds = (($P_weeks * 7) + $P_days) * 86400;
        $TempHour_Seconds = 3600 * $P_hours;
        $TempMin_Seconds = 60 * $P_min;

        $Time_stamp = $TempDay_Seconds + $TempHour_Seconds + $TempMin_Seconds;

        # Add row to database
        $query = "INSERT INTO InfResp_messages (Subject, SecMinHoursDays, Months, absDay, absMins, absHours, BodyText, BodyHTML)
                VALUES('$P_subj', '$Time_stamp', '$P_months', '$P_absday', '$P_absmin', '$P_abshours', '$P_bodytext', '$P_bodyhtml')";
        $DB_result = mysql_query($query)
        or die("Invalid query: " . mysql_error());

        # Clear $M_ID. If the query was successful then get the new $M_ID and
        # and attach it to the end of the Responder's message list.
        $M_ID = 0;
        if (mysql_affected_rows() > 0) {
            $M_ID = mysql_insert_id();
            $Update_MsgList = $DB_MsgList . "," . $M_ID;
            $Update_MsgList = trim($Update_MsgList, ",");
        }

        # Update Responder MsgList with new list string.
        $query = "UPDATE InfResp_responders
                SET MsgList = '$Update_MsgList'
                WHERE ResponderID = '$Responder_ID'";
        $DB_result = mysql_query($query)
        or die("Invalid query: " . mysql_error());

        # Done!
        print "<H3 style=\"color : #003300\">Message added!</H3> \n";
        print "<font size=4 color=\"#660000\">Return to list. <br></font> \n";

        # Print back button
        $return_action = "update";
        include('templates/back_button.messages.php');
    } elseif ($action == "do_update") {
        # Prep the data
        $P_subj = MakeSemiSafe($_REQUEST['subj']);
        $P_bodytext = MakeSemiSafe($_REQUEST['bodytext']);
        $P_bodyhtml = MakeSemiSafe($_REQUEST['bodyhtml']);
        $P_months = MakeSafe($_REQUEST['months']);
        $P_weeks = MakeSafe($_REQUEST['weeks']);
        $P_days = MakeSafe($_REQUEST['days']);
        $P_hours = MakeSafe($_REQUEST['hours']);
        $P_min = MakeSafe($_REQUEST['min']);
        $P_absday = MakeSafe($_REQUEST['abs_day']);
        $P_abshours = MakeSafe($_REQUEST['abs_hours']);
        $P_absmin = MakeSafe($_REQUEST['abs_min']);

        if (!(is_numeric($P_months))) {
            $P_months = 0;
        }
        if (!(is_numeric($P_weeks))) {
            $P_weeks = 0;
        }
        if (!(is_numeric($P_days))) {
            $P_days = 0;
        }
        if (!(is_numeric($P_hours))) {
            $P_hours = 0;
        }
        if (!(is_numeric($P_min))) {
            $P_min = 0;
        }
        if (!(is_numeric($P_abshours))) {
            $P_abshours = 0;
        }
        if (!(is_numeric($P_absmin))) {
            $P_absmin = 0;
        }
        if (($P_absday != "Monday") && ($P_absday != "Tuesday") && ($P_absday != "Wednesday") && ($P_absday != "Thursday") && ($P_absday != "Friday") && ($P_absday != "Saturday") && ($P_absday != "Sunday")) {
            $P_absday = "";
        }

        $TempDay_Seconds = (($P_weeks * 7) + $P_days) * 86400;
        $TempHour_Seconds = 3600 * $P_hours;
        $TempMin_Seconds = 60 * $P_min;

        $Time_stamp = $TempDay_Seconds + $TempHour_Seconds + $TempMin_Seconds;

        #print "M_ID: $M_ID <br>\n";
        #print "P_subj: $P_subj <br>\n";
        #print "P_bodytext: $P_bodytext <br>\n";
        #print "P_bodyhtml: $P_bodyhtml <br>\n";
        #print "P_months: $P_months <br>\n";
        #print "P_weeks: $P_weeks <br>\n";
        #print "P_days: $P_days <br>\n";
        #print "P_hours: $P_hours <br>\n";
        #print "P_min: $P_min <br>\n";
        #print "Time: $Time_stamp <br>\n";
        #print "Abs day: " . $P_absday . "<br>\n";
        #print "Abs min: " . $P_absmin . "<br>\n";
        #print "Abs hour: " . $P_abshours . "<br>\n";

        # subject, body text, body html, timestamp, months

        $query = "UPDATE InfResp_messages
                SET Subject = '$P_subj',
                    SecMinHoursDays = '$Time_stamp',
                    Months = '$P_months',
                    absDay = '$P_absday',
                    absMins = '$P_absmin',
                    absHours = '$P_abshours',
                    BodyText = '$P_bodytext',
                    BodyHTML = '$P_bodyhtml'
                WHERE MsgID = '$M_ID'";

        $DB_result = mysql_query($query)
        or die("Invalid query: " . mysql_error());

        # Done!
        print "<H3 style=\"color : #003300\">Message Saved!</H3> \n";
        print "<font size=4 color=\"#660000\">Return to list. <br></font> \n";

        # Print back button
        $return_action = "update";
        include('templates/back_button.messages.php');
    } elseif ($action == "do_delete") {

        if (!(ResponderExists($Responder_ID))) {
            die("Responder $Responder_ID does not exist");
        }

        GetResponderInfo();

        $NewList = "";
        $MsgList_Array = explode(',', $DB_MsgList);
        $Max_Index = sizeof($MsgList_Array);
        for ($i = 0; $i <= $Max_Index - 1; $i++) {
            $Temp_ID = trim($MsgList_Array[$i]);
            if ($Temp_ID != $M_ID) {
                $NewList = $NewList . "," . $Temp_ID;
            }
        }
        $NewList = trim($NewList, ",");

        $query = "DELETE FROM InfResp_messages WHERE MsgID = '$M_ID'";
        $DB_result = mysql_query($query)
        or die("Invalid query: " . mysql_error());

        $query = "UPDATE InfResp_responders SET MsgList = '$NewList' WHERE ResponderID = '$Responder_ID'";
        $DB_result = mysql_query($query)
        or die("Invalid query: " . mysql_error());

        # Done!
        print "<H3 style=\"color : #003300\">Message deleted!</H3> \n";
        print "<font size=4 color=\"#660000\">Return to list. <br></font> \n";

        # Print back button
        $return_action = "update";
        include('templates/back_button.messages.php');
    } else {
        print "<br> \n";
        print "I'm sorry, I didn't understand your 'action' variable. Please try again. <br> \n";
    }

    # Template bottom
    copyright();
    include('templates/close.page.php');
} else {
    admin_redirect();
}

DB_disconnect();
?>