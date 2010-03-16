<?php
abstract class AbstractModel
{

    /**
     * @var DibiConnection
     */
    private $connection;

    public function __construct(DibiConnection $connection = NULL) {
	if (empty($connection)) {
	    $this->connection = dibi::getConnection();
	}
	else {
	    $this->connection = $connection;
	}
    }

    /**
     *
     * @return DibiConnection
     */
    protected final function getConnection() {
	return $this->connection;
    }

    protected final function checkEmpty($param, $name) {
	if (empty($param)) {
	    throw new NullPointerException("The paramater [$name] is empty.");
	}
    }

}

