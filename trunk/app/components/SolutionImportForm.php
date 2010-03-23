<?php
class SolutionImportForm extends BaseControl
{

    protected function init() {}

    public function formSubmitted(Form $form) {
	$values	    = $form->getValues();
	$temporary  = $values["file"]->getTemporaryFile();
	$import	    = simplexml_load_file($temporary);
	if (empty($import["task-id"])) {
	    $this->getPresenter()->flashMessage("Nebylo zadáno číslo úkolu.", "error");
	    return;
	}
	$task	    = $this->getTasks()->find($import["task-id"]);
	if (empty($task)) {
	    $this->getPresenter()->flashMessage("Úkol číslo ".$import["task-id"]." neexistuje.", "error");
	    return;
	}
	try {
	    if ($values["transform"]) {
		$import = $this->transform($import, $values["limit"], empty($values["const"]) ? 0 : $values["const"] );
	    }
	    dibi::begin();
	    $this->getSolutions()->delete()->where("id_task = %i", (int)$import["task-id"])->execute();
	    foreach($import AS $team) {
		if (empty($team->score)) {
		    continue;
		}
		dibi::insert("solutions", array(
		    "id_team"   => (int)($team->id),
		    "id_task"   => (int)($import["task-id"]),
		    "score"	=> (int)($team->score)
		))->execute();
	    }
	    dibi::commit();
	    $this->getPresenter()->flashMessage("Import úspěšně proběhl.", "success");
	    $this->getPresenter()->redirect("Admin:tasks");
	}
	catch(DibiException $e) {
	    Debug::paintBlueScreen($e);
	    $this->getPresenter()->flashMessage("Při importu dat se stala neočekávaná chyba: " . $e->getMessage());
	    try {
		dibi::rollback();
	    }
	    catch(Exception $e) {}
	}
    }

    public function render() {
	$this->getComponent("importForm")->render();
    }

    protected function transform($import, $limit, $const) {
	// Find maximum
	$maximum = 0;
	foreach($import AS $team) {
	    if ($team->score > $maximum) {
		$maximum = (int)$team->score;
	    }
	}
	foreach($import AS $team) {
	    if (empty($team->score)) {
		continue;
	    }
	    $team->score = round(($team->score/$maximum) * $limit) + $const;
	}
	return $import;
    }

    protected function createComponentImportForm($name) {
	$form = new AppForm($this, $name);
	$form->addFile("file", "Soubor s importovanými daty:")
	    ->addRule(Form::FILLED, "Soubor s importovanými daty není k dispozici.");
	$form->addCheckbox("transform", "Transformovat");
	$form->addText("limit", "Limit pro transformaci:")
	    ->addConditionOn($form["transform"], Form::FILLED)
	    ->addRule(Form::FILLED, "Pokud chcete transformovat, musíte vyplnit limit.");
	$form->addText("const", "Konstantí část transformace:");
	$form->addSubmit("import_submit", "Importovat");
	$form->onSubmit[] = array($this, "formSubmitted");
	return $form;
    }

}

