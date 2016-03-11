<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

include_once('common.php');
requireUserToBeLoggedIn();

# Top template
include('templates/open.page.php');

# Cpanel top
$help_section = "tools";
include('templates/controlpanel.php');

# Handle actions
if ($_REQUEST['action'] == "run_sendmails") {
    $sendmails_included = TRUE;
    include('sendmails.php');
    print "<p class=\"big_header\">Sendmails Done!</p>\n";
} elseif ($_REQUEST['action'] == "run_mailchecker") {
    $included = TRUE;
    include('mailchecker.php');
    print "<p class=\"big_header\">Mail Checker Done!</p>\n";
} elseif ($_REQUEST['action'] == "run_bouncechecker") {
    $included = TRUE;
    include('bouncechecker.php');
    print "<p class=\"big_header\">Bounce Checker Done!</p>\n";
} else {
    print "<p class=\"big_header\">Tools</p>\n";
}

# Display config template
include('templates/main.tools.php');

# Display back to admin button
include('templates/back_button.tools.php');

# Display the bottom template
copyright();
include('templates/close.page.php');
