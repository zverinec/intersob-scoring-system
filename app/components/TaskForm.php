<?php
class TaskForm extends BaseControl {

    protected function init() {}

    public function render() {
	$this->getComponent("form")->render();
    }

    public function taskFormSubmitted(Form $form) {
	try {
	    $solution = $this->getSolutions()->findByTeamAndTask($form["team"]->getValue(), $form["task"]->getValue());
	    if ($solution) {
		throw new IntersobException("Tým '".$solution->team_name."' už úkol '".$solution->task_name."' řešil.");
	    }
	    $input = array(
		"id_team" => $form["team"]->getValue(),
		"id_task" => $form["task"]->getValue(),
		"score" => $form["score"]->getValue()
	    );
	    dibi::query("INSERT INTO [solutions]", $input);
	    $this->getPresenter()->redirect($this->getPresenter()->backlink());
	}
	catch(IntersobException $e) {
	    $form->addError($e->getMessage());
	}
    }

    protected function createComponentForm($name) {
	$form = new AppForm($this, $name);
	// Ukoly, ktere muze editovat prave prihlaseny uzivatel
	$tasks = $this->getTasks()
	    ->findBySurveyor(Environment::getUser()->getIdentity()->getName())
	    ->fetchPairs("id_task","task_name");
	$form->addSelect("task", "Úkol:", $tasks)
	    ->addRule(Form::FILLED,"Vyplňte prosím úkol, který tým řešil..");
	// Tymy
	$teams = $this->getTeams()->findAll()->fetchPairs("id_team", "name");

	$form->addSelect("team","Tým:",$teams)
	    ->addRule(Form::FILLED,"Vyplňte prosím tým, který úkol vyřešil.");
	$form->addText("score", "Body:")
	    ->addRule(Form::RANGE, "Body reprezentují pouze přirozená čísla  (%d až %d).",array(1,10000))
	    ->addRule(Form::INTEGER,"Body reprezentují pouze přirozená čísla.");
	$form->addSubmit("solve", "Zapsat řešení");
	$form->onSubmit[] = array($this, 'taskFormSubmitted');

	return $form;
    }

}
