<?php
if ($DB_Confirmed == "1") {
    $conf = "Yes";
    $conf_chk = "CHECKED";
} else {
    $conf = "No";
    $conf_chk = "";
}
?>
<br>
<center>
    <FORM action="admin.php" method=POST>
        <table border="0" width="650" cellspacing="5" cellpadding="0" bgcolor="#3366cc"
               style="border: 1px solid #000000;">
            <tr>
                <td>
                    <center><font color="#FFFFFF" style="font-size: 200%">-- Edit Subscriber --</font></center>
                </td>
            </tr>
        </table>
        <table border="0" width="650" cellspacing="5" cellpadding="0" bgcolor="#CCCCCC"
               style="border: 1px solid #000000;">
            <tr>
                <td width="150"><strong>Subscriber ID:</strong></td>
                <td width="500"><?php echo $Subscriber_ID; ?></td>
            </tr>
            <tr>
                <td width="150"><strong>Subscribed To:</strong></td>
                <td width="500"><u><?php echo $DB_ResponderName; ?></u></td>
            </tr>
            <tr>
                <td width="150"><strong>First Name:</strong></td>
                <td width="500"><input name="firstname" size=50 maxlength=100 value="<?php echo $DB_FirstName; ?>"
                                       class="fields"></td>
            </tr>
            <tr>
                <td width="150"><strong>Last Name:</strong></td>
                <td width="500"><input name="lastname" size=50 maxlength=100 value="<?php echo $DB_LastName; ?>"
                                       class="fields"></td>
            </tr>
            <tr>
                <td width="150"><strong>Email Addy:</strong></td>
                <td width="500"><input name="email_addy" size=50 maxlength=100 value="<?php echo $DB_EmailAddress; ?>"
                                       class="fields"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr style="border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;">
                </td>
            </tr>
            <tr>
                <td width="150"><strong>Send HTML:</strong></td>
                <td width="500">
                    <?php
                    if ($CanReceiveHTML == 1) {
                        print "    <input type=\"RADIO\" name=\"h\" value=\"1\" checked>Yes &nbsp;\n";
                        print "    <input type=\"RADIO\" name=\"h\" value=\"0\">No<br>\n";
                    } else {
                        print "    <input type=\"RADIO\" name=\"h\" value=\"1\">Yes &nbsp;\n";
                        print "    <input type=\"RADIO\" name=\"h\" value=\"0\" checked>No<br>\n";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td width="150"><strong>IP Addy:</strong></td>
                <td width="500"><?php echo $DB_IPaddy; ?></td>
            </tr>
            <tr>
                <td width="150"><strong>Referral Source:</strong></td>
                <td width="500"><input name="ReferralSource" size=50 maxlength=99
                                       value="<?php echo $DB_ReferralSource; ?>" class="fields"></td>
            </tr>
            <tr>
                <td width="150"><strong>Unique Code:</strong></td>
                <td width="500"><input name="UniqueCode" size=50 maxlength=99 value="<?php echo $DB_UniqueCode; ?>"
                                       class="fields"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr style="border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;">
                </td>
            </tr>
            <tr>
                <td width="150">
                    <strong>Confirmed:</strong>
                    <input type="checkbox" name="Confirmed" value="1"<?php echo $conf_chk; ?>>
                </td>
                <td width="500">
                    <?php
                    if ($DB_Confirmed == "1") {
                        # Resend unsubscribe confirmation
                        $url = $siteURL . $ResponderDirectory . "/resend_subscription_confirmation.php?r_ID=$Responder_ID&sub_ID=$Subscriber_ID";
                        print "<A HREF=\"$url\">Resend Unsub Confirm</A>\n";
                    } else {
                        # Resend subscribe confirmation
                        $url = $siteURL . $ResponderDirectory . "/resend_subscription_confirmation.php?r_ID=$Responder_ID&sub_ID=$Subscriber_ID";
                        print "<A HREF=\"$url\">Resend Sub Confirm</A>\n";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr style="border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;">
                </td>
            </tr>
            <tr>
                <td width="150"><strong>Time Joined:</strong></td>
                <td width="500"><?php echo date("F j, Y, g:i a", $DB_TimeJoined); ?></td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Reset Timestamp</strong> <em>(Resends messages on schedule)</em>:
                    <input type="checkbox" name="Reset_Time" value="yes">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr style="border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;">
                </td>
            </tr>
            <tr>
                <td width="120">
                    <strong>Resend Msg:</strong><br>
                </td>
                <td valign="top" width="300">
                    <select name="msg_to_resend" class="fields">
                        <option value="none">Don't Re-Send Any</option>
                        <option value="all">Re-Send All</option>
                        <?php echo $option_list; ?>
                    </select>
                    <br/><em>(Re-queues message immediately. Superceeded by Reset Timestamp.)</em>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr style="border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table cellpadding="0" cellspacing="0" border="0" align="right">
                        <tr>
                            <td>
                                <input type="hidden" name="sub_ID" value="<?php echo $Subscriber_ID; ?>">
                                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                                <input type="hidden" name="action" value="sub_edit_do">
                                <input type="submit" name="Save" value="Save" class="save_b">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </FORM>
</center>

<br/>
<table>
    <tr>
        <td width="600">&nbsp;</td>
        <td>
            <FORM action="admin.php" method=POST>
                <input type="hidden" name="email_addy" value="<?php echo $DB_EmailAddress; ?>">
                <input type="hidden" name="sub_ID" value="<?php echo $Subscriber_ID; ?>">
                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                <input type="hidden" name="action" value="custom_edit">
                <input type="submit" name="submit" value="Custom Data">
            </FORM>
        </td>
    </tr>
</table>
