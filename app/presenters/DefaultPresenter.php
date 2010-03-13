<?php
class DefaultPresenter extends Presenter {
	
	public $logged = FALSE;
	
	public $refresh = TRUE;
	
	protected function beforeRender() {
		$user = Environment::getUser();
		if ($user->isAuthenticated()) {
			$this->logged = TRUE;
		}
    	$this->template->registerFilter('CurlyBracketsFilter::invoke');
	}
	
	public function renderDefault() {
		$sql = "SELECT * FROM [".Intersob::DATABASE_PREFIX.Intersob::DATABASE_VIEW_RESULTS."] GROUP BY teamID ORDER BY score DESC, teamName ASC";
		$res = dibi::query($sql);
		$this->template->tables = array();
		$count = $res->count();
		for ($i = 0; $i*20 < $count; $i++) {
			$table = $res->fetchAll($i*20,20);
			$this->template->tables[] = $table;
		}
		$this->template->columns = array("#", "Tým", "Body");
	}
	
	public function renderDetail() {
		$sql = "SELECT * FROM ".Intersob::DATABASE_PREFIX.Intersob::DATABASE_VIEW_TASKS." ORDER BY taskID";
		$this->template->tasks = dibi::query($sql)->fetchAll();
		$sql = "SELECT * FROM [".Intersob::DATABASE_PREFIX.Intersob::DATABASE_VIEW_RESULTS ."]";
		$rows = dibi::query($sql);
		$this->template->teams = array();
		$before = null;
		while($row = $rows->fetch()) {
			if ($before == null) {
				$before = $row; 
				$tasks = array();
			}
			else if ($row->teamID != $before->teamID) {
				$this->template->teams[] = array(
					"teamID" => $before->teamID,
					"teamName" => $before->teamName,
					"tasks" => $tasks,
					"score" => $before->score
				);
				$before = $row; 
				$tasks = array();
			}
			$tasks[] = array(
				"taskScore" => $row->taskScore,
				"taskAvgScore" => $row->taskAvgScore
			);
		}
	}
	
	public function renderTasks() {
		$sql = "SELECT * FROM ".Intersob::DATABASE_PREFIX.Intersob::DATABASE_VIEW_TASKS." ORDER BY taskID";
		$res = dibi::query($sql);
		$this->template->rows = $res->fetchAll();
		$this->template->columns = array("#","Stanoviště", "Průměr","Nejlepší","Nejhorší","Počet řešitelů");
	}
	
}
