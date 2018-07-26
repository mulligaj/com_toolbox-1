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

$this->css('toolsList');

$breadcrumbs = [
	'Toolbox' => '/toolbox',
	'Tools' => '/tools'
];

$cumulativePath = '';
$page = Lang::txt('COM_TOOLBOX_TOOLS_LIST');
Document::setTitle($page);

foreach ($breadcrumbs as $text => $url)
{
	$cumulativePath .= $url;
	Pathway::append($text, $cumulativePath);
}

$formAction = Route::url(
	"index.php?option=$this->option&controller=guidedsearch&task=updateAll"
);
$query = $this->query;
$toolListUrl = Route::url(
	"index.php?option=$this->option&controller=$this->controller&task=$this->task"
);
$tools = $this->tools;
$types = $this->types;
?>

<?php
	$this->view('_header')
		->set('text', $page)
		->display();
?>

<section class="main section">
	<div class="grid">

		<div id="search-form" class="col span2">
			<?php
				$this->view('_tool_search_form')
					->set('action', $formAction)
					->set('query', $query)
					->set('types', $types)
					->display();
			?>
		</div>

		<div id="results" class="col span9">
			<?php
				if ($tools->count() > 0):
					$this->view('_tool_list')
						->set('tools', $tools)
						->display();
			?>
				<form method="POST" action="<?php echo $toolListUrl; ?>">
					<?php echo $tools->pagination; ?>
				</form>
			<?php else:	?>
				<div id="no-results">
					<?php echo Lang::txt('COM_TOOLBOX_LIST_NO_RESULTS'); ?>
				</div>
			<?php endif;	?>
		</div>


	</div>
</section>
