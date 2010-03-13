<?php
/**
 * 				Adminitration part of web. Users can insert and update solutions of their tasks.
 * @author 		Jan Papousek
 * @version 	2009-02-09
 */
class AdminPresenter extends Presenter {

/** @persistent */
    public $backlink = array();


    public $logged = FALSE;

    public $refresh = FALSE;

    protected function beforeRender() {
	$user = Environment::getUser();
	if ($user->isAuthenticated()) {
	    $this->logged = TRUE;
	}
	$this->template->registerFilter('CurlyBracketsFilter::invoke');
    }

    /**
     *	    The page with login form.
     * @param   string
     */
    public function actionLogin() {
	$form = new AppForm($this, 'form');
	$form->addText("userName","Přihlašovací jméno:")
	    ->addRule(Form::FILLED,"Vyplňte prosím přihlašovací jméno.");
	$form->addPassword("password","Heslo:")
	    ->addRule(Form::FILLED,"Vyplňte prosím heslo.");
	$form->addSubmit('login', 'Přihlásit se');
	$form->onSubmit[] = array($this, 'loginFormSubmitted');
	$this->template->form = $form;
    }

    /**
     *	    The page where the logged user is logged out.
     */
    public function renderLogout() {
	Environment::getUser()->signOut();
	$this->redirect('Default:');
    }

    /**
     *	    The page where the login form is executed.
     *
     * @param   Form    Login form.
     */
    public function loginFormSubmitted($form) {
	try {
	    Environment::getUser()->authenticate($form['userName']->getValue(), $form['password']->getValue());
	    $this->redirect('Admin:tasks');

	} catch (AuthenticationException $e) {
	    $form->addError($e->getMessage());
	}
    }

