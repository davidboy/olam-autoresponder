<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

include_once('common.php');

if ($silent != TRUE) {
    print "<html>\n";
    print "<head>\n";
    print "   <title>Infinite Responder Mailer</title>\n";
    print "   <meta http-equiv=Content-Type content=\"text/html; charset=UTF-8\">\n";
    print "</head>\n";
}

# Verbose
if ($silent != TRUE) {
    echo "Loading...<br>\n";
}

# Check mail and bounces?
if ($sendmails_included != TRUE) {
    if ($config['check_mail'] == '1') {
        $included = TRUE;
        if ($silent != TRUE) {
            echo "Running MailChecker...<br>\n";
        }
        include_once('mailchecker.php');
    }
    if ($config['check_bounces'] == '1') {
        $included = TRUE;
        if ($silent != TRUE) {
            echo "Running BounceChecker...<br>\n";
        }
        include_once('bouncechecker.php');
    }
}

# Verbose
if ($silent != TRUE) {
    echo "<br>Initializing...<br>\n";
}

# Init the batch send count
$Send_Count = 0;

# Prep the daily send count
$now = time();
if ($config['daily_reset'] == 0) {
    $config['daily_reset'] = $now;
    $query = "UPDATE InfResp_config SET daily_count = '0', daily_reset = '$now'";
    $result = mysql_query($query) or die("Invalid query: " . mysql_error());
}
$reset_time = strtotime("+1 day", $config['daily_reset']);
if ($now > $reset_time) {
    # It's time to reset the count!
    $config['daily_reset'] = $now;
    $config['daily_count'] = 0;
    $query = "UPDATE InfResp_config SET daily_count = '0', daily_reset = '$now'";
    $result = mysql_query($query) or die("Invalid query: " . mysql_error());
}

# - - - - - - - - - - - - - - - - - - -
# Pre-cache DB data to reduce SQL calls
# - - - - - - - - - - - - - - - - - - -

# Check the send counts first...
if ($config['daily_count'] <= $config['daily_limit']) {
    # Verbose
    if ($silent != TRUE) {
        echo "Pre-caching the database...<br>\n";
    }

    # Cache the messages
    $query = "SELECT * FROM InfResp_messages ORDER BY MsgID";
    $DB_Message_Result = mysql_query($query) or die("Invalid query: " . mysql_error());
    for ($i = 0; $i < mysql_num_rows($DB_Message_Result); $i++) {
        $this_row = mysql_fetch_assoc($DB_Message_Result);
        $message_id = $this_row['MsgID'];
        $message_array[$message_id] = $this_row;
        # echo $this_row['MsgID'] . " " . $this_row['BodyText'] . "<br>\n";
    }
    mysql_free_result($DB_Message_Result);

    # Cache the responders
    $query = "SELECT * FROM InfResp_responders ORDER BY ResponderID";
    $DB_Responder_Result = mysql_query($query) or die("Invalid query: " . mysql_error());
    for ($i = 0; $i < mysql_num_rows($DB_Responder_Result); $i++) {
        $this_row = mysql_fetch_assoc($DB_Responder_Result);
        $responder_id = $this_row['ResponderID'];
        $responder_array[$responder_id] = $this_row;
        # foreach ($responder_array[$responder_id] as $key => $value) {
        #    echo $key . " - " . $value . "<br />\n";
        # }
    }
    mysql_free_result($DB_Responder_Result);

    # Cache the subscribers
    $query = "SELECT * FROM InfResp_subscribers WHERE Confirmed = '1' ORDER BY ResponderID";
    $DB_Subscriber_Result = mysql_query($query) or die("Invalid query: " . mysql_error());
    for ($i = 0; $i < mysql_num_rows($DB_Subscriber_Result); $i++) {
        $this_row = mysql_fetch_assoc($DB_Subscriber_Result);
        $subscriber_id = $this_row['SubscriberID'];
        $subscriber_array[$subscriber_id] = $this_row;
        # foreach ($subscriber_array[$subscriber_id] as $key => $value) {
        #    echo $key . " - " . $value . "<br />\n";
        # }
        # echo "<br>\n";
    }
    mysql_free_result($DB_Subscriber_Result);

    # Cache the mail messages
    $query = "SELECT * FROM InfResp_mail ORDER BY Mail_ID";
    $DB_Mail_Result = mysql_query($query) or die("Invalid query: " . mysql_error());
    for ($i = 0; $i < mysql_num_rows($DB_Mail_Result); $i++) {
        $this_row = mysql_fetch_assoc($DB_Mail_Result);
        $mail_id = $this_row['Mail_ID'];
        $mail_msg_array[$mail_id] = $this_row;
        # foreach ($mail_msg_array[$mail_id] as $key => $value) {
        #    echo $key . " - " . $value . "<br />\n";
        # }
    }
    mysql_free_result($DB_Mail_Result);
} else {
    # Verbose
    if ($silent != TRUE) {
        echo "Daily throttle reached!<br>\n";
    }
}
$cop = checkit();

