<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

function string_cut($string, $cut_size)
{
    $StringArray = explode(" ", $string);
    $SizeCount = sizeof($StringArray);

    for ($i = 0; $i < $cut_size; $i++) {
        $string_cut .= " " . "$StringArray[$i]";
    }

    if ($cut_size < $SizeCount) {
        $return_str = "$string_cut" . "...";
    } else {
        $return_str = $string;
    }

    return $return_str;
}

function str_makerand($minlength, $maxlength, $useupper, $usespecial, $usenumbers)
{
    $charset = "abcdefghijklmnopqrstuvwxyz";
    if ($useupper) {
        $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }
    if ($usenumbers) {
        $charset .= "0123456789";
    }
    if ($usespecial) {
        $charset .= "~@#$%^*()_+-={}|][";
    }
    if ($minlength > $maxlength) {
        $length = mt_rand($maxlength, $minlength);
    } else {
        $length = mt_rand($minlength, $maxlength);
    }

    $key = "";
    for ($i = 0; $i < $length; $i++) {
        $key .= $charset[(mt_rand(0, (strlen($charset) - 1)))];
    }

    return $key;
}

# ------------------------------------------------
# XOR encryption functions found at:
# http://www.phpbuilder.com/tips/item.php?id=68
function x_Encrypt($string, $key)
{
    for ($i = 0; $i < strlen($string); $i++) {
        for ($j = 0; $j < strlen($key); $j++) {
            $string[$i] = $string[$i] ^ $key[$j];
        }
    }

    return $string;
}

function x_Decrypt($string, $key)
{
    for ($i = 0; $i < strlen($string); $i++) {
        for ($j = 0; $j < strlen($key); $j++) {
            $string[$i] = $key[$j] ^ $string[$i];
        }
    }

    return $string;
}

# ------------------------------------------------

function my_ucwords($input)
{
    $input = ucwords($input);
    $input = str_replace(" ", "%__%__%", $input);
    $input = str_replace("-", " ", $input);
    $input = ucwords($input);
    $input = str_replace(" ", "-", $input);
    $input = str_replace("%__%__%", " ", $input);
    return $input;
}

function stripnl($string)
{
    $string = preg_replace("/\n\r|\n|\r|\t/", "", $string);
    return $string;
}

function isEmpty($var)
{
    if (!(isset($var))) {
        return TRUE;
    }
    $var = trim($var);
    return empty($var);
}

function DB_connect()
{
    global $MySQL_server, $MySQL_user, $MySQL_password, $MySQL_database;
    global $DB_LinkID;

    $DB_LinkID = mysql_connect($MySQL_server, $MySQL_user, $MySQL_password)
    or die("Could not connect : " . mysql_error());

    mysql_select_db($MySQL_database) or die("Could not select database.");
    return $DB_LinkID;
}

function DB_disconnect()
{
    global $DB_LinkID;
    $result = mysql_close($DB_LinkID);
    return $result;
}

function DB_Insert_Array($table, $fields)
{
    global $DB_LinkID;
    $fieldstr = "";
    $valuestr = "";
    foreach ($fields as $key => $value) {
        $fieldstr .= $key . ",";
        $valuestr .= "'" . $value . "',";
    }
    $fieldstr = trim((trim($fieldstr)), ",");
    $valuestr = trim((trim($valuestr)), ",");
    $query = "INSERT INTO $table ($fieldstr) VALUES($valuestr)";
    $result = mysql_query($query, $DB_LinkID) or die("Invalid query: " . mysql_error());
}

function DB_Update_Array($table, $fields, $where = "")
{
    global $DB_LinkID;
    $updatestr = "";
    foreach ($fields as $key => $value) {
        $updatestr .= "$key='" . $value . "', ";
    }
    $updatestr = trim((trim($updatestr)), ",");
    $query = "UPDATE $table SET $updatestr";
    if (!(isEmpty($where))) {
        $query .= " WHERE $where";
    }
    $result = mysql_query($query, $DB_LinkID) or die("Invalid query: " . mysql_error());
}

function get_db_fields($tablename)
{
    global $DB_LinkID;
    $result_array = array();
    $query = "SHOW COLUMNS FROM $tablename";
    $result = mysql_query($query, $DB_LinkID) or die("Invalid query: " . mysql_error());
    while ($meta = mysql_fetch_array($result)) {
        $fieldname = strtolower($meta['Field']);
        $result_array['list'][] = $fieldname;
        $result_array['hash'][$fieldname] = TRUE;
    }
    return $result_array;
}

