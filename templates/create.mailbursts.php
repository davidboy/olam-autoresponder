<br>
<table width="750" border="0">
    <tr>
        <td>
            <table width="750" bgcolor="#3366cc" style="border: 1px solid #000000;">
                <tr>
                    <td>
                        <p align="center" style="font-size: 200%">
                            <font color="#FFFFFF"><?php echo $heading; ?></font>
                        </p>
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
                                        <td>
                                            <table align="right" cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td>
                                                        <a href="manual.html#createburst"
                                                           onclick="return popper('manual.html#createburst')">Help</a>
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
                                        <td>
                                            <strong>Subject:</strong><br>
                                            <input name="subj" size=97 maxlength=250 value="<?php echo $subject; ?>"
                                                   class="fields">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br>
                                            <strong>Body: Text Version</strong> &nbsp; &nbsp; &nbsp; &nbsp; -- <em>[Try
                                                copy and paste]</em><br>
                                            <textarea name="bodytext" rows=10 cols=95
                                                      class="text_area"><?php echo $text_msg; ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br>
                                            <strong>Body: HTML Version</strong> &nbsp; &nbsp; &nbsp; &nbsp; -- <em>[Try
                                                copy and paste]</em><br>
                                            <textarea name="bodyhtml" rows=14 cols=95
                                                      class="html_area"><?php echo $html_msg; ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>
                                            <hr style="border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;"/>
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
                                </table>
                            </center>
                            <table align="right">
                                <tr>
                                    <td>
                                        <input type="hidden" name="action" value="<?php echo $submit_action; ?>">
                                        <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                                        <input type="submit" name="submit" value="Save & Send" alt="Save & Send">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </FORM>
            <br>
            <FORM action="mailbursts.php" method=POST>
                <input type="hidden" name="action" value="<?php echo $return_action; ?>">
                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                <input type="submit" name="submit" value="<< Back" alt="<< Back">
            </FORM>
        </td>
    </tr>
</table>
