<?php

use Hubzero\Content\Migration\Base;

// no direct access
defined('_HZEXEC_') or die();

class Migration20180712150751ComToolboxLinks extends Base
{

	static $tableName = '#__toolbox_links';

	public function up()
	{
		$tableName = self::$tableName;

		$createTable = "CREATE TABLE $tableName (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`tool_id` int(11) unsigned NOT NULL,
			`text` varchar(255) NOT NULL,
			`url` text NOT NULL,
			`broken` tinyint(1) NULL DEFAULT NULL,
			`created` timestamp NULL DEFAULT NULL,
			`modified` timestamp NULL DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8;";

		if (!$this->db->tableExists($tableName))
		{
			$this->db->setQuery($createTable);
			$this->db->query();
		}
	}

	public function down()
	{
		$tableName = self::$tableName;

		$dropTable = "DROP TABLE $tableName";

		if ($this->db->tableExists($tableName))
		{
			$this->db->setQuery($dropTable);
			$this->db->query();
		}
	}

}
