<?php
class Teams extends AbstractModel {

    /**
     * @return DibiDataSource
     */
    public function getResults() {
	return $this->getConnection()->dataSource("
	    SELECT *
	    FROM [view_results]
	    GROUP BY [id_team]
	    ORDER BY score DESC, [team_name] ASC
	");
    }

    /**
     * @return DibiDataSource
     */
     public function getResultsDetail() {
	return $this->getConnection()->dataSource("
	    SELECT *
	    FROM [view_results]
	    ORDER BY score DESC, [team_name] ASC, [id_task] ASC
	");
     }

     public function find($id) {
	 $this->checkEmpty($id, "id");
	 return $this->findAll()->where("[id_team] = %i", $id)->fetch();
     }

    /**
     * @return DibiDataSource
     */
    public function findAll() {
	return $this->getConnection()->dataSource("SELECT * FROM [teams]");
    }

}