# - - - - - - - - - - - - - - - - - - -
# Handle the responder-style messages
# - - - - - - - - - - - - - - - - - - -

# Are we under the send counts?
if (($Send_Count <= $max_send_count) && ($config['daily_count'] <= $config['daily_limit']) && (count($subscriber_array) > 0)) {
    # Verbose
    if ($silent != TRUE) {
        echo "<br>Checking responder messages...<br>\n";
    }

    # Yes, start going thru the subscribers to handle the responder-style msgs
    foreach ($subscriber_array as $subscriber_id => $this_subscriber) {
        if ($Send_Count <= $max_send_count) {
            # Fetch the responder ID
            $this_responder_id = $this_subscriber['ResponderID'];

            # Get the message list for this subscriber's responder
            $this_responder = $responder_array[$this_responder_id];

            # Debug info
            # echo "<br>\n";
            # echo "Resp:  " . $this_subscriber['ResponderID'] . "<br>\n";
            # echo "Email: " . $this_subscriber['EmailAddress'] . "<br>\n";

            # Split and process the list
            $DB_MsgList = trim($this_responder['MsgList'], ",");
            $DB_MsgList = trim($DB_MsgList);
            $MsgList_Array = explode(',', $DB_MsgList);
            $Max_Index = sizeof($MsgList_Array);

            # echo "Max index: " . $Max_Index . "<br>\n";

            # Work thru the msg list
            for ($msg_idx = 0; $msg_idx < $Max_Index; $msg_idx++) {
                $msg_id = $MsgList_Array[$msg_idx];
                $msg_data = $message_array[$msg_id];

                # Check to see if the message ID is in the message list.
                if ((!(isInList($this_subscriber['SentMsgs'], $msg_id))) && ($Send_Count <= $max_send_count) && (is_numeric($msg_id)) && ($config['daily_count'] <= $config['daily_limit'])) {
                    # Debug info
                    # echo "ID:   " . $msg_id . "<br>\n";
                    # echo "Subj: " . $msg_data['Subject'] . "<br>\n";

                    # Figure out the time that this subscriber should receive this message.

                    # Seconds math (mins, hours, days).
                    $message_send_time = $this_subscriber['TimeJoined'] + $msg_data['SecMinHoursDays'];

                    # Months math.
                    if ($msg_data['Months'] > 0) {
                        $month_str = "+" . $msg_data['Months'] . " months";
                        $message_send_time = strtotime($month_str, $message_send_time);
                    }

                    # Check bounds
                    if (!(is_numeric($msg_data['absHours']))) {
                        $msg_data['absHours'] = 0;
                    }
                    if (!(is_numeric($msg_data['absMins']))) {
                        $msg_data['absMins'] = 0;
                    }

                    # Calculate absolute positioning.
                    if (($msg_data['absDay'] != "") || ($msg_data['absHours'] > 0) || ($msg_data['absMins'] > 0)) {
                        # Reposition the clock to the day
                        $that_day = date('j F Y', $message_send_time);
                        $message_send_time = strtotime($that_day);

                        # Figure the next day
                        if (($msg_data['absDay'] == "Monday") || ($msg_data['absDay'] == "Tuesday") || ($msg_data['absDay'] == "Wednesday") || ($msg_data['absDay'] == "Thursday") || ($msg_data['absDay'] == "Friday") || ($msg_data['absDay'] == "Saturday") || ($msg_data['absDay'] == "Sunday")) {
                            # Get this day
                            $day_in_question = date('l', $message_send_time);

                            # Do we need to find the next day?
                            if ($day_in_question != $msg_data['absDay']) {
                                # Yes, reposition the day
                                $day_str = "next " . $msg_data['absDay'];
                                $message_send_time = strtotime($day_str, $message_send_time);
                            }
                        }

                        # Add the hours
                        if ($msg_data['absHours'] > 0) {
                            $message_send_time = strtotime("+" . $msg_data['absHours'] . " hours", $message_send_time);
                        }

                        # Add the minutes
                        if ($msg_data['absMins'] > 0) {
                            $message_send_time = strtotime("+" . $msg_data['absMins'] . " minutes", $message_send_time);
                        }
                    }

                    # Ok, we've constructed the correct send time, is it time yet?
                    if (time() >= $message_send_time) {
                        # Yes, it is.

                        # Make the new msg str
                        $NewMsgStr = $this_subscriber['SentMsgs'] . "," . $msg_id;
                        $NewMsgStr = trim($NewMsgStr, ",");
                        $NewMsgStr = trim($NewMsgStr);
                        $this_subscriber['SentMsgs'] = $NewMsgStr;

                        # Update a little more data
                        $Set_LastActivity = time();
                        $this_subscriber['LastActivity'] = $Set_LastActivity;
                        $subscriber_array[$subscriber_id]['LastActivity'] = $Set_LastActivity;

                        # Set the tag variables
                        $DB_TimeJoined = $this_subscriber['TimeJoined'];
                        $DB_Real_TimeJoined = $this_subscriber['Real_TimeJoined'];
                        $DB_EmailAddress = $this_subscriber['EmailAddress'];
                        $DB_LastActivity = $this_subscriber['LastActivity'];
                        $DB_FirstName = $this_subscriber['FirstName'];
                        $DB_LastName = $this_subscriber['LastName'];
                        $CanReceiveHTML = $this_subscriber['CanReceiveHTML'];
                        $DB_SubscriberID = $this_subscriber['SubscriberID'];
                        $DB_SentMsgs = $this_subscriber['SentMsgs'];
                        $DB_UniqueCode = $this_subscriber['UniqueCode'];
                        $DB_ResponderName = $this_responder['Name'];
                        $DB_OwnerEmail = $this_responder['OwnerEmail'];
                        $DB_OwnerName = $this_responder['OwnerName'];
                        $DB_ReplyToEmail = $this_responder['ReplyToEmail'];
                        $DB_ResponderDesc = $this_responder['ResponderDesc'];
                        $DB_MsgBodyHTML = $msg_data['BodyHTML'];
                        $DB_MsgBodyText = $msg_data['BodyText'];
                        $DB_MsgSub = $msg_data['Subject'];
                        $Responder_ID = $this_responder_id;
                        $Send_Subject = "$DB_MsgSub";
                        $subcode = "s" . $DB_UniqueCode;
                        $unsubcode = "u" . $DB_UniqueCode;
                        $UnsubURL = $siteURL . $ResponderDirectory . "/confirm_subscription.php?c=$unsubcode";

                        # Filter the email address of a few nasties
                        $DB_EmailAddress = stripNewlines(str_replace("|", "", $DB_EmailAddress));
                        $DB_EmailAddress = str_replace(">", "", $DB_EmailAddress);
                        $DB_EmailAddress = str_replace("<", "", $DB_EmailAddress);
                        $DB_EmailAddress = str_replace('/', "", $DB_EmailAddress);
                        $DB_EmailAddress = str_replace('..', "", $DB_EmailAddress);

                        # Process the tags
                        processMessageTags();

                        # Generate the headers
                        $Message_Body = "";
                        $Message_Headers = "Return-Path: <" . $DB_ReplyToEmail . ">$newline";
                        # $Message_Headers .= "Return-Receipt-To: <" . $DB_ReplyToEmail . ">$newline";
                        $Message_Headers .= "Envelope-to: $DB_EmailAddress$newline";
                        $Message_Headers .= "From: $DB_OwnerName <" . $DB_ReplyToEmail . ">$newline";
                        # $Message_Headers .= "Date: " . date('D\, j F Y H:i:s O') . "$newline";
                        $Message_Headers .= "Date: " . date('r') . "$newline";
                        $Message_Headers .= "Reply-To: $DB_ReplyToEmail$newline";
                        $Message_Headers .= "Sender-IP: " . $_SERVER["SERVER_ADDR"] . $newline;
                        $Message_Headers .= "MIME-Version: 1.0$newline";
                        $Message_Headers .= "Priority: normal$newline";
                        $Message_Headers .= "X-Mailer: Infinite Responder$newline";

                        # Generate the body
                        if ($CanReceiveHTML == 1) {
                            $boundary = md5(time()) . rand(1000, 9999);
                            $Message_Headers .= "Content-Type: multipart/alternative; $newline            boundary=\"$boundary\"$newline";
                            $Message_Body .= "This is a multi-part message in MIME format.$newline$newline";
                            $Message_Body .= "--" . $boundary . $newline;
                            $Message_Body .= "Content-type: text/plain; charset=$charset$newline";
                            $Message_Body .= "Content-Transfer-Encoding: base64" . $newline;
                            $Message_Body .= "Content-Disposition: inline$newline$newline";
                            $Message_Body .= $DB_MsgBodyText . $newline . $newline;
                            $Message_Body .= "--" . $boundary . $newline;
                            $Message_Body .= "Content-type: text/html; charset=$charset$newline";
                            $Message_Body .= "Content-Transfer-Encoding: base64" . $newline;
                            $Message_Body .= "Content-Disposition: inline$newline$newline";
                            $Message_Body .= rtrim(chunk_split(base64_encode($DB_MsgBodyHTML))) . $newline . $newline;
                        } else {
                            $Message_Headers .= "Content-type: text/plain; charset=$charset$newline";
                            $Message_Headers .= "Content-Transfer-Encoding: base64" . $newline;
                            $Message_Body = rtrim(chunk_split(base64_encode($DB_MsgBodyText))) . $newline;
                        }

                        # Final filtering
                        $Send_Subject = stripNewlines(str_replace("|", "", $Send_Subject));
                        $Message_Body = str_replace("|", "", $Message_Body);
                        $Message_Headers = str_replace("|", "", $Message_Headers);
                        $Message_Body = utf8_decode($Message_Body);

                        # Actually send the email
                        mail($DB_EmailAddress, $Send_Subject, $Message_Body, $Message_Headers, "-f $DB_ReplyToEmail");

                        # Verbose
                        if ($silent != TRUE) {
                            echo "Responder msg to sub #" . $subscriber_id . "<br>\n";
                        }

                        # Update the DB
                        $query = "UPDATE InfResp_subscribers
                          SET SentMsgs = '$NewMsgStr',
                          LastActivity = '$Set_LastActivity' 
                          WHERE SubscriberID = '$DB_SubscriberID'";
                        $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());

                        # Increment the send counts
                        $Send_Count++;
                        $config['daily_count']++;
                    }
                }
            }
        }
    }
} else {
    if (count($subscriber_array) > 0) {
        # Verbose
        if ($silent != TRUE) {
            echo "No responder messages sent - throttle limit reached.<br>\n";
        }
    } else {
        # Verbose
        if ($silent != TRUE) {
            echo "No subscribers to send to yet.<br>\n";
        }
    }
}


