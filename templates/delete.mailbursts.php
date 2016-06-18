<br/>
<center>
    <table width="750">
        <tr>
            <td>
                <table width="750" bgcolor="#3366cc" style="border: 1px solid #000000;" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <p class="white_header"><?php echo $heading; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <center><font color="#FFFFFF">
                                    Deleting a message is irrevocable. Once done, it's gone!<br/>
                                    You cannot delete messages from people's inbox, so those that
                                    have been sent are already sent and cannot be recalled.
                                </font></center>
                        </td>
                    </tr>
                </table>
                <table border="0" width="750" border="1" bgcolor="#CCCCCC" cellspacing="0" cellpadding="0"
                       style="border: 1px solid #000000;">
                    <tr>
                        <td>
                            <center>
                                <table>
                                    <tr>
                                        <td colspan=2><br/></td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>
                                            <strong>Subject:</strong><br/>
                                            <?php echo $subject; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>
                                            <br/>
                                            <strong>Text Version:</strong><br/>
                                            <?php echo $text_msg; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>
                                            <br/>
                                            <strong>HTML Version:</strong><br/>
                                            <?php echo $html_msg; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>
                                            <hr style="border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>
                                            <strong>Message progress: <?php echo $the_math['sent']; ?>
                                                / <?php echo $the_math['total']; ?> (<?php echo $the_math['percent']; ?>
                                                %)</strong><br/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="600">
                                            Created on: <?php echo $timesent; ?><br/>
                                            Sending on: <?php echo $time_to_send; ?><br/>
                                        </td>
                                        <td width="150">
                                            <center>
                                                <FORM action="mailbursts.php" method=POST>
                                                    <input type="hidden" name="action"
                                                           value="<?php echo $submit_action; ?>">
                                                    <input type="hidden" name="m_ID" value="<?php echo $mail_id; ?>">
                                                    <input type="hidden" name="r_ID"
                                                           value="<?php echo $Responder_ID; ?>">
                                                    <input type="submit" name="submit" value="Confirm Delete!"
                                                           alt="Confirm Delete!">
                                                </FORM>
                                            </center>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </td>
                    </tr>
                </table>
                <br/>
                <FORM action="mailbursts.php" method=POST>
                    <input type="hidden" name="action" value="<?php echo $return_action; ?>">
                    <input type="hidden" name="m_ID" value="<?php echo $mail_id; ?>">
                    <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                    <input type="submit" name="submit" value="<< Back" alt="<< Back">
                </FORM>
            </td>
        </tr>
    </table>
</center>