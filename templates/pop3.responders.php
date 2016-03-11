<?php
if ($DB_Mail_Type == "imap") {
    $sel_imap = " SELECTED";
    $sel_pop3 = "";
    $sel_nntp = "";
} elseif ($DB_Mail_Type == "nntp") {
    $sel_imap = "";
    $sel_pop3 = "";
    $sel_nntp = " SELECTED";
} else {
    $sel_imap = "";
    $sel_pop3 = " SELECTED";
    $sel_nntp = "";
}
?>

<br>
<center>
    <FORM action="responders.php" method=POST>
        <table width="750" bgcolor="#660000" style="border: 1px solid #000000;">
            <tr>
                <td>
                    <p class="white_header">-- POP3 Settings --</p>
                </td>
            </tr>
        </table>
        <table border="0" width="750" bgcolor="#CCCCCC" style="border: 1px solid #000000;">
            <tr>
                <td width="150"><strong>POP3 ID:</strong></td>
                <td width="150"><?php echo $DB_POP_ConfID; ?></td>
                <td width="90">&nbsp;</td>
                <td width="150"><strong>Responder ID:</strong></td>
                <td width="150"><?php echo $DB_Attached_Responder; ?></td>
            </tr>
            <tr>
                <td><strong>Enabled:</strong></td>
                <td>
                    <?php
                    if ($DB_Pop_Enabled == 1) {
                        print "    <input type=\"RADIO\" name=\"pop3_enabled\" value=\"1\" checked>Yes \n";
                        print "    <input type=\"RADIO\" name=\"pop3_enabled\" value=\"0\">No\n";
                    } else {
                        print "    <input type=\"RADIO\" name=\"pop3_enabled\" value=\"1\">Yes \n";
                        print "    <input type=\"RADIO\" name=\"pop3_enabled\" value=\"0\" checked>No\n";
                    }
                    ?>
                </td>
                <td>&nbsp;</td>
                <td><strong>Use HTML:</strong></td>
                <td>
                    <?php
                    if ($DB_HTML_YN == 1) {
                        print "    <input type=\"RADIO\" name=\"h\" value=\"1\" checked>Yes \n";
                        print "    <input type=\"RADIO\" name=\"h\" value=\"0\">No\n";
                    } else {
                        print "    <input type=\"RADIO\" name=\"h\" value=\"1\">Yes \n";
                        print "    <input type=\"RADIO\" name=\"h\" value=\"0\" checked>No\n";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Double Opt-In:</strong></td>
                <td>
                    <?php
                    if ($DB_Confirm_Join == 1) {
                        print "    <input type=\"RADIO\" name=\"pop3_confirmjoin\" value=\"1\" checked>Yes \n";
                        print "    <input type=\"RADIO\" name=\"pop3_confirmjoin\" value=\"0\">No\n";
                    } else {
                        print "    <input type=\"RADIO\" name=\"pop3_confirmjoin\" value=\"1\">Yes \n";
                        print "    <input type=\"RADIO\" name=\"pop3_confirmjoin\" value=\"0\" checked>No\n";
                    }
                    ?>
                </td>
                <td>&nbsp;</td>
                <td><strong>Clear Emails:</strong></td>
                <td>
                    <?php
                    if ($DB_DeleteYN == 1) {
                        print "    <input type=\"RADIO\" name=\"pop3_deletemsgs\" value=\"1\" checked>Yes \n";
                        print "    <input type=\"RADIO\" name=\"pop3_deletemsgs\" value=\"0\">No\n";
                    } else {
                        print "    <input type=\"RADIO\" name=\"pop3_deletemsgs\" value=\"1\">Yes \n";
                        print "    <input type=\"RADIO\" name=\"pop3_deletemsgs\" value=\"0\" checked>No\n";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Attach middle initial to first name?</strong></td>
                <td colspan="3">
                    <?php
                    if ($DB_ConcatMid == 1) {
                        print "    <input type=\"RADIO\" name=\"pop3_cmid\" value=\"1\" checked>Yes \n";
                        print "    <input type=\"RADIO\" name=\"pop3_cmid\" value=\"0\">No\n";
                    } else {
                        print "    <input type=\"RADIO\" name=\"pop3_cmid\" value=\"1\">Yes \n";
                        print "    <input type=\"RADIO\" name=\"pop3_cmid\" value=\"0\" checked>No\n";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Host:</strong></td>
                <td colspan="2"><input name=pop3_host size=29 maxlength=95 value="<?php echo $DB_POP3_host; ?>"
                                       class="fields"></td>
                <td><strong>Port #:</strong></td>
                <td><input name=pop3_port size=15 maxlength=15 value="<?php echo $DB_POP3_port; ?>" class="fields"></td>
            </tr>
            <tr>
                <td><strong>Spam Header:</strong></td>
                <td colspan="2"><input name=pop3_spam size=29 maxlength=95 value="<?php echo $DB_SpamHeader; ?>"
                                       class="fields"></td>
                <td><strong>Type:</strong></td>
                <td>
                    <select name="pop3_type" class="fields">
                        <option<?php echo $sel_imap; ?> value="imap">imap</option>
                        <option<?php echo $sel_pop3; ?> value="pop3">pop3</option>
                        <option<?php echo $sel_nntp; ?> value="nntp">nntp</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Mailbox name:</strong></td>
                <td colspan="4"><input name=pop3_box size=29 maxlength=95 value="<?php echo $DB_POP3_mailbox; ?>"
                                       class="fields"></td>
            </tr>
            <tr>
                <td colspan="5">
                    <hr style="border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;">
                </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Username:</strong> &nbsp; <input name=pop3_user size=27 maxlength=95
                                                                         value="<?php echo $DB_POP3_username; ?>"
                                                                         class="fields"></td>
                <td>&nbsp;</td>
                <td colspan="2"><strong>Password:</strong> &nbsp; <input name=pop3_pw size=27 maxlength=95
                                                                         value="<?php echo $DB_POP3_password; ?>"
                                                                         class="fields"></td>
            </tr>
            <tr>
                <td colspan="5">
                    <table cellpadding="0" cellspacing="0" border="0" align="right">
                        <tr>
                            <td>
                                <input type="hidden" name="pop3_ID" value="<?php echo $DB_POP_ConfID; ?>">
                                <input type="hidden" name="r_ID" value="<?php echo $DB_Attached_Responder; ?>">
                                <input type="hidden" name="action" value="do_POP3">
                                <input type="submit" name="Save" value="Save" alt="Save" class="save_b">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
    </FORM>
