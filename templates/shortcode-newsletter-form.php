<form action="" method="post" name="f12-newsletter">
    <div class="bfi-ce-form">
			<?php echo $args["wp_nonce_field"]; ?>
            <input type="text" name="f12-newsletter-name" class="<?php if($args["error-name"]) echo "error";?>" value="<?php $args["f12-newsletter-name"]; ?>"
                   placeholder="Name*"/>
            <input type="text" name="f12-newsletter-email" class="<?php if($args["error-email"]) echo "error";?>" value="<?php $args["f12-newsletter-email"]; ?>"
                   placeholder="E-Mail*"/>
            <div class="bfi-ce-form__legende">
                <p>
                    *Pflichtfeld
                </p>
            </div>
            <div class="bfi-ce-form__options">
                <input type="submit" name="f12-newsletter-submit" value="Kostenlos abonnieren"/>
            </div>
    </div>
</form>
