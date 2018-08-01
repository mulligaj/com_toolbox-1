<?php

use Hubzero\Content\Migration\Base;

// no direct access
defined('_HZEXEC_') or die();

class Migration20180801090401ComToolboxSeedToolTypes extends Base
{

	static $tableName = '#__toolbox_tool_types';

	public function up()
	{
		$tableName = self::$tableName;

		$seedTable = "INSERT INTO $tableName"
			. " (`id`,`description`)"
			. " VALUES (1, 'Experiential Activity'), (2, 'Assessment'),"
			. " (3, 'Media'), (4, 'Reflection')"
			. ";";

		if ($this->db->tableExists($tableName))
		{
			$this->db->setQuery($seedTable);
			$this->db->query();
		}
	}

	public function down()
	{
	}

}
