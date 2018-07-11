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

$breadcrumbs = [
	'Toolbox' => '/toolbox',
	'Tools' => '/tools',
	'Guided Search' => '/guidedsearch',
	'Tool Type' => '/type'
];

$cumulativePath = '';
$page = Lang::txt('COM_TOOLBOX_GUIDED_SEARCH');

foreach ($breadcrumbs as $text => $url)
{
	$cumulativePath .= $url;
	Pathway::append($text, $cumulativePath);
}

Document::setTitle($page);

$formAction = Route::url(
	"index.php?option=$this->option&controller=$this->controller&task=update"
);
$selectedTypesIds = $this->selectedTypesIds;
$step = 'type';
$types = $this->types;
?>

<?php
	$this->view('_header', 'tools')
		->set('text', $page)
		->display();
?>

<section class="main section">
	<div class="grid">

		<?php
			$this->view('_steps_nav')
				->set('current', 'Tool Type')
				->display();
		?>

		<div class="col span10 offset1">
			<?php
				$this->view('_tool_type_form')
					->set('action', $formAction)
					->set('selectedTypesIds', $selectedTypesIds)
					->set('step', $step)
					->set('types', $types)
					->display();
			?>
		</div>

	</div>
</section>

