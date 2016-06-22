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

require_once('subscriptions_common.php');

# Is there a confirm string?
if (isEmpty($Confirm_String)) {
    // TODO: display an error
}

# Is the code type valid?
$type = strtolower(substr($Confirm_String, 0, 1));
if (($type != "s") && ($type != "u")) {
    # Invalid code. Print it!
    if ($SilentMode != 1) {
        include('templates/open.page.php');
        include('templates/invalid_code.subhandler.php');
        copyright();
        include('templates/close.page.php');
    }
    die();
}

# Verify the code
$code = substr($Confirm_String, 1, (strlen($Confirm_String) - 1));
$query = "SELECT * FROM InfResp_subscribers WHERE UniqueCode = '$code'";
$result = $DB->query($query) or die("Invalid query: " . $DB->error);
if ($result->num_rows < 1) {
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
$result_data = $result->fetch_assoc();
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
$DB_IsSubscribed = $result_data['IsSubscribed'];

# Grab the relevant responder data
$Responder_ID = $DB_ResponderID;
if (!(responderExists($Responder_ID))) {
    # Invalid code. Print it!
    if ($SilentMode != 1) {
        include('templates/open.page.php');
        include('templates/invalid_code.subhandler.php');
        copyright();
        include('templates/close.page.php');
    }
    die();
}
getResponderInfo();

# Emails, DB and redir/template
if ($type == "s") {
    # Do DB update
    $Set_LastActivity = time();
    $query = "UPDATE InfResp_subscribers SET LastActivity = '$Set_LastActivity', TimeJoined = '$Set_LastActivity', Real_TimeJoined = '$Set_LastActivity', Confirmed = '1', IsSubscribed = '1' WHERE SubscriberID = '$DB_SubscriberID'";
    $DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);

    # Handle custom fields
    addCustomFields();

    # Send mail
    sendMessageTemplate('templates/subscribe.complete.txt');
    if ($DB_NotifyOnSub == "1") {
        sendMessageTemplate('templates/new_subscriber.notify.txt', $DB_OwnerEmail, $DB_OwnerEmail);
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
    
	# check if the user has verified
	$Unsub_Verified = makeSafe($_REQUEST['unsub_verify']);
	
	if (Empty($Unsub_Verified) || $Unsub_Verified <> 1) 
	{
		# Get responder name
		$query = "SELECT Name from InfResp_responders  WHERE ResponderID = '$Responder_ID'";
		$DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);
		$this_row = $DB_result->fetch_assoc();
		$This_Resp_Name = $this_row['Name'];
		
		# Display the page to ask them to verify
		if ($SilentMode != 1) {
			include('templates/open.page.php');
			include('templates/unsub_verify.subhandler.php');
			copyright();
			include('templates/close.page.php');
		}
		die();
	}
	else  # they have verified, let's continue
	{
		# Send mail
	    sendMessageTemplate('templates/unsubscribe.complete.txt');
	    if ($DB_NotifyOnSub == "1") {
	        sendMessageTemplate('templates/subscriber_left.notify.txt', $DB_OwnerEmail, $DB_OwnerEmail);
	    }
	
	    # Set the user's status to unsubscribed, but leave them in the database if they want to resubscribe later
	    $query = "UPDATE InfResp_subscribers SET IsSubscribed = '0' WHERE SubscriberID = '$DB_SubscriberID'";
	    $DB_result = $DB->query($query)
	    or die("Invalid query: " . $DB->error);
	
	    $query = "DELETE FROM InfResp_customfields WHERE user_attached = '$DB_SubscriberID'";
	    $result = $DB->query($query)
	    or die("Invalid query: " . $DB->error);
	
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
}