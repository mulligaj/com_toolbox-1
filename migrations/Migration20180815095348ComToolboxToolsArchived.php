<?php

use Hubzero\Content\Migration\Base;

// no direct access
defined('_HZEXEC_') or die();

class Migration20180815095348ComToolboxToolsArchived extends Base
{

	static $tableName = '#__toolbox_tools';

	public function up()
	{
		$tableName = self::$tableName;

		if ($this->db->tableExists($tableName))
		{
			$updateArchived = "ALTER TABLE $tableName
				MODIFY archived tinyint(1)
				DEFAULT 0;";

			$this->db->setQuery($updateArchived);
			$this->db->query();
		}
	}

	public function down()
	{
		$tableName = self::$tableName;

		if ($this->db->tableExists($tableName))
		{
			$reverseArchived = "ALTER TABLE $tableName
				MODIFY archived tinyint(1)
				DEFAULT NULL;";

			$this->db->setQuery($reverseArchived);
			$this->db->query();
		}
	}

}
