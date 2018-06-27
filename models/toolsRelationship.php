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

use Hubzero\Database\Relational;

class ToolsRelationship extends Relational
{

	/*
	 * Records table
	 *
	 * @var string
	 */
	protected $table = '#__toolbox_tools_relationships';

	/*
	 * Attribute validation
	 *
	 * @var array
	 */
	protected $rules = [
		'origin_id' => 'positive',
		'related_id' => 'positive'
	];

	/*
	 * Attributes to be populated on record creation
	 *
	 * @var array
	 */
	public $initiate = ['created'];


	/*
	 * Returns the upper limit of relationships a tool is allowed to have
	 *
	 * @return   int
	 */
	public static function maximum()
	{
		return 12;
	}

	/*
	 * Returns the name of the related tool
	 *
	 * @return   string
	 */
	public function relatedToolName()
	{
		$relatedTool = $this->relatedTool()->row();

		$relatedToolName = $relatedTool->get('name');

		return $relatedToolName;
	}

	/*
	 * Returns related tool instance
	 *
	 * @return   object
	 */
	public function relatedTool()
	{
		$toolModelName = 'Components\Toolbox\Models\Tool';
		$foreignKey = 'related_id';

		$relatedTool = $this->belongsToOne(
			$toolModelName,
			$foreignKey
		);

		return $relatedTool;
	}

}
