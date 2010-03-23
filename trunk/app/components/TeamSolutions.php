<?php
class TeamSolutions extends BaseControl
{

    private $team;

    public function  __construct(IComponentContainer $parent = NULL, $name = NULL, $team = NULL) {
	parent::__construct($parent, $name);
	$this->team = $team;
    }

    public function init() {
	$template   = $this->getTemplate();
	$team	    = $this->getTeams()->find($id);
	$teams	    = $this->getTeams()->findAll()->fetchPairs("id_team", "name");
	$tasks	    = $this->getTasks()
	    ->findBySurveyor(Environment::getUser()->getIdentity()->getName())
	    ->fetchPairs("id_task", "name");
	$solutions  = $this->getSolutions()
	    ->findByTeam($this->team)
	    ->where("[id_task] IN %l", array_keys($tasks));
	while($solution = $solutions->fetch()) {
		$form = new AppForm($this,"form" . $solution->id_solution);
		$form->addHidden("solution");
		$form->addSelect("team","",$teams)
		    ->addRule(Form::FILLED,"Vyplňte prosím tým, který úkol vyřešil.");
		$form->addSelect("task","",$tasks)
		    ->addRule(Form::FILLED,"Vyplňte prosím úkol, o který se jedná.");
		$form->addText("score","")
		    ->addRule(Form::RANGE, "Body reprezentují pouze přirozená čísla (%d až %d)",array(1,1000000))
		    ->addRule(Form::INTEGER,"Body reprezentují pouze přirozená čísla.")
		    ->addRule(Form::FILLED,"Vyplňte prosím ohodnocení týmu.");
		$form->addSubmit("edit","Oprav");
		$form->addSubmit("delete","Smazat");
		$form->setDefaults(array(
		    "team" => $row->teamID,
		    "task" => $row->taskID,
		    "score" => $row->score,
		    "solution" => $row->solutionID
		));
		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = 'tr';
		$renderer->wrappers['pair']['container'] = NULL;
		$renderer->wrappers['label']['container'] = "td class=\"hidden\"";
		$renderer->wrappers['control']['container'] = 'td';
		$form->onSubmit[] = array($this, 'taskEditFormSubmitted');
		$this->template->solutions[] = $form;
	}
    }


}
