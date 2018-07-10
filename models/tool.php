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

namespace Components\Toolbox\Models;

$toolboxPath = Component::path('com_toolbox');
$tagsPath = Component::path('com_tags');

require_once "$toolboxPath/models/download.php";
require_once "$tagsPath/models/tag.php";
require_once "$tagsPath/models/cloud.php";

use Components\Tags\Models\Cloud;
use Hubzero\Database\Relational;

class Tool extends Relational
{

	/*
	 * Records table
	 *
	 * @var string
	 */
	protected $table = '#__toolbox_tools';

	/*
	 * Attributes to be populated on record creation
	 *
	 * @var array
	 */
	public $initiate = ['created'];

	/*
	 * Attribute validation
	 *
	 * @var  array
	 */
	protected $rules = [
		'duration' => 'positive',
		'external_cost' => 'positive',
		'kinesthetic' => 'positive',
		'name' => 'notempty',
		'source' => 'notempty',
		'subgroup_size' => 'notempty'
	];

	/*
	 * Instantiates a Tool model
	 *
	 * @return   object
	 */
	public static function blank()
	{
		$tool = parent::blank();

		$defaults = [
			'minimum_participants' => '0',
			'suggested_participants' => '0',
			'maximum_participants' => '0',
			'duration' => '0',
			'cost' => 0
		];

		$tool->set($defaults);

		return $tool;
	}

	/*
	 * Get tools with IDs other than the provided tool(s)
	 *
	 *  @param    array    $tools   The tools to exclude from query
	 *  @return   object
	 */
	public static function otherTools($tools)
	{
		$toolIds = array_map(function($tool) {
			return $tool->get('id');
		}, $tools);

		$otherTools = self::all()
			->whereNotIn('id', $toolIds)
			->rows();

		return $otherTools;
	}

	/*
	 * Returns an array containing the associated types' IDs
	 *
	 * @return   array
	 */
	public function typeIds()
	{
		$types = $this->types()->rows()->toArray();

		$typeIds = array_map(function($type) {
			return $type['id'];
		}, $types);

		return $typeIds;
	}

	/*
	 * Returns associated Type records
	 *
	 * @return   object
	 */
	public function types()
	{
		$toolTypeModelName = 'Components\Toolbox\Models\ToolType';
		$associativeTable = '#__toolbox_tools_types';
		$primaryKey = 'tool_id';
		$foreignKey = 'type_id';

		$types = $this->manyToMany(
			$toolTypeModelName,
			$associativeTable,
			$primaryKey,
			$foreignKey
		);

		return $types;
	}

	/*
	 * Returns the IDs of related tool records
	 *
	 * @return   array
	 */
	public function relatedToolsIds()
	{
		$relatedTools = $this->relatedTools()->rows();
		$relatedToolsIds = [];

		foreach ($relatedTools as $relatedTool)
		{
			$relatedToolsIds[] = $relatedTool->get('id');
		}

		return $relatedToolsIds;
	}

	/*
	 * Returns associated Tool records
	 *
	 * @return   object
	 */
	public function relatedTools()
	{
		$toolModelName = 'Components\Toolbox\Models\Tool';
		$associativeTable = '#__toolbox_tools_relationships';
		$primaryKey = 'origin_id';
		$foreignKey = 'related_id';

		$relatedTools = $this->manyToMany(
			$toolModelName,
			$associativeTable,
			$primaryKey,
			$foreignKey
		);

		return $relatedTools;
	}

	/*
	 * Returns the IDs of associated Tag records
	 *
	 * @return   array
	 */
	public function tagsIds()
	{
		$tags = $this->tags()->rows();
		$tagsIds = [];

		foreach ($tags as $tag)
		{
			$tagsIds[] = $tag->get('id');
		}

		return $tagsIds;
	}

	/*
	 * Returns associated Tag records
	 *
	 * @return   object
	 */
	public function tags()
	{
		$tagModelName = 'Components\Tags\Models\Tag';
		$associativeTable = '#__tags_object';
		$primaryKey = 'objectid';
		$foreignKey = 'tagid';

		$tags = $this->manyToMany(
			$tagModelName,
			$associativeTable,
			$primaryKey,
			$foreignKey
		);

		return $tags;
	}

	/*
	 * Returns associated Download records
	 *
	 * @return   object
	 */
	public function downloads()
	{
		$downloadModelName = 'Components\Toolbox\Models\Download';

		$downloads = $this->oneToMany($downloadModelName, 'tool_id')->rows();

		return $downloads;
	}

