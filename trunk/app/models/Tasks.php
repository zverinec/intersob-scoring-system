<?php
class Tasks extends AbstractModel
{

    public function find($id) {
	$this->checkEmpty($id, "id");
	return $this->findAll()->where("[id_task] = %i", $id);
    }

    /**
     * @return DibiDataSource
     */
     public function findAll() {
	return $this->getConnection()->dataSource("SELECT * FROM [view_tasks] ORDER BY id_task");
     }

    /**
     * @return DibiDataSource
     */
    public function findBySurveyor($name) {
	$this->checkEmpty($name, "name");
	return $this->getConnection()->dataSource("
	    SELECT *
	    FROM [view_tasks_and_surveyors]
	    WHERE user_name = %s",$name,"
	    ORDER BY id_task
	");
    }

}
