<?php
class Solutions extends AbstractModel
{

    /**
     * @return DibiFluent
     */
    public function delete() {
	return $this->getConnection()->delete("solutions");
    }

    public function find($id) {
	$this->checkEmpty($id, "id");
	return $this->getConnection()
	    ->query("SELECT * FROM [view_solutions] WHERE [id_solution] = %i", $id)
	    ->fetch();
    }

    public function findByTeam($team) {
	$this->checkEmpty($team, "team");
	return $this->getConnection()->dataSource("
	    SELECT *
	    FROM [view_solutions]
	    WHERE id_team = %i", $team
	);
    }

    public function findByTeamAndTask($team, $task) {
	$this->checkEmpty($team, "team");
	$this->checkEmpty($task, "task");
	return $this->getConnection()->query("
	    SELECT *
	    FROM [view_solutions]
	    WHERE id_team = %i", $team,"
	    AND id_task = %i",$task
	)->fetch();
    }

    public function findLastBySurveyor($name) {
	$this->checkEmpty($name, "name");
	return $this->getConnection()->dataSource("
	    SELECT *
	    FROM [view_solutions]
	    INNER JOIN [surveyors] USING ([id_task])
	    INNER JOIN [users] USING([id_user])
	    WHERE [users].[name] = %s", $name,"
	    GROUP BY [view_solutions].[id_solution]
	");
    }

    /**
     * @return DibiFluent
     */
    public function update(array $values) {
	$this->checkEmpty($values, "values");
	return $this->getConnection()->update("solutions", $values);
    }

}
