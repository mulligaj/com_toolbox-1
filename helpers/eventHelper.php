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

class EventHelper
{

	/**
	 * Toolbox plugin scope
	 *
	 * @var  string
	 */
	const TOOLBOX_PLUGIN_SCOPE = 'toolbox';

	/**
	 * Toolbox plugin actions
	 *
	 * @var  array
	 */
	const TOOLBOX_ACTIONS = [
		'update' => 'onUpdate'
	];

	/**
	 * Triggers the tool update event
	 *
	 * @param    object   $tool          Tool object being udpated
	 * @param    string   $description   Update description
	 * @return   void
	 */
	public static function onToolUpdate($tool, $description)
	{
		$eventName = self::_generateEventName('update');
		$updateReport = [
			$tool,
			$description
		];

		self::_triggerEvent($eventName, $updateReport);
	}

	/**
	 * Generates event name based on action taken
	 *
	 * @param    string   $actionDescription   Description of action taken
	 * @return   string
	 */
	protected static function _generateEventName($actionDescription)
	{
		$action = self::TOOLBOX_ACTIONS[$actionDescription];
		$eventName = self::TOOLBOX_PLUGIN_SCOPE . ".$action";

		return $eventName;
	}

	/**
	 * Triggers given event with the given data
	 *
	 * @param    string   $eventName   Name of the event
	 * @param    array    $eventData   Arguments to the event handler
	 * @return   void
	 */
	protected static function _triggerEvent($eventName, $eventData = [])
	{
		Event::trigger($eventName, $eventData);
	}

}
