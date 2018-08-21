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

namespace Components\Toolbox\Site\Controllers;

$toolboxPath = Component::path('com_toolbox');

require_once "$toolboxPath/helpers/toolAuthHelper.php";
require_once "$toolboxPath/helpers/fileUploadHelper.php";
require_once "$toolboxPath/helpers/downloadsFactory.php";
require_once "$toolboxPath/helpers/downloadsHelper.php";
require_once "$toolboxPath/models/tool.php";

use Components\Toolbox\Helpers\FileUploadHelper;
use Components\Toolbox\Helpers\DownloadsFactory;
use Components\Toolbox\Helpers\DownloadsHelper;
use Components\Toolbox\Helpers\ToolAuthHelper;
use Components\Toolbox\Models\Tool;
use Hubzero\Component\SiteController;
use Hubzero\Filesystem\Util\MimeType;

class Downloads extends SiteController
{

	/*
	 * Uploads file(s) via AJAX
	 *
	 * @return  string
	 */
	protected function ajaxUploadTask()
	{
		// get tool's ID
		$toolId = Request::getInt('id');

		// ensure tool ID is present
		if (!$toolId)
		{
			echo json_encode(['errors' => Lang::txt('COM_TOOLBOX_DOWNLOAD_UPLOAD_TOOL_ID_MISSING')]);
			return;
		}

		// get the file name & size
		if (isset($_GET['qqfile']) && isset($_SERVER['CONTENT_LENGTH']))
		{
			$stream = true;
			$name = $_GET['qqfile'];
			$size = (int) $_SERVER['CONTENT_LENGTH'];
		}
		else
		{
			echo json_encode(['errors' => Lang::txt('COM_TOOLBOX_DOWNLOAD_UPLOAD_FILE_MISSING')]);
			return;
		}

		// get file type
		if (isset($_GET['qqfile']) && isset($_SERVER['CONTENT_TYPE']))
		{
			$type = $_SERVER['CONTENT_TYPE'];
		}
		else
		{
			$matches = [];

			if (preg_match('/(\w+\z)/', $name, $matches))
			{
				$extension = $matches[0];
			}

			$type = MimeType::detectByFileExtension($extension);
		}

		// max upload size
		$sizeLimit = $this->config->get('maxAllowed', 10000000);

		// validate file size
		if ($size == 0)
		{
			echo json_encode(['errors' => Lang::txt('COM_TOOLBOX_DOWNLOAD_UPLOAD_FILE_EMPTY')]);
			return;
		}
		if ($size > $sizeLimit)
		{
			$max = preg_replace('/<abbr \w+=\\"\w+\\">(\w{1,3})<\\/abbr>/', '$1', Number::formatBytes($sizeLimit));
			echo json_encode(['errors' => Lang::txt('COM_TOOLBOX_DOWNLOAD_UPLOAD_FILE_TOO_LARGE', $max)]);
			return;
		}

		if ($stream)
		{
			// move file to expected temporary location
			$temporaryDirectory = DownloadsHelper::writableTemp();
			$temporaryLocation = "$temporaryDirectory/$name";
			$temporaryFile = fopen($temporaryLocation, 'w');
			$input = fopen('php://input', 'r');
			$realSize = stream_copy_to_stream($input, $temporaryFile);
			fclose($input);
		}

		$downloadData = [
			'tool_id' => $toolId,
			'name' => $name,
			'type' => $type,
			'size' => $size,
			'tmp_name' => $temporaryLocation
		];

		$saveResult = DownloadsFactory::createOrUpdateMany([$downloadData]);

		if ($saveResult->succeeded())
		{
			$download = $saveResult->getSuccessfulSaves()[0];
			$downloadId = $download->get('id');
			$toolId = $download->get('tool_id');

			$response = json_encode([
				'id' => $downloadId,
				'success' => true,
				'tool_id' => $toolId
			]);
		}
		else
		{
			$download = $saveResult->getFailedSaves()[0];
			$errors = $download->getErrors();

			$response = json_encode([
				'success' => false,
				'errors'   => $errors
			]);
		}

		echo $response;
	}

	/*
	 * Updates download records
	 *
	 * @return  void
	 */
	public function updateTask()
	{
		// get tool
		$toolId = Request::getInt('id');
    $tool = Tool::oneOrFail($toolId);
		ToolAuthHelper::authorizeEditing($tool);

		if (Request::has('qqfile'))
		{
			return $this->ajaxUploadTask();
		}

		Request::checkToken();

		// get posted downloads data
		$downloadsData = $_FILES['downloads'];

		// collate downloads data
		$downloadsData = FileUploadHelper::collateFilesData($downloadsData);

		// add tool ID
		$downloadsData = array_map(function ($downloadData) use ($toolId) {
			$downloadData['tool_id'] = $toolId;
			return $downloadData;
		}, $downloadsData);

		// attempt to create download records
		$saveResult = DownloadsFactory::createOrUpdateMany($downloadsData);

		if ($saveResult->succeeded())
		{
			$this->_successfulUpdate();
		}
		else
		{
			$this->_failedUpdate($saveResult);
		}
	}

	/*
	 * Process successful creation of download records
	 *
	 * @return  void
	 */
	protected function _successfulUpdate()
	{
		$forwardingUrl = Request::getString('forward');

		App::redirect(
			$forwardingUrl,
			Lang::txt('COM_TOOLBOX_DOWNLOAD_UPDATES_SUCCESSFUL'),
			'passed'
		);
	}

	/*
	 * Process failed creation of download records
	 *
	 * @param   object   $saveResult   Result of attempting to create, update records
	 * @return  void
	 */
	protected function _failedUpdate($saveResult)
	{
		$originUrl = Request::getString('origin');
		$errors = DownloadsFactory::parseSaveErrors($saveResult);
		$errorsMessage = implode($errors, '<br><br>');

		Notify::error($errorsMessage);

		App::redirect($originUrl);
	}

	/*
	 * Delete download records based on ID
	 *
	 * @return  void
	 */
	public function destroyTask()
	{
		Request::checkToken();

		// get IDs of download records to be deleted
		$downloadIds = Request::getArray('downloads');

		// get records with given IDs
		$destroyResult = DownloadsFactory::destroyById($downloadIds);

		if ($destroyResult->succeeded())
		{
			$this->_successfulDestroy();
		}
		else
		{
			$errors = DownloadsFactory::parseSaveErrors($saveResult);
			$this->_failedDestroy($destroyResult);
		}
	}

	/*
	 * Process successful destruction of download record(s)
	 *
	 * @return   void
	 */
	protected function _successfulDestroy()
	{
		$forwardingUrl = Request::getString('forward');

		App::redirect(
			$forwardingUrl,
			Lang::txt('COM_TOOLBOX_DOWNLOAD_DESTROY_SUCCESS'),
			'passed'
		);
	}

	/*
	 * Process failed destruction of download record(s)
	 *
	 * @param   object   $destroyResult   Result of attempting to destroying records
	 * @return   void
	 */
	protected function _failedDestroy($destroyResult)
	{
		$originUrl = Request::getString('origin');
		$errors = DownloadsFactory::parseDestroyErrors($destroyResult);
		$errorsMessage = implode($errors, '<br><br>');

		Notify::error($errorsMessage);

		App::redirect($originUrl);
	}

}
