<?php
/**
 * 	en.php - English language variables
 */
$english = array(
	'validation_reminder:first_message_input' => "De eerste herinnering moet verstuurd worden na:",
	'validation_reminder:second_message_input' => "De tweede herinnering moet verstuurd worden na:",
	'validation_reminder:remove_input' => "Het account moet verwijderd worden na:",

	'validation_reminder:validate:token:subject' => "%s, bevestig alsjeblieft je e-mailadres voor %s!",
	'validation_reminder:validate:token:body' => "Beste %s,

U heeft %s dag(en) geleden een account aangemaakt op %s.
U kunt uw account echter pas gebruiken als u uw e-mailadres heeft bevestigd.

Om je e-mailadres te bevestigen dien je de volgende code in onze app in te vullen: %s

Wanneer u perongelijk de app afgesloten heeft, kunt u uw account ook valideren door op de volgende link te drukken: %s

Als uw account niet binnen %s dagen geactiveerd word wordt uw account automatisch verwijderd.
%s
%s

---
Dit is een automatisch aangemaakt bericht. Je kunt hier niet op reageren.
"
);

add_translation("en", $english);
