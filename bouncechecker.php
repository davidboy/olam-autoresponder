<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

include_once('common.php');

# Load the regexps
$query = "SELECT DISTINCT * FROM InfResp_BounceRegs";
$regexp_result = mysql_query($query) or die("Invalid query: " . mysql_error());
for ($i = 0; $i < mysql_num_rows($regexp_result); $i++) {
    $this_row = mysql_fetch_assoc($regexp_result);
    $regexp_id = $this_row['BounceRegexpID'];
    $regexp[$regexp_id] = $this_row['RegX'];
}

# Go thru the enabled bouncers
$query = "SELECT * FROM InfResp_Bouncers WHERE Enabled = '1' ORDER BY BouncerID";
$bouncer_result = mysql_query($query) or die("Invalid query: " . mysql_error());
while ($bouncer = mysql_fetch_assoc($bouncer_result)) {
    # Re-init the array
    $bounced_addy_array = array();

    # Connect up
    # echo $bouncer['host'] . "<br>\n";
    # echo $bouncer['port'] . "<br>\n";
    # echo $bouncer['mailtype'] . "<br>\n";
    # echo $bouncer['mailbox'] . "<br>\n";
    # echo $bouncer['username'] . "<br>\n";
    # echo $bouncer['password'] . "<br>\n";
    $conn = @imap_open("{" . $bouncer['host'] . ":" . $bouncer['port'] . "/" . $bouncer['mailtype'] . "/notls}" . $bouncer['mailbox'], $bouncer['username'], $bouncer['password']);
    $headers = @imap_headers($conn);
    if ($headers) {
        $email_count = sizeof($headers);
        for ($i = 1; $i <= $email_count; $i++) {
            # Check the body against all saved patterns
            $mail_head = imap_headerinfo($conn, $i);
            $mail_body = imap_fetchbody($conn, $i, 1);
            # echo "mail head: $mail_head<br>\n";
            # echo "mail body: $mail_body<br>\n";

            $matched = FALSE;
            foreach ($regexp as $regexp_id => $pattern) {
                if ((preg_match("/" . $pattern . "/ims", $mail_body)) == TRUE) {
                    $matched = TRUE;
                }
            }
            if ($matched == TRUE) {
                # Got a match, grab the email address.
                if (preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z0-9.-]+$/i", $mail_body, $matches)) {
                    $bounced_address = $matches[0];
                    $bounced_address = str_replace('(', "", $bounced_address);
                    $bounced_address = str_replace(')', "", $bounced_address);
                    $bounced_address = str_replace('<', "", $bounced_address);
                    $bounced_address = str_replace('>', "", $bounced_address);

                    # Add the email address into the array if it's not there.
                    if (!(isInArray($bounced_addy_array, $bounced_address))) {
                        # echo "bounced: $bounced_address <br>\n";
                        $bounced_addy_array[] = makeSafe($bounced_address);
                    }
                }

                # Delete just bounces?
                if ($bouncer['DeleteLevel'] == "1") {
                    @imap_delete($conn, $i);
                }
            }

            # Delete all?
            if ($bouncer['DeleteLevel'] == "2") {
                @imap_delete($conn, $i);
            }
        }

        # Expunge and close.
        @imap_expunge($conn);
        @imap_close($conn);

        # Remove bounced subscribers
        foreach ($bounced_addy_array as $bouncenum => $bounced_addy) {
            # Pull subscriber info.
            $query = "SELECT * FROM InfResp_subscribers WHERE EmailAddress = '$bounced_addy'";
            $result = mysql_query($query) or die("Invalid query: " . mysql_error());
            if (mysql_num_rows($result) > 0) {
                # Prep data
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
                $DB_IsSubscribed = $result_data['IsSubscribed'];

                # Remove user and custom fields
                $query = "DELETE FROM InfResp_subscribers WHERE EmailAddress = '$bounced_addy'";
                $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());
                $query = "DELETE FROM InfResp_customfields WHERE email_attached = '$bounced_addy'";
                $DB_result = mysql_query($query) or die("Invalid query: " . mysql_error());

                # Notify owner
                if ($bouncer['NotifyOwner'] == "1") {
                    # print "Got a bounce, assigned email: " . $bouncer['EmailAddy'] . "<br>\n";
                    # print "sending notify...<br>\n";
                    sendMessageTemplate('templates/subscriber_removed.notify.txt', $bouncer['EmailAddy'], $bouncer['EmailAddy']);
                }
            }
        }
    }
}

# Should we disconnect from the DB?
if ($included != TRUE) {
    dbDisconnect();
}
?>
