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

# this is a dummy page to maintain functionality with subscription forms designed for the old tool, Infinite Responder
# as well as confirm links and unsubscribe links sent out before migration from Infinite Responder
# it simply redirects to the appropriate pages in Olam AutoResponder

require_once('subscriptions_common.php');

$url_query_string = $_SERVER['QUERY_STRING'];

if (!isEmpty($url_query_string))
{
	echo $url_query_string;
}

# Is there a confirm string? If so, redirect to confirm_subscription.php
if (!isEmpty($Confirm_String)) 
{
	header("Location: confirm_subscription.php?" . $url_query_string);
	die;
}
# Is there an email address and responder ID? If so, redirect to subscribe.php
elseif (!isEmpty($Email_Address) && !isEmpty($Responder_ID)) 
{
	header("Location: subscribe.php?" . $url_query_string);
	die;
}
else  # don't know what this is... 
{
	echo " Sorry, your request could not be processed.";
	die;
}