<?php
namespace CollectingTogether\Controller\Site;

use Laminas\Form\Element;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Omeka\Mvc\Exception\NotFoundException;

class FormController extends AbstractActionController
{
    const CUSTOM_VOCAB_ID_CATEGORY = 1;
    const CUSTOM_VOCAB_ID_GEOGRAPHY = 1;
    const CUSTOM_VOCAB_ID_MATERIAL = 1;
    const CUSTOM_VOCAB_ID_METHOD = 1;

    public function indexAction()
    {
        // mare:categoricalFocus
        $categorySelect = new Element\Select('category');
        $categorySelect->setLabel('Categorical focus')
            ->setAttribute('id', 'category-select')
            ->setEmptyOption('Select below')
            ->setValueOptions($this->getTermsForSelect(self::CUSTOM_VOCAB_ID_CATEGORY));

        // mare:geographicalFocus
        $geographySelect = new Element\Select('geography');
        $geographySelect->setLabel('Geographical focus')
            ->setAttribute('id', 'geography-select')
            ->setEmptyOption('Select below')
            ->setValueOptions($this->getTermsForSelect(self::CUSTOM_VOCAB_ID_GEOGRAPHY));

        // mare:materialFocus
        $materialSelect = new Element\Select('material');
        $materialSelect->setLabel('Material focus')
            ->setAttribute('id', 'material-select')
            ->setEmptyOption('Select below')
            ->setValueOptions($this->getTermsForSelect(self::CUSTOM_VOCAB_ID_MATERIAL));

        // dcterms:accrualMethod
        $methodSelect = new Element\Select('method');
        $methodSelect->setLabel('Accrual method')
            ->setAttribute('id', 'method-select')
            ->setEmptyOption('Select below')
            ->setValueOptions($this->getTermsForSelect(self::CUSTOM_VOCAB_ID_METHOD));

        $view = new ViewModel;
        $view->setVariable('categorySelect', $categorySelect);
        $view->setVariable('geographySelect', $geographySelect);
        $view->setVariable('materialSelect', $materialSelect);
        $view->setVariable('methodSelect', $methodSelect);
        return $view;
    }

    public function projectsAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            throw new NotFoundException;
        }
        $query = json_decode($request->getContent(), true);
        // @todo: filter projects by query then render them in the view template

        $view = new ViewModel;
        $view->setTerminal(true);
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