# - - - - - - - - - - - - - - - - - - -
# Handle the newsletter-style messages
# - - - - - - - - - - - - - - - - - - -

# Are we under the send counts?
if (($Send_Count <= $max_send_count) && ($config['daily_count'] <= $config['daily_limit'])) {
    # Verbose
    if ($silent != TRUE) {
        echo "<br>Checking newsletter messages...<br>\n";
    }

    # Check for unsent mail in the cache
    $update_list = "";
    $query = "SELECT * FROM InfResp_mail_cache WHERE Status = 'queued'";
    $DB_Mail_Cache_Result = mysql_query($query) or die("Invalid query: " . mysql_error());
    while ($this_entry = mysql_fetch_assoc($DB_Mail_Cache_Result)) {
        # Should we send?
        if (($Send_Count <= $max_send_count) && ($config['daily_count'] <= $config['daily_limit']) && ($mail_msg_array[$mail_id]['Closed'] == "0") && ($mail_msg_array[$mail_id]['Time_To_Send'] <= time())) {
            # Fetch the cache entry details
            $mail_id = $this_entry['Mail_ID'];
            $sub_id = $this_entry['SubscriberID'];
            $cache_id = $this_entry['Cache_ID'];

            # Get the other relevant data
            $this_mail_msg = $mail_msg_array[$mail_id];
            $this_subscriber = $subscriber_array[$sub_id];
            $responder_id = $this_mail_msg['ResponderID'];
            $this_responder = $responder_array[$responder_id];

            # Set the tag variables and send?
            if (!(isEmpty($this_subscriber))) {
                $DB_TimeJoined = $this_subscriber['TimeJoined'];
                $DB_Real_TimeJoined = $this_subscriber['Real_TimeJoined'];
                $DB_EmailAddress = $this_subscriber['EmailAddress'];
                $DB_LastActivity = $this_subscriber['LastActivity'];
                $DB_FirstName = $this_subscriber['FirstName'];
                $DB_LastName = $this_subscriber['LastName'];
                $CanReceiveHTML = $this_subscriber['CanReceiveHTML'];
                $DB_SubscriberID = $this_subscriber['SubscriberID'];
                $DB_SentMsgs = $this_subscriber['SentMsgs'];
                $DB_UniqueCode = $this_subscriber['UniqueCode'];
                $DB_ResponderName = $this_responder['Name'];
                $DB_OwnerEmail = $this_responder['OwnerEmail'];
                $DB_OwnerName = $this_responder['OwnerName'];
                $DB_ReplyToEmail = $this_responder['ReplyToEmail'];
                $DB_ResponderDesc = $this_responder['ResponderDesc'];
                $DB_MsgBodyHTML = $this_mail_msg['HTML_msg'];
                $DB_MsgBodyText = $this_mail_msg['TEXT_msg'];
                $DB_MsgSub = $this_mail_msg['Subject'];
                $Responder_ID = $this_responder_id;
                $Send_Subject = "$DB_MsgSub";
                $subcode = "s" . $DB_UniqueCode;
                $unsubcode = "u" . $DB_UniqueCode;
                $UnsubURL = $siteURL . $ResponderDirectory . "/confirm_subscription.php?c=$unsubcode";

                # Filter the email address of a few nasties
                $DB_EmailAddress = stripNewlines(str_replace("|", "", $DB_EmailAddress));
                $DB_EmailAddress = str_replace(">", "", $DB_EmailAddress);
                $DB_EmailAddress = str_replace("<", "", $DB_EmailAddress);
                $DB_EmailAddress = str_replace('/', "", $DB_EmailAddress);
                $DB_EmailAddress = str_replace('..', "", $DB_EmailAddress);

                # Process the tags
                processMessageTags();

                # Generate the headers
                $Message_Body = "";
                $Message_Headers = "Return-Path: <" . $DB_ReplyToEmail . ">$newline";
                # $Message_Headers .= "Return-Receipt-To: <" . $DB_ReplyToEmail . ">$newline";
                $Message_Headers .= "Envelope-to: $DB_EmailAddress$newline";
                $Message_Headers .= "From: $DB_OwnerName <" . $DB_ReplyToEmail . ">$newline";
                # $Message_Headers .= "Date: " . date('D\, j F Y H:i:s O') . "$newline";
                $Message_Headers .= "Date: " . date('r') . "$newline";
                $Message_Headers .= "Reply-To: $DB_ReplyToEmail$newline";
                $Message_Headers .= "Sender-IP: " . $_SERVER["SERVER_ADDR"] . $newline;
                $Message_Headers .= "MIME-Version: 1.0$newline";
                $Message_Headers .= "Priority: normal$newline";
                $Message_Headers .= "X-Mailer: Infinite Responder$newline";

                # Generate the body
                if ($CanReceiveHTML == 1) {
                    $boundary = md5(time()) . rand(1000, 9999);
                    $Message_Headers .= "Content-Type: multipart/alternative; $newline            boundary=\"$boundary\"$newline";
                    $Message_Body .= "This is a multi-part message in MIME format.$newline$newline";
                    $Message_Body .= "--" . $boundary . $newline;
                    $Message_Body .= "Content-type: text/plain; charset=$charset$newline";
                    $Message_Body .= "Content-Transfer-Encoding: 8bit" . $newline;
                    $Message_Body .= "Content-Disposition: inline$newline$newline";
                    $Message_Body .= $DB_MsgBodyText . $newline . $newline;
                    $Message_Body .= "--" . $boundary . $newline;
                    $Message_Body .= "Content-type: text/html; charset=$charset$newline";
                    $Message_Body .= "Content-Transfer-Encoding: 8bit" . $newline;
                    $Message_Body .= "Content-Disposition: inline$newline$newline";
                    $Message_Body .= $DB_MsgBodyHTML . $newline . $newline;
                } else {
                    $Message_Headers .= "Content-type: text/plain; charset=$charset$newline";
                    $Message_Headers .= "Content-Transfer-Encoding: 8bit" . $newline;
                    $Message_Body = $DB_MsgBodyText . $newline;
                }

                # Final filtering
                $Send_Subject = stripNewlines(str_replace("|", "", $Send_Subject));
                $Message_Body = str_replace("|", "", $Message_Body);
                $Message_Headers = str_replace("|", "", $Message_Headers);
                $Message_Body = utf8_decode($Message_Body);

                # Send the mail
                # echo "addy: $DB_EmailAddress <br>\n";
                # echo "subj: $Send_Subject <br>\n";
                # echo "body: $Message_Body <br>\n";
                # echo "head: $Message_Headers <br>\n";
                # echo "---------------------------<br>\n";
                mail($DB_EmailAddress, $Send_Subject, $Message_Body, $Message_Headers, "-f $DB_ReplyToEmail");

                # Verbose
                if ($silent != TRUE) {
                    echo "Newsletter msg to sub #" . $sub_id . "<br>\n";
                }

                # Update the last activity field
                $Set_LastActivity = time();
                $this_subscriber['LastActivity'] = $Set_LastActivity;
                $subscriber_array[$sub_id]['LastActivity'] = $Set_LastActivity;
                $query = "UPDATE InfResp_subscribers SET LastActivity = '$Set_LastActivity' WHERE SubscriberID = '$DB_SubscriberID'";
                $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());

                # Update the cache database
                $query = "UPDATE InfResp_mail_cache SET Status = 'sent', LastActivity = '$Set_LastActivity' WHERE Cache_ID = '$cache_id'";
                $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());

                # Increment the send counts
                $Send_Count++;
                $config['daily_count']++;
            }
        }
    }
} else {
    # Verbose
    if ($silent != TRUE) {
        echo "No newsletter messages sent - throttle limit reached.<br>\n";
    }
}

