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

class ToolUpdateHelper
{

	/*
	 * Component name
	 *
	 * @var  string
	 */
	protected static $_component = 'com_toolbox';

	/*
	 * Controller name
	 *
	 * @var  string
	 */
	protected static $_controller = 'tools';

	/*
	 * Step to next step map
	 *
	 * @var array
	 */
	protected static $nextStepMap = [
		'basic' => 'frameworks',
		'frameworks' => 'materials',
		'materials' => 'links'
	];

	/*
	 * Step to task map
	 *
	 * @var array
	 */
	protected static $stepToTaskMap = [
		'basic' => 'editbasic',
		'downloads' => 'editdownloads',
		'frameworks' => 'editframeworks',
		'links' => 'editlinks',
		'materials' => 'editmaterials'
	];

	/*
	 * Step to view map
	 *
	 * @var array
	 */
	protected static $stepToViewMap = [
		'basic' => 'editbasic',
		'frameworks' => 'editframeworks',
		'materials' => 'editmaterials'
	];

	/*
	 * Generates URL to next step in tool update process
	 *
	 * @param   string    $originStep  Name of the submitted step
	 * @param   integer   $toolId      ID for newly created Tool record
	 * @return  string
	 */
	public static function nextStepUrl($originStep, $toolId)
	{
		$nextStepTask = self::_nextStepTask($originStep);
		$component = self::$_component;
		$controller = self::$_controller;

		$nextStepUrl = Route::url(
			"index.php?option=$component&controller=$controller&task=$nextStepTask&id=$toolId",
			false
		);

		return $nextStepUrl;
	}

	/*
	 * Determines next task in update process
	 *
	 * @param   string    $originStep  Name of the submitted step
	 * @return  string
	 */
	public static function _nextStepTask($originStep)
	{
		$nextStep = self::_getNextStep($originStep);
		$nextTask = self::stepToTask($nextStep, false);

		return $nextTask;
	}

	/*
	 * Determines next step in update process
	 *
	 * @param    string   $step   Given step
	 * @return   string
	 */
	protected static function _getNextStep($step)
	{
		$nextStep = self::$nextStepMap[$step];

		return $nextStep;
	}

	/*
	 * Determines task name for the given step
	 *
	 * @param    string   $step         Name of a step
	 * @param    bool     $appendTask   Indicates whether to append 'Task'
	 * @return   string
	 */
	public static function stepToTask($step, $appendTask = true)
	{
		$task = self::$stepToTaskMap[$step];

		if ($appendTask)
		{
			$task .= 'Task';
		}

		return $task;
	}

	/*
	 * Determines view name for the given step
	 *
	 * @param    string   $step   Name of a step
	 * @return   string
	 */
	public static function stepToView($step)
	{
		$view = self::$stepToViewMap[$step];

		return $view;
	}

}
