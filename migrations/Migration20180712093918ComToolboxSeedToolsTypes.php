<?php

use Hubzero\Content\Migration\Base;

// no direct access
defined('_HZEXEC_') or die();

use Components\Toolbox\Helpers\CsvHelper;
use Components\Toolbox\Helpers\SqlHelper;

class Migration20180712093918ComToolboxSeedToolsTypes extends Base
{

	static $tableName = '#__toolbox_tools_types';

	public function up()
	{
		$toolboxPath = Component::path('com_toolbox');
		require_once "$toolboxPath/helpers/csvHelper.php";
		require_once "$toolboxPath/helpers/sqlHelper.php";

		$tableName = self::$tableName;
		$seedFilePath =  "$toolboxPath/seed_data/tools_types.csv";

		$columnHeaders = CsvHelper::columnTitles($seedFilePath);
		$columnHeaders[] = 'created';
		$columnHeaders = array_map(function($attribute) {
			return "`$attribute`";
		}, $columnHeaders);
		$attributesString = '(' . implode($columnHeaders, ',') .  ')';

		$toolsTypes = CsvHelper::rowsToArrays($seedFilePath);
		$toolsTypes = SqlHelper::updateRecords($toolsTypes, ['created' => \Date::toSql()]);
		$valuesString = SqlHelper::generateValuesString($toolsTypes);

		$seedTable = "INSERT INTO $tableName "
			. $attributesString
			. ' VALUES'
			. $valuesString
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
