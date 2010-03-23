<?php
class AuthPresenter extends BasePresenter
{

    private $backlink = array();

    protected function startUp() {
	if (!Environment::getUser()->isAuthenticated()) {
	    $this->getTemplate()->setFile(APP_DIR . "/templates/Auth/login.phtml");
	}
    }

    protected function createComponentLoginForm($name) {
	return new LoginForm($this, $name);
    }
}
