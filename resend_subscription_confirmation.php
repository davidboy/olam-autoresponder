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