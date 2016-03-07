<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

include('common.php');

# -------------------------------------------------------------------

function AddCustomFields()
{
    global $Email_Address, $Responder_ID;
    global $FirstName, $LastName, $DB_LinkID;

    $CustomFieldsArray = GetFieldNames('InfResp_customfields');
    $CustomFieldsExist = FALSE;
    foreach ($CustomFieldsArray as $key => $value) {
        $blah = "cf_" . $value;
        $reqblah = trim($_REQUEST[$blah]);
        if (!(Empty($reqblah))) {
            $CustomFieldsArray[$value] = MakeSafe($reqblah);
            $CustomFieldsExist = TRUE;
        }
    }

    # Any custom fields?
    if ($CustomFieldsExist == TRUE) {
        #------------- Mandatory fields checking ------------------
        # if (empty($CustomFieldsArray['blah'])) { die('Error Message'); }
        #----------------------------------------------------------

        # --- Custom code ---
        $Fullname = "$FirstName $LastName";
        $CustomFieldsArray['full_name'] = $Fullname;
        # -------------------

        # Set static data
        $CustomFieldsArray['email_attached'] = $Email_Address;
        $CustomFieldsArray['resp_attached'] = $Responder_ID;
        unset($CustomFieldsArray['fieldID']);
        unset($CustomFieldsArray['user_attached']);

        # Delete any old data
        $query = "SELECT * FROM InfResp_customfields WHERE email_attached = '$Email_Address' AND resp_attached = '$Responder_ID'";
        $result = mysql_query($query) or die("Invalid query: " . mysql_error());
        if (mysql_num_rows($result) > 0) {
            $query = "DELETE FROM InfResp_customfields WHERE email_attached = '$Email_Address' AND resp_attached = '$Responder_ID'";
            $result = mysql_query($query) or die("Invalid query: " . mysql_error());
        }

        # Insert new data
        DB_Insert_Array("InfResp_customfields", $CustomFieldsArray);
    }
}

# -------------------------------------------------------------------

# Process inputs
if ($_REQUEST['s'] == "1") {
    $SilentMode = 1;
} else {
    $SilentMode = 0;
}

# Process input
$Email_Address = rawurldecode(trim($_REQUEST['e']));
$Email_Address = str_replace(">", "", $Email_Address);
$Email_Address = str_replace("<", "", $Email_Address);
$Email_Address = str_replace("\\", "", $Email_Address);
$Email_Address = str_replace('/', "", $Email_Address);
$Email_Address = str_replace('..', "", $Email_Address);
$Email_Address = str_replace('|', "", $Email_Address);
$Email_Address = stripnl(MakeSafe($Email_Address));
$Confirm_String = MakeSafe($_REQUEST['c']);
$Subscriber_ID = MakeSafe($_REQUEST['sub_ID']);
$HandleHTML = MakeSafe($_REQUEST['h']);
$ReferralSrc = MakeSafe($_REQUEST['ref']);
$IPaddy = $_SERVER['REMOTE_ADDR'];

# Grab the name
if (isEmpty($_REQUEST['n'])) {
    $FirstName = MakeSafe($_REQUEST['f']);
    $LastName = MakeSafe($_REQUEST['l']);
} else {
    $FullName = MakeSafe($_REQUEST['n']);
    $names = explode(' ', $FullName);
    $FirstName = $names[0];
    $LastName = '';
    for ($k = 1; $k <= (count($names) - 1); $k++) {
        $LastName = $LastName . " " . $names[$k];
    }
    $LastName = trim($LastName);
}

# Grab the action var
if (isEmpty($_REQUEST['a'])) {
    $action = strtolower(MakeSafe($_REQUEST['action']));
} else {
    $action = strtolower(MakeSafe($_REQUEST['a']));
}

# Grab responder ID
if (isset($_REQUEST['r'])) {
    $Responder_ID = MakeSafe($_REQUEST['r']);
} else {
    $Responder_ID = MakeSafe($_REQUEST['r_ID']);
}

