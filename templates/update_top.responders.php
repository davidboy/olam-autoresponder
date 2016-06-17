<?php
if ($DB_OptMethod == "Single") {
    $opt_1 = "CHECKED";
    $opt_2 = "";
} else {
    $opt_1 = "";
    $opt_2 = "CHECKED";
}
include_once('popup_js.php');
?>

<table border="0" cellpadding="5">
    <tr>
        <td width="700">
            <A HREF="#responder_msgs"><< Zoom down to the responder's messages >></A>
        </td>
        <td>
            <a href="manual.html#editresps2" onclick="return popper('manual.html#editresps2')">Help</a>
        </td>
    </tr>
</table>

<br>
<center>
    <table width="700">
        <tr>
            <td>
                <table width="100%" bgcolor="#3366cc" style="border: 1px solid #000000;">
                    <tr>
                        <td>
                            <p class="white_header">-- Edit Responder --</p>
                        </td>
                    </tr>
                </table>
                <center>
                    <table width="100%" bgcolor="#CCCCCC" style="border: 1px solid #000000;">
                        <tr>
                            <td>
                                <center>
                                    <table border="0">
                                        <tr>
                                            <td width="200">
                                                <FORM action="responders.php" method=POST>
                                                    <strong><br>Responder Name:</strong>
                                            </td>
                                            <td><input name=Resp_Name size=63 maxlength=250 class="fields"
                                                       value="<?= $DB_ResponderName ?>"></td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Opt-In Level:</strong></td>
                                            <td>
                                                <input type="radio" name="OptMethod" value="Single" <?= $opt_1 ?>>Single
                                                <input type="radio" name="OptMethod" value="Double" <?= $opt_2 ?>>Double
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Notify Owner on Sub/Unsub:</strong></td>
                                            <td>
                                                <?php if ($DB_NotifyOnSub == 1): ?>
                                                    <input type="RADIO" name="NotifyOwner" value="1" checked>Yes
                                                    <input type="RADIO" name="NotifyOwner" value="0">No
                                                <?php else: ?>
                                                    <input type="RADIO" name="NotifyOwner" value="1">Yes
                                                    <input type="RADIO" name="NotifyOwner" value="0" checked>No
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Owner Name:</strong></td>
                                            <td><input name=Owner_Name size=63 maxlength=97 value="<?= $DB_OwnerName ?>"
                                                       class="fields">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Owner Email:</strong></td>
                                            <td><input name=Owner_Email size=63 maxlength=100
                                                       value="<?= $DB_OwnerEmail ?>"
                                                       class="fields">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Reply-to Email:</strong></td>
                                            <td><input name=Reply_To size=63 maxlength=100
                                                       value="<?= $DB_ReplyToEmail ?>"
                                                       class="fields">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="200">
                                                <strong>Start date (optional):</strong><br/>
                                                <em>(YYYY-MM-DD)</em></td>
                                            <td><input name="StartDate" value="<?= $DB_StartDate ?>" type="text"
                                                       size="55" maxlength="95" class="fields"/></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <br>
                                                <strong>Responder Description:</strong> ---
                                                <em>[Note: Supports HTML -- Be careful!]</em><br>
                                <textarea name="Resp_Desc" rows=14 cols=90
                                          class="html_area"><?= $DB_ResponderDesc ?></textarea>
                                                <br><br></td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Opt-In Redirect URL:</strong></td>
                                            <td><input name=OptInRedir size=63 maxlength=100
                                                       value="<?= $DB_OptInRedir ?>"
                                                       class="fields">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Opt-Out Redirect URL:</strong></td>
                                            <td><input name=OptOutRedir size=63 maxlength=100
                                                       value="<?= $DB_OptOutRedir ?>"
                                                       class="fields"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <br>
                                                <strong>Opt-In Confirmation Page:</strong> --- <em>[Note: Supports HTML
                                                    -- Be
                                                    careful!]</em><br>
                                <textarea name="OptInDisplay" rows=14 cols=90
                                          class="html_area"><?= $DB_OptInDisplay ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <br>
                                                <strong>Opt-Out Confirmation Page:</strong> --- <em>[Note: Supports HTML
                                                    -- Be
                                                    careful!]</em><br>
                                <textarea name="OptOutDisplay" rows=14 cols=90
                                          class="html_area"><?= $DB_OptOutDisplay ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <input type="hidden" name="action" value="do_update">
                                                <input type="hidden" name="r_ID" value="<?= $Responder_ID ?>">
                                                <p align="right">
                                                    <input type="submit" name="Save" value="Save" alt="Save"
                                                           class="save_b">
                                                </p>
                                            </td>
                                        </tr>
                                        </td></tr></table>
                                </center>
                                </FORM>
                            </td>
                        </tr>
                    </table>
                </center>
                <br>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="200">
                <FORM action="responders.php" method=POST>
                    <input type="hidden" name="action" value="list">
                    <input type="submit" name="back" value="<< Back" alt="<< Back" class="b_b">
                </FORM>
            </td>
            <td width="200">
                <FORM action="responders.php" method=POST>
                    <input type="hidden" name="action" value="custom_stuff">
                    <input type="hidden" name="r_ID" value="$Responder_ID">
                    <input type="submit" name="submit" value="All custom data">
                </FORM>
            </td>
            <td width="80">
                <FORM action="responders.php" method=POST>
                    <input type="hidden" name="action" value="POP3">
                    <input type="hidden" name="r_ID" value="$Responder_ID">
                    <input type="submit" name="POP3" value="POP3" alt="POP3">
                </FORM>
            </td>
        </tr>
    </table>
</center>
