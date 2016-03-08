<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

// Common file used by all subscription management pages

require_once('common.php');

# Process inputs
if ($_REQUEST['s'] == "1") {
    $SilentMode = 1;
} else {
    $SilentMode = 0;
}

# Process input
// TODO: a lot of these could be moved to the specific files
$Email_Address = rawurldecode(trim($_REQUEST['e']));
$Email_Address = str_replace(">", "", $Email_Address);
$Email_Address = str_replace("<", "", $Email_Address);
$Email_Address = str_replace("\\", "", $Email_Address);
$Email_Address = str_replace('/', "", $Email_Address);
$Email_Address = str_replace('..', "", $Email_Address);
$Email_Address = str_replace('|', "", $Email_Address);
$Email_Address = stripNewlines(makeSafe($Email_Address));
$Confirm_String = makeSafe($_REQUEST['c']);
$Subscriber_ID = makeSafe($_REQUEST['sub_ID']);
$HandleHTML = makeSafe($_REQUEST['h']);
$ReferralSrc = makeSafe($_REQUEST['ref']);
$IPaddy = $_SERVER['REMOTE_ADDR'];

# Grab the name
if (isEmpty($_REQUEST['n'])) {
    $FirstName = makeSafe($_REQUEST['f']);
    $LastName = makeSafe($_REQUEST['l']);
} else {
    $FullName = makeSafe($_REQUEST['n']);
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
    $action = strtolower(makeSafe($_REQUEST['action']));
} else {
    $action = strtolower(makeSafe($_REQUEST['a']));
}

# Grab responder ID
if (isset($_REQUEST['r'])) {
    $Responder_ID = makeSafe($_REQUEST['r']);
} else {
    $Responder_ID = makeSafe($_REQUEST['r_ID']);
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