# Bounds checking
if (!(is_numeric($Responder_ID))) {
    $Responder_ID = 0;
}
if (!(is_numeric($Subscriber_ID))) {
    $Subscriber_ID = 0;
}
if ($HandleHTML != "1") {
    $HandleHTML = "0";
}

# Actions from admin.php
if (($action == "resend_unsub_conf") || ($action == "resend_sub_conf")) {
    # Pull info
    if (!(ResponderExists($Responder_ID))) {
        admin_redirect();
    }
    GetResponderInfo();
    if ((GetSubscriberInfo($Subscriber_ID)) == FALSE) {
        admin_redirect();
    }

    # Open template
    if ($SilentMode != 1) {
        include('templates/open.page.php');
    }

    # Handle the action
    if ($action == "resend_sub_conf") {
        SendMessageTemplate('templates/subscribe.confirm.txt');
        if ($SilentMode != 1) {
            print "<br />Subscription confirmation message sent!<br />\n";
        }
    } elseif ($action == "resend_unsub_conf") {
        SendMessageTemplate('templates/unsubscribe.confirm.txt');
        if ($SilentMode != 1) {
            print "<br />Unsubscribe confirmation message sent!<br />\n";
        }
    }

    # Back to admin button
    $return_action = 'sub_edit';
    if ($SilentMode != 1) {
        include('templates/admin_button.subhandler.php');
    }

    # Close template
    if ($SilentMode != 1) {
        copyright();
        include('templates/close.page.php');
    }
    die();
}

