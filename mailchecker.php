<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

include_once('common.php');

# Start checking the mail...
$query = "SELECT * FROM InfResp_POP3 WHERE username != 'username' AND password != 'password'";
$DB_POP3_Result = mysql_query($query) or die("Invalid query: " . mysql_error());
if (mysql_num_rows($DB_POP3_Result) > 0) {
    while ($POP3_Result = mysql_fetch_assoc($DB_POP3_Result)) {
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
        if ($DB_Pop_Enabled == 1) {
            $Responder_ID = $DB_Attached_Responder;
            $conn = @imap_open("\{$DB_POP3_host:$DB_POP3_port/$DB_Mail_Type/notls}$DB_POP3_mailbox", $DB_POP3_username, $DB_POP3_password);
            #or die("Couldn't connect to server: $DB_POP3_host <br>\n");

            $headers = 0;
            $headers = @imap_headers($conn);
            # or die("Couldn't get email headers!");

            if ($headers) {
                $Num_Emails = sizeof($headers);

                for ($i = 1; $i <= $Num_Emails; $i++) {
                    $mailHeader = imap_headerinfo($conn, $i);
                    $mail_body = imap_fetchbody($conn, $i, 0);

                    $subject = makeSafe($mailHeader->subject);
                    $date = makeSafe($mailHeader->date);
                    $mail_body = makeSafe($mail_body);

                    $from = $mailHeader->from;
                    foreach ($from as $id => $object) {
                        $fromname = $object->personal;
                        $fromaddress = $object->mailbox . "@" . $object->host;
                        $fromhost = $object->host;
                    }

                    $fromname = preg_replace("/\{.*\}/i", "", $fromname);
                    $fromname = preg_replace("/\(.*\)/i", "", $fromname);
                    $fromname = preg_replace("/\[.*\]/i", "", $fromname);
                    $fromname = preg_replace("/<.*>/i", "", $fromname);
                    $fromname = makeSafe($fromname);
                    $Email_Address = makeSafe($fromaddress);

                    $IsEmail = eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$", $fromname);
                    if ($IsEmail == 1) {
                        $FirstName = $fromname;
                        $LastName = '';
                    } else {
                        $Comma_List = explode(',', trim($fromname));
                        $Comma_MaxIndex = sizeof($Comma_List);
                        if ($Comma_MaxIndex > 1) {
                            $FirstName = '';
                            $LastName = $Comma_List[0];
                            for ($j = 1; $j <= $Comma_MaxIndex - 1; $j++) {
                                $FirstName .= ' ';
                                $FirstName .= $Comma_List[$j];
                            }
                            if ($DB_ConcatMid != 1) {
                                $Space_List = explode(' ', trim($FirstName));
                                $FirstName = $Space_List[0];
                            }
                        } else {
                            $Space_List = explode(' ', trim($fromname));
                            $Space_MaxIndex = sizeof($Space_List);
                            $LastName = $Space_List[$Space_MaxIndex - 1];
                            if ($DB_ConcatMid == 1) {
                                # --- Concats middle and first name ---
                                # print "$DB_ConcatMid - Bleh! <br>\n";
                                $FirstName = '';
                                for ($k = 0; $k <= $Space_MaxIndex - 2; $k++) {
                                    $FirstName .= ' ';
                                    $FirstName .= $Space_List[$k];
                                }
                            } else {
                                $FirstName = $Space_List[0];
                            }
                        }
                    }

                    $FirstName = trim($FirstName);
                    $LastName = trim($LastName);

                    preg_match_all("/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/", $mail_body, $matches);
                    $Capped_IP = sizeof($matches[0]) - 1;
                    $MailCaptured_IPaddy = $matches[0][$Capped_IP];
                    $IPaddy = "$MailCaptured_IPaddy (Email guess)";

                    if ($DB_DeleteYN == 1) {
                        imap_delete($conn, $i);
                    }

                    $spam_filtered = 0;
                    if (!(isEmpty($DB_SpamHeader))) {
                        $pos = strpos($subject, $DB_SpamHeader);
                        if ($pos === false) {
                            $spam_filtered = 0;
                        } else {
                            $spam_filtered = 1;
                        }
                    }
                    if ((!(userIsSubscribed())) && (!(isInBlacklist($Email_Address))) && ($spam_filtered == 0) AND (isEmail($Email_Address))) {
                        if ($DB_HTML_YN == 1) {
                            $Set_HTML = 1;
                        } else {
                            $Set_HTML = 0;
                        }

                        # Get responder info
                        getResponderInfo();

                        # Setup the data
                        $DB_ResponderID = $Responder_ID;
                        $DB_SentMsgs = '';
                        $DB_EmailAddress = $Email_Address;
                        $DB_TimeJoined = time();
                        $DB_Real_TimeJoined = time();
                        $CanReceiveHTML = $Set_HTML;
                        $DB_LastActivity = time();
                        $DB_FirstName = $FirstName;
                        $DB_LastName = $LastName;
                        $DB_IPaddy = $IPaddy;
                        $DB_ReferralSource = "email join";
                        $DB_UniqueCode = generateUniqueCode();

                        if ($DB_Confirm_Join == 1) {
                            # Add a non-confirmed row to the DB
                            $DB_Confirmed = "0";
                            $query = "INSERT INTO InfResp_subscribers (ResponderID, SentMsgs, EmailAddress, TimeJoined, Real_TimeJoined, CanReceiveHTML, LastActivity, FirstName, LastName, IP_Addy, ReferralSource, UniqueCode, Confirmed, IsSubscribed)
                                     VALUES('$DB_ResponderID','$DB_SentMsgs', '$DB_EmailAddress', '$DB_TimeJoined', '$DB_Real_TimeJoined', '$CanReceiveHTML', '$DB_LastActivity', '$DB_FirstName', '$DB_LastName', '$DB_IPaddy', '$DB_ReferralSource', '$DB_UniqueCode', '$DB_Confirmed', '$DB_IsSubscribed')";
                            $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());
                            $DB_SubscriberID = mysql_insert_id();

                            # Send confirmation msg
                            sendMessageTemplate('templates/subscribe.confirm.txt');
                        } else {
                            # Add a confirmed row to the DB
                            $DB_Confirmed = "1";
                            $query = "INSERT INTO InfResp_subscribers (ResponderID, SentMsgs, EmailAddress, TimeJoined, Real_TimeJoined, CanReceiveHTML, LastActivity, FirstName, LastName, IP_Addy, ReferralSource, UniqueCode, Confirmed, IsSubscribed)
                                     VALUES('$DB_ResponderID','$DB_SentMsgs', '$DB_EmailAddress', '$DB_TimeJoined', '$DB_Real_TimeJoined', '$CanReceiveHTML', '$DB_LastActivity', '$DB_FirstName', '$DB_LastName', '$DB_IPaddy', '$DB_ReferralSource', '$DB_UniqueCode', '$DB_Confirmed', '$DB_IsSubscribed')";
                            $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());
                            $DB_SubscriberID = mysql_insert_id();

                            # Send welcome and notification
                            sendMessageTemplate('templates/subscribe.complete.txt');
                            if ($DB_NotifyOnSub == "1") {
                                sendMessageTemplate('templates/new_subscriber.notify.txt', $DB_OwnerEmail, $DB_OwnerEmail);
                            }
                        }
                    }
                }
                @imap_expunge($conn);
                @imap_close($conn);
            }
        }
    }
}
