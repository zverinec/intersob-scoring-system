<?php
/**
 *		Adminitration part of web. Users can insert and update solutions of their tasks.
 * @author 	Jan Papousek
 * @version 	2009-02-09
 */
class AdminPresenter extends AuthPresenter {

    public $refresh = FALSE;

    /**
     *	    The page where the logged user is logged out.
     */
    public function renderLogout() {
	Environment::getUser()->signOut();
	$this->redirect('Default:');
    }

    /**
     *			The page with tasks and team solutions.
     */
    public function renderTasks() {}


    public function renderGenerateImport() {
	$this->getTemplate()->teams = dibi::query("
	    SELECT *
	    FROM [".Intersob::DATABASE_PREFIX.Intersob::DATABASE_TABLE_TEAMS."]"
	);
    }

    public function renderTeams($teamID) {
	$this->template->team = dibi::query("
            SELECT * FROM ".Intersob::DATABASE_PREFIX.Intersob::DATABASE_TABLE_TEAMS."
            WHERE id = %i
        ",$teamID)->fetch();
	$user = Environment::getUser();
	$sql = "
			SELECT *
			FROM ".Intersob::DATABASE_PREFIX.Intersob::DATABASE_TABLE_TEAMS."
			ORDER BY name
		";
	$teams = dibi::query($sql)->fetchPairs("id","name");
	$sql = "
            SELECT *
    		FROM ".Intersob::DATABASE_PREFIX.Intersob::DATABASE_VIEW_TASKS_AND_SURVEYORS."
			WHERE userName = '".$user->getIdentity()->getName()."'
			ORDER BY taskName
		";
	$tasks = dibi::query($sql)->fetchPairs("taskID","taskName");
	$usersTasks = array_keys($tasks);
	$rows = dibi::query("
            SELECT * FROM ".Intersob::DATABASE_PREFIX.Intersob::DATABASE_VIEW_SOLUTIONS."
            WHERE teamID = %i
        ",$teamID);
	$this->template->solutions = array();
	while ($row = $rows->fetch()) {
	    if (in_array($row->taskID,$usersTasks)) {
		$form = new AppForm($this,"form" . $row->solutionID);
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


    protected function createComponentTaskForm($name) {
	return new TaskForm($this, $name);
    }

    protected function createComponentTaskHistory($name) {
	return new TaskHistory($this, $name);
    }

    protected function createComponentTeamForm($name) {
	return new TeamForm($this, $name);
    }
}