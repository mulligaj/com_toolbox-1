<?php

namespace Components\Toolbox\Helpers;

class MigrationHelper
{

	public static function generateSqlValuesString($data, $multipleRecords = true)
	{
		if ($multipleRecords)
		{
			return self::_generateMultiRecordValuesString($data);
		}
		else
		{
			return self::_generateSingleRecordValuesString($data);
		}
	}

	protected static function _generateMultiRecordValuesString($entities)
	{
		$sql = "";

		foreach ($entities as $entity)
		{
			$sql .= self::_generateSingleRecordValuesString($entity) . ",";
		}

		return rtrim($sql, ",");
	}

	protected static function _generateSingleRecordValuesString($attributes)
	{
		$sql = "(";

		foreach ($attributes as $attribute)
		{
			$sql .= "'$attribute'" . ",";
		}

		return rtrim($sql, ",") . ")";
	}

}
