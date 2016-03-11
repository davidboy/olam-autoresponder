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

function mailMessageExists($mail_id = "0")
{
    global $DB;

    # Bounds check
    if (isEmpty($mail_id)) {
        return FALSE;
    }
    if ($mail_id == "0") {
        return FALSE;
    }
    if (!(is_numeric($mail_id))) {
        return FALSE;
    }

    # Check for it's existance
    $query = "SELECT * FROM InfResp_mail WHERE Mail_ID = '$mail_id'";
    $result = $DB->query($query) or die("Invalid query: " . $DB->error);
    if ($result->num_rows > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

# ------------------------------------------------

function getSendFigures()
{
    global $DB, $mail_id;

    # Init
    $the_math['total'] = 0;
    $the_math['sent'] = 0;
    $the_math['queued'] = 0;

    # Query it
    $query = "SELECT * FROM InfResp_mail_cache WHERE Mail_ID = '$mail_id'";
    $result = $DB->query($query) or die("Invalid query: " . $DB->error);
    for ($i = 0; $i < $result->num_rows; $i++) {
        # Grab query results
        $this_row = $result->fetch_assoc();

        # Tally it
        if ($this_row['Status'] == 'queued') {
            $the_math['queued']++;
        } elseif ($this_row['Status'] == 'sent') {
            $the_math['sent']++;
        }
    }

    # Tally it up
    $the_math['total'] = $result->num_rows;
    if ($the_math['total'] == 0) {
        $the_math['percent'] = 100;
    } else {
        $the_math['percent'] = (round($the_math['sent'] / $the_math['total']) * 100);
    }

    # Head on back
    return $the_math;
}

# ------------------------------------------------

# Get and verify input
$Responder_ID = makeSafe($_REQUEST['r_ID']);
$action = makeSafe(@$_REQUEST['action']);
if (!(is_numeric($Responder_ID))) {
    $Responder_ID = FALSE;
}

# Check the responder ID
if (!($Responder_ID)) {
    redirectTo('/admin.php');
}
if (!(responderExists($Responder_ID))) {
    redirectTo('/admin.php');
}
getResponderInfo();

# Top template
include('templates/open.page.php');
include_once('popup_js.php');

# Check the mail ID
$mail_id = makeSafe(@$_REQUEST['m_ID']);
if ((!(is_numeric($mail_id))) || (empty($mail_id)) || ($mail_id == "")) {
    $mail_id = "0";
}

if ($action == "create") {
    # Init vars
    $subject = "";
    $text_msg = "%unsub_msg%";
    $html_msg = "<br>%unsub_msg%";
    $heading = "Send a new burst to:<br>$DB_ResponderName";
    $submit_action = "do_create";
    $return_action = "list";
    $month_to_send = strtolower(date('F', time()));
    $day_to_send = date('d', time());
    $year_to_send = date('Y', time());
    $hour_to_send = date('G', time());
    $min_to_send = date('i', time());
    $time_to_send = date('l, dS \of F Y h:i:s A', time());
    $this_year = date('Y', time());

    # Show the template
    include('templates/create.mailbursts.php');
} elseif ($action == "do_create") {
    # Sanitize the input
    $P_subj = makeSemiSafe($_REQUEST['subj']);
    $P_bodytext = makeSemiSafe($_REQUEST['bodytext']);
    $P_bodyhtml = makeSemiSafe($_REQUEST['bodyhtml']);
    $send_month = strtolower(makeSafe($_REQUEST['send_month']));
    $send_day = makeSafe($_REQUEST['send_day']);
    $send_year = makeSafe($_REQUEST['send_year']);
    $send_hour = makeSafe($_REQUEST['send_hour']);
    $send_min = makeSafe($_REQUEST['send_min']);
    if (!(is_numeric($send_day))) {
        $send_day = date('d', time());
    }
    if (!(is_numeric($send_year))) {
        $send_year = date('Y', time());
    }
    if (!(is_numeric($send_hour))) {
        $send_hour = date('h', time());
    }
    if (!(is_numeric($send_min))) {
        $send_min = date('i', time());
    }
    if (($send_month != 'january') && ($send_month != 'february') && ($send_month != 'march') && ($send_month != 'april') && ($send_month != 'may') && ($send_month != 'june') && ($send_month != 'july') && ($send_month != 'august') && ($send_month != 'september') && ($send_month != 'october') && ($send_month != 'november') && ($send_month != 'december')) {
        $send_month = strtolower(date('F', time()));
    }

    # Get the timestamp
    $str = "$send_month $send_day $send_year +$send_hour hours +$send_min minutes";
    $time_to_send = strtotime($str);
    # echo $str . "<br>\n";
    # echo $time_to_send . "<br>\n";
    # echo date('l, dS \of F Y h:i:s A', $time_to_send);

    # Add the burst message to the DB
    $timestamp = time();
    $query = "INSERT INTO InfResp_mail (ResponderID,Closed,Subject,TEXT_msg,HTML_msg,Time_To_Send,Time_Sent) VALUES ('$Responder_ID','0','$P_subj','$P_bodytext','$P_bodyhtml','$time_to_send','$timestamp')";
    $result = $DB->query($query) OR die("Invalid query: " . $DB->error);
    $mail_id = $DB->insert_id;

    # Get the subscriber list for this responder
    $query = "SELECT * FROM InfResp_subscribers WHERE ResponderID = '$Responder_ID'";
    $DB_Subscriber_Result = $DB->query($query) or die("Invalid query: " . $DB->error);
    while ($this_row = $DB_Subscriber_Result->fetch_assoc()) {
        # Grab query results
        $subscriber_id = $this_row['SubscriberID'];

        # Add the cache entries for this subscriber if they're confirmed
        if ($this_row['Confirmed'] == 1) {
            $query = "INSERT INTO InfResp_mail_cache (Mail_ID,SubscriberID,Status,LastActivity) VALUES ('$mail_id','$subscriber_id','queued','$timestamp')";
            $result = $DB->query($query) OR die("Invalid query: " . $DB->error);
        }
    }

    # Done! Take us back...
    $return_action = "list";
    print "<p class=\"big_header\">Burst added!</p>\n";
    include('templates/back_button.mailbursts.php');
} elseif (($action == "edit") && (mailMessageExists($mail_id))) {
    # Query DB - We already know there's a row for it.
    $query = "SELECT * FROM InfResp_mail WHERE Mail_ID = '$mail_id'";
    $result = $DB->query($query) OR die("Invalid query: " . $DB->error);
    $this_msg = $result->fetch_assoc();

    # Init vars
    $subject = $this_msg['Subject'];
    $text_msg = $this_msg['TEXT_msg'];
    $html_msg = $this_msg['HTML_msg'];
    $timesent = date('l dS \of F Y h:i:s A', $this_msg['Time_Sent']);
    $month_to_send = strtolower(date('F', $this_msg['Time_To_Send']));
    $day_to_send = date('d', $this_msg['Time_To_Send']);
    $year_to_send = date('Y', $this_msg['Time_To_Send']);
    $hour_to_send = date('G', $this_msg['Time_To_Send']);
    $min_to_send = date('i', $this_msg['Time_To_Send']);
    $time_to_send = date('l, dS \of F Y h:i:s A', $this_msg['Time_To_Send']);
    $this_year = date('Y', time());
    if ($this_msg['Closed'] == "0") {
        $status = "Active";
    } else {
        $status = "Paused";
    }
    $heading = "Edit a burst to:<br>$DB_ResponderName";
    $submit_action = "do_edit";
    $return_action = "list";

    # Get the math numbers
    $the_math = getSendFigures();

    # Show the template
    include('templates/edit.mailbursts.php');
} elseif (($action == "do_edit") && (mailMessageExists($mail_id))) {
    # Sanitize the input
    $P_subj = makeSemiSafe($_REQUEST['subj']);
    $P_bodytext = makeSemiSafe($_REQUEST['bodytext']);
    $P_bodyhtml = makeSemiSafe($_REQUEST['bodyhtml']);
    $send_month = strtolower(makeSafe($_REQUEST['send_month']));
    $send_day = makeSafe($_REQUEST['send_day']);
    $send_year = makeSafe($_REQUEST['send_year']);
    $send_hour = makeSafe($_REQUEST['send_hour']);
    $send_min = makeSafe($_REQUEST['send_min']);
    if (!(is_numeric($send_day))) {
        $send_day = date('d', time());
    }
    if (!(is_numeric($send_year))) {
        $send_year = date('Y', time());
    }
    if (!(is_numeric($send_hour))) {
        $send_hour = date('h', time());
    }
    if (!(is_numeric($send_min))) {
        $send_min = date('i', time());
    }
    if (($send_month != 'january') && ($send_month != 'february') && ($send_month != 'march') && ($send_month != 'april') && ($send_month != 'may') && ($send_month != 'june') && ($send_month != 'july') && ($send_month != 'august') && ($send_month != 'september') && ($send_month != 'october') && ($send_month != 'november') && ($send_month != 'december')) {
        $send_month = strtolower(date('F', time()));
    }

    # Get the timestamp
    $str = "$send_month $send_day $send_year +$send_hour hours +$send_min minutes";
    $time_to_send = strtotime($str);
    # echo $str . "<br>\n";
    # echo $time_to_send . "<br>\n";
    # echo date('l, dS \of F Y h:i:s A', $time_to_send);

    # Do the update
    $query = "UPDATE InfResp_mail SET Subject = '$P_subj', TEXT_msg = '$P_bodytext', HTML_msg = '$P_bodyhtml', Time_To_Send = '$time_to_send' WHERE Mail_ID = '$mail_id'";
    $result = $DB->query($query) OR die("Invalid query: " . $DB->error);

    # Done! Take us back...
    $return_action = "list";
    print "<p class=\"big_header\">Burst changed!</p>\n";
    include('templates/back_button.mailbursts.php');
} elseif (($action == "pause") && (mailMessageExists($mail_id))) {
    # Toggle pause
    $query = "SELECT * FROM InfResp_mail WHERE Mail_ID = '$mail_id'";
    $result = $DB->query($query) OR die("Invalid query: " . $DB->error);
    if ($result->num_rows > 0) {
        $this_row = $result->fetch_assoc();
        if ($this_row['Closed'] == "0") {
            $msg = "<br><center>Message paused!</center><br><br>\n";
            $toggle_query = "UPDATE InfResp_mail SET Closed = '1' WHERE Mail_ID = '$mail_id'";
        } else {
            $msg = "<br><center>Message re-activated!</center><br><br>\n";
            $toggle_query = "UPDATE InfResp_mail SET Closed = '0' WHERE Mail_ID = '$mail_id'";
        }
        $tog_result = $DB->query($toggle_query) or die("Invalid query: " . $DB->error);

        # Show screen msg
        $return_action = "edit";
        print $msg;
        include('templates/back_button.mailbursts.php');
    }
} elseif (($action == "delete") && (mailMessageExists($mail_id))) {
    # Query DB - We already know there's a row for it.
    $query = "SELECT * FROM InfResp_mail WHERE Mail_ID = '$mail_id'";
    $result = $DB->query($query) OR die("Invalid query: " . $DB->error);
    $this_msg = $result->fetch_assoc();

    # Init vars
    $subject = $this_msg['Subject'];
    $text_msg = $this_msg['TEXT_msg'];
    $html_msg = $this_msg['HTML_msg'];
    $timesent = date('l, dS \of F Y h:i:s A', $this_msg['Time_Sent']);
    $month_to_send = strtolower(date('F', $this_msg['Time_To_Send']));
    $day_to_send = date('d', $this_msg['Time_To_Send']);
    $year_to_send = date('Y', $this_msg['Time_To_Send']);
    $hour_to_send = date('G', $this_msg['Time_To_Send']);
    $min_to_send = date('i', $this_msg['Time_To_Send']);
    $time_to_send = date('l, dS \of F Y h:i:s A', $this_msg['Time_To_Send']);
    if ($this_msg['Closed'] == "0") {
        $status = "Active";
    } else {
        $status = "Paused";
    }
    $heading = "Delete a burst?";
    $submit_action = "do_delete";
    $return_action = "list";

    # Get the math numbers
    $the_math = getSendFigures();

    # Show the template
    include('templates/delete.mailbursts.php');
} elseif (($action == "do_delete") && (mailMessageExists($mail_id))) {
    # Delete from the mail table
    $query = "DELETE FROM InfResp_mail WHERE Mail_ID = '$mail_id'";
    $result = $DB->query($query) OR die("Invalid query: " . $DB->error);

    # Delete from the mail cache table
    $query = "DELETE FROM InfResp_mail_cache WHERE Mail_ID = '$mail_id'";
    $result = $DB->query($query) OR die("Invalid query: " . $DB->error);

    # Done! Take us back...
    $return_action = "list";
    print "<p class=\"big_header\">Burst deleted!</p>\n";
    include('templates/back_button.mailbursts.php');
} else {
    # Init vars
    $submit_action = "create";
    $return_action = "list";
    $heading = "Mailbursts for: $DB_ResponderName";

    # Responder button template
    include('templates/responder_button.mailbursts.php');

    $alt = TRUE;
    $query = "SELECT * FROM InfResp_mail WHERE ResponderID = '$Responder_ID'";
    $DB_Mail_Result = $DB->query($query) or die("Invalid query: " . $DB->error);
    if ($DB_Mail_Result->num_rows > 0) {
        # Top template
        include('templates/list_top.mailbursts.php');

        while($this_msg = $DB_Mail_Result->fetch_assoc()) {
            # Mail_ID, ResponderID, Closed, Subject, TEXT_msg, HTML_msg, Time_Sent

            # Init vars
            $timesent = date('l, dS \of F Y h:i:s A', $this_msg['Time_Sent']);
            $month_to_send = strtolower(date('F', $this_msg['Time_To_Send']));
            $day_to_send = date('d', $this_msg['Time_To_Send']);
            $year_to_send = date('Y', $this_msg['Time_To_Send']);
            $hour_to_send = date('G', $this_msg['Time_To_Send']);
            $min_to_send = date('i', $this_msg['Time_To_Send']);
            $time_to_send = date('l, dS \of F Y h:i:s A', $this_msg['Time_To_Send']);
            if ($this_msg['Closed'] == "0") {
                $status = "Active";
            } else {
                $status = "Paused";
            }

            # Show the template
            include('templates/list_row.mailbursts.php');

            # Alternate colors
            $alt = (!($alt));
        }

        # Bottom template
        include('templates/list_bottom.mailbursts.php');
    } else {
        print "<p class=\"big_header\">No bursts exist. Create one?</p>";
    }

    # Bottom template - Add new / back
    include('templates/add_new.mailbursts.php');
}

# Template bottom
copyright();
include('templates/close.page.php');
