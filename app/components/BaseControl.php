<?php
abstract class BaseControl extends Control
{
    private $solutions;

    private $tasks;

    private $teams;


    public function  __construct(IComponentContainer $parent = NULL, $name = NULL) {
	parent::__construct($parent, $name);
	$this->init();
    }

    protected function createTemplate() {
	$template = new Template();

	$template->setFile(APP_DIR . "/templates/components/" . $this->getName() . ".phtml");

	$template->registerFilter('CurlyBracketsFilter::invoke');

	return $template;
    }

    protected abstract function init();

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

