<?php echo $args["wp_nonce_field"]; ?>
<table class="f12-table">
	<tr>
		<td class="label" style="width:300px;">
			<label>Vor- / Nachanme</label>
		</td>
		<td>
			<input type="text" name="f12-newsletter-name" value="<?php echo $args["f12-newsletter-name"];?>" />
		</td>
	</tr>
	<tr>
		<td class="label">
			<label>E-Mail</label>
		</td>
		<td>
			<input type="text" name="f12-newsletter-email" value="<?php echo $args["f12-newsletter-email"];?>" />
		</td>
	</tr>
    <tr>
        <td class="label">
            <label>Bestätigt?</label>
        </td>
        <td>
            <input type="checkbox" name="f12-newsletter-doubleoptin" value="1" <?php if(!empty($args["f12-newsletter-doubleoptin"])) echo "checked=\"checked\"";?>>
        </td>
    </tr>
	<tr>
		<td class="label">
			<label>IP-Addresse</label>
		</td>
		<td>
			<input type="text" name="f12-newsletter-ip" value="<?php echo $args["f12-newsletter-ip"];?>" />
		</td>
	</tr>
</table>