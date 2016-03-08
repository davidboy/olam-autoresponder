<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

require_once('subscriptions_common.php');

# Pull info
if (!(responderExists($Responder_ID))) {
    redirectTo('/../admin.php');
}
getResponderInfo();
if ((getSubscriberInfo($Subscriber_ID)) == FALSE) {
    redirectTo('/../admin.php');
}

# Open template
if ($SilentMode != 1) {
    include('templates/open.page.php');
}

# Handle the action
if ($action == "resend_sub_conf") {
    sendMessageTemplate('templates/subscribe.confirm.txt');
    if ($SilentMode != 1) {
        print "<br />Subscription confirmation message sent!<br />\n";
    }
} elseif ($action == "resend_unsub_conf") {
    sendMessageTemplate('templates/unsubscribe.confirm.txt');
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