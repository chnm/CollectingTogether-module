<?php
namespace CollectingTogether\Site\BlockLayout;

use CollectingTogether\Module;
use Omeka\Api\Representation\SiteRepresentation;
use Omeka\Api\Representation\SitePageRepresentation;
use Omeka\Api\Representation\SitePageBlockRepresentation;
use Omeka\Site\BlockLayout\AbstractBlockLayout;
use Laminas\Form\Element;
use Laminas\View\Renderer\PhpRenderer;

class CollectingTogetherForm extends AbstractBlockLayout
{
    public function getLabel()
    {
        return 'Collecting Together form'; // @translate
    }

    public function form(PhpRenderer $view, SiteRepresentation $site,
        SitePageRepresentation $page = null, SitePageBlockRepresentation $block = null
    ) {
        return '';
    }

    public function render(PhpRenderer $view, SitePageBlockRepresentation $block)
    {
        // mare:categoricalFocus
        $cfSelect = new Element\Select('cf');
        $cfSelect->setLabel('Filter by category')
            ->setAttribute('id', 'cf-select')
            ->setEmptyOption('Select below')
            ->setValueOptions($this->getTermsForSelect($view, Module::CUSTOM_VOCAB_ID_CF));

        // mare:geographicalFocus
        $gfSelect = new Element\Select('gf');
        $gfSelect->setLabel('Filter by region')
            ->setAttribute('id', 'gf-select')
            ->setEmptyOption('Select below')
            ->setValueOptions($this->getTermsForSelect($view, Module::CUSTOM_VOCAB_ID_GF));

        // mare:materialFocus
        $mfSelect = new Element\Select('mf');
        $mfSelect->setLabel('Filter by material')
            ->setAttribute('id', 'mf-select')
            ->setEmptyOption('Select below')
            ->setValueOptions($this->getTermsForSelect($view, Module::CUSTOM_VOCAB_ID_MF));

        // dcterms:accrualMethod
        $amSelect = new Element\Select('am');
        $amSelect->setLabel('Filter by method')
            ->setAttribute('id', 'am-select')
            ->setEmptyOption('Select below')
            ->setValueOptions($this->getTermsForSelect($view, Module::CUSTOM_VOCAB_ID_AM));

        return $view->partial('common/block-layout/collecting-together-form', [
            'cfSelect' => $cfSelect,
            'gfSelect' => $gfSelect,
            'mfSelect' => $mfSelect,
            'amSelect' => $amSelect,
        ]);
    }

    /**
     * Get Custom Vocab terms for use in a select element.
     *
     * @param int $id
     * @return array
     */
    protected function getTermsForSelect($view, $id)
    {
        $customVocab = $view->api()->read('custom_vocabs', $id)->getContent();
        $terms = array_map('trim', preg_split("/\r\n|\n|\r/", $customVocab->terms()));
        return array_combine($terms, $terms);
    }
}
