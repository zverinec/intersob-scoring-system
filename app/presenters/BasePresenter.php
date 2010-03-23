<?php
class BasePresenter extends Presenter
{

    private $solutions;

    private $tasks;

    private $teams;

    protected function createTemplate() {
	$template = parent::createTemplate();
	
	$this->oldLayoutMode = false;

	$user = Environment::getUser();
	if ($user->isAuthenticated()) {
	    $template->logged = TRUE;
	}
	else {
	    $template->logged = FALSE;
	}
	$template->registerFilter('CurlyBracketsFilter::invoke');
	return $template;
    }

    /**
     * @return Solutions
     */
    protected final function getSolutions() {
	if (empty($this->solutions)) {
	    $this->solutions = new Solutions();
	}
	return $this->solutions;
    }

    /**
     * @return Tasks
     */
    protected final function getTasks() {
	if (empty($this->tasks)) {
	    $this->tasks = new Tasks();
	}
	return $this->tasks;
    }

    /**
     *
     * @return Teams
     */
    protected final function getTeams() {
	if (empty ($this->teams)) {
	    $this->teams = new Teams();
	}
	return $this->teams;
    }

}


