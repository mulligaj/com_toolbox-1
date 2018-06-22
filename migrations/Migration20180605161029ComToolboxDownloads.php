<?php

use Hubzero\Content\Migration\Base;

// no direct access
defined('_HZEXEC_') or die();

class Migration20180605161029ComToolboxDownloads extends Base
{

	static $tableName = '#__toolbox_downloads';

	public function up()
	{
		$tableName = self::$tableName;

		$createTable = "CREATE TABLE $tableName (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`tool_id` int(11) unsigned NOT NULL,
			`name` varchar(255) NOT NULL,
			`type` varchar(100) NOT NULL,
			`size` int(11) NULL DEFAULT NULL,
			`desynchronized` tinyint(1) NULL DEFAULT NULL,
			`created` timestamp NULL DEFAULT NULL,
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
