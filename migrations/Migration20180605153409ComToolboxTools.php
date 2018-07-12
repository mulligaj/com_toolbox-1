<?php

use Hubzero\Content\Migration\Base;

// no direct access
defined('_HZEXEC_') or die();

class Migration20180605153409ComToolboxTools extends Base
{

	static $tableName = '#__toolbox_tools';

	public function up()
	{
		$tableName = self::$tableName;

		$createTable = "CREATE TABLE $tableName (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(150) NULL DEFAULT NULL,
			`source` text NULL DEFAULT NULL,
			`external_cost` tinyint(1) NULL DEFAULT NULL,
			`duration` int(6) NULL DEFAULT NULL,
			`materials` text NULL DEFAULT NULL,
			`notes` text NULL DEFAULT NULL,
			`learning_objectives` text NULL DEFAULT NULL,
			`kinesthetic` tinyint(1) NULL DEFAULT NULL,
			`subgroup_size` varchar(75) NULL DEFAULT NULL,
			`published` tinyint(1) NULL DEFAULT NULL,
			`archived` tinyint(1) NULL DEFAULT NULL,
			`self_awareness` tinyint(1) NULL DEFAULT NULL,
			`openness` tinyint(1) NULL DEFAULT NULL,
			`communication` tinyint(1) NULL DEFAULT NULL,
			`empathy` tinyint(1) NULL DEFAULT NULL,
			`curiousity` tinyint(1) NULL DEFAULT NULL,
			`worldview` tinyint(1) NULL DEFAULT NULL,
			`denial` tinyint(1) NULL DEFAULT NULL,
			`polarization` tinyint(1) NULL DEFAULT NULL,
			`minimization` tinyint(1) NULL DEFAULT NULL,
			`acceptance` tinyint(1) NULL DEFAULT NULL,
			`self` tinyint(1) NULL DEFAULT NULL,
			`other` tinyint(1) NULL DEFAULT NULL,
			`emotions` tinyint(1) NULL DEFAULT NULL,
			`bridging` tinyint(1) NULL DEFAULT NULL,
			`friendship` tinyint(1) NULL DEFAULT NULL,
			`teamwork` tinyint(1) NULL DEFAULT NULL,
			`mentorship` tinyint(1) NULL DEFAULT NULL,
			`diversity_inclusion` tinyint(1) NULL DEFAULT NULL,
			`leadership` tinyint(1) NULL DEFAULT NULL,
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
