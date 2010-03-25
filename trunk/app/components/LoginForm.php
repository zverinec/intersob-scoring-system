<?php
class LoginForm extends BaseControl
{

    public function  __construct(IComponentContainer $parent = NULL, $name = NULL) {
	parent::__construct($parent, $name);
    }

    protected function init() {}

    public function loginFormSubmitted(Form $form) {
	try {
	    $values = $form->getValues();
	    Environment::getUser()->authenticate($values['user_name'], $values['password']);
	    $this->getPresenter()->redirect($this->getPresenter()->backlink());
	} catch (AuthenticationException $e) {
	    // HACK: http://forum.nettephp.com/cs/2319-pri-zachyceni-vyjimky-bila-obrazovka?pid=20859#p20859
	    if ($e->getCode() == IAuthenticator::IDENTITY_NOT_FOUND) {
		echo "Uzivale neexistuje.";
	    }
	    else {
		echo "Spatne heslo";
	    }
	    die;
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
