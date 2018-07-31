<?php

use Hubzero\Content\Migration\Base;

// no direct access
defined('_HZEXEC_') or die();

class Migration20180731105022ComToolboxToolTypesArchive extends Base
{

	static $tableName = '#__toolbox_tool_types';

	public function up()
	{
		$tableName = self::$tableName;

		if ($this->db->tableExists($tableName))
		{
			$addArchived = "ALTER TABLE $tableName
				ADD archived tinyint(1)
				DEFAULT 0;";

			$this->db->setQuery($addArchived);
			$this->db->query();
		}
	}

	public function down()
	{
		$tableName = self::$tableName;

		if ($this->db->tableExists($tableName))
		{
			$removeArchived = "ALTER TABLE $tableName
				DROP COLUMN archived;";

			$this->db->setQuery($removeArchived);
			$this->db->query();
		}
	}

}
