<?php

use Hubzero\Content\Migration\Base;

// no direct access
defined('_HZEXEC_') or die();

class Migration20180712093918ComToolboxSeedToolsTypes extends Base
{

	static $tableName = '#__toolbox_tools_types';

	public function up()
	{
		$seedFilePath = Component::path('com_toolbox') . '/seed_data/tools_types.csv';
		$tableName = self::$tableName;

		$seedTable = "LOAD DATA LOCAL INFILE"
			. " '$seedFilePath'"
			. " INTO TABLE $tableName"
			. " COLUMNS TERMINATED BY ','"
			. " ENCLOSED BY '\"'"
			. " LINES TERMINATED BY '\n'"
			. " IGNORE 1 LINES"
			. ";";

		if ($this->db->tableExists($tableName))
		{
			$this->db->setQuery($seedTable);

			if ($this->db->query())
			{
				$date = Date::toSql();

				$addCreatedDate = "UPDATE $tableName"
					. " set created='$date'"
					. ";";

				$this->db->setQuery($addCreatedDate);
				$this->db->query();
			}
		}
	}

	public function down()
	{
	}

}
