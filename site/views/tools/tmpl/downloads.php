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

// No direct access
defined('_HZEXEC_') or die();

$this->css('infoTabs');
$this->css('infoWrapper');

$tool = $this->tool;
$toolId = $tool->get('id');
$toolName = $tool->get('name');

$breadcrumbs = [
	'Toolbox' => '/toolbox',
	'Tools' => '/tools',
	$toolName => "/$toolId",
	'Downloads' => '/downloads'
];

$cumulativePath = '';
$page = $toolName;

foreach ($breadcrumbs as $text => $url)
{
	$cumulativePath .= $url;
	Pathway::append($text, $cumulativePath);
}

$downloads = $tool->downloads();
Document::setTitle($page);
?>

<?php
	$this->view('_header')
		->set('text', $page)
		->display();
?>

<section class="main section">
	<div class="grid">

	<?php
		$this->view('_tool_info_combined_header')
			->set('current', 'Downloads')
			->set('tool', $tool)
			->display();
	?>

	<div class="col span12 info-wrapper">
		<?php if ($downloads->count() > 0): ?>
			<?php
				$this->view('_tool_info_downloads_list')
					->set('downloads', $downloads)
					->display();
			?>
		<?php else: ?>
			<div class="empty-info">
				<?php echo Lang::txt('COM_TOOLBOX_DOWNLOAD_NO_DOWNLOADS'); ?>
			</div>
		<?php endif; ?>
	</div>

	<?php
			$this->view('_edit_link')
				->set('attribute', 'downloads')
				->set('toolId', $toolId)
				->display();
	?>

	</div>
</section>
