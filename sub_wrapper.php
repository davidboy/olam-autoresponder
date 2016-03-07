<?php

include('common.php');
/*
   Before using this, make sure you read the entry about it in manual.txt!
  
   Make a copy of it and rename it to something like sub_wrapper1.php or
   my_course.php or something similiar. Then edit the variables below
   to match your settings.
  
   After that's done, simply install the wrapper like this:

   <?php
   $email = 'john@doe.org';
   $html = 0;
   $redir_link = "http://www.yourdomain.com/course/sub_wrapper1.php?e=$email&h=$html";
   header("Location: $redir_link");
   print "<br>\n";
   print "If your browser doesn't support redirects then you'll need to <A HREF=\"$redir_link\">click here.</A><br>\n";
   ?>  

   Where, naturally, yourdomain.com is your domain name, /course is your course and
   /sub_wrapper1.php is the name of this wrapper. Of course you'll also need to
   make sure that you've got PHP set up and that the $email and $html variables
   are passed appropriately from the form they were captured on. Otherwise nothing 
   will be passed to the wrapper. For most users this will make their subscription 
   process perfectly seamless.
*/

  #------------------- ** Configure these variables ** -------------------
  # Remember to include the leading slash in the dir and subfile vars.
  # These are the variables to the s itself, NOT the wrapper. 
  #-----------------------------------------------------------------------
  $resp = "1"
  $mydomain = 'http://www.yourdomain.com'
  $resp_dir = '/course'
  $resp_subfile = '/s.php'
  $silent = 0;    # Set to 1 if you don't want the sub page to return anything.
  #-----------------------------------------------------------------------


  #-----------------------------------------------------------------------
  #                **** Don't edit ANYTHING in this block. ****
  #-----------------------------------------------------------------------
  # These 2 variables do get MakeSafed in the s, but someone
  # could try to insert something nasty to mess with the img load.
  $email = makeSafe($_GET['e']);
  $html  = makeSafe($_GET['h']);

  $options = "?e=$email&r=$resp_ID&a=sub&h=$html&s=$silent";
  $FullLink = $mydomain.$resp_dir.$resp_subfile.$options;

  header("Location: $FullLink");
  print "<br>\n";
  print "Now loading remote subscription page...<br>\n";
  print "<br>\n";
  print "If your browser doesn't support redirects then you'll need to <A HREF=\"$FullLink\">click here.</A><br>\n";
  print "<br>\n";
  #-----------------------------------------------------------------------
?>