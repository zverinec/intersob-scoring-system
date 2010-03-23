<?php
/**
 *		Adminitration part of web. Users can insert and update solutions of their tasks.
 * @author 	Jan Papousek
 * @version 	2009-02-09
 */
class AdminPresenter extends AuthPresenter {

    public $refresh = FALSE;

    /** @persistent */
    public $team;

    /**
     *	    The page where the logged user is logged out.
     */
    public function renderLogout() {
	Environment::getUser()->signOut();
	$this->redirect('Default:');
    }

    /**
     *	    The page with tasks and team solutions.
     */
    public function renderTasks() {}


    public function renderGenerateImport() {
	header ("content-type: text/xml");
	$this->getTemplate()->teams = $this->getTeams()->findAll();
    }

    public function renderTeams($teamID) {
	if (!empty($teamID)) {
	    $this->team = $teamID;
	}
	$this->getTemplate()->team = $this->getTeams()->find($this->team);
    }


    protected function createComponentTaskForm($name) {
	return new TaskForm($this, $name);
    }

    protected function createComponentTaskHistory($name) {
	$list = new SolutionFormList($this, $name);
	$list->setUp($this->getSolutions()->findLastBySurveyor(Environment::getUser()->getIdentity()->getName()));
	return $list;
    }

    protected function createComponentTeamSolutions($name) {
	$list = new SolutionFormList($this, $name);
	$list->setUp($this->getSolutions()->findByTeam($this->team));
	return $list;
    }

    protected function createComponentSolutionImportForm($name) {
	return new SolutionImportForm($this, $name);
    }

    protected function createComponentTeamForm($name) {
	return new TeamForm($this, $name);
    }
}