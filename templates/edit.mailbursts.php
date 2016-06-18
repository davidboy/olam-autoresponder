<br/>
<table width="750" border="0">
    <tr>
        <td>
            <table width="750" bgcolor="#3366cc" style="border: 1px solid #000000;">
                <tr>
                    <td>
                        <p align="center" style="font-size: 200%; margin: 3px;">
                            <font color="#FFFFFF"><?php echo $heading; ?></font>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <center><font color="#CCCCCC">
                                Reminder: Editing a message will only effect those yet to be sent.
                            </font></center>
                    </td>
                </tr>
            </table>
            <FORM action="mailbursts.php" method=POST>
                <table border="0" width="750" bgcolor="#CCCCCC" style="border: 1px solid #000000;">
                    <tr>
                        <td>
                            <center>
                                <table>
                                    <tr>
                                        <td colspan="2">
                                            <table align="right" cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td>
                                                        <a href="manual.html#editbursts"
                                                           onclick="return popper('manual.html#editbursts')">Help</a>
                                                    </td>
                                                    <td width="50">
                                                        &nbsp;
                                                    </td>
                                                    <td>
                                                        <a href="tagref.html" onclick="return popper('tagref.html')">Tag
                                                            Reference</a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>
                                            <strong>Subject:</strong><br/>
                                            <center>
                                                <input name="subj" size=97 maxlength=250 value="<?php echo $subject; ?>"
                                                       class="fields">
                                            </center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>
                                            <br/>
                                            <strong>Body: Text Version</strong> &nbsp; &nbsp; &nbsp; &nbsp; -- <em>[Try
                                                copy and paste]</em><br/>
                                            <center>
                                                <textarea name="bodytext" rows=10 cols=95
                                                          class="text_area"><?php echo $text_msg; ?></textarea>
                                            </center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>
                                            <br/>
                                            <strong>Body: HTML Version</strong> &nbsp; &nbsp; &nbsp; &nbsp; -- <em>[Try
                                                copy and paste]</em><br/>
                                            <center>
                                                <textarea name="bodyhtml" rows=14 cols=95
                                                          class="html_area"><?php echo $html_msg; ?></textarea>
                                            </center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <strong>Start sending on:</strong>
                                            <select name="send_month" class="fields">
                                                <?php echo $month_to_send; ?>

                                                <option<?php if ($month_to_send == "january") {
                                                    echo " SELECTED";
                                                } ?> value="January">January
                                                </option>
                                                \n";
                                                <option<?php if ($month_to_send == "february") {
                                                    echo " SELECTED";
                                                } ?> value="February">February
                                                </option>
                                                \n";
                                                <option<?php if ($month_to_send == "march") {
                                                    echo " SELECTED";
                                                } ?> value="March">March
                                                </option>
                                                \n";
                                                <option<?php if ($month_to_send == "april") {
                                                    echo " SELECTED";
                                                } ?> value="April">April
                                                </option>
                                                \n";
                                                <option<?php if ($month_to_send == "may") {
                                                    echo " SELECTED";
                                                } ?> value="May">May
                                                </option>
                                                \n";
                                                <option<?php if ($month_to_send == "june") {
                                                    echo " SELECTED";
                                                } ?> value="June">June
                                                </option>
                                                \n";
                                                <option<?php if ($month_to_send == "july") {
                                                    echo " SELECTED";
                                                } ?> value="July">July
                                                </option>
                                                \n";
                                                <option<?php if ($month_to_send == "august") {
                                                    echo " SELECTED";
                                                } ?> value="August">August
                                                </option>
                                                \n";
                                                <option<?php if ($month_to_send == "september") {
                                                    echo " SELECTED";
                                                } ?> value="September">September
                                                </option>
                                                \n";
                                                <option<?php if ($month_to_send == "october") {
                                                    echo " SELECTED";
                                                } ?> value="October">October
                                                </option>
                                                \n";
                                                <option<?php if ($month_to_send == "november") {
                                                    echo " SELECTED";
                                                } ?> value="November">November
                                                </option>
                                                \n";
                                                <option<?php if ($month_to_send == "december") {
                                                    echo " SELECTED";
                                                } ?> value="December">December
                                                </option>
                                                \n";
                                            </select>
                                            <select name="send_day" class="fields">
                                                <?php echo $month_to_send; ?>

                                                <?php
                                                for ($i = 1; $i <= 31; $i++) {
                                                    $selected = "";
                                                    if ($i == $day_to_send) {
                                                        $selected = " SELECTED";
                                                    }
                                                    print "<option$selected value=\"$i\">$i</option>\n";
                                                }
                                                ?>
                                            </select>
                                            <select name="send_year" class="fields">
                                                <?php
                                                for ($i = $this_year; $i <= ($this_year + 10); $i++) {
                                                    $selected = "";
                                                    if ($i == $year_to_send) {
                                                        $selected = " SELECTED";
                                                    }
                                                    print "<option$selected value=\"$i\">$i</option>\n";
                                                }
                                                ?>
                                            </select>
                                            &nbsp; at &nbsp;
                                            <select name="send_hour" class="fields">
                                                <?php
                                                for ($i = 0; $i <= 23; $i++) {
                                                    $selected = "";
                                                    if ($i == $hour_to_send) {
                                                        $selected = " SELECTED";
                                                    }
                                                    print "<option$selected value=\"$i\">$i</option>\n";
                                                }
                                                ?>
                                            </select>
                                            :
                                            <select name="send_min" class="fields">
                                                <?php
                                                for ($i = 0; $i <= 59; $i++) {
                                                    $selected = "";
                                                    if ($i == $min_to_send) {
                                                        $selected = " SELECTED";
                                                    }
                                                    print "<option$selected value=\"$i\">$i</option>\n";
                                                }
                                                ?>
                                            </select>
                                            o'clock.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>
                                            <hr style="border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">
                                            <center>
                                                <input type="hidden" name="action" value="do_edit">
                                                <input type="hidden" name="m_ID" value="<?php echo $mail_id; ?>">
                                                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                                                <input type="submit" name="submit" value="Save Changes"
                                                       alt="Save Changes">
                                            </center>
            </FORM>
        </td>
        <td width="50%">
            <center>
                <FORM action="mailbursts.php" method=POST>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="m_ID" value="<?php echo $mail_id; ?>">
                    <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                    <input type="submit" name="submit" value="Delete Msg" alt="Delete Msg">
                </FORM>
            </center>
        </td>
    </tr>
    <tr>
        <td colspan=2>
            <hr style="border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;"/>
        </td>
    </tr>
    <tr>
        <td>
            Created on: <br/>
            <?php echo $timesent; ?><br/>
            <br/>
        </td>
        <td>
            <center>
                Message status: <strong><?php echo $status; ?></strong><br/>
                <FORM action="mailbursts.php" method=POST>
                    <input type="hidden" name="action" value="pause">
                    <input type="hidden" name="m_ID" value="<?php echo $mail_id; ?>">
                    <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                    <input type="submit" name="submit" value="Toggle Status" alt="Toggle Status">
                </FORM>
            </center>
        </td>
    </tr>
    <tr>
        <td colspan=2>
            <strong>Message progress: <?php echo $the_math['sent']; ?> / <?php echo $the_math['total']; ?>
                (<?php echo $the_math['percent']; ?>%)</strong>
        </td>
    </tr>
</table>
</center>
</td></tr></table>
<br/>
<FORM action="mailbursts.php" method=POST>
    <input type="hidden" name="action" value="<?php echo $return_action; ?>">
    <?php if ($mail_id > 0) { ?>
        <input type="hidden" name="m_ID" value="<?php echo $mail_id; ?>">
    <?php } ?>
    <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
    <input type="submit" name="submit" value="<< Back" alt="<< Back">
</FORM>
</td></tr></table>
