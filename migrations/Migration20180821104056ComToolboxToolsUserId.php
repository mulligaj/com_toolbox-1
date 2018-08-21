<?php

use Hubzero\Content\Migration\Base;

// no direct access
defined('_HZEXEC_') or die();

class Migration20180821104056ComToolboxToolsUserId extends Base
{

	static $tableName = '#__toolbox_tools';

	public function up()
	{
		$tableName = self::$tableName;

		if ($this->db->tableExists($tableName))
		{
			$addUserId = "ALTER TABLE $tableName
				ADD COLUMN user_id int(11) unsigned
				DEFAULT NULL;";

			$this->db->setQuery($addUserId);
			$this->db->query();
		}
	}

	public function down()
	{
		$tableName = self::$tableName;

		if ($this->db->tableExists($tableName))
		{
			$removeUserId = "ALTER TABLE $tableName
				DROP COLUMN user_id;";

			$this->db->setQuery($removeUserId);
			$this->db->query();
		}
	}

}
