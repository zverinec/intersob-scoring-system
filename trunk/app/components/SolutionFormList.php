<?php

class SolutionFormList extends BaseControl
{

    protected function createTemplate() {
	$template = parent::createTemplate();
	$template->setFile(APP_DIR . "/templates/components/solutionFormList.phtml");
	return $template;
    }

    public function setUp(DibiDataSource $solutions) {
	$template = $this->getTemplate();
	$template->solutions = array();
	$tasks = $this->getTasks()->findBySurveyor(
	    Environment::getUser()
		->getIdentity()
		->getName()
	    )
	    ->fetchPairs("id_task","task_name");
	$teams = $this->getTeams()->findAll()->fetchPairs("id_team", "name");
	while ($solution = $solutions->fetch()) {
	    $form = new AppForm($this,"form" . $solution->id_solution);
	    $form->addHidden("solution");
	    $form->addSelect("team","Tým:",$teams)
		->addRule(Form::FILLED,"Vyplňte prosím tým, který úkol vyřešil.");
	    $form->addSelect("task","Úkol:",$tasks)
		->addRule(Form::FILLED,"Vyplňte prosím úkol, o který se jedná.");
	    $form->addText("score","Body:")
		->addRule(Form::RANGE, "Body reprezentují pouze přirozená čísla (%d až %d)",array(1,1000000))
		->addRule(Form::INTEGER,"Body reprezentují pouze přirozená čísla.")
		->addRule(Form::FILLED,"Vyplňte prosím ohodnocení týmu.");
	    $form->addSubmit("edit","Oprav");
	    $form->addSubmit("delete","Smazat");
	    $form->setDefaults(array(
		"team" => $solution->id_team,
		"task" => $solution->id_task,
		"score" => $solution->score,
		"solution" => $solution->id_solution
	    ));
	    $renderer = $form->getRenderer();
	    $renderer->wrappers['controls']['container'] = 'tr';
	    $renderer->wrappers['pair']['container'] = NULL;
	    $renderer->wrappers['label']['container'] = "td class=\"hidden\"";
	    $renderer->wrappers['control']['container'] = 'td';
	    $form->onSubmit[] = array($this, 'solutionEditFormSubmitted');
	    $template->solutions[] = $form;
	}
    }

    public function solutionEditFormSubmitted($form) {
	try {
	    $solution = $this->getSolutions()->find($form["solution"]->getValue());
	    if (empty($solution)) {
		throw new IntersobException("Snažíte se změnit něco, co neexistuje.");
	    }
	    if ($form["edit"]->getValue()) {
		$existed = $this->getSolutions()->findByTeamAndTask($form["team"]->getValue(), $form["task"]->getValue());
		if (!empty($existed) && ($existed->id_solution != $form["solution"]->getValue())) {
		    throw new IntersobException("Tým '".$solution->team_name."' už úkol '".$solution->task_name."' řešil.");
		}
		$changes = array(
		    "id_team" => $form["team"]->getValue(),
		    "id_task" => $form["task"]->getValue(),
		    "score" => $form["score"]->getValue()
		);
		$this->getSolutions()->update($changes)->where("[id_solution] = %i", $solution->id_solution)->execute();
	    }
	    else if ($form["delete"]->getValue()) {
		    $this->getSolutions()->delete()->where("[id_solution] = %i", $solution->id_solution)->execute();
	    }
	    $this->getPresenter()->redirect($this->getPresenter()->backlink());
	}
	catch(IntersobException $e) {
	    $form->addError($e->getMessage());
	}
    }

    public function render() {
	$this->getTemplate()->render();
    }

    protected function init() {}

}
