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

# Grab the data
$Responder_ID = makeSafe(@$_REQUEST['r_ID']);
$M_ID = makeSafe(@$_REQUEST['MSG_ID']);
$action = makeSafe(@$_REQUEST['action']);

if (!(is_numeric($Responder_ID))) {
    # A small bit of magic to filter out any screwy crackerness of the RespID
    $Responder_ID = NULL;
}
if (!(is_numeric($M_ID))) {
    # Same with Message ID.
    $M_ID = NULL;
}

# Template top
include('templates/open.page.php');
include_once('popup_js.php');

# Check responder ID
if (!(responderExists($Responder_ID))) {
    redirectTo('/admin.php');
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
    getMsgInfo($M_ID);

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

    # Display template
    include('templates/delete.messages.php');
} elseif ($action == "do_create") {

    # Process the uploaded file
    if (!empty($_FILES['attachment']['name'])) {
        # Ensure the uploads directory exists
        $upload_directory = __DIR__ . '/storage/';
        if (!file_exists($upload_directory)) {
            mkdir($upload_directory);
        }

        $attachment_filename = basename($_FILES['attachment']['name']);
        $attachment_storage_filename = uniqid(rand());

        move_uploaded_file($_FILES['attachment']['tmp_name'], $upload_directory . $attachment_storage_filename);
    }

    # Prep data
    $P_subj = makeSemiSafe($_REQUEST['subj']);
    $P_bodytext = makeSemiSafe($_REQUEST['bodytext']);
    $P_bodyhtml = makeSemiSafe($_REQUEST['bodyhtml']);
    $P_months = makeSafe($_REQUEST['months']);
    $P_weeks = makeSafe($_REQUEST['weeks']);
    $P_days = makeSafe($_REQUEST['days']);
    $P_hours = makeSafe($_REQUEST['hours']);
    $P_min = makeSafe($_REQUEST['min']);
    $P_absday = makeSafe($_REQUEST['abs_day']);
    $P_abshours = makeSafe($_REQUEST['abs_hours']);
    $P_absmin = makeSafe($_REQUEST['abs_min']);

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

    getResponderInfo();

    $TempDay_Seconds = (($P_weeks * 7) + $P_days) * 86400;
    $TempHour_Seconds = 3600 * $P_hours;
    $TempMin_Seconds = 60 * $P_min;

    $Time_stamp = $TempDay_Seconds + $TempHour_Seconds + $TempMin_Seconds;

    // The query to create a message is slightly different if we have an attachment
    if (isset($attachment_filename) && isset($attachment_storage_filename)) {
        $query = "INSERT INTO InfResp_messages (Subject, SecMinHoursDays, Months, absDay, absMins, absHours, BodyText, BodyHTML, attachmentName, attachmentStorageName)
              VALUES('$P_subj', '$Time_stamp', '$P_months', '$P_absday', '$P_absmin', '$P_abshours', '$P_bodytext', '$P_bodyhtml', '$attachment_filename', '$attachment_storage_filename')";
    } else {
        $query = "INSERT INTO InfResp_messages (Subject, SecMinHoursDays, Months, absDay, absMins, absHours, BodyText, BodyHTML)
              VALUES('$P_subj', '$Time_stamp', '$P_months', '$P_absday', '$P_absmin', '$P_abshours', '$P_bodytext', '$P_bodyhtml')";

    }

    # Add row to database
    $DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);

    # Clear $M_ID. If the query was successful then get the new $M_ID and
    # and attach it to the end of the Responder's message list.
    $M_ID = 0;
    if ($DB->affected_rows > 0) {
        $M_ID = $DB->insert_id;
        $Update_MsgList = $DB_MsgList . "," . $M_ID;
        $Update_MsgList = trim($Update_MsgList, ",");
    }

    # Update Responder MsgList with new list string.
    $query = "UPDATE InfResp_responders
            SET MsgList = '$Update_MsgList'
            WHERE ResponderID = '$Responder_ID'";
    $DB_result = $DB->query($query)
    or die("Invalid query: " . $DB->error);

    # Done!
    print "<H3 style=\"color : #003300\">Message added!</H3> \n";   #This appears after you right a message and hit the save button
    print "<font size=4 color=\"#660000\">Return to list. <br></font> \n";

    # Print back button
    $return_action = "update";
    include('templates/back_button.messages.php');
} elseif ($action == "do_update") {
    # Prep the data
    $P_subj = makeSemiSafe($_REQUEST['subj']);
    $P_bodytext = makeSemiSafe($_REQUEST['bodytext']);
    $P_bodyhtml = makeSemiSafe($_REQUEST['bodyhtml']);
    $P_months = makeSafe($_REQUEST['months']);
    $P_weeks = makeSafe($_REQUEST['weeks']);
    $P_days = makeSafe($_REQUEST['days']);
    $P_hours = makeSafe($_REQUEST['hours']);
    $P_min = makeSafe($_REQUEST['min']);
    $P_absday = makeSafe($_REQUEST['abs_day']);
    $P_abshours = makeSafe($_REQUEST['abs_hours']);
    $P_absmin = makeSafe($_REQUEST['abs_min']);


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

    $DB_result = $DB->query($query)
    or die("Invalid query: " . $DB->error);

    # Done!
    print "<H3 style=\"color : #003300\">Message Saved!</H3> \n";
    print "<font size=4 color=\"#660000\">Return to list. <br></font> \n";

    # Print back button
    $return_action = "update";
    include('templates/back_button.messages.php');
} elseif ($action == "do_delete") {

    if (!(responderExists($Responder_ID))) {
        die("Responder $Responder_ID does not exist");
    }

    getResponderInfo();

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
    $DB_result = $DB->query($query)
    or die("Invalid query: " . $DB->error);

    $query = "UPDATE InfResp_responders SET MsgList = '$NewList' WHERE ResponderID = '$Responder_ID'";
    $DB_result = $DB->query($query)
    or die("Invalid query: " . $DB->error);

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
