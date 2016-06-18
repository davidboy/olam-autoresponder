<?php
include_once('popup_js.php');
?>

<table border="0" cellpadding="5">
    <tr>
        <td width="700">
            &nbsp;
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
                            <p class="white_header">-- Create a Responder --</p>
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
                                                <form action="responders.php" method=POST>
                                                    <strong><br>Responder Name:</strong>
                                            </td>
                                            <td><input name=Resp_Name size=55 maxlength=250 class="fields"></td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Opt-In Level:</strong></td>
                                            <td>
                                                <input type="radio" name="OptMethod" value="Single">Single</input>
                                                <input type="radio" name="OptMethod" value="Double"
                                                       CHECKED>Double</input>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Notify Owner on Sub/Unsub:</strong></td>
                                            <td>
                                                <input type="RADIO" name="NotifyOwner" value="1">Yes
                                                <input type="RADIO" name="NotifyOwner" value="0" checked>No</input>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Owner Name:</strong></td>
                                            <td><input name=Owner_Name size=55 maxlength=95 class="fields"></td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Owner Email:</strong></td>
                                            <td><input name=Owner_Email size=55 maxlength=95 class="fields"></td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Reply-to Email:</strong></td>
                                            <td><input name=Reply_To size=55 maxlength=95 class="fields"></td>
                                        </tr>
                                        <tr>
                                            <td width="200">
                                                <strong>Start date (optional):</strong><br/>
                                                <em>(YYYY-MM-DD)</em></td>
                                            <td><input name="StartDate" type="text" size="55" maxlength="95"
                                                       class="fields"/></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <br>
                                                <strong>Responder Description:</strong> --- <em>[Note: Supports HTML --
                                                    Be
                                                    careful!]</em><br>
                                                <textarea name="Resp_Desc" rows=14 cols=82 class="html_area"></textarea>
                                                <br><br></td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Opt-In Redirect URL:</strong></td>
                                            <td><input name=OptInRedir size=55 maxlength=95 class="fields"></td>
                                        </tr>
                                        <tr>
                                            <td width="200"><strong>Opt-Out Redirect URL:</strong></td>
                                            <td><input name=OptOutRedir size=55 maxlength=95 class="fields"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <br>
                                                <strong>Opt-In Confirmation Page:</strong> --- <em>[Note: Supports HTML
                                                    -- Be
                                                    careful!]</em><br>
                                                <textarea name="OptInDisplay" rows=14 cols=82
                                                          class="html_area"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <br>
                                                <strong>Opt-Out Confirmation Page:</strong> --- <em>[Note: Supports HTML
                                                    -- Be
                                                    careful!]</em><br>
                                                <textarea name="OptOutDisplay" rows=14 cols=82
                                                          class="html_area"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <input type="hidden" name="action" value="do_create">
                                                <p align="right">
                                                    <input type="submit" name="Save" value="Save" alt="Save"
                                                           class="save_b">
                                                </p>
                                            </td>
                                        </tr>
                                        </td></tr></table>
                                </center>
                                </form>
                            </td>
                        </tr>
                    </table>
                </center>
                <br>
                <table width="100%">
                    <tr>
                        <td width="200">
                            <form action="responders.php" method=POST>
                                <input type="hidden" name="action" value="list"/>
                                <input type="submit" name="Back" value="Back" alt="Back" class="b_b"/>
                            </form>
                        </td>
                        <td width="220">
                            <font size=3 color="#660000">
                                Tip: After this responder is created you may add messages by clicking "Edit" from the
                                responder
                                menu. <br>
                            </font>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</center>

<!--
                        <tr>
                            <td colspan="6">
                                <font color="#003300" size="4"><em>Schedule a start date: (Optional)</em></font><br/>
                                <font color="#006600" size="3"><em>Emails won't be sent out until this date</em></font>
                            </td>
                        </tr>
                        -->
