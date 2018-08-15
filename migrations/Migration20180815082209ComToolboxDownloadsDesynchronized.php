<?php

use Hubzero\Content\Migration\Base;

// no direct access
defined('_HZEXEC_') or die();

class Migration20180815082209ComToolboxDownloadsDesynchronized extends Base
{

	static $tableName = '#__toolbox_downloads';

	public function up()
	{
		$tableName = self::$tableName;

		if ($this->db->tableExists($tableName))
		{
			$updateDesynchronized = "ALTER TABLE $tableName
				MODIFY desynchronized tinyint(1)
				DEFAULT 0;";

			$this->db->setQuery($updateDesynchronized);
			$this->db->query();
		}
	}

	public function down()
	{
		$tableName = self::$tableName;

		if ($this->db->tableExists($tableName))
		{
			$reverseDesynchronized = "ALTER TABLE $tableName
				MODIFY desynchronized tinyint(1)
				DEFAULT NULL;";

			$this->db->setQuery($reverseDesynchronized);
			$this->db->query();
		}
	}

}
