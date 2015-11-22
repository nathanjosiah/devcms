<?php

namespace DevCms\Resolver;

use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
class DbResolver {
	protected $sql,$resultSetPrototype,$tableName;
	public function __construct($table_name,Sql $sql,ResultSetInterface $result_set_prototype) {
		$this->sql = $sql;
		$this->resultSetPrototype = $result_set_prototype;
		$this->tableName = $table_name;
	}
	/**
	 * @param string $id
	 * @return \DevCms\Entity\ContentEntity
	 */
	public function resolveModelWithId($id) {
		$select = new Select($this->tableName);
		$select->where->equalTo('id',$id);

		$result = $this->sql->prepareStatementForSqlObject($select)->execute();

		$result_set = clone $this->resultSetPrototype;
		$result_set->initialize($result);

		$model = $result_set->current();
		return $model;
	}

	/**
	 * @param string $id The id to set
	 * @param string $content The content to set it to
	 */
	public function setContent($id,$content) {
		if($this->resolveModelWithId($id)) {
			$update = new Update($this->tableName);
			$update->set(['html'=>$content]);
			$update->where->equalTo('id',$id);
			$this->sql->prepareStatementForSqlObject($update)->execute();
		}
		else {
			$insert = new Insert($this->tableName);
			$insert->values(['html'=>$content,'id'=>$id]);
			$this->sql->prepareStatementForSqlObject($insert)->execute();
		}
		return null;
	}
}