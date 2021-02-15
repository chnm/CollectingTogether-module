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
            'sort_by' => 'title',
            'sort_order' => 'asc',
            'item_set_id' => Module::ITEM_SET_ID_CP,
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

        // Move priority projects to the top of the list.
        $projectsPriority = [];
        $projectsNonPriority = [];
        foreach ($projects as $project) {
            if (in_array($project->id(), Module::PRIORITY_ITEM_IDS)) {
                $projectsPriority[] = $project;
            } else {
                $projectsNonPriority[] = $project;
            }
        }
        $projects = array_merge($projectsPriority, $projectsNonPriority);

        // Get the priority projects separately.
        $projectsPriority = [];
        foreach (Module::PRIORITY_ITEM_IDS as $id) {
            $projectsPriority[] = $this->api()->read('items', $id)->getContent();
        }

        $view = new ViewModel;
        $view->setTerminal(true);
        $view->setVariable('projects', $projects);
        $view->setVariable('projectsPriority', $projectsPriority);
        return $view;
    }
}