# - - - - - - - - - - - - - - - - - - -
# Update the daily count in the DB
# - - - - - - - - - - - - - - - - - - -
$query = "UPDATE InfResp_config SET daily_count = '" . $config['daily_count'] . "'";
$result = mysql_query($query) or die("Invalid query: " . mysql_error());

# Verbose
if ($Send_Count > 0) {
    if ($silent != TRUE) {
        echo "<br>Updating counts...<br>\n";
    }
} else {
    if ($silent != TRUE) {
        echo "<br>No messages sent.<br>\n";
    }
}

# - - - - - - - - - - - - - - - - - - -
# Handle last activity trim
# - - - - - - - - - - - - - - - - - - -

if (($last_activity_trim > 0) && ($this_subscriber['LastActivity'] != "") AND ($this_subscriber['LastActivity'] != NULL) AND ($this_subscriber['LastActivity'] != 0)) {
    # Set some vars
    $trim_str = "+" . $last_activity_trim . " months";

    # Loop thru the subscribers
    foreach ($subscriber_array as $subscriber_id => $this_subscriber) {
        $trim_time = strtotime($trim_str, $this_subscriber['LastActivity']);
        if (time() > $trim_time) {
            $query = "DELETE FROM InfResp_subscribers WHERE SubscriberID = '" . $this_subscriber['SubscriberID'] . "'";
            $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());

            $query = "DELETE FROM InfResp_customfields WHERE user_attached = '" . $this_subscriber['SubscriberID'] . "'";
            $result = mysql_query($query) or die("Invalid query: " . mysql_error());
        }
    }
}

if ($sendmails_included != TRUE) {
    dbDisconnect();
}

# Verbose
if ($silent != TRUE) {
    echo "Done!<br>\n";
}

# Reset var
$silent = FALSE;
?>