	/*
	 * Returns tool's participant minimum
	 *
	 * @return   mixed
	 */
	public function minimum()
	{
		$minimum = $this->get('minimum_participants');

		$minimum = $this->_translateLimit($minimum);

		return $minimum;
	}

	/*
	 * Returns tool's suggested participant number
	 *
	 * @return   mixed
	 */
	public function suggested()
	{
		$suggested = $this->get('suggested_participants');

		$suggested = $this->_translateLimit($suggested);

		return $suggested;
	}

	/*
	 * Returns tool's participant maximum
	 *
	 * @return   mixed
	 */
	public function maximum()
	{
		$maximum = $this->get('maximum_participants');

		$maximum = $this->_translateLimit($maximum);

		return $maximum;
	}

	/*
	 * Translates participant limits for display
	 *
	 * @param    int     $limit   Participant limit
	 * @return   mixed
	 */
	public function _translateLimit($limit)
	{
		$limit = ($limit == 0) ? 'Any' : $limit;

		return $limit;
	}

	/*
	 * Indicates whether or not the tool has a maximum
	 *
	 * @return   bool
	 */
	public function hasMaximum()
	{
		$hasMaximum = !!$this->get('maximum_participants');

		return $hasMaximum;
	}

	/*
	 * Indicates whether or not the tool has a minimum
	 *
	 * @return   bool
	 */
	public function hasMinimum()
	{
		$hasMinimum = !!$this->get('minimum_participants');

		return $hasMinimum;
	}

	/*
	 * Translates duration into descriptive phrase
	 *
	 * @return   string
	 */
	public function durationDescription()
	{
		$minutes = $this->get('duration');
		$hours = round($minutes / 60);
		$remainingMinutes = $minutes - ($hours * 60);
		$durationDescription = "";

		if ($hours > 0)
		{
			$durationDescription .= "$hours " . Lang::txt('COM_TOOLBOX_COMMON_HOUR');

			if ($hours > 1)
			{
				$durationDescription .= "s";
			}
		}

		if ($remainingMinutes > 0)
		{
			if ($hours > 0)
			{
				$durationDescription .= " " . Lang::txt('COM_TOOLBOX_COMMON_AND') . " ";
			}

			$durationDescription .= "$remainingMinutes " . Lang::txt('COM_TOOLBOX_COMMON_MINUTE');

			if ($remainingMinutes > 1)
			{
				$durationDescription .= "s";
			}
		}

		if ($durationDescription == '')
		{
			$durationDescription = Lang::txt('COM_TOOLBOX_TOOL_NO_DURATION');
		}

		return $durationDescription;
	}

	/*
	 * Constructs a tag cloud using the tool's tags
	 *
	 * @return   string   HTML
	 */
	public function tagsCloud()
	{
		$id = $this->get('id');
		$table = $this->table;
		$scope = ltrim($table, '#__');

		$cloud = new Cloud($id, $scope);
		$tagCloud = $cloud->render();

		return $tagCloud;
	}

	/*
	 * Translates external cost boolean into descriptive phrase
	 *
	 * @return   string
	 */
	public function costDescription()
	{
		if ($this->get('external_cost'))
		{
			$costDescription = Lang::txt('COM_TOOLBOX_TOOL_EXTERNAL_COST');
		}
		else
		{
			$costDescription = Lang::txt('COM_TOOLBOX_TOOL_NO_EXTERNAL_COST');
		}

		return $costDescription;
	}

	/*
	 * Translates subgroup size into descriptive phrase
	 *
	 * @return   array
	 */
	public function subgroupSizeDescription()
	{
		$subgroupSize = $this->get('subgroup_size');

		if (!!$subgroupSize)
		{
			$subgroupSizeKey = $this->getSubgroupSizes();
			$subgroupSizeDescription = $subgroupSizeKey[$subgroupSize];
		}
		else
		{
			$subgroupSizeDescription = Lang::txt('COM_TOOLBOX_TOOL_NO_SUBGROUP_SIZE');
		}

		return $subgroupSizeDescription;
	}

	/*
	 * Returns the set of possible subgroup sizes
	 *
	 * @return   array
	 */
	public function getSubgroupSizes()
	{
		return [
				'pairs' => Lang::txt('COM_TOOLBOX_SUBGROUP_PAIRS'),
				'small' => Lang::txt('COM_TOOLBOX_SUBGROUP_SMALL'),
				'large' => Lang::txt('COM_TOOLBOX_SUBGROUP_LARGE'),
				'whole' => Lang::txt('COM_TOOLBOX_SUBGROUP_WHOLE')
			];
	}

}
