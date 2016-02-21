<br />
<table cellpadding="0" cellspacing="0" border="0">
   <tr>
      <td>
         <form action="responders.php" method=POST>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="r_ID"   value="<?php echo $DB_ResponderID; ?>">
            <input type="submit" name="submit" value="Edit Responder Messages >>" class="butt">
         </form>
      </td>
      <td width="510">
         &nbsp;
      </td>
      <td>
         <a href="manual.html#mailbursts" onclick="return popper('manual.html#mailbursts')">Help</a>
      </td>
   </tr>
</table>