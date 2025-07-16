<div class="meta-page f12-page-settings">
    <h1>Newsletter Einstellungen</h1>

    <form action="<?php echo esc_url( admin_url( "admin-post.php" ) ); ?>" method="post"
          name="f12_newsletter_settings">
        <input type="hidden" name="action" value="f12_newsletter_settings_save">
		<?php echo $args["f12-newsletter-nonce"]; ?>
        <div class="f12-panel">
            <div class="f12-panel__header">
                <h2>Newsletter Formular</h2>
                <p>
                    Einstellungen für das abonnieren des Newsletters
                </p>
            </div>
            <div class="f12-panel__content">
                <table class="f12-table">
                    <tr>
                        <td class="label" style="width:300px;">
                            <label>Absender E-Mail</label>
                            <p>Geben Sie die E-Mail ein, von der die Mails versendet werden sollen.</p>
                        </td>
                        <td>
                            <input type="text" name="f12-newsletter-email"
                                   value="<?php echo $args["f12-newsletter-email"]; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="label" style="width:300px;">
                            <label>Seite nach dem senden?</label>
                            <p>Wählen Sie eine Seite aus, die nach dem senden des Formulars angezeigt werden soll.</p>
                        </td>
                        <td>
                            <select name="f12-newsletter-page-send">
								<?php echo $args["f12-newsletter-page-send"]; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            <label>Seite bestätigung Double-Opt-In</label>
                            <p>Wählen Sie eine Seite aus die beim klicken des Double-Opt-In verwendet werden soll.</p>
                        </td>
                        <td>
                            <select name="f12-newsletter-page-doubleoptin">
								<?php echo $args["f12-newsletter-page-doubleoptin"]; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                            Interne E-Mail
                            <p>Diese Informationen werden an Sie übermittelt.</p>
                        </th>
                    </tr>
                    <tr>
                        <td class="label">
                            <label>Betreff</label>
                            <p>Geben Sie einen Betreff für die Nachricht ein</p>
                        </td>
                        <td>
                            <input type="text" name="f12-newsletter-intern-subject"
                                   value="<?php echo $args["f12-newsletter-intern-subject"]; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            <label>Nachricht</label>
                            <p>Geben Sie den Inhalt der Nachricht ein.</p>
                            <p><strong>Platzhalter</strong></p>
                            <p>{email}</p>
                            <p>{name}</p>
                        </td>
                        <td>
							<?php
							echo wp_editor( $args["f12-newsletter-intern-message"], "f12-newsletter-intern-message" );
							?>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                            Externe E-Mail
                            <p>Diese Informationen werden an den Besucher übermittelt um das Anmelden für den Newsletter
                                zu bestätigen.</p>
                        </th>
                    </tr>
                    <tr>
                        <td class="label">
                            <label>Betreff</label>
                            <p>Geben Sie einen Betreff für die Nachricht ein</p>
                        </td>
                        <td>
                            <input type="text" name="f12-newsletter-extern-subject"
                                   value="<?php echo $args["f12-newsletter-extern-subject"]; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            <label>Nachricht</label>
                            <p>Geben Sie den Inhalt der Nachricht ein.</p>
                            <p><strong>Platzhalter</strong></p>
                            <p>{email}</p>
                            <p>{name}</p>
                            <p>{link}</p>
                            <p>Geben Sie den Platzhalter {link} mit an um das Abonnieren zu ermöglichen.</p>
                        </td>
                        <td>
							<?php
							echo wp_editor( $args["f12-newsletter-extern-message"], "f12-newsletter-extern-message" );
							?>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            Senden Sie eine Testmail:
                        </td>
                        <td style="padding:0;">
                            <table class="f12-table">
                                <tr>
                                    <td class="label">
                                        Empfänger:
                                    </td>
                                    <td>
                                        <input type="text" name="f12_newsletter_testmail_email" value="" placeholder="info@mail.com">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">
                                        Senden?
                                    </td>
                                    <td>
                                        <input type="submit" name="f12_newsletter_testmail" value="Testmail Senden"/>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <input type="submit" name="f12_newsletter_settings" value="Speichern"/>
    </form>

</div>