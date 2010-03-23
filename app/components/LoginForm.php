<?php
class LoginForm extends BaseControl
{

    public function  __construct(IComponentContainer $parent = NULL, $name = NULL) {
	parent::__construct($parent, $name);
    }

    protected function init() {}

    public function loginFormSubmitted(Form $form) {
	try {
	    Environment::getUser()->authenticate($form['user_name']->getValue(), $form['password']->getValue());
	    $this->getPresenter()->redirect($this->getPresenter()->backlink());
	} catch (AuthenticationException $e) {
	    $form->addError($e->getMessage());
	}
    }

    public function render() {
	$this->getComponent("form")->render();
    }

    protected function createComponentForm($name) {
	$form = new AppForm($this, $name);
	$form->addText("user_name","Přihlašovací jméno:")
	    ->addRule(Form::FILLED,"Vyplňte prosím přihlašovací jméno.");
	$form->addPassword("password","Heslo:")
	    ->addRule(Form::FILLED,"Vyplňte prosím heslo.");
	$form->addSubmit('login', 'Přihlásit se');
	$form->onSubmit[] = array($this, 'loginFormSubmitted');
	return $form;
    }
}
