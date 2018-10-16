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

class DownloadsHelper
{

	/*
	 * Returns path to tools' downloads directory
	 *
	 * @return   string
	 */
	public static function getDownloadsPath()
	{
		$downloadsPath = PATH_APP . '/site/toolbox/downloads';

		return $downloadsPath;
	}

	/*
	 * Moves download file to given directory
	 *
	 * @param    object   $downloadModel   A download model
	 * @return   bool
	 */
	public static function store($downloadModel)
	{
		$temporaryLocation = $downloadModel->get('tmp_name');
		$destinationDirectory = $downloadModel->destinationDirectory();
		$destinationPath = $downloadModel->destinationPath();

		static::ensureDirectoryExists($destinationDirectory);

		$moveResult = Filesystem::move($temporaryLocation, $destinationPath);

		return $moveResult;
	}

	/*
	 * Creates given directory if it doesn't already exist
	 *
	 * @param    string   $path   Path to directory
	 * @return   bool
	 */
	protected static function ensureDirectoryExists($path)
	{
		if (!is_dir($path))
		{
			if (!Filesystem::makeDirectory($path))
			{
				throw new Exception(
					Lang::txt('COM_TOOLBOX_DOWNLOAD_DIRECTORY_CREATE_ERROR'),
					500
				);
			}
		}

		return true;
	}

	/*
	 * Moves file to temporary directory
	 *
	 * @param    object   $download   Download instance
	 * @return   bool
	 */
	public static function destroy($download)
	{
		$name = $download->get('name');
		$path = $download->destinationPath();

		if (Filesystem::exists($path))
		{
			$temporaryPath = static::temporaryDirectory() . $name;

			$fileWasDestroyed = Filesystem::move($path, $temporaryPath);
		}
		else
		{
			$fileWasDestroyed = true;
		}

		return $fileWasDestroyed;
	}

	/*
	 * Retrieves a file in the temporary directory
	 *
	 * @param    object   $download   Given Download instance
	 * @return   bool
	 */
	public static function recover($download)
	{
		$destination = $download->destinationPath();
		$name = $download->get('name');
		$temporaryPath = static::temporaryDirectory() . $name;

		if (Filesystem::exists($temporaryPath))
		{
			$recoverResult = Filesystem::move($temporaryPath, $destination);
		}
		else
		{
			$recoverResult = false;
		}

		return $recoverResult;
	}

	/*
	 * Returns path to the hub's temporary/ temporary directory
	 *
	 * @return   string
	 */
	public static function temporaryDirectory()
	{
		$temporaryDirectory = '/tmp/';

		return $temporaryDirectory;
	}

	/*
	 * Removes temporary file
	 *
	 * @return   bool
	 */
	public static function removeTemp($download)
	{
		$name = $download->get('name');
		$tempPath = static::temporaryDirectory() . $name;

		$deleteResult = static::_delete($tempPath);

		return $deleteResult;
	}

	/*
	 * Deletes a target at a given path
	 *
	 * @return   bool
	 */
	protected static function _delete($path)
	{
		if (!Filesystem::exists($path))
		{
			$deleteResult = true;
		}
		else
		{
			$deleteResult = Filesystem::delete($path);
		}

		return $deleteResult;
	}

	/*
	 * Swaps old file with the new file
	 *
	 * @param    object   $download Download instance
	 * @return   bool
	 */
	public static function replace($download)
	{
		$oldFileWasRemoved = static::destroy($download);
		$newFileWasStored = static::store($download);

		if (!$oldFileWasRemoved || !$newFileWasStored)
		{
			$download->addError(Lang::txt('COM_TOOLBOX_DOWNLOAD_FILE_REPLACE_ERROR'));
		}

		return true;
	}

	/*
	 * Swaps new file with the old file
	 *
	 * @param    object   $download Download instance
	 * @return   bool
	 */
	public static function unReplace($download)
	{
		$temporaryDirectory = static::temporaryDirectory();
		$name = $download->get('name');
		$newFilePath = $download->destinationPath();
		$newFileWasReplaced = Filesystem::move(
			$temporaryDirectory . $name, $newFilePath
		);

		if (!$newFileWasReplaced)
		{
			$download->addError(Lang::txt('COM_TOOLBOX_DOWNLOAD_FILE_REPLACE_ERROR'));
		}

		return true;
	}

	/*
	 * Returns path to a writable temporary directory
	 *
	 * @return   string
	 */
	public static function writableTemp()
	{
		$writableTemp = PATH_ROOT . '/app/site/toolbox/downloads/tmp';

		return $writableTemp;
	}

}
