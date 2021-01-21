<?php
namespace CollectingTogether\Controller\Site;

use CollectingTogether\Module;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Omeka\Mvc\Exception\NotFoundException;

class FormController extends AbstractActionController
{
    public function projectsAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            throw new NotFoundException;
        }
        $filterQuery = json_decode($request->getContent(), true);
        $query = [
            'resource_class_id' => Module::CLASS_ID_CP,
            'property' => [
                [
                    'joiner' => 'and',
                    'property' => Module::PROPERTY_ID_CF,
                    'type' => 'eq',
                    'text' => $filterQuery['cf'],
                ],
                [
                    'joiner' => 'and',
                    'property' => Module::PROPERTY_ID_GF,
                    'type' => 'eq',
                    'text' => $filterQuery['gf'],
                ],
                [
                    'joiner' => 'and',
                    'property' => Module::PROPERTY_ID_MF,
                    'type' => 'eq',
                    'text' => $filterQuery['mf'],
                ],
                [
                    'joiner' => 'and',
                    'property' => Module::PROPERTY_ID_AM,
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
}