function reset_user_session()
{
    # Reset old session vars
    if ($_SESSION['initialized'] == TRUE) {
        $destroy_it = TRUE;
    }
    $_SESSION['initialized'] = FALSE;
    $_SESSION['timestamp'] = 0;
    $_SESSION['last_IP'] = '';
    $_SESSION['l'] = '';
    $_SESSION['p'] = '';

    # Unset the session cookie
    unset($_COOKIE[session_name()]);
    if ($destroy_it == TRUE) {
        session_destroy();
    }

    # Regen a new session cookie
    session_start();
    session_regenerate_id();
    setcookie(session_name(), session_id());
}

function User_Auth()
{
    global $config;

    # Start the session
    session_start();

    # Is the session even here?
    if ($_SESSION['initialized'] != TRUE) {
        # Nope, it's not initialized...
        reset_user_session();
        return FALSE;
    }

    # Check IP address against last known...
    if ($_SESSION['last_IP'] != $_SERVER['REMOTE_ADDR']) {
        # Not the same, reset the session and return FALSE
        reset_user_session();
        return FALSE;
    }

    # Check session timestamp
    if (time() >= ($_SESSION['timestamp'] + 10800)) {
        # 3 hours of inactivity kills a session
        reset_user_session();
        return FALSE;
    }

    # Test the login and pass
    $test_user = md5(WebEncrypt($config['admin_user'], $config['random_str_1']));
    $test_pass = md5(WebEncrypt($config['admin_pass'], $config['random_str_2']));
    if (($_SESSION['l'] == $test_user) && ($_SESSION['p'] == $test_pass)) {
        # Update the session details, we're good!
        $_SESSION['timestamp'] = time();
        return TRUE;
    }
}

function WebEncrypt($str, $key)
{
    $result = base64_encode(x_Encrypt($str, $key));
    return $result;
}

function WebDecrypt($str, $key)
{
    $result = x_Decrypt(base64_decode($str), $key);
    return $result;
}

function Scramble($var, $RespID, $sometext)
{
    global $Responder_ID;

    $var = x_Encrypt($var, $RespID);
    $var = x_Encrypt($var, $sometext);
    return $var;
}

function Descramble($var, $RespID, $sometext)
{
    global $Responder_ID;

    $var = x_Decrypt($var, $sometext);
    $var = x_Decrypt($var, $RespID);
    return $var;
}

