<?php
namespace CollectingTogether;

use Omeka\Module\AbstractModule;

class Module extends AbstractModule
{
    const ITEM_SET_ID_CP = 20448; // Collecting Projects

    const PROPERTY_ID_CF = 233; // mare:categoricalFocus
    const PROPERTY_ID_GF = 231; // mare:geographicalFocus
    const PROPERTY_ID_MF = 232; // mare:materialFocus
    const PROPERTY_ID_AM = 53; // dcterms:accrualMethod

    const CUSTOM_VOCAB_ID_CF = 5; // Collecting Together: Categorical Focus
    const CUSTOM_VOCAB_ID_GF = 6; // Collecting Together: Geographical Focus
    const CUSTOM_VOCAB_ID_MF = 8; // Collecting Together: Material Focus
    const CUSTOM_VOCAB_ID_AM = 9; // Collecting Together: Accrual Method

    public function getConfig()
    {
        return include sprintf('%s/config/module.config.php', __DIR__);
    }
}
