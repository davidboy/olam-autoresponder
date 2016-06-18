<?php
if ($data['Enabled'] == 1) {
    $enabled = "Yes";
} else {
    $enabled = "No";
}

if ($data['DeleteLevel'] == "3") {
    $del = "Delete All Messages";
} elseif ($data['DeleteLevel'] == "2") {
    $del = "Delete Bounces Only";
} else {
    $del = "Don't Delete Any";
}

if ($data['NotifyOwner'] == "1") {
    $notify = "Yes";
} else {
    $notify = "No";
}
?>

<br/>
<center>
    <table border="0" cellspacing="2" cellpadding="0" width="750" bgcolor="#3366cc" style="border: 1px solid #000000;">
        <tr>
            <td>
                <p class="white_header">
                    Delete a Bouncer
                </p>
            </td>
        </tr>
    </table>
    <table border="0" cellspacing="2" cellpadding="0" width="750" bgcolor="#CCCCCC" style="border: 1px solid #000000;">
        <tr>
            <td colspan="4"><br/></td>
        </tr>
        <tr>
            <td width="165">
                <strong>Assigned Email Addy:</strong>
            </td>
            <td width="200">
                <?php echo $data['EmailAddy']; ?>
            </td>
            <td width="165">
                <strong>Enabled:</strong>
            </td>
            <td width="200">
                <?php echo $enabled; ?>
            </td>
        </tr>
        <tr>
            <td width="165">
                <strong>Username:</strong>
            </td>
            <td width="200">
                <?php echo $data['username']; ?>
            </td>
            <td width="165">
                <strong>Password:</strong>
            </td>
            <td width="200">
                <?php echo $data['password']; ?>
            </td>
        </tr>
        <tr>
            <td width="165">
                <strong>Host:</strong>
            </td>
            <td width="200">
                <?php echo $data['host']; ?>
            </td>
            <td width="165">
                <strong>Port:</strong>
            </td>
            <td width="200">
                <?php echo $data['port']; ?>
            </td>
        </tr>
        <tr>
            <td width="165">
                <strong>Mailbox:</strong>
            </td>
            <td width="200">
                <?php echo $data['mailbox']; ?>
            </td>
            <td width="165">
                <strong>Type:</strong>
            </td>
            <td width="200">
                <?php echo $data['mailtype']; ?>
            </td>
        </tr>
        <tr>
            <td width="165">
                <strong>Delete:</strong>
            </td>
            <td width="200">
                <?php echo $del; ?>
            </td>
            <td width="165">
                <strong>Notify:</strong>
            </td>
            <td width="200">
                <?php echo $notify; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td width="165">
                <strong>Spam Header:</strong>
            </td>
            <td width="200">
                <?php echo $data['SpamHeader']; ?>
            </td>
        </tr>
        <tr>
            <td colspan="4"><br/></td>
        </tr>
        <tr>
            <td colspan="4">
                <center><strong>Delete Bouncer?</strong></center>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table align="right" cellpadding="10" cellspacing="0">
                    <tr>
                        <td>
                            <FORM action="bouncers.php" method=POST>
                                <input type="hidden" name="action" value="do_delete">
                                <input type="hidden" name="b_ID" value="<?php echo $bouncer_id; ?>">
                                <input type="submit" name="Yes" value="Yes" class="butt">
                            </FORM>
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan="2">
                <table align="left" cellpadding="10" cellspacing="0">
                    <tr>
                        <td>
                            <FORM action="bouncers.php" method=POST>
                                <input type="hidden" name="action" value="list">
                                <input type="hidden" name="b_ID" value="<?php echo $bouncer_id; ?>">
                                <input type="submit" name="No" value="No" class="butt">
                            </FORM>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    </FORM>
</center>