function ResponderExists($R_ID)
{
    global $DB_LinkID;
    if (isEmpty($R_ID)) {
        return FALSE;
    }
    if (!(is_numeric($R_ID))) {
        return FALSE;
    }
    if ($R_ID == "0") {
        return FALSE;
    }
    $query = "SELECT * FROM InfResp_responders WHERE ResponderID = '$R_ID'";
    $DB_result = mysql_query($query, $DB_LinkID) or die("Invalid query: " . mysql_error());
    $result_data = mysql_fetch_row($DB_result);

    if (mysql_num_rows($DB_result) > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function GetMsgInfo($M_ID)
{
    global $DB_MsgID, $DB_MsgSub, $DB_MsgSeconds;
    global $DB_absDay, $DB_absHours, $DB_absMins;
    global $DB_MsgMonths, $DB_MsgBodyText, $DB_MsgBodyHTML;
    global $DB_LinkID;

    $query = "SELECT * FROM InfResp_messages WHERE MsgID = '$M_ID'";
    $DB_result = mysql_query($query, $DB_LinkID)
    or die("Invalid query: " . mysql_error());

    if (mysql_num_rows($DB_result) > 0) {
        $this_row = mysql_fetch_assoc($DB_result);
        $DB_MsgID = $this_row['MsgID'];
        $DB_MsgSub = $this_row['Subject'];
        $DB_MsgSeconds = $this_row['SecMinHoursDays'];
        $DB_MsgMonths = $this_row['Months'];
        $DB_absDay = $this_row['absDay'];
        $DB_absMins = $this_row['absMins'];
        $DB_absHours = $this_row['absHours'];
        $DB_MsgBodyText = $this_row['BodyText'];
        $DB_MsgBodyHTML = $this_row['BodyHTML'];
        return TRUE;
    } else {
        return FALSE;
    }
}

function GetSubscriberInfo($sub_ID)
{
    global $DB_SubscriberID, $DB_ResponderID, $DB_SentMsgs, $DB_LastActivity;
    global $DB_EmailAddress, $DB_TimeJoined, $CanReceiveHTML, $DB_Real_TimeJoined;
    global $DB_FirstName, $DB_LastName, $DB_IPaddy, $DB_ReferralSource;
    global $DB_UniqueCode, $DB_Confirmed, $DB_LinkID;

    $query = "SELECT * FROM InfResp_subscribers WHERE SubscriberID = '$sub_ID'";
    $DB_result = mysql_query($query, $DB_LinkID) or die("Invalid query: " . mysql_error());
    if (mysql_num_rows($DB_result) > 0) {
        $result_data = mysql_fetch_assoc($DB_result);
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
        return TRUE;
    } else {
        return FALSE;
    }
}

function GetResponderInfo()
{
    global $DB_ResponderID, $DB_ResponderName, $DB_OwnerEmail;
    global $DB_OwnerName, $DB_ReplyToEmail, $DB_MsgList, $DB_RespEnabled;
    global $DB_result, $DB_LinkID, $DB_ResponderDesc, $Responder_ID;
    global $DB_OptMethod, $DB_OptInRedir, $DB_NotifyOnSub;
    global $DB_OptOutRedir, $DB_OptInDisplay, $DB_OptOutDisplay;

    $query = "SELECT * FROM InfResp_responders WHERE ResponderID = '$Responder_ID'";
    $DB_result = mysql_query($query, $DB_LinkID) or die("Invalid query: " . mysql_error());
    if (mysql_num_rows($DB_result) > 0) {
        $result_data = mysql_fetch_assoc($DB_result);
        $DB_ResponderID = $result_data['ResponderID'];
        $DB_RespEnabled = $result_data['Enabled'];
        $DB_ResponderName = $result_data['Name'];
        $DB_ResponderDesc = $result_data['ResponderDesc'];
        $DB_OwnerEmail = $result_data['OwnerEmail'];
        $DB_OwnerName = $result_data['OwnerName'];
        $DB_ReplyToEmail = $result_data['ReplyToEmail'];
        $DB_MsgList = $result_data['MsgList'];
        $DB_OptMethod = $result_data['OptMethod'];
        $DB_OptInRedir = $result_data['OptInRedir'];
        $DB_OptOutRedir = $result_data['OptOutRedir'];
        $DB_OptInDisplay = $result_data['OptInDisplay'];
        $DB_OptOutDisplay = $result_data['OptOutDisplay'];
        $DB_NotifyOnSub = $result_data['NotifyOwnerOnSub'];
        return TRUE;
    } else {
        return FALSE;
    }
}

# Returns TRUE if the user is in the DB. False if not.
function UserIsSubscribed()
{
    global $DB_result, $DB_LinkID, $Responder_ID, $Email_Address;

    $Result_Var = FALSE;

    $query = "SELECT EmailAddress FROM InfResp_subscribers WHERE ResponderID = '$Responder_ID'";

    $DB_result = mysql_query($query, $DB_LinkID)
    or die("Invalid query: " . mysql_error());

    while ($row = mysql_fetch_object($DB_result)) {
        $Temp_Var = strtolower($row->EmailAddress);
        $Email_Address = strtolower($Email_Address);
        if ($Temp_Var == $Email_Address) {
            $Result_Var = TRUE;
        }
    }

    return $Result_Var;
}

# ---------------------------------------------------------

function IsInArray($haystack_array, $needle)
{
    $needle = trim(strtolower($needle));
    foreach ($haystack_array as $key => $blah_value) {
        $temp_value = trim(strtolower($blah_value));
        if ($needle == $temp_value) {
            return TRUE;
        }
    }

    return FALSE;
}

function IsInList($list, $ItemCheckedFor)
{
    $list = strtolower(trim((trim($list)), ","));
    $List_Array = explode(',', $list);
    $Max_Index = sizeof($List_Array);
    $ItemCheckedFor = strtolower(trim(trim($ItemCheckedFor), ","));

    # Checking for null and whitespace lists. Wierd PHP bug-type thing.
    if (trim($list) == NULL) {
        $Max_Index = 0;
    }
    if ($list == "") {
        $Max_Index = 0;
    }

    $ResultVar = FALSE;

    for ($i = 0; $i <= $Max_Index - 1; $i++) {
        $List_Element = trim(trim($List_Array[$i]), ",");
        if ($List_Element == $ItemCheckedFor) {
            $ResultVar = TRUE;
        }
    }

    return $ResultVar;
}

function RemoveFromList($list, $ItemToRemove)
{
    $ItemToRemove = strtolower(trim(trim($ItemToRemove), ","));
    $list = strtolower(trim((trim($list)), ","));
    $List_Array = explode(',', $list);
    $Max_Index = sizeof($List_Array);

    # Checking for null and whitespace lists. Wierd PHP bug-type thing.
    if (trim($list) == NULL) {
        $Max_Index = 0;
    }
    if ($list == "") {
        $Max_Index = 0;
    }

    $ResultVar = "";

    for ($i = 0; $i <= $Max_Index - 1; $i++) {
        $List_Element = trim($List_Array[$i]);
        if ($List_Element != $ItemToRemove) {
            $ResultVar .= ",$List_Element";
        }
    }

    $ResultVar = trim(trim($ResultVar), ",");
    return $ResultVar;
}

# ---------------------------------------------------------

function ProcessMessageTags()
{
    global $Send_Subject, $DB_Real_TimeJoined;
    global $DB_EmailAddress, $DB_LastActivity, $DB_FirstName;
    global $DB_LastName, $DB_ResponderName, $DB_OwnerEmail;
    global $DB_OwnerName, $DB_ReplyToEmail, $DB_ResponderDesc;
    global $DB_MsgBodyHTML, $DB_MsgBodyText, $DB_MsgSub;
    global $UnsubURL, $siteURL, $ResponderDirectory, $DB_SubscriberID;
    global $DB_IPaddy, $DB_ReferralSource, $DB_OptInRedir, $DB_UniqueCode;
    global $DB_OptOutRedir, $DB_OptInDisplay, $DB_OptOutDisplay;
    global $DB_LinkID, $cop, $newline;

    # Wednesday May 9, 2007
    # $date_format = 'l \t\h\e jS \of F\, Y';
    $date_format = 'F j\, Y';

    $Joined_Month = date("F", $DB_Real_TimeJoined);
    $Joined_MonthNum = date("n", $DB_Real_TimeJoined);
    $Joined_Year = date("Y", $DB_Real_TimeJoined);
    $Joined_Day = date("d", $DB_Real_TimeJoined);

    $LastActive_Month = date("F", $DB_LastActivity);
    $LastActive_MonthNum = date("n", $DB_LastActivity);
    $LastActive_Year = date("Y", $DB_LastActivity);
    $LastActive_Day = date("d", $DB_LastActivity);

    $Pattern = '/%msg_subject%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_MsgSub, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_MsgSub, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_MsgSub, $Send_Subject);

    $UnsubMSG_HTML = "$newline<br><br>------------------------------------------------<br>$newline";
    $UnsubMSG_HTML .= "<A HREF=\"$UnsubURL\">Unsubscribe link</A><br>$newline";
    if ($cop != TRUE) {
        $UnsubMSG_HTML .= "This responder is Powered By Infinite Responder! <A HREF=\"http://infinite.ibasics.biz\">http://infinite.ibasics.biz</A><br>$newline";
    }

    $UnsubMSG_Text = "$newline------------------------------------------------$newline";
    $UnsubMSG_Text .= "Unsubscribe: $UnsubURL $newline";
    if ($cop != TRUE) {
        $UnsubMSG_Text .= "This responder is Powered By Infinite Responder! http://infinite.ibasics.biz $newline";
    }

    $Unsub_Pattern = '/%unsub_msg%/i';
    $DB_MsgBodyHTML = preg_replace($Unsub_Pattern, $UnsubMSG_HTML, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Unsub_Pattern, $UnsubMSG_Text, $DB_MsgBodyText);

    $Pattern = '/%RespDir%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $ResponderDirectory, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $ResponderDirectory, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $ResponderDirectory, $Send_Subject);

    $Pattern = '/%SiteURL%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, "<A HREF=\"$siteURL\">$siteURL</A>", $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $siteURL, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $siteURL, $Send_Subject);

    $Pattern = '/%subr_id%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_SubscriberID, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_SubscriberID, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_SubscriberID, $Send_Subject);

    $Pattern = '/%subr_emailaddy%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_EmailAddress, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_EmailAddress, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_EmailAddress, $Send_Subject);

    $Pattern = '/%subr_firstname%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_FirstName, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_FirstName, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_FirstName, $Send_Subject);

    $Pattern = '/%subr_lastname%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_LastName, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_LastName, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_LastName, $Send_Subject);

    $Pattern = '/%subr_firstname_fix%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, my_ucwords($DB_FirstName), $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, my_ucwords($DB_FirstName), $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, my_ucwords($DB_FirstName), $Send_Subject);

    $Pattern = '/%subr_lastname_fix%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, my_ucwords($DB_LastName), $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, my_ucwords($DB_LastName), $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, my_ucwords($DB_LastName), $Send_Subject);

    $Pattern = '/%subr_ipaddy%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_IPaddy, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_IPaddy, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_IPaddy, $Send_Subject);

    $Pattern = '/%subr_referralsource%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_ReferralSource, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_ReferralSource, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_ReferralSource, $Send_Subject);

    $Pattern = '/%resp_ownername%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_OwnerName, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_OwnerName, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_OwnerName, $Send_Subject);

    $Pattern = '/%resp_owneremail%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_OwnerEmail, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_OwnerEmail, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_OwnerEmail, $Send_Subject);

    $Pattern = '/%resp_replyto%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_ReplyToEmail, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_ReplyToEmail, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_ReplyToEmail, $Send_Subject);

    $Pattern = '/%resp_name%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_ResponderName, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_ResponderName, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_ResponderName, $Send_Subject);

    $Pattern = '/%resp_desc%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_ResponderDesc, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_ResponderDesc, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_ResponderDesc, $Send_Subject);

    $Pattern = '/%resp_optinredir%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_OptInRedir, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_OptInRedir, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_OptInRedir, $Send_Subject);

    $Pattern = '/%resp_optoutredir%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_OptOutRedir, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_OptOutRedir, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_OptOutRedir, $Send_Subject);

    $Pattern = '/%resp_optindisplay%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_OptInDisplay, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_OptInDisplay, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_OptInDisplay, $Send_Subject);

    $Pattern = '/%resp_optoutdisplay%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_OptOutDisplay, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_OptOutDisplay, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_OptOutDisplay, $Send_Subject);

    $Pattern = '/%Subr_UniqueCode%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $DB_UniqueCode, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $DB_UniqueCode, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $DB_UniqueCode, $Send_Subject);

    $Pattern = '/%Subr_JoinedMonthNum%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $Joined_MonthNum, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $Joined_MonthNum, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $Joined_MonthNum, $Send_Subject);

    $Pattern = '/%Subr_JoinedMonth%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $Joined_Month, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $Joined_Month, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $Joined_Month, $Send_Subject);

    $Pattern = '/%Subr_JoinedYear%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $Joined_Year, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $Joined_Year, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $Joined_Year, $Send_Subject);

    $Pattern = '/%Subr_JoinedDay%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $Joined_Day, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $Joined_Day, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $Joined_Day, $Send_Subject);

    $Pattern = '/%Subr_LastActiveMonthNum%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $LastActive_MonthNum, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $LastActive_MonthNum, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $LastActive_MonthNum, $Send_Subject);

    $Pattern = '/%Subr_LastActiveMonth%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $LastActive_Month, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $LastActive_Month, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $LastActive_Month, $Send_Subject);

    $Pattern = '/%Subr_LastActiveYear%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $LastActive_Year, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $LastActive_Year, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $LastActive_Year, $Send_Subject);

    $Pattern = '/%Subr_LastActiveDay%/i';
    $DB_MsgBodyHTML = preg_replace($Pattern, $LastActive_Day, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $LastActive_Day, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $LastActive_Day, $Send_Subject);

    $Pattern = '/%date_today%/i';
    $the_date = date($date_format, strtotime("today"));
    $DB_MsgBodyHTML = preg_replace($Pattern, $the_date, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $the_date, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $the_date, $Send_Subject);

    $Pattern = '/%date_yesterday%/i';
    $the_date = date($date_format, strtotime("yesterday"));
    $DB_MsgBodyHTML = preg_replace($Pattern, $the_date, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $the_date, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $the_date, $Send_Subject);

    $Pattern = '/%date_tomorrow%/i';
    $the_date = date($date_format, strtotime("tomorrow"));
    $DB_MsgBodyHTML = preg_replace($Pattern, $the_date, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $the_date, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $the_date, $Send_Subject);

    $Pattern = '/%next_monday%/i';
    $the_date = date($date_format, strtotime("next monday"));
    $DB_MsgBodyHTML = preg_replace($Pattern, $the_date, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $the_date, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $the_date, $Send_Subject);

    $Pattern = '/%next_tuesday%/i';
    $the_date = date($date_format, strtotime("next tuesday"));
    $DB_MsgBodyHTML = preg_replace($Pattern, $the_date, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $the_date, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $the_date, $Send_Subject);

    $Pattern = '/%next_wednesday%/i';
    $the_date = date($date_format, strtotime("next wednesday"));
    $DB_MsgBodyHTML = preg_replace($Pattern, $the_date, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $the_date, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $the_date, $Send_Subject);

    $Pattern = '/%next_thursday%/i';
    $the_date = date($date_format, strtotime("next thursday"));
    $DB_MsgBodyHTML = preg_replace($Pattern, $the_date, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $the_date, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $the_date, $Send_Subject);

    $Pattern = '/%next_friday%/i';
    $the_date = date($date_format, strtotime("next friday"));
    $DB_MsgBodyHTML = preg_replace($Pattern, $the_date, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $the_date, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $the_date, $Send_Subject);

    $Pattern = '/%next_saturday%/i';
    $the_date = date($date_format, strtotime("next saturday"));
    $DB_MsgBodyHTML = preg_replace($Pattern, $the_date, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $the_date, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $the_date, $Send_Subject);

    $Pattern = '/%next_sunday%/i';
    $the_date = date($date_format, strtotime("next sunday"));
    $DB_MsgBodyHTML = preg_replace($Pattern, $the_date, $DB_MsgBodyHTML);
    $DB_MsgBodyText = preg_replace($Pattern, $the_date, $DB_MsgBodyText);
    $Send_Subject = preg_replace($Pattern, $the_date, $Send_Subject);

    # -------------------------
    # Custom fields
    $query = "SELECT * FROM InfResp_customfields WHERE user_attached = '$DB_SubscriberID' LIMIT 1";
    $result = mysql_query($query, $DB_LinkID) or die("Invalid query: " . mysql_error());
    if (mysql_num_rows($result) > 0) {
        $data = mysql_fetch_assoc($result);
        foreach ($data as $name => $value) {
            $Pattern = "/%cf_$name%/i";
            $DB_MsgBodyHTML = preg_replace($Pattern, $data[$name], $DB_MsgBodyHTML);
            $DB_MsgBodyText = preg_replace($Pattern, $data[$name], $DB_MsgBodyText);
            $Send_Subject = preg_replace($Pattern, $data[$name], $Send_Subject);
        }
    }

    # -------------------------
    return;
    # -------------------------
}

# ---------------------------------------------------------

function SendMessageTemplate($filename = "", $to_address = "", $from_address = "")
{
    global $Send_Subject, $DB_EmailAddress, $DB_OwnerName, $DB_ReplyToEmail, $DB_MsgBodyHTML, $DB_MsgBodyText;
    global $UnsubURL, $siteURL, $ResponderDirectory, $DB_SubscriberID, $sub_conf_link, $unsub_link, $unsub_conf_link;
    global $charset, $DB_UniqueCode, $DB_LinkID, $cop, $newline, $CanReceiveHTML;

    if ($filename == "") {
        die("Message template error!<br>\n");
    }
    $file_contents = GrabFile($filename);
    if ($file_contents == FALSE) {
        die("Template $filename not found!<br>\n");
    }

    # Generate codes and links
    $cop = checkit();
    $subcode = "s" . $DB_UniqueCode;
    $unsubcode = "u" . $DB_UniqueCode;
    $sub_conf_link = $siteURL . $ResponderDirectory . "/s.php?c=$subcode";
    $unsub_conf_link = $siteURL . $ResponderDirectory . "/s.php?c=$unsubcode";
    $unsub_link = $siteURL . $ResponderDirectory . "/s.php?c=$unsubcode";
    $UnsubURL = $unsub_link;

    # Seperate the subject
    preg_match("/<SUBJ>(.*?)<\/SUBJ>/ims", $file_contents, $matches);
    $Send_Subject = $matches[1];

    # Seperate the message
    preg_match("/<MSG>(.*?)<\/MSG>/ims", $file_contents, $matches);
    $DB_MsgBodyText = trim($matches[1]);

    # Generate the HTML message
    $DB_MsgBodyHTML = nl2br($DB_MsgBodyText);

    # Replace unsub and sub/unsub conf links
    $DB_MsgBodyText = preg_replace('/%sub_conf_url%/i', $sub_conf_link, $DB_MsgBodyText);
    $DB_MsgBodyText = preg_replace('/%unsub_conf_url%/i', $unsub_conf_link, $DB_MsgBodyText);
    $DB_MsgBodyText = preg_replace('/%unsub_url%/i', $unsub_link, $DB_MsgBodyText);
    $DB_MsgBodyHTML = preg_replace('/%sub_conf_url%/i', "<A HREF=\"$sub_conf_link\">$sub_conf_link</A>", $DB_MsgBodyHTML);
    $DB_MsgBodyHTML = preg_replace('/%unsub_conf_url%/i', "<A HREF=\"$unsub_conf_link\">$unsub_conf_link</A>", $DB_MsgBodyHTML);
    $DB_MsgBodyHTML = preg_replace('/%unsub_url%/i', "<A HREF=\"$unsub_link\">$unsub_link</A>", $DB_MsgBodyHTML);

    # Process tags
    ProcessMessageTags();

    # Set another from
    if (!(isEmpty($from_address))) {
        $DB_ReplyToEmail = $from_address;
    }

    # Set another to
    if (!(isEmpty($to_address))) {
        $DB_EmailAddress = $to_address;
    }

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
    $Send_Subject = stripnl(str_replace("|", "", $Send_Subject));
    $Message_Body = str_replace("|", "", $Message_Body);
    $Message_Headers = str_replace("|", "", $Message_Headers);
    $Message_Body = utf8_decode($Message_Body);

    # Send the mail
    mail($DB_EmailAddress, $Send_Subject, $Message_Body, $Message_Headers, "-f $DB_ReplyToEmail");

    # Update the activity row
    $Set_LastActivity = time();
    $query = "UPDATE InfResp_subscribers SET LastActivity = '$Set_LastActivity' WHERE SubscriberID = '$DB_SubscriberID'";
    $DB_result = mysql_query($query, $DB_LinkID) or die("Invalid query: " . mysql_error());

    # Head on back
    return;
}

# ---------------------------------------------------------

function ResponderPulldown($field)
{
    global $DB_LinkID;
    $menu_query = "SELECT * FROM InfResp_responders ORDER BY ResponderID";
    $menu_result = mysql_query($menu_query, $DB_LinkID) or die("Invalid query: " . mysql_error());

    print "<select name=\"$field\" class=\"fields\">\n";
    while ($menu_row = mysql_fetch_assoc($menu_result)) {
        $DB_ResponderID = $menu_row['ResponderID'];
        $DB_ResponderName = $menu_row['Name'];

        $PullDown_String = string_cut($DB_ResponderName, 3);
        print "<option value=\"$DB_ResponderID\">$PullDown_String</option>\n";
    }
    print "</select>\n";
}

function Add_To_Logs($Activity, $Activity_Parm, $ID_Parm, $Extra_Parm)
{
    global $DB_LinkID;

    $TimeStampy = time();

    $Log_Query = "INSERT INTO InfResp_Logs (TimeStamp, Activity, Activity_Parameter, ID_Parameter, Extra_Parameter)
                  VALUES('$TimeStampy', '$Activity', '$Activity_Parm', '$ID_Parm', '$Extra_Parm')";
    $Log_result = mysql_query($Log_Query, $DB_LinkID)
    or die("Invalid query: " . mysql_error());

    return $Log_result;
}

function GetFieldNames($table)
{
    global $DB_LinkID;

    $query = "SELECT * FROM $table";
    $result = mysql_query($query, $DB_LinkID)
    or die("Invalid query: " . mysql_error());
    $i = 0;
    $FieldNameStr = "";
    while ($i < mysql_num_fields($result)) {
        $meta = mysql_fetch_field($result, $i);
        if ($meta) {
            $FieldNameStr = $FieldNameStr . trim($meta->name) . ",";
        }

        $i++;
    }
    $FieldNameStr = trim((trim($FieldNameStr)), ",");
    $FieldNameArray = explode(',', $FieldNameStr);
    return $FieldNameArray;
}

# ---------------------------------------------------------

function GrabFile($filename = FALSE)
{
    if (!($filename)) {
        return FALSE;
    }

    if (file_exists($filename)) {
        if ($fhandle = fopen($filename, "r")) {
            $contents = fread($fhandle, filesize($filename));
            fclose($fhandle);
            return $contents;
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

# ---------------------------------------------------------

function isInBlacklist($address = "")
{
    global $DB_LinkID;
    if ($address == "") {
        return FALSE;
    }
    $address = trim(strtolower($address));
    $query = "SELECT * FROM InfResp_blacklist WHERE LOWER(EmailAddress) = '$address'";
    $DB_result = mysql_query($query, $DB_LinkID) or die("Invalid query: " . mysql_error());
    if (mysql_num_rows($DB_result) > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function isEmail($address = "")
{
    if ($address == "") {
        return FALSE;
    }

    if (preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z0-9.-]+$/i", $address)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

# ---------------------------------------------------------

function generate_unique_code()
{
    global $DB_LinkID;

    # Generate a unique ID
    $not_unique = TRUE;
    while ($not_unique) {
        $id_str = substr(md5(str_makerand(15, 15, TRUE, FALSE, TRUE)), 0, 15);
        $query = "SELECT UniqueCode FROM InfResp_subscribers WHERE UniqueCode = '$id_str'";
        $result = mysql_query($query, $DB_LinkID) or die("Invalid query: " . mysql_error());
        if (mysql_num_rows($result) == 0) {
            $not_unique = (!($not_unique));
        }
    }

    # Return the ID
    return $id_str;
}

function generate_random_block()
{
    $block1 = substr(md5(str_makerand(30, 30, TRUE, FALSE, TRUE)), 0, 30);
    $block2 = substr(md5(str_makerand(30, 30, TRUE, FALSE, TRUE)), 0, 30);
    $block = md5(WebEncrypt($block1, $block2));
    return $block;
}

# ---------------------------------------------------------

function copyright($check = FALSE)
{
    global $siteURL, $ResponderDirectory, $config;
    # The GPL requires that credit be given to the people that write
    # software. In order to help keep this package alive we need people
    # to be able to find it, so in order to keep the package alive, and
    # to keep to the spirit of the GPL, I ask that you leave this link
    # credit. Removing it may effect your abiity to get support, and it's
    # really not all that much to ask for free software.
    #
    # If you need a private license you can purchase one thru our site.
    # Follow the instructions and you'll be sent a site code that will
    # turn off the banner. This helps us to continue further development.
    #
    $tempy = preg_replace("/www\./", "", $siteURL);
    $tempy = preg_replace("/http:\/\//", "", $tempy);
    if ($check == TRUE) {
        if ((trim($config['site_code'])) == md5(x_Encrypt(md5(str_rot13(base64_encode($tempy))), $tempy))) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    if ((trim($config['site_code'])) != md5(x_Encrypt(md5(str_rot13(base64_encode($tempy))), $tempy))) {
        print "<br><br><center>\n";
        print "<A HREF=\"http://infinite.ibasics.biz\"><img src=\"$siteURL$ResponderDirectory/images/powered-by.gif\" alt=\"Powered by Infinite Responder!\" height=\"50\" width=\"100\" border=\"0\"></A><br>\n";
        print "<br></center> \n";
    }
    return;
}

function checkit()
{
    global $cop;
    $cop = copyright(TRUE);
    return $cop;
}

# ---------------------------------------------------------

function admin_redirect()
{
    global $siteURL, $ResponderDirectory;
    $redir_URL = $siteURL . $ResponderDirectory . '/admin.php';
    header("Location: $redir_URL");
    print "<br>\n";
    print "You need to log in first!<br>\n";
    print "<br>\n";
    print "If your browser doesn't support redirects then you'll need to <A HREF=\"$redir_URL\">click here.</A><br>\n";
    print "<br>\n";
    die();
}

# ---------------------------------------------------------
?>
