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

require_once "$toolboxPath/helpers/downloadsHelper.php";
require_once "$toolboxPath/models/tool.php";

use Components\Toolbox\Helpers\DownloadsHelper;
use Hubzero\Database\Relational;

class Download extends Relational
{

	/*
	 * Records table
	 *
	 * @var string
	 */
	protected $table = '#__toolbox_downloads';

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
		'name' => 'notempty',
		'tool_id' => 'positive',
		'type' => 'notempty'
	];

	/*
	 * Searches for a record based on given attribute(s) or instantiates a model
	 *
	 * @param    array    $attributes   Attributes & values to search by
	 * @return   object
	 */
	public static function oneByOrNew($attributes)
	{
		$record = self::oneBy($attributes);

		$record = $record ? $record : self::blank();

		return $record;
	}

	/*
	 * Searches for a record based on given attribute(s)
	 *
	 * @param    array    $attributes   Attributes & values to search by
	 * @return   object
	 */
	public static function oneBy($attributes)
	{
		$records = self::all();

		foreach ($attributes as $attribute => $value)
		{
			$records->whereEquals($attribute, $value);
		}

		$records = $records->rows()->raw();
		$record =  (count($records) === 1) ? array_pop($records) : false;

		return $record;
	}

	/*
	 * Persists model data to the DB & moves file to correct directory
	 *
	 * @return bool
	 */
	public function save()
	{
		$name = $this->get('name');

		// determine which function to use to move file
		$fileIoFunction = $this->isNew() ? 'store' : 'replace';

		// add error if file was not moved
		if ($this->get('error') || !DownloadsHelper::$fileIoFunction($this))
		{
			$this->addError(Lang::txt('COM_TOOLBOX_DOWNLOAD_FILE_SAVE_ERROR', $name));
		}

		if (!empty($this->getErrors()))
		{
			return false;
		}

		// persist model data to DB
		if (!parent::save())
		{
			if ($this->isNew())
			{
				DownloadsHelper::destroy($this);
			}
			else
			{
				DownloadsHelper::unReplace($this);
			}

			return false;
		}

		return true;
	}

	/*
	 * Destroys record & deletes the local file
	 *
	 * @return bool
	 */
	public function destroy()
	{
		// get record ID
		$id = $this->get('id');

		// delete local file
		if (!DownloadsHelper::destroy($this))
		{
			$name = $this->get('name');

			$this->addError(Lang::txt('COM_TOOLBOX_DOWNLOAD_FILE_DESTROY_ERROR', $name));

			return false;
		}

		// delete record data from DB
		if (!parent::destroy())
		{
			if (!DownloadsHelper::recover($this))
			{
				$this->set('desynchronized', true);

				parent::save();

				$this->addError(Lang::txt('COM_TOOLBOX_DOWNLOAD_FILE_RECOVER_ERROR'));
			}
			else
			{
				$this->addError(Lang::txt('COM_TOOLBOX_DOWNLOAD_RECORD_DESTROY_ERROR'));
			}

			return false;
		}

		DownloadsHelper::removeTemp($this);

		return true;
	}

	/*
	 * Determines full path where the file should be stored
	 *
	 * @return   string
	 */
	public function destinationPath()
	{
		$destinationDirectory = $this->destinationDirectory();
		$fileName = Filesystem::cleanPath($this->get('name'));

		$destinationPath = "$destinationDirectory$fileName";

		return $destinationPath;
	}

	/*
	 * Determines where the file should be stored
	 *
	 * @return   string
	 */
	public function destinationDirectory()
	{
		$downloadsDirectory = DownloadsHelper::getDownloadsPath();
		$toolId = $this->getToolId();

		$destinationPath = "$downloadsDirectory/$toolId/";

		return $destinationPath;
	}

	/*
	 * Returns associated tools ID
	 *
	 * @return   int
	 */
	public function getToolId()
	{
		$tool = $this->tool();

		$id = $tool->get('id');

		return $id;
	}

	/*
	 * Returns associated tool model
	 *
	 * @return   object
	 */
	public function tool()
	{
		if(!isset($this->tool))
		{
			$this->tool = $this->belongsToOne(
				'Components\Toolbox\Models\Tool', 'tool_id', 'id'
			)->rows();
		}

		return $this->tool;
	}

	/*
	 * Generates a URL to the file on the hub server
	 *
	 * @return   string
	 */
	public function url()
	{
		$toolId = $this->getToolId();
		$name = $this->get('name');
		$localPath = "/app/site/toolbox/downloads/$toolId/$name";

		$url = Route::url($localPath);

		return $url;
	}

}
