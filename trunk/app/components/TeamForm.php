<?php
class TeamForm extends BaseControl {

    protected function init() {}

    public function render() {
	$this->getComponent("form")->render();
    }

    public function formSubmitted(Form $form) {
	$this->getPresenter()->redirect("Admin:teams",$form["team"]->getValue());
    }

    protected function createComponentForm($name) {
	$form = new AppForm($this, $name);
	$teams = $this->getTeams()->findAll()->fetchPairs("id_team", "name");
	$form->addSelect("team", "Název týmu: ", $teams);
	$form->addSubmit("selectTeam", "Vyber tým k editaci");
	$form->onSubmit[] = array($this,"formSubmitted");
	return $form;
    }

}