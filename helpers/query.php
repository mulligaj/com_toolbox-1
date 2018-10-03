<?php
/*
 * HUBzero CMS
 *
 * Copyright 2005-2015 HUBzero Foundation, LLC.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Anthony Fuentes <fuentesa@purdue.edu>
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Components\Toolbox\Helpers;

$toolboxPath = Component::path('com_toolbox');

require_once "$toolboxPath/models/tool.php";

use Components\Toolbox\Models\Tool;
use Hubzero\Session;
use Hubzero\Utility\Arr;

class Query
{

	/*
	 * Default session namespace
	 *
	 * @var   string
	 */
	protected static $_sessionNamespace = 'toolbox';

	/*
	 * Default session key
	 *
	 * @var   string
	 */
	protected static $_sessionKey = 'query';

	/*
	 * Set of attributes a query will accept
	 *
	 * @var   string
	 */
	protected static $_attributesWhitelist = [
		'self_awareness' => 0,
		'openness' => 0,
		'communication' => 0,
		'empathy' => 0,
		'curiosity' => 0,
		'worldview' => 0,
		'denial' => 0,
		'polarization' => 0,
		'minimization' => 0,
		'acceptance' => 0,
		'self' => 0,
		'other' => 0,
		'emotions' => 0,
		'bridging' => 0,
		'friendship' => 0,
		'teamwork' => 0,
		'mentorship' => 0,
		'diversity_inclusion' => 0,
		'leadership' => 0,
		'subgroup_size' => 0,
		'external_cost' => null,
		'duration_max' => null,
		'duration_min' => null,
		'typesIds' => [],
		'kinesthetic' => null
	];

	/*
	 * Returns query wrapper object
	 *
	 * @return   object
	 */
	public static function getCurrent()
	{
		$queryData = static::_getSessionQueryData();

		$query = new static($queryData);

		return $query;
	}

	/*
	 * Retrieves query data from session
	 *
	 */
	protected static function _getSessionQueryData()
	{
		$sessionNamespace = self::$_sessionNamespace;
		$sessionKey = self::$_sessionKey;

		$queryData = Session::get($sessionKey, [], $sessionNamespace);

		return $queryData;
	}

	/*
	 * Returns attribute whitelist
	 *
	 * @param    bool    $withoutDefaults   Indicates whether defaults should be omitted
	 * @return   array
	 */
	protected static function _getAttributesWhitelist($withoutDefaults = true)
	{
		$attributesWhitelist = self::$_attributesWhitelist;

		if ($withoutDefaults)
		{
			$attributesWhitelist = array_keys($attributesWhitelist);
		}

		return $attributesWhitelist;
	}

	/*
	 * Constructor function
	 *
	 * @return   object
	 */
	public function __construct($args = [])
	{
		$attributesWhitelist = self::_getAttributesWhitelist(false);

		foreach ($attributesWhitelist as $attribute => $default)
		{
			$this->$attribute = Arr::getValue($args, $attribute, $default);
		}

		$this->errors = [];
	}

	/*
	 * Sets data on the Query instance
	 *
	 * @param    array   $attributes   Data to update the query with
	 * @return   void
	 */
	public function set($attributes)
	{
		$whitelist = $this->_getAttributesWhitelist();
		$filtered = Arr::filterKeys($attributes, $whitelist);

		foreach ($filtered as $attribute => $value)
		{
			$this->$attribute = $value;
		}
	}

	/*
	 * Updates query instance's typesIds attribute after validation
	 *
	 * @param    array   $data   Data to update the query with
	 * @return   void
	 */
	public function setType($data)
	{
		$key = 'typesIds';

		if (!isset($data[$key]) || empty($data[$key]))
		{
			$this->_addError('COM_TOOLBOX_GUIDED_QUERY_TYPE_REQUIRED');
			$typesIds = [];
		}
		else
		{
			$typesIds = $data[$key];
		}

		$this->set([$key => $typesIds]);
	}

	/*
	 * Updates query instance's kinesthetic attribute after validation
	 *
	 * @param    array   $data   Data to update the query with
	 * @return   void
	 */
	public function setKinesthetic($data)
	{
		$key = 'kinesthetic';

		if (!isset($data[$key]))
		{
			$this->_addError('COM_TOOLBOX_GUIDED_QUERY_KINESTHETIC_REQUIRED');
			$kinesthetic = 0;
		}
		else
		{
			$kinesthetic = $data[$key];
		}

		$this->set([$key => $kinesthetic]);
	}

	/*
	 * Sets AACU data on the Query instance
	 *
	 * @param    array   $data   Data to update the query with
	 * @return   void
	 */
	public function setAacu($data)
	{
		$aacuAttributes = $this->_getAacuAttributesList();
		$aacuData = Arr::filterKeys($data, $aacuAttributes);
		$selectedSum = array_sum(array_values($aacuData));

		if ($selectedSum <= 0)
		{
			$this->_addError('COM_TOOLBOX_GUIDED_QUERY_AACU_REQUIRED_ERROR');
		}
		elseif ($selectedSum >= count($aacuAttributes))
		{
			$this->_addError('COM_TOOLBOX_GUIDED_QUERY_AACU_NOT_ALL');
		}

		$this->set($aacuData);
	}

	/*
	 * Returns AACU attributes' names
	 *
	 * @return   array
	 */
	protected function _getAacuAttributesList()
	{
		$aacuAttributes = [
			'self_awareness',
			'openness',
			'communication',
			'empathy',
			'curiosity',
			'worldview'
		];

		return $aacuAttributes;
	}

	/*
	 * Sets IDC data on the Query instance
	 *
	 * @param    array   $data   Data to update the query with
	 * @return   void
	 */
	public function setIdc($data)
	{
		$idcAttributes = $this->_getIdcAttributesList();
		$idcData = Arr::filterKeys($data, $idcAttributes);

		$this->set($idcData);
	}

	/*
	 * Returns IDC attributes' names
	 *
	 * @return   array
	 */
	protected function _getIdcAttributesList()
	{
		$idcAttributes = [
			'denial',
			'polarization',
			'minimization',
			'acceptance'
		];

		return $idcAttributes;
	}

	/*
	 * Changes all values to bool true if all are false
	 *
	 * @param    array   $data   Data to update the query with
	 * @return   void
	 */
	public function setBergs($data)
	{
		$bergsAttributes = $this->_getBergsAttributesList();
		$bergsData = Arr::filterKeys($data, $bergsAttributes);

		$this->set($bergsData);
	}

	/*
	 * Returns Berg's attributes' names
	 *
	 * @return   array
	 */
	protected function _getBergsAttributesList()
	{
		$bergsAttributes = [
			'self',
			'other',
			'emotions',
			'bridging'
		];

		return $bergsAttributes;
	}

	/*
	 * Changes all values to bool true if all are false
	 *
	 * @param    array   $data   Data to update the query with
	 * @return   void
	 */
	public function setOtherSkills($data)
	{
		$otherAttributes = $this->_getOtherAttributesList();
		$otherData = Arr::filterKeys($data, $otherAttributes);

		$this->set($otherData);
	}

	/*
	 * Returns Other Skills attributes' names
	 *
	 * @return   array
	 */
	protected function _getOtherAttributesList()
	{
		$otherAttributes = [
			'friendship',
			'teamwork',
			'mentorship',
			'diversity_inclusion',
			'leadership'
		];

		return $otherAttributes;
	}

	/*
	 * Updates query instance's subgroup_size attribute after validation
	 *
	 * @param    array   $data   Data to update the query with
	 * @return   void
	 */
	public function setSubgroupSize($data)
	{
		$key = 'subgroup_size';

		if (!isset($data[$key]) || empty($data[$key]))
		{
			$this->_addError('COM_TOOLBOX_GUIDED_QUERY_SUBGROUP_SIZE_REQUIRED');
		}
		else
		{
			$subgroupSize = $data[$key];
			$this->set([$key => $subgroupSize]);
		}
	}

	/*
	 * Updates query instance's external_cost attribute after validation
	 *
	 * @param    array   $data   Data to update the query with
	 * @return   void
	 */
	public function setExternalCost($data)
	{
		$key = 'external_cost';

		if (!isset($data[$key]))
		{
			$this->_addError('COM_TOOLBOX_GUIDED_QUERY_EXTERNAL_COST_REQUIRED');
		}
		else
		{
			$externalCost = $data[$key];
			$this->set([$key => $externalCost]);
		}
	}

	/*
	 * Updates query instance's duration attributes after validation
	 *
	 * @param    array   $data   Data to update the query with
	 * @return   void
	 */
	public function setDuration($data)
	{
		$isValid = true;
		$keysSet = true;
		$minKey = 'duration_min';
		$maxKey = 'duration_max';

		if (!isset($data[$maxKey]) || $data[$maxKey] === '')
		{
			$this->_addError('COM_TOOLBOX_GUIDED_QUERY_DURATION_MAX_REQUIRED');
			$isValid = false;
			$keysSet = false;
		}

		if (!isset($data[$minKey]) || $data[$minKey] === '')
		{
			$this->_addError('COM_TOOLBOX_GUIDED_QUERY_DURATION_MIN_REQUIRED');
			$isValid = false;
			$keysSet = false;
		}

		if ($keysSet)
		{
			$durationMin = $data[$minKey];
			$durationMax = $data[$maxKey];

			if ($durationMin >= $durationMax)
			{
				$this->_addError('COM_TOOLBOX_GUIDED_QUERY_DURATION_MIN_GREATER_MAX');
			}

			$this->set([
				$minKey => $durationMin,
				$maxKey => $durationMax
			]);
		}
	}

	/*
	 * Persists the query instance's data to the CMS session
	 *
	 * @return   bool
	 */
	public function save()
	{
		if (!$this->isValid())
		{
			return false;
		}

		$sessionNamespace = self::$_sessionNamespace;
		$sessionKey = self::$_sessionKey;
		$queryData = $this->toArray();

		try {
			Session::set($sessionKey, $queryData, $sessionNamespace);
		}
		catch (Exception $e) {
			$this->_addError('COM_TOOLBOX_GUIDED_QUERY_SAVE_ERROR');
		}

		return true;
	}

	/*
	 * Indicates whether or not query is valid
	 *
	 * @return   bool
	 */
	public function isValid()
	{
		$isValid = empty($this->getErrors());

		return $isValid;
	}

	/*
	 * Adds an error to instance's error set
	 *
	 * @param    string   $errorKey   The key that corresponds to the error message
	 * @return   void
	 */
	protected function _addError($errorKey)
	{
		$errorMessage = Lang::txt($errorKey);

		$this->errors[] = $errorMessage;
	}

	/*
	 * Returns instance's errors
	 *
	 * @return   array
	 */
	public function getErrors()
	{
		$errors = $this->errors;

		return $errors;
	}

	/*
	 * Returns an array containing all data set on query instance
	 *
	 * @return   array
	 */
	public function toArray()
	{
		$attributesList = self::_getAttributesWhitelist();
		$queryData = [];

		foreach ($attributesList as $attribute)
		{
			$queryData[$attribute] = $this->$attribute;
		}

		return $queryData;
	}

	/*
	 * Returns data or a default for given attribute
	 *
	 * @param    string   $attribute   Name of attribute
	 * @return   mixed
	 */
	public function get($attribute)
	{
		if (isset($this->$attribute))
		{
			$value = $this->$attribute;
		}
		else
		{
			$value = null;
		}

		return $value;
	}

	/*
	 * Finds records based query criteria
	 *
	 * @param    object   $recordClass   Record ORM class
	 * @return   object
	 */
	public function findRecords($recordClass)
	{
		$records = $recordClass::all();

		if (!$this->isEmpty())
		{
			$this->_filterRecords($records);
		}

		return $records;
	}

	/*
	 * Indicates whether or not query has any non-empty values
	 *
	 * @return   bool
	 */
	public function isEmpty()
	{
		$criteria = $this->toArray();
		$criteriaSum = array_sum(array_values($criteria));
		$durationMax = Arr::pluck($criteria, 'duration_max');
		$durationMin = Arr::pluck($criteria ,'duration_min');
		$typesIds = Arr::pluck($criteria, 'typesIds');

		$isEmpty = !$criteriaSum && !$durationMax && !$durationMin && empty($typesIds);

		return $isEmpty;
	}

	/*
	 * Filters records based on query instance's criteria
	 *
	 * @param    object   $records   All entity records
	 * @return   object
	 */
	protected function _filterRecords($records)
	{
		$criteria = $this->toArray();
		$durationMax = Arr::pluck($criteria, 'duration_max');
		$durationMin = Arr::pluck($criteria ,'duration_min');
		$externalCost = Arr::pluck($criteria, 'external_cost');
		$kinesthetic = Arr::pluck($criteria, 'kinesthetic');
		$typesIds = Arr::pluck($criteria, 'typesIds');

		// filter by duration
		$records->where('duration', '>=', $durationMin);
		$records->where('duration', '<=', $durationMax);

		// filter by meta attributes
		$records->whereEquals('external_cost', $externalCost);
		$records->whereEquals('kinesthetic', $kinesthetic);

		// filter by Type ID
		$this->_filterByAssociationIds($records, $typesIds);

		// filter by one-to-one criteria
		foreach ($criteria as $attribute => $value)
		{
			if (!!$value)
			{
				$records->whereEquals($attribute, $value);
			}
		}

		return $records;
	}

	/*
	 * Filters records based on association IDs
	 *
	 * @param    object   $records           All entity records
	 * @param    array    $associationsIds   Association IDs
	 * @return   void
	 */
	protected function _filterByAssociationIds($records, $associationsIds)
	{
		$toolsTypesTable = $this->_getToolsTypesTable();
		$toolTypesTable = $this->_getToolTypesTable();
		$toolsTable = $this->_getToolsTable();

		$records->join($toolsTypesTable, "$toolsTable.id", "$toolsTypesTable.tool_id", 'left');
		$records->whereIn("$toolsTypesTable.type_id", $associationsIds);
	}

	/*
	 * Returns name of the ToolsTypes table
	 *
	 * @return   string
	 */
	protected function _getToolsTypesTable()
	{
		$toolsTypesTable = '#__toolbox_tools_types';

		return $toolsTypesTable;
	}

	/*
	 * Returns name of the ToolTypes table
	 *
	 * @return   string
	 */
	protected function _getToolTypesTable()
	{
		$toolTypesTable = '#__toolbox_tool_types';

		return $toolTypesTable;
	}

	/*
	 * Returns name of the Tools table
	 *
	 * @return   string
	 */
	protected function _getToolsTable()
	{
		$toolsTable = '#__toolbox_tools';

		return $toolsTable;
	}

}
