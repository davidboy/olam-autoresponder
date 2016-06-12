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

# Is the email already on this responder?
$query = "SELECT * FROM InfResp_subscribers WHERE ResponderID = '$Responder_ID' AND EmailAddress = '$Email_Address'";
$result = $DB->query($query) or die("Invalid query: " . $DB->error);
if ($result->num_rows > 0) {
    # Yes, it is.
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

    # Are they confirmed and subscribed?
    if ($DB_Confirmed == "1" AND $DB_IsSubscribed == "1") {
        # Yes, display the error page.
        if ($SilentMode != 1) {
            include('templates/open.page.php');
            include('templates/already_subscribed.subhandler.php');
            copyright();
            include('templates/close.page.php');
        }
        die();
    # Are they confirmed but unsubscribed? (this is a resubscription request)
    } else if ($DB_Confirmed == "1" AND $DB_IsSubscribed == "0") {
    	
    		# get LastActivity and TimeJoined
    		$Timestamper = time();
    		$query = "SELECT LastActivity, TimeJoined from InfResp_subscribers  WHERE ResponderID = '$Responder_ID' AND EmailAddress = '$Email_Address'";
    		$DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);
    		$this_row = $DB_result->fetch_assoc();
    		
    		# use lastactivity and timejoined to calculate what date to use for resuming responder messages
    		$Time_Subscribed = $this_row['LastActivity'] - $this_row['TimeJoined'];
    		$Resume_Messages_Date = $Timestamper - $Time_Subscribed;
    		
    		$query = "UPDATE InfResp_subscribers SET TimeJoined = '$Resume_Messages_Date', CanReceiveHTML = '$CanReceiveHTML', LastActivity = '$Timestamper', FirstName = '$FirstNameArray[$i]', LastName = '$LastNameArray[$i]', Confirmed = '1', IsSubscribed = '1' WHERE ResponderID = '$Responder_ID' AND EmailAddress = '$Email_Address'";
    		$DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);
    		
    		# Send mail and notify
		    sendMessageTemplate('templates/subscribe.complete.txt');
		    if ($DB_NotifyOnSub == "1") {
		        sendMessageTemplate('templates/new_subscriber.notify.txt', $DB_OwnerEmail, $DB_OwnerEmail);
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
		    
    } else {
        # Send confirmation msg
        sendMessageTemplate('templates/subscribe.confirm.txt');

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
$DB_UniqueCode = generateUniqueCode();
$DB_Confirmed = "0";
$DB_IsSubscribed = "1";

if ($DB_OptMethod == "Double") {
    # Add a non-confirmed row to the DB
    $query = "INSERT INTO InfResp_subscribers (ResponderID, SentMsgs, EmailAddress, TimeJoined, Real_TimeJoined, CanReceiveHTML, LastActivity, FirstName, LastName, IP_Addy, ReferralSource, UniqueCode, Confirmed, IsSubscribed)
                 VALUES('$DB_ResponderID','$DB_SentMsgs', '$DB_EmailAddress', '$DB_TimeJoined', '$DB_Real_TimeJoined', '$CanReceiveHTML', '$DB_LastActivity', '$DB_FirstName', '$DB_LastName', '$DB_IPaddy', '$DB_ReferralSource', '$DB_UniqueCode', '$DB_Confirmed', '$DB_IsSubscribed')";
    $DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);
    $DB_SubscriberID = $DB->insert_id;

    # Send confirmation msg
    sendMessageTemplate('templates/subscribe.confirm.txt');

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
    $query = "INSERT INTO InfResp_subscribers (ResponderID, SentMsgs, EmailAddress, TimeJoined, Real_TimeJoined, CanReceiveHTML, LastActivity, FirstName, LastName, IP_Addy, ReferralSource, UniqueCode, Confirmed, IsSubscribed)
                 VALUES('$DB_ResponderID','$DB_SentMsgs', '$DB_EmailAddress', '$DB_TimeJoined', '$DB_Real_TimeJoined', '$CanReceiveHTML', '$DB_LastActivity', '$DB_FirstName', '$DB_LastName', '$DB_IPaddy', '$DB_ReferralSource', '$DB_UniqueCode', '$DB_Confirmed', '$DB_IsSubscribed')";
    $DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);
    $DB_SubscriberID = $DB->insert_id;

    # Handle custom fields
    addCustomFields();

    # Send mail and notify
    sendMessageTemplate('templates/subscribe.complete.txt');
    if ($DB_NotifyOnSub == "1") {
        sendMessageTemplate('templates/new_subscriber.notify.txt', $DB_OwnerEmail, $DB_OwnerEmail);
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