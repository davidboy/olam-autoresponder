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

require_once 'common.php';

# Load the regexps
$query = "SELECT DISTINCT * FROM InfResp_BounceRegs";
$regexp_result = $DB->query($query) or die("Invalid query: " . $DB->error);
while ($this_row = $regexp_result->fetch_assoc()) {
    $regexp_id = $this_row['BounceRegexpID'];
    $regexp[$regexp_id] = $this_row['RegX'];
}

# Go thru the enabled bouncers
$query = "SELECT * FROM InfResp_Bouncers WHERE Enabled = '1' ORDER BY BouncerID";
$bouncer_result = $DB->query($query) or die("Invalid query: " . $DB->error);
while ($bouncer = $bouncer_result->fetch_assoc()) {
    # Re-init the array
    $bounced_addy_array = array();

    # Connect up
    $conn = @imap_open("{" . $bouncer['host'] . ":" . $bouncer['port'] . "/" . $bouncer['mailtype'] . "/notls}" . $bouncer['mailbox'], $bouncer['username'], $bouncer['password']);
    $headers = @imap_headers($conn);
    if ($headers) {
        $email_count = sizeof($headers);
        for ($i = 1; $i <= $email_count; $i++) {
            # Check the body against all saved patterns
            $mail_head = imap_headerinfo($conn, $i);
            $mail_body = imap_fetchbody($conn, $i, 1);

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
            $result = $DB->query($query) or die("Invalid query: " . $DB->error);
            if ($result->num_rows > 0) {
                # Prep data
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

                # Remove user and custom fields
                $query = "DELETE FROM InfResp_subscribers WHERE EmailAddress = '$bounced_addy'";
                $DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);
                $query = "DELETE FROM InfResp_customfields WHERE email_attached = '$bounced_addy'";
                $DB_result = $DB->query($query) or die("Invalid query: " . $DB->error);

                # Notify owner
                if ($bouncer['NotifyOwner'] == "1") {
                    sendMessageTemplate('templates/subscriber_removed.notify.txt', $bouncer['EmailAddy'], $bouncer['EmailAddy']);
                }
            }
        }
    }
}
