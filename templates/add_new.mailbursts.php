<br/>
<center>
    <div style="width:550px">
        <div style="width:50%; float: left;">
            <FORM action="responders.php" method=POST>
                <input type="hidden" name="action" value="<?php echo $return_action; ?>">
                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                <input type="submit" name="submit" value="<< Back" alt="<< Back">
            </FORM>
        </div>
        <div style="width:50%; float: right;">
            <FORM action="mailbursts.php" method=POST>
                <input type="hidden" name="action" value="<?php echo $submit_action; ?>">
                <input type="hidden" name="r_ID" value="<?php echo $Responder_ID; ?>">
                <input type="submit" name="submit" value="Create Burst" alt="Create Burst">
            </FORM>
        </div>
    </div>
</center>
