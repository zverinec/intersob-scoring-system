<?php
class DefaultPresenter extends BasePresenter {
	
	public $refresh = TRUE;
	
	public function renderDefault() {
		$res = $this->getTeams()->getResults()->getResult();
		$this->template->tables = array();
		$count = $res->count();
		for ($i = 0; $i*20 < $count; $i++) {
			$table = $res->fetchAll($i*20,20);
			$this->template->tables[] = $table;
		}
		$this->template->columns = array("#", "Tým", "Body");
	}
	
	public function renderDetail() {
		$this->template->tasks = $this->getTasks()->findAll();
		$this->getTemplate()->teams = $this->getTeams()->getResultsDetail()->fetchAssoc("id_team,=,id_task");		
	}
	
	public function renderTasks() {
		$this->template->rows = $this->getTasks()->findAll();
		$this->template->columns = array("#","Stanoviště", "Průměr","Nejlepší","Nejhorší","Počet řešitelů");
	}
	
}