    /**
     *			The page with tasks and team solutions.
     */
    public function presentTasks() {
	$user = Environment::getUser();
	if ($user->isAuthenticated()) {
	    $this->backlink[0] = "Admin:tasks";
	    $this->backlink[1] = null;
	    // Ukoly, ktere muze editovat prave prihlaseny uzivatel
	    $sql = "
		    SELECT *
		    FROM ".Intersob::DATABASE_PREFIX.Intersob::DATABASE_VIEW_TASKS_AND_SURVEYORS."
		    WHERE userName = '".$user->getIdentity()->getName()."'
		    ORDER BY taskName
		";
	    $tasks = dibi::query($sql)->fetchPairs("taskID","taskName");
	    $form = new AppForm($this, 'form');
	    $form->addSelect("task","Splněný úkol:",$tasks)
		->addRule(Form::FILLED,"Vyplňte prosím úkol, o který se jedná.");
	    // Tymy
	    $sql = "
		SELECT *
		FROM ".Intersob::DATABASE_PREFIX.Intersob::DATABASE_TABLE_TEAMS."
		ORDER BY name
	    ";
	    $teams = dibi::query($sql)->fetchPairs("id","name");
	    $form->addSelect("team","Tým:",$teams)
		->addRule(Form::FILLED,"Vyplňte prosím tým, který úkol vyřešil.");
	    $form->addText("score", "Body:")
		->addRule(Form::RANGE, "Body reprezentují pouze přirozená čísla  (%d až %d).",array(1,10000))
		->addRule(Form::INTEGER,"Body reprezentují pouze přirozená čísla.");
	    $form->addSubmit("solve", "Zapsat řešení");
	    $form->onSubmit[] = array($this, 'taskFormSubmitted');
	    $this->template->form = $form;

	    $selectTeamForm = new AppForm($this,'selectTeamForm');
	    $sql = "
                SELECT *
                FROM ".Intersob::DATABASE_PREFIX.Intersob::DATABASE_TABLE_TEAMS."
                ORDER BY name
            ";
	    $selectTeamForm->addSelect("team", "Název týmu: ", $teams);
	    $selectTeamForm->addSubmit("selectTeam", "Vyber tým k editaci");
	    $selectTeamForm->onSubmit[] = array($this,"selectTeamFormSubmitted");
	    $this->template->selectTeamForm = $selectTeamForm;

	    // Naposledy pridane body
	    $rows = dibi::query(
		"SELECT *
		 FROM ".Intersob::DATABASE_PREFIX.Intersob::DATABASE_VIEW_SOLUTIONS."
		 WHERE taskID in %l", array_keys($tasks) ,
		"LIMIT 0,30"
	    );
	    $usersTasks = array_keys($tasks);
	    $this->template->solutions = array();
	    while ($row = $rows->fetch()) {
		if (in_array($row->taskID,$usersTasks)) {
		    $form = new AppForm($this,"form" . $row->solutionID);
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
	else {
	    $this->redirect("Admin:login");
	}
    }

    public function selectTeamFormSubmitted($form) {
	$this->redirect("Admin:teams",$form["team"]->getValue());
    }

    public function presentTeams($teamID) {
	$this->backlink[0] = "Admin:teams";
	$this->backlink[1] = $teamID;
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


    /**
     *	 				The page where the solution of the task by the team is inserted.
     *
     * @param 	Form	Task form.
     */
    public function taskFormSubmitted($form) {
	try {
	    $row = dibi::query("SELECT * FROM [".Intersob::DATABASE_PREFIX.Intersob::DATABASE_VIEW_SOLUTIONS."]
				WHERE teamID = %i AND taskID = %i",$form["team"]->getValue(),$form["task"]->getValue())->fetch();
	    if ($row) {
		throw new IntersobException("Tým '".$row->teamName."' už úkol '".$row->taskName."' řešil.");
	    }
	    $input = array(
		"teamID" => $form["team"]->getValue(),
		"taskID" => $form["task"]->getValue(),
		"score" => $form["score"]->getValue()
	    );
	    dibi::query("INSERT INTO ".Intersob::DATABASE_PREFIX.Intersob::DATABASE_TABLE_SOLUTIONS, $input);
	    $this->redirect("Admin:tasks");
	}
	catch(IntersobException $e) {
	    $form->addError($e->getMessage());
	}
    }

    public function taskEditFormSubmitted($form) {
	try {
	    $row = dibi::query("SELECT * FROM [".Intersob::DATABASE_PREFIX.Intersob::DATABASE_VIEW_SOLUTIONS."] WHERE solutionID=%i",$form["solution"]->getValue())->fetch();
	    if (!$row) {
		throw new IntersobException("Snažíte se změnit něco, co neexistuje.");
	    }
	    if ($form["edit"]->getValue()) {
		$row = dibi::query("SELECT * FROM [".Intersob::DATABASE_PREFIX.Intersob::DATABASE_VIEW_SOLUTIONS."]
					WHERE teamID = %i AND taskID = %i",$form["team"]->getValue(),$form["task"]->getValue())->fetch();
		if (($row) && ($row->solutionID != $form["solution"]->getValue())) {
		    throw new IntersobException("Tým '".$row->teamName."' už úkol '".$row->taskName."' řešil.");
		}
		$changes = array(
		    "teamID" => $form["team"]->getValue(),
		    "taskID" => $form["task"]->getValue(),
		    "score" => $form["score"]->getValue()
		);
		dibi::query("UPDATE [".Intersob::DATABASE_PREFIX.Intersob::DATABASE_TABLE_SOLUTIONS."]
					SET",$changes,"WHERE id = %i",$form["solution"]->getValue());
	    }
	    else if ($form["delete"]->getValue()) {
		    dibi::query("DELETE FROM [".Intersob::DATABASE_PREFIX.Intersob::DATABASE_TABLE_SOLUTIONS."] WHERE id=%i",$form["solution"]->getValue());
		}
	    $this->redirect($this->backlink[0],$this->backlink[1]);
	}
	catch(IntersobException $e) {
	    $form->addError($e->getMessage());
	}
    }
}