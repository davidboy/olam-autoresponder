<br/>
<center>
    <table width="750" bgcolor="#3366cc" cellpadding="0" cellspacing="3" style="border: 1px solid #000000;">
        <tr>
            <td>
                <p align="center" style="font-size: 200%"><font color="#FFFFFF">-- Add a List of Addresses --</font></p>
            </td>
        </tr>
    </table>
    <table width="750" bgcolor="#FFFFFF" cellpadding="0" cellspacing="3" style="border: 1px solid #000000;">
        <tr>
            <td>
                <p align="center" style="font-size: 100"><font color="#990000"><strong>Warning: </strong>Manually-added
                        subscribers will not receive a confirmation email!</font></p>
            </td>
        </tr>
    </table>
    <FORM action="admin.php" name="List_Adder" enctype="multipart/form-data" method="POST">
        <table width="750" bgcolor="#CCCCCC" cellpadding="0" cellspacing="3" style="border: 1px solid #000000;">
            <tr>
                <td colspan="2">
                    <center><strong><font size="4" color="#333333">Universal settings (Effects all
                                names)</font></strong></center>
                </td>
            </tr>
            <tr>
                <td width="440">
                    <font size="4" color="#330000">Select Responder:<br></font>
                    <center>
                        <?php responderPulldown('r_ID'); ?>
                    </center>
                </td>
                <td width="250">
                    <center>
                        <font size="4" color="#003300">
                            HTML:
                            <input type="RADIO" name="h" value="1">Yes &nbsp;
                            <input type="RADIO" name="h" value="0" checked="checked">No
                        </font>
                    </center>
                </td>
            <tr>
                <td colspan="2">
                    <hr style="border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <center>
                        <strong>Enter list of email addresses, seperated by a comma.</strong><br>
                        Example: <em>john@doe.org, jane@me-tarzan.com, lois@lane.net</em><br>
                        <textarea name="comma_list" rows="15" cols="95" class="text_area"></textarea>
                    </center>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr style="border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Load a comma-spliced file: </strong><br>
                    <center><input type="file" name="load_file" size="80" maxlength="200" class="fields"></center>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table align="right">
                        <tr>
                            <td>
                                <input type="hidden" name="action" value="bulk_add_do">
                                <input type="submit" name="Save" value="Save" class="save_b">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </FORM>
</center>