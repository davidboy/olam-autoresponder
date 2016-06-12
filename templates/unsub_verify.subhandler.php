<br/>
<form action="confirm_subscription.php" method="get">
<br/>Are you sure you'd like to stop receiving messages at <?php echo $DB_EmailAddress; ?> from '<?php echo $This_Resp_Name; ?>'?<br/><br/>
<input type="submit" value=" Unsubscribe "> &nbsp; &nbsp; &nbsp; &nbsp; <a href="keep_subscription.php">Cancel</a>
<input type="hidden" value="1" name="unsub_verify">
<input type="hidden" value="<?php echo $Confirm_String; ?>" name="c">
</form>
<br/>