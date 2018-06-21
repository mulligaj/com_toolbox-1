<?php

namespace Components\Toolbox\Helpers;

class MigrationHelper
{

	public static function generateSqlValuesString($data, $multipleRecords = true)
	{
		if ($multipleRecords)
		{
			$sql = self::_generateMultiRecordValuesString($data);
		}
		else
		{
			$sql = self::_generateSingleRecordValuesString($data);
		}

		return $sql;
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
