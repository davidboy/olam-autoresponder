<?php
# ---------------------------------------------------------
# Set the valid responder numbers
$responder_list = array();
$responder_list[1] = "1";
$responder_list[2] = "2";
$responder_list[3] = "3";
$responder_list[4] = "4";
$responder_list[5] = "5";
$responder_list[6] = "6";
$responder_list[7] = "7";
$responder_list[8] = "8";
$responder_list[9] = "9";
$responder_list[10] = "10";
$responder_list[11] = "11";
$responder_list[12] = "12";
$responder_list[13] = "13";
$responder_list[14] = "14";
$responder_list[15] = "15";
$responder_list[16] = "16";
$responder_list[17] = "17";
$responder_list[18] = "18";
$responder_list[19] = "19";
$responder_list[20] = "20";

# ---------------------------------------------------------

# Set stuff up
if ($config_init != TRUE) {
    $config_init = TRUE;
    include('common.php');
}

# ---------------------------------------------------------

# Grab and check the move_to variable
if (empty($passed['MOVE_TO'])) {
    $passed['MOVE_TO'] = $_REQUEST['MOVE_TO'];
}
$move_to_checked = FALSE;
foreach ($responder_list as $key => $value) {
    if ($passed['MOVE_TO'] == $value) {
        $move_to_checked = TRUE;
    }
}
if ($move_to_checked != TRUE) {
    die("Invalid responder number!<br>\n");
}

# Get the other passed data
if (empty($passed['NAME'])) {
    $passed['NAME'] = $_REQUEST['NAME'];
}
if (empty($passed['EMAIL'])) {
    $passed['EMAIL'] = $_REQUEST['EMAIL'];
}
if (empty($passed['FIRST'])) {
    $passed['FIRST'] = $_REQUEST['FIRST'];
}
if (empty($passed['LAST'])) {
    $passed['LAST'] = $_REQUEST['LAST'];
}
if (empty($passed['IP'])) {
    $passed['IP'] = $_REQUEST['IP'];
}
if (empty($passed['DEL'])) {
    $passed['DEL'] = $_REQUEST['DEL'];
}
if ((empty($passed['DEL'])) || ($passed['DEL'] == "") || ($passed['DEL'] == 0) || ($passed['DEL'] == FALSE)) {
    $passed['DEL'] = FALSE;
} else {
    $passed['DEL'] = TRUE;
}
if ($passed['ONLYDEL'] == "YES") {
    $passed['ONLYDEL'] = TRUE;
} else {
    $passed['ONLYDEL'] = FALSE;
}
if ((empty($passed['EMAIL'])) OR ($passed['EMAIL'] == "") OR ($passed['EMAIL'] == NULL)) {
    die("Nobody was passed for me to move!<br>\n");
}
if ((empty($passed['FIRST'])) || (empty($passed['LAST']))) {
    if (!(empty($passed['NAME']))) {
        $Space_List = explode(' ', trim($passed['NAME']));
        $Space_MaxIndex = sizeof($Space_List);
        if (empty($passed['LAST'])) {
            $passed['LAST'] = $Space_List[$Space_MaxIndex - 1];
        }
        if (empty($passed['FIRST'])) {
            $passed['FIRST'] = '';
            for ($k = 0; $k <= $Space_MaxIndex - 2; $k++) {
                $passed['FIRST'] = $passed['FIRST'] . ' ' . $Space_List[$k];
            }
            $passed['FIRST'] = trim($passed['FIRST']);
        }
    }
}

# Create the safe data array
foreach ($passed as $key => $value) {
    $safe[$key] = makeSafe($value);
}

