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

require_once "$toolboxPath/models/tool.php";

use Hubzero\Database\Relational;

class ToolType extends Relational
{

	/*
	 * Records table
	 *
	 * @var string
	 */
	protected $table = '#__toolbox_tool_types';

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
		'description' => 'notempty'
	];

	/*
	 * Performs any instance-specific setup
	 *
	 * @return   void
	 */
	public function setup()
	{
		$this->addRule('archived', function($attributes) {
			$tools = $this->tools();

			if ($tools->count() > 0)
			{
				return Lang::txt('COM_TOOLBOX_TYPE_ARCHIVE_FAILURE_TOOLS');
			}

			return false;
		});
	}

	/*
	 * Returns associated tool record(s)
	 *
	 * @return   object
	 */
	public function tools()
	{
		$toolModelName = 'Components\Toolbox\Models\Tool';
		$associativeTable = '#__toolbox_tools_types';
		$primaryKey = 'type_id';
		$foreignKey = 'tool_id';

		$tools = $this->manyToMany(
			$toolModelName,
			$associativeTable,
			$primaryKey,
			$foreignKey
		);

		return $tools;
	}

}
