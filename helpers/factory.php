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

$componentPath = Component::path('com_toolbox');

require_once "$componentPath/helpers/createBatch.php";
require_once "$componentPath/helpers/destroyBatch.php";

use Components\Toolbox\Helpers\CreateBatch;
use Components\Toolbox\Helpers\DestroyBatch;

class Factory
{

	/*
	 * Destroys records
	 *
	 * @param    Hubzero\Rows   $models   Models to destroy
	 * @return   object
	 */
	public static function destroyMany($models)
	{
		$result = new DestroyBatch();

		foreach ($models as $model)
		{
			self::_destroy($model, $result);
		}

		return $result;
	}

	/*
	 * Destroys a model
	 *
	 * @param   object   $model    Model to destroy
	 * @param   object   $result   Outcome(s) of destroying model(s)
	 * @return  void
	 */
	protected static function _destroy($model, $result)
	{
		if (!$model->destroy())
		{
			$result->addFailedDestroy($model);
		}
		else
		{
			$result->addSuccessfulDestroy($model);
		}
	}

	/*
	 * Creates records based on data provided
	 *
	 * @param   array   $modelsData   Data to persist
	 * @return  object
	 */
	public static function createMany($modelsData)
	{
		$models = self::instantiateMany($modelsData);

		$result = self::save($models);

		return $result;
	}

	/*
	 * Instantiates models based on data provided
	 *
	 * @param   array   $modelsData   Data to instantiate models with
	 * @return  array
	 */
	public static function instantiateMany($modelsData)
	{
		$models = [];

		foreach ($modelsData as $modelData)
		{
			$model = self::instantiate($modelData);
			array_push($models, $model);
		}

		return $models;
	}

	/*
	 * Instantiate a model based on data provided
	 *
	 * @param   array   $modelData   Data to instantiate model with
	 * @return  object
	 */
	public static function instantiate($modelData)
	{
		$model = new static::$modelName();

		$model->set($modelData);

		return $model;
	}

	/*
	 * Saves models
	 *
	 * @param   array   $models   Models to save
	 * @return  array
	 */
	public static function save($models)
	{
		$result = new CreateBatch();

		if (!is_array($models))
		{
			$models = [$models];
		}

		foreach ($models as $model)
		{
			self::_save($model, $result);
		}

		return $result;
	}

	/*
	 * Saves a model
	 *
	 * @param   object   $model    Model to save
	 * @param   object   $result   Outcome(s) of saving model(s)
	 * @return  void
	 */
	protected static function _save($model, $result)
	{
		if (!$model->save())
		{
			$result->addFailedSave($model);
		}
		else
		{
			$result->addSuccessfulSave($model);
		}
	}

}
