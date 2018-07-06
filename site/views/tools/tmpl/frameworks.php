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

$tool = $this->tool;
$toolId = $tool->get('id');
$toolName = $tool->get('name');

$breadcrumbs = [
	'Toolbox' => '/toolbox',
	'Tools' => '/tools',
	$toolName => "/$toolId",
	'Notes' => '/notes'
];

$cumulativePath = '';
$page = $toolName;

foreach ($breadcrumbs as $text => $url)
{
	$cumulativePath .= $url;
	Pathway::append($text, $cumulativePath);
}

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
				->set('current', 'Theoretical Frameworks')
				->set('tool', $tool)
				->display();
		?>

		<div id="frameworks-wrapper" class="col span12">
			<div class="grid">
				<div class="col span12">
					<?php
						$this->view('_tool_info_aacu_rubric')
							->set('tool', $tool)
							->display();
					?>
				</div>
				<div class="col span12">
					<?php
						$this->view('_tool_info_idc')
							->set('tool', $tool)
							->display();
					?>
				</div>
				<div class="col span12">
					<?php
						$this->view('_tool_info_bergs')
							->set('tool', $tool)
							->display();
					?>
				</div>
				<div class="col span12">
					<?php
						$this->view('_tool_info_other')
							->set('tool', $tool)
							->display();
					?>
				</div>
			</div>
			</div>
		</div>

	</div>
</section>

<style>
#frameworks-wrapper {
	font-size: 1.5em;
	padding: .75em 0 0 0;
}

.checkbox-wrapper {
	display: block;
	margin: 0 0 10px 0;
}

h4 {
	font-weight: bold;
	text-decoration: underline;
}
</style>
