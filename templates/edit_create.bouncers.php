<?php
if ($data['Enabled'] == 1) {
    $enb_1 = " CHECKED";
    $enb_2 = "";
} else {
    $enb_1 = "";
    $enb_2 = " CHECKED";
}
if ($data['mailtype'] == "imap") {
    $sel_imap = " SELECTED";
    $sel_pop3 = "";
    $sel_nntp = "";
} elseif ($data['mailtype'] == "nntp") {
    $sel_imap = "";
    $sel_pop3 = "";
    $sel_nntp = " SELECTED";
} else {
    $sel_imap = "";
    $sel_pop3 = " SELECTED";
    $sel_nntp = "";
}

$delvl = $data['DeleteLevel'];
$del[0] = "";
$del[1] = "";
$del[2] = "";
$del[$delvl] = " SELECTED";

$notlvl = $data['NotifyOwner'];
$not[0] = "";
$not[1] = "";
$not[$notlvl] = " SELECTED";
?>

<br/>
<center>
    <table border="0" cellspacing="2" cellpadding="0" width="750" bgcolor="#3366cc" style="border: 1px solid #000000;">
        <tr>
            <td>
                <p class="white_header">
                    <?php echo $heading; ?>
                </p>
            </td>
        </tr>
    </table>
    <FORM action="bouncers.php" method=POST>
        <table border="0" cellspacing="2" cellpadding="0" width="750" bgcolor="#CCCCCC"
               style="border: 1px solid #000000;">
            <tr>
                <td colspan="4"><br/></td>
            </tr>
            <tr>
                <td width="175">
                    <strong>Assigned Email Addy:</strong>
                </td>
                <td width="200">
                    <input name="EmailAddy" size=20 maxlength=250 value="<?php echo $data['EmailAddy']; ?>"
                           class="fields">
                </td>
                <td width="155">
                    <strong>Enabled:</strong>
                </td>
                <td width="200">
                    <input type="RADIO" name="Enabled" value="1"<?php echo $enb_1; ?>>Yes &nbsp;
                    <input type="RADIO" name="Enabled" value="0"<?php echo $enb_2; ?>>No
                </td>
            </tr>
            <tr>
                <td width="175">
                    <strong>Username:</strong>
                </td>
                <td width="200">
                    <input name="username" size=20 maxlength=250 value="<?php echo $data['username']; ?>"
                           class="fields">
                </td>
                <td width="155">
                    <strong>Password:</strong>
                </td>
                <td width="200">
                    <input name="password" size=20 maxlength=250 value="<?php echo $data['password']; ?>"
                           class="fields">
                </td>
            </tr>
            <tr>
                <td width="175">
                    <strong>Host:</strong>
                </td>
                <td width="200">
                    <input name="host" size=20 maxlength=250 value="<?php echo $data['host']; ?>" class="fields">
                </td>
                <td width="155">
                    <strong>Port:</strong>
                </td>
                <td width="200">
                    <input name="port" size=20 maxlength=250 value="<?php echo $data['port']; ?>" class="fields">
                </td>
            </tr>
            <tr>
                <td width="175">
                    <strong>Mailbox:</strong>
                </td>
                <td width="200">
                    <input name="mailbox" size=20 maxlength=250 value="<?php echo $data['mailbox']; ?>" class="fields">
                </td>
                <td width="155">
                    <strong>Type:</strong>
                </td>
                <td width="200">
                    <select name="mailtype" class="fields">
                        <option<?php echo $sel_imap; ?> value="imap">imap</option>
                        <option<?php echo $sel_pop3; ?> value="pop3">pop3</option>
                        <option<?php echo $sel_nntp; ?> value="nntp">nntp</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="175">
                    <strong>Delete Messages:</strong>
                </td>
                <td width="200">
                    <select name="DeleteLevel" class="fields">
                        <option<?php echo $del[0]; ?> value="0">Never Clear</option>
                        <option<?php echo $del[1]; ?> value="1">Clear Bounces</option>
                        <option<?php echo $del[2]; ?> value="2">Clear All</option>
                    </select>
                </td>
                <td width="155">
                    <strong>Notify On Removals:</strong>
                </td>
                <td width="200">
                    <select name="NotifyOwner" class="fields">
                        <option<?php echo $not[0]; ?> value="0">Silently Bounce</option>
                        <option<?php echo $not[1]; ?> value="1">Notify Me</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td width="155">
                    <strong>Spam Header:</strong>
                </td>
                <td width="200">
                    <input name="SpamHeader" size=20 maxlength=250 value="<?php echo $data['SpamHeader']; ?>"
                           class="fields">
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table cellpadding="0" cellspacing="0" border="0" align="right">
                        <tr>
                            <td>
                                <input type="hidden" name="b_ID" value="<?php echo $bouncer_id; ?>">
                                <input type="hidden" name="action" value="<?php echo $submit_action; ?>">
                                <input type="submit" name="Save" value="Save" class="save_b">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </FORM>
</center>
