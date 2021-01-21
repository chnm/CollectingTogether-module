<?php
namespace CollectingTogether\Controller\Site;

use Laminas\Form\Element;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Omeka\Mvc\Exception\NotFoundException;

class FormController extends AbstractActionController
{
    const CLASS_ID_CP = 85; // mare:CollectingProject

    const PROPERTY_ID_CF = 1; // mare:categoricalFocus
    const PROPERTY_ID_GF = 1; // mare:geographicalFocus
    const PROPERTY_ID_MF = 1; // mare:materialFocus
    const PROPERTY_ID_AM = 1; // dcterms:accrualMethod

    const CUSTOM_VOCAB_ID_CF = 1; // Collecting Together: Categorical Focus
    const CUSTOM_VOCAB_ID_GF = 1; // Collecting Together: Geographical Focus
    const CUSTOM_VOCAB_ID_MF = 1; // Collecting Together: Material Focus
    const CUSTOM_VOCAB_ID_AM = 1; // Collecting Together: Accrual Method

    public function indexAction()
    {
        // mare:categoricalFocus
        $cfSelect = new Element\Select('cf');
        $cfSelect->setLabel('Filter by category')
            ->setAttribute('id', 'cf-select')
            ->setEmptyOption('Select below')
            ->setValueOptions($this->getTermsForSelect(self::CUSTOM_VOCAB_ID_CF));

        // mare:geographicalFocus
        $gfSelect = new Element\Select('gf');
        $gfSelect->setLabel('Filter by region')
            ->setAttribute('id', 'gf-select')
            ->setEmptyOption('Select below')
            ->setValueOptions($this->getTermsForSelect(self::CUSTOM_VOCAB_ID_GF));

        // mare:materialFocus
        $mfSelect = new Element\Select('mf');
        $mfSelect->setLabel('Filter by material')
            ->setAttribute('id', 'mf-select')
            ->setEmptyOption('Select below')
            ->setValueOptions($this->getTermsForSelect(self::CUSTOM_VOCAB_ID_MF));

        // dcterms:accrualMethod
        $amSelect = new Element\Select('am');
        $amSelect->setLabel('Filter by method')
            ->setAttribute('id', 'am-select')
            ->setEmptyOption('Select below')
            ->setValueOptions($this->getTermsForSelect(self::CUSTOM_VOCAB_ID_AM));

        $view = new ViewModel;
        $view->setVariable('cfSelect', $cfSelect);
        $view->setVariable('gfSelect', $gfSelect);
        $view->setVariable('mfSelect', $mfSelect);
        $view->setVariable('amSelect', $amSelect);
        return $view;
    }

    public function projectsAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            throw new NotFoundException;
        }
        $filterQuery = json_decode($request->getContent(), true);
        $query = [
            'resource_class_id' => self::CLASS_ID_CP,
            'property' => [
                [
                    'joiner' => 'and',
                    'property' => self::PROPERTY_ID_CF,
                    'type' => 'eq',
                    'text' => $filterQuery['cf'],
                ],
                [
                    'joiner' => 'and',
                    'property' => self::PROPERTY_ID_GF,
                    'type' => 'eq',
                    'text' => $filterQuery['gf'],
                ],
                [
                    'joiner' => 'and',
                    'property' => self::PROPERTY_ID_MF,
                    'type' => 'eq',
                    'text' => $filterQuery['mf'],
                ],
                [
                    'joiner' => 'and',
                    'property' => self::PROPERTY_ID_AM,
                    'type' => 'eq',
                    'text' => $filterQuery['am'],
                ],
            ],
        ];
        $projects = $this->api()->search('items', $query)->getContent();

        $view = new ViewModel;
        $view->setTerminal(true);
        $view->setVariable('projects', $projects);
        return $view;
    }

    /**
     * Get Custom Vocab terms for use in a select element.
     *
     * @param int $id
     * @return array
     */
    protected function getTermsForSelect($id)
    {
        $customVocab = $this->api()->read('custom_vocabs', $id)->getContent();
        $terms = array_map('trim', preg_split("/\r\n|\n|\r/", $customVocab->terms()));
        return array_combine($terms, $terms);
    }
}
