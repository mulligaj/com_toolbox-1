<?php

use Hubzero\Content\Migration\Base;

// no direct access
defined('_HZEXEC_') or die();

use Components\Toolbox\Helpers\CsvHelper;
use Components\Toolbox\Helpers\SqlHelper;

class Migration20180712104307ComToolboxSeedToolsRelationships extends Base
{

	static $tableName = '#__toolbox_tools_relationships';

	public function up()
	{
		$toolboxPath = Component::path('com_toolbox');
		require_once "$toolboxPath/helpers/csvHelper.php";
		require_once "$toolboxPath/helpers/sqlHelper.php";

		$tableName = self::$tableName;
		$seedFilePath = "$toolboxPath/seed_data/tools_relationships.csv";

		$columnHeaders = CsvHelper::columnTitles($seedFilePath);
		$columnHeaders[] = 'created';
		$columnHeaders = array_map(function($attribute) {
			return "`$attribute`";
		}, $columnHeaders);
		$attributesString = '(' . implode($columnHeaders, ',') .  ')';

		$toolsRelationships = CsvHelper::rowsToArrays($seedFilePath);
		$toolsRelationships = SqlHelper::updateRecords($toolsRelationships, ['created' => \Date::toSql()]);
		$valuesString = SqlHelper::generateValuesString($toolsRelationships);

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
