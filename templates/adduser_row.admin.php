<tr>
    <td width="250">
        <input name="add_email<?php echo $i; ?>" size=30 maxlength=95 class="fields">
    </td>
    <td width="125">
        <input type="RADIO" name="send_html<?php echo $i; ?>" value="1">Yes
        <input type="RADIO" name="send_html<?php echo $i; ?>" value="0" checked="checked"> No<br>
    </td>
    <td width="300">
        <?php responderPulldown("chosen_resp$i"); ?>
    </td>
</tr>
<tr>
    <td colspan="2">
        <strong>First Name: </strong><br/>
        <input name="firstname<?php echo $i; ?>" size=30 maxlength=40 class="fields">
    </td>
    <td>
        <strong>Last Name: </strong><br/>
        <input name="lastname<?php echo $i; ?>" size=30 maxlength=40 class="fields">
    </td>
</tr>
<tr>
    <td colspan="3">
        <hr style="border: 0; background-color: #660000; color: #660000; height: 1px; width: 100%;">
    </td>
</tr>
