<center>
    <FORM action="messages.php" method=POST enctype="multipart/form-data">
        <table width="750" cellpadding="0" cellspacing="5" bgcolor="#3366cc" style="border: 1px solid #000000;">
            <tr>
                <td>
                    <p class="white_header">-- Create a New Message --</p>
                </td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="5" width="750" bgcolor="#CCCCCC"
               style="border: 1px solid #000000;">
            <tr>
                <td colspan="2">
                    <table align="right" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <a href="manual.html#editrespmsgs" onclick="return popper('manual.html#editrespmsgs')">Help</a>
                            </td>
                            <td width="50">
                                &nbsp;
                            </td>
                            <td>
                                <a href="tagref.html" onclick="return popper('tagref.html')">Tag Reference</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Message Subject:</strong> <em>[Supports Tags]</em><br>
                    <input name="subj" size=98 maxlength=250 class="fields">
                </td>
            </tr>

            <tr>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td colspan="2">
                    <strong>Attach File:</strong> <em>(optional)</em><br/>
                    <input type="file" name="attachment">
                </td>
            </tr>

            <tr>
                <td colspan="2"><br/></td>
            </tr>

            <tr>
                <td colspan="2">
                    <strong>Body: Text Version</strong> &nbsp; &nbsp; &nbsp; &nbsp; -- <em>[Try copy and paste]</em><br>
                    <textarea name="bodytext" rows=10 cols=96 class="text_area">%unsub_msg%</textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2"><br/></td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Body: HTML Version</strong> &nbsp; &nbsp; &nbsp; &nbsp; -- <em>[Try copy and paste]</em><br>
                    <textarea name="bodyhtml" rows=14 cols=96 class="html_area">%unsub_msg%</textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2"><br/></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td colspan="6"><font color="#003300" size="4"><em>Sequential Timing:</em></font></td>
                        </tr>
                        <tr>
                            <td width="80"><font color="#660000" size="4">Months:</font></td>
                            <td width="165"><input name="months" size=13 maxlength=20 value="0" class="fields"></td>
                            <td width="80"><font color="#660000" size="4">Weeks:</font></td>
                            <td width="165"><input name="weeks" size=13 maxlength=20 value="0" class="fields"></td>
                            <td width="80">&nbsp;</td>
                            <td width="165">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="80"><font color="#660066" size="4">Days:</font></td>
                            <td width="165"><input name="days" size=13 maxlength=20 value="0" class="fields"></td>
                            <td width="80"><font color="#660066" size="4">Hours:</font></td>
                            <td width="165"><input name="hours" size=13 maxlength=20 value="0" class="fields"></td>
                            <td width="80"><font color="#000066" size="4">Minutes:</font></td>
                            <td width="165"><input name="min" size=13 maxlength=20 value="0" class="fields"></td>
                        </tr>
                        <tr>
                            <td colspan="6"><br/>
                                <hr style="border: 0; background-color: #000000; color: #000000; height: 1px; width: 100%;">
                                <br/></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <font color="#003300" size="4"><em>Absolute Timing: (Optional)</em></font><br>
                                <font color="#006600" size="3">Select the day and time to run (after the run after time
                                    above has expired).</font> <font color="#000066">Use military time.</font>
                                <br/><br/>
                            </td>
                        </tr>
                        <tr>
                            <td width="80"><font color="#330000" size="4">Day:</font></td>
                            <td width="165">
                                <select name="abs_day" class="fields">
                                    <option value="">None</option>
                                    <option value="Sunday">Sunday</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                </select>
                            </td>
                            <td width="80"><font color="#660066" size="4"><font color="#660066" size="4">@ Hours:</font></font>
                            </td>
                            <td width="165">
                                <select name="abs_hours" class="fields">
                                    <?php
                                    for ($i = 0; $i <= 23; $i++) {
                                        $selected = "";
                                        if ($i == $DB_absHours) {
                                            $selected = " SELECTED";
                                        }
                                        print "<option$selected value=\"$i\">$i</option>\n";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td width="80"><font color="#000066" size="4">Minutes:</font></td>
                            <td width="165">
                                <select name="abs_min" class="fields">
                                    <?php
                                    for ($i = 0; $i <= 59; $i++) {
                                        $selected = "";
                                        if ($i == $DB_absMins) {
                                            $selected = " SELECTED";
                                        }
                                        print "<option$selected value=\"$i\">$i</option>\n";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2"><br/>
                    <hr style="border: 0; background-color: #000000; color: #000000; height: 1px; width: 100%;">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table align="right" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                                <input type="hidden" name="action" value="do_create">
                                <input type="submit" name="Save" value="Save" class="save_b">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </FORM>
</center>
<br>
<FORM action="responders.php" method=POST>
    <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
    <input type="hidden" name="action" value="update">
    <input type="submit" name="Back" value="<< Back" alt="<< Back">
</FORM>