if (!(isInBlacklist($safe['EMAIL']))) {
    # Get old responder info
    $got_custom_fields = FALSE;
    $user_data = array();
    $custom_fields = array();
    $resp_list = "";
    foreach ($responder_list as $idx => $resp_num) {
        $resp_list = $resp_list . "'" . $resp_num . "',";
    }
    $resp_list = trim($resp_list, ",");
    $query = "SELECT * FROM InfResp_subscribers WHERE (EmailAddress = '" . $safe['EMAIL'] . "') AND ResponderID IN (" . $resp_list . ")";
    # echo $query . "<br>\n";
    $result = $DB->query($query) OR die("Invalid query: " . $DB->error);
    if ($result->num_rows > 0) {
        # Get current data
        $user_data = $result->fetch_assoc();

        # Get the relevant responder ID
        $attached_responder_id = $user_data['ResponderID'];

        # Update array based on any provided data
        if ((!(empty($safe['FIRST']))) && ($safe['FIRST'] != "")) {
            $user_data['FirstName'] = $safe['FIRST'];
        }
        if ((!(empty($safe['LAST']))) && ($safe['LAST'] != "")) {
            $user_data['LastName'] = $safe['LAST'];
        }
        if ((!(empty($safe['IP']))) && ($safe['IP'] != "")) {
            $user_data['IP_Addy'] = $safe['IP'];
        }

        # Is there any custom data?
        $query = "SELECT * FROM InfResp_customfields WHERE (email_attached= '" . $safe['EMAIL'] . "') AND resp_attached IN (" . $resp_list . ")";
        # echo $query . "<br>\n";
        $custom_result = $DB->query($query) OR die("Invalid query: " . $DB->error);
        if ($custom_result->num_rows > 0) {
            # Yep, get it.
            $custom_fields = $custom_result->fetch_assoc();
            $got_custom_fields = TRUE;

            # Delete the current custom fields?
            if ($passed['DEL'] == TRUE) {
                if ($passed['ONLYDEL'] == TRUE) {
                    $query = "DELETE FROM InfResp_customfields WHERE (email_attached= '" . $safe['EMAIL'] . "') AND resp_attached IN (" . $resp_list . ")";
                    # echo $query . "<br>\n";
                } else {
                    $query = "DELETE FROM InfResp_customfields WHERE email_attached = '" . $safe['EMAIL'] . "' AND resp_attached = '" . $attached_responder_id . "'";
                    # echo $query . "<br>\n";
                }
                $custom_delete = $DB->query($query) OR die("Invalid query: " . $DB->error);
            }
        }

        # Delete old responder info?
        if ($passed['DEL'] == TRUE) {
            if ($passed['ONLYDEL'] == TRUE) {
                $query = "DELETE FROM InfResp_subscribers WHERE (EmailAddress = '" . $safe['EMAIL'] . "') AND ResponderID IN (" . $resp_list . ")";
            } else {
                $query = "DELETE FROM InfResp_subscribers WHERE EmailAddress = '" . $safe['EMAIL'] . "' AND ResponderID = '" . $attached_responder_id . "'";
            }
            $delete_result = $DB->query($query) OR die("Invalid query: " . $DB->error);
        }
    } else {
        # Make the data array
        $user_data['SentMsgs'] = '';
        $user_data['EmailAddress'] = $safe['EMAIL'];
        $user_data['TimeJoined'] = time();
        $user_data['Real_TimeJoined'] = time();
        $user_data['CanReceiveHTML'] = '1';
        $user_data['LastActivity'] = time();
        $user_data['FirstName'] = $safe['FIRST'];
        $user_data['LastName'] = $safe['LAST'];
        $user_data['IP_Addy'] = $safe['IP'];
        $user_data['ReferralSource'] = 'Added w/ Move Subscriber';
        $user_data['UniqueCode'] = generateUniqueCode();
        $user_data['Confirmed'] = '1';
        $user_data['IsSubscribed'] = '1';
    }

    # Check for existance in new responder.
    $query = "SELECT * FROM InfResp_subscribers WHERE EmailAddress = '" . $safe['EMAIL'] . "' AND ResponderID = '" . $passed['MOVE_TO'] . "'";
    # echo $query . "<br>\n";
    $result = $DB->query($query) OR die("Invalid query: " . $DB->error);
    if ($result->num_rows > 0) {
        # Update existing data
        $query = "UPDATE InfResp_subscribers SET SentMsgs = '', TimeJoined = '" . $user_data['TimeJoined'] . "', Real_TimeJoined = '" . $user_data['Real_TimeJoined'] . "', LastActivity = '" . $user_data['LastActivity'] . "', FirstName = '" . $user_data['FirstName'] . "', LastName = '" . $user_data['LastName'] . "', IP_Addy = '" . $user_data['IP_Addy'] . "', ReferralSource = '" . $user_data['ReferralSource'] . "', Confirmed = '" . $user_data['Confirmed'] . "', IsSubscribed = '" . $user_data['IsSubscribed'] . "' WHERE EmailAddress = '" . $safe['EMAIL'] . "' AND ResponderID = '" . $passed['MOVE_TO'] . "'";

        # echo $query . "<br>\n";
        $result = $DB->query($query) OR die("Invalid query: " . $DB->error);
    } else {
        # Make a new entry?
        if ($passed['ONLYDEL'] != TRUE) {
            $insert_values = "'" . $passed['MOVE_TO'] . "','" . $user_data['SentMsgs'] . "','" . $user_data['EmailAddress'] . "','" . $user_data['TimeJoined'] . "','" . $user_data['Real_TimeJoined'] . "','" . $user_data['CanReceiveHTML'] . "','" . $user_data['LastActivity'] . "','" . $user_data['FirstName'] . "','" . $user_data['LastName'] . "','" . $user_data['IP_Addy'] . "','" . $user_data['ReferralSource'] . "','" . $user_data['UniqueCode'] . "','" . $user_data['Confirmed'] . "','" . $user_data['IsSubscribed'] . "'";
            $query = "INSERT INTO InfResp_subscribers (ResponderID,SentMsgs,EmailAddress,TimeJoined,Real_TimeJoined,CanReceiveHTML,LastActivity,FirstName,LastName,IP_Addy,ReferralSource,UniqueCode,Confirmed, IsSubscribed) VALUES ($insert_values)";

            # echo $query . "<br>\n";
            $result = $DB->query($query) OR die("Invalid query: " . $DB->error);
            $subscriber_id = $DB->insert_id;

            # Insert any custom field data
            if (($got_custom_fields == TRUE) && ($subscriber_id) && ($subscriber_id != NULL) && ($subscriber_id != "") && ($subscriber_id != 0)) {
                $fieldstr = "";
                $valuestr = "";
                $custom_fields['user_attached'] = $subscriber_id;
                $custom_fields['resp_attached'] = $passed['MOVE_TO'];
                foreach ($custom_fields as $key => $value) {
                    if ($key != "fieldID") {
                        $fieldstr .= $key . ",";
                        $valuestr .= "'" . $value . "',";
                    }
                }
                $fieldstr = trim((trim($fieldstr)), ",");
                $valuestr = trim((trim($valuestr)), ",");
                $query = "INSERT INTO InfResp_customfields ($fieldstr) VALUES($valuestr)";
                # echo $query . "<br>\n";
                $custom_result = $DB->query($query) or die("Invalid query: " . $DB->error);
            }
        }
    }
}
