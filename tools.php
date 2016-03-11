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