# Is there a confirm string?
if (!(isEmpty($Confirm_String))) {
    # Is a sub or an unsub code?
    $type = strtolower(substr($Confirm_String, 0, 1));
    if (($type == "s") || ($type == "u")) {
        # Verify the code
        $code = substr($Confirm_String, 1, (strlen($Confirm_String) - 1));
        $query = "SELECT * FROM InfResp_subscribers WHERE UniqueCode = '$code'";
        $result = mysql_query($query) or die("Invalid query: " . mysql_error());
        if (mysql_num_rows($result) < 1) {
            # Invalid code. Print it!
            if ($SilentMode != 1) {
                include('templates/open.page.php');
                include('templates/invalid_code.subhandler.php');
                copyright();
                include('templates/close.page.php');
            }
            die();
        }

        # Grab the subscriber data
        $result_data = mysql_fetch_assoc($result);
        $DB_SubscriberID = $result_data['SubscriberID'];
        $DB_ResponderID = $result_data['ResponderID'];
        $DB_SentMsgs = $result_data['SentMsgs'];
        $DB_EmailAddress = $result_data['EmailAddress'];
        $DB_TimeJoined = $result_data['TimeJoined'];
        $DB_Real_TimeJoined = $result_data['Real_TimeJoined'];
        $CanReceiveHTML = $result_data['CanReceiveHTML'];
        $DB_LastActivity = $result_data['LastActivity'];
        $DB_FirstName = $result_data['FirstName'];
        $DB_LastName = $result_data['LastName'];
        $DB_IPaddy = $result_data['IP_Addy'];
        $DB_ReferralSource = $result_data['ReferralSource'];
        $DB_UniqueCode = $result_data['UniqueCode'];
        $DB_Confirmed = $result_data['Confirmed'];

        # Grab the relevant responder data
        $Responder_ID = $DB_ResponderID;
        if (!(ResponderExists($Responder_ID))) {
            # Invalid code. Print it!
            if ($SilentMode != 1) {
                include('templates/open.page.php');
                include('templates/invalid_code.subhandler.php');
                copyright();
                include('templates/close.page.php');
            }
            die();
        }
        GetResponderInfo();

        # Emails, DB and redir/template
        if ($type == "s") {
            # Do DB update
            $Set_LastActivity = time();
            $query = "UPDATE InfResp_subscribers SET LastActivity = '$Set_LastActivity', TimeJoined = '$Set_LastActivity', Real_TimeJoined = '$Set_LastActivity', Confirmed = '1' WHERE SubscriberID = '$DB_SubscriberID'";
            $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());

            # Handle custom fields
            AddCustomFields();

            # Send mail
            SendMessageTemplate('templates/subscribe.complete.txt');
            if ($DB_NotifyOnSub == "1") {
                SendMessageTemplate('templates/new_subscriber.notify.txt', $DB_OwnerEmail, $DB_OwnerEmail);
            }

            # Autocall sendmails on subscribe?
            if ($config['autocall_sendmails'] == "1") {
                $silent = TRUE;
                include('sendmails.php');
            }

            # Redir or template
            if ((trim($DB_OptInRedir)) == "") {
                # Display the page
                if ($SilentMode != 1) {
                    include('templates/open.page.php');
                    include('templates/sub_complete.subhandler.php');
                    copyright();
                    include('templates/close.page.php');
                }
                die();
            } else {
                if ($SilentMode != 1) {
                    header("Location: $DB_OptInRedir");
                    print "<br>\n";
                    print "Now redirecting you to a new page...<br>\n";
                    print "<br>\n";
                    print "If your browser doesn't support redirects then you'll need to <A HREF=\"$DB_OptInRedir\">click here.</A><br>\n";
                    print "<br>\n";
                }
                die();
            }
        } elseif ($type == "u") {
            # Send mail
            SendMessageTemplate('templates/unsubscribe.complete.txt');
            if ($DB_NotifyOnSub == "1") {
                SendMessageTemplate('templates/subscriber_left.notify.txt', $DB_OwnerEmail, $DB_OwnerEmail);
            }

            # Delete from DB
            $query = "DELETE FROM InfResp_subscribers WHERE SubscriberID = '$DB_SubscriberID'";
            $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());
            $query = "DELETE FROM InfResp_customfields WHERE user_attached = '$DB_SubscriberID'";
            $result = mysql_query($query) or die("Invalid query: " . mysql_error());

            # Redirect or template
            if ((trim($DB_OptOutRedir)) == "") {
                # Display the page
                if ($SilentMode != 1) {
                    include('templates/open.page.php');
                    include('templates/unsub_complete.subhandler.php');
                    copyright();
                    include('templates/close.page.php');
                }
                die();
            } else {
                if ($SilentMode != 1) {
                    header("Location: $DB_OptOutRedir");
                    print "<br>\n";
                    print "Now redirecting you to a new page...<br>\n";
                    print "<br>\n";
                    print "If your browser doesn't support redirects then you'll need to <A HREF=\"$DB_OptOutRedir\">click here.</A><br>\n";
                    print "<br>\n";
                }
                die();
            }
        }
    } else {
        # Invalid code. Print it!
        if ($SilentMode != 1) {
            include('templates/open.page.php');
            include('templates/invalid_code.subhandler.php');
            copyright();
            include('templates/close.page.php');
        }
        die();
    }
} else {
    # if ($action == "unsub") {
    #      # Get user and responder info
    #      if ((GetSubscriberInfo($Subscriber_ID)) == FALSE) {
    #           if ($SilentMode != 1) {
    #                include('templates/open.page.php');
    #                include('templates/invalid_action.subhandler.php');
    #                copyright();
    #                include('templates/close.page.php');
    #           }
    #           die();
    #      }
    #      $Responder_ID = $DB_ResponderID;
    #      if (!(ResponderExists($Responder_ID))) {
    #           if ($SilentMode != 1) {
    #                include('templates/open.page.php');
    #                include('templates/invalid_action.subhandler.php');
    #                copyright();
    #                include('templates/close.page.php');
    #           }
    #           die();
    #      }
    #      GetResponderInfo();
    #
    #      # Send confirmation msg
    #      SendMessageTemplate('templates/unsubscribe.confirm.txt');
    #
    #      # Display from the DB or the template
    #      if ((trim($DB_OptOutDisplay)) == "") {
    #           # Display the template
    #           if ($SilentMode != 1) {
    #                include('templates/open.page.php');
    #                include('templates/unsub_confirm.subhandler.php');
    #                copyright();
    #                include('templates/close.page.php');
    #           }
    #           die();
    #      }
    #      else {
    #           # Display from the DB
    #           if ($SilentMode != 1) {
    #                include('templates/open.page.php');
    #                print $DB_OptOutDisplay;
    #                copyright();
    #                include('templates/close.page.php');
    #           }
    #           die();
    #      }
    # }
    if (($action == "sub") || ($action == "subscribe") || ($action == "s")) {
        # Check the email address format
        if (!(isEmail($Email_Address))) {
            if ($SilentMode != 1) {
                include('templates/open.page.php');
                include('templates/invalid_email.subhandler.php');
                copyright();
                include('templates/close.page.php');
            }
            die();
        }

        # Is the email address blacklisted?
        if (isInBlacklist($Email_Address)) {
            if ($SilentMode != 1) {
                include('templates/open.page.php');
                include('templates/blacklisted.subhandler.php');
                copyright();
                include('templates/close.page.php');
            }
            die();
        }

        # Get responder info.
        if (!(ResponderExists($Responder_ID))) {
            # Invalid code. Print it!
            if ($SilentMode != 1) {
                include('templates/open.page.php');
                include('templates/invalid_code.subhandler.php');
                copyright();
                include('templates/close.page.php');
            }
            die();
        }
        GetResponderInfo();

        # Is the email already on this responder?
        $query = "SELECT * FROM InfResp_subscribers WHERE ResponderID = '$Responder_ID' AND EmailAddress = '$Email_Address'";
        $result = mysql_query($query) or die("Invalid query: " . mysql_error());
        if (mysql_num_rows($result) > 0) {
            # Yes, it is.
            $result_data = mysql_fetch_assoc($result);
            $DB_SubscriberID = $result_data['SubscriberID'];
            $DB_ResponderID = $result_data['ResponderID'];
            $DB_SentMsgs = $result_data['SentMsgs'];
            $DB_EmailAddress = $result_data['EmailAddress'];
            $DB_TimeJoined = $result_data['TimeJoined'];
            $DB_Real_TimeJoined = $result_data['Real_TimeJoined'];
            $CanReceiveHTML = $result_data['CanReceiveHTML'];
            $DB_LastActivity = $result_data['LastActivity'];
            $DB_FirstName = $result_data['FirstName'];
            $DB_LastName = $result_data['LastName'];
            $DB_IPaddy = $result_data['IP_Addy'];
            $DB_ReferralSource = $result_data['ReferralSource'];
            $DB_UniqueCode = $result_data['UniqueCode'];
            $DB_Confirmed = $result_data['Confirmed'];

            # Are they confirmed?
            if ($DB_Confirmed == "1") {
                # Yes, display the error page.
                if ($SilentMode != 1) {
                    include('templates/open.page.php');
                    include('templates/already_subscribed.subhandler.php');
                    copyright();
                    include('templates/close.page.php');
                }
                die();
            } else {
                # Send confirmation msg
                SendMessageTemplate('templates/subscribe.confirm.txt');

                # Display from the DB or the template
                if ((trim($DB_OptInDisplay)) == "") {
                    # Display the template
                    if ($SilentMode != 1) {
                        include('templates/open.page.php');
                        include('templates/sub_confirm.subhandler.php');
                        copyright();
                        include('templates/close.page.php');
                    }
                    die();
                } else {
                    # Display from the DB
                    if ($SilentMode != 1) {
                        include('templates/open.page.php');
                        print $DB_OptInDisplay;
                        copyright();
                        include('templates/close.page.php');
                    }
                    die();
                }
            }
        }

        # They aren't already subscribed, let's proceed...
        $DB_ResponderID = $Responder_ID;
        $DB_SentMsgs = "";
        $DB_EmailAddress = $Email_Address;
        $DB_TimeJoined = time();
        $DB_Real_TimeJoined = time();
        $CanReceiveHTML = $HandleHTML;
        $DB_LastActivity = time();
        $DB_FirstName = $FirstName;
        $DB_LastName = $LastName;
        $DB_IPaddy = $IPaddy;
        $DB_ReferralSource = $ReferralSrc;
        $DB_UniqueCode = generate_unique_code();
        $DB_Confirmed = "0";

        if ($DB_OptMethod == "Double") {
            # Add a non-confirmed row to the DB
            $query = "INSERT INTO InfResp_subscribers (ResponderID, SentMsgs, EmailAddress, TimeJoined, Real_TimeJoined, CanReceiveHTML, LastActivity, FirstName, LastName, IP_Addy, ReferralSource, UniqueCode, Confirmed)
                         VALUES('$DB_ResponderID','$DB_SentMsgs', '$DB_EmailAddress', '$DB_TimeJoined', '$DB_Real_TimeJoined', '$CanReceiveHTML', '$DB_LastActivity', '$DB_FirstName', '$DB_LastName', '$DB_IPaddy', '$DB_ReferralSource', '$DB_UniqueCode', '$DB_Confirmed')";
            $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());
            $DB_SubscriberID = mysql_insert_id();

            # Send confirmation msg
            SendMessageTemplate('templates/subscribe.confirm.txt');

            # Display from the DB or the template
            if ((trim($DB_OptInDisplay)) == "") {
                # Display the template
                if ($SilentMode != 1) {
                    include('templates/open.page.php');
                    include('templates/sub_confirm.subhandler.php');
                    copyright();
                    include('templates/close.page.php');
                }
                die();
            } else {
                # Display from the DB
                if ($SilentMode != 1) {
                    include('templates/open.page.php');
                    print $DB_OptInDisplay;
                    copyright();
                    include('templates/close.page.php');
                }
                die();
            }
        } else {
            # Add a confirmed row to the DB
            $DB_Confirmed = "1";
            $query = "INSERT INTO InfResp_subscribers (ResponderID, SentMsgs, EmailAddress, TimeJoined, Real_TimeJoined, CanReceiveHTML, LastActivity, FirstName, LastName, IP_Addy, ReferralSource, UniqueCode, Confirmed)
                         VALUES('$DB_ResponderID','$DB_SentMsgs', '$DB_EmailAddress', '$DB_TimeJoined', '$DB_Real_TimeJoined', '$CanReceiveHTML', '$DB_LastActivity', '$DB_FirstName', '$DB_LastName', '$DB_IPaddy', '$DB_ReferralSource', '$DB_UniqueCode', '$DB_Confirmed')";
            $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());
            $DB_SubscriberID = mysql_insert_id();

            # Handle custom fields
            AddCustomFields();

            # Send mail and notify
            SendMessageTemplate('templates/subscribe.complete.txt');
            if ($DB_NotifyOnSub == "1") {
                SendMessageTemplate('templates/new_subscriber.notify.txt', $DB_OwnerEmail, $DB_OwnerEmail);
            }

            # Autocall sendmails on subscribe?
            if ($config['autocall_sendmails'] == "1") {
                $silent = TRUE;
                include('sendmails.php');
            }

            # Template or redirect
            if ((trim($DB_OptInRedir)) == "") {
                # Display the page
                if ($SilentMode != 1) {
                    include('templates/open.page.php');
                    include('templates/sub_complete.subhandler.php');
                    copyright();
                    include('templates/close.page.php');
                }
                die();
            } else {
                if ($SilentMode != 1) {
                    header("Location: $DB_OptInRedir");
                    print "<br>\n";
                    print "Now redirecting you to a new page...<br>\n";
                    print "<br>\n";
                    print "If your browser doesn't support redirects then you'll need to <A HREF=\"$DB_OptInRedir\">click here.</A><br>\n";
                    print "<br>\n";
                }
                die();
            }
        }
    } else {
        if ($SilentMode != 1) {
            include('templates/open.page.php');
            include('templates/invalid_action.subhandler.php');
            copyright();
            include('templates/close.page.php');
        }
        die();
    }
}

DB_disconnect();
?>