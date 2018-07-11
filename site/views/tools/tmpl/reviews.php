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

$this->css('emptyInfo');
$this->css('infoTabs');
$this->css('infoWrapper');
$this->css('reviews');

$tool = $this->tool;
$toolId = $tool->get('id');
$toolName = $tool->get('name');
$toolScope = $tool->getScope();

$breadcrumbs = [
	'Toolbox' => '/toolbox',
	'Tools' => '/tools',
	$toolName => "/$toolId",
	'Reviews' => '/reviews'
];

$cumulativePath = '';
$page = $toolName;
$reviews = $this->reviews;
$toolReviewsTaskUrl = Route::url(
	"index.php?option=$this->option&controller=$this->controller&task=reviews&id=$toolId"
);

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
				->set('current', 'Reviews')
				->set('tool', $tool)
				->display();
		?>

		<div id="review-space-wrapper" class="col span12">
			<?php
				$this->view('_tool_review_space')
					->set('toolId', $toolId)
					->set('toolScope', $toolScope)
					->display();
				?>
		</div>

		<div class="col span12 info-wrapper">
			<?php
				if ($reviews->count() > 0):
					$this->view('_tool_reviews_list')
						->set('reviews', $reviews)
						->display(); ?>
					<form method="POST" action="<?php echo $toolReviewsTaskUrl; ?>">
						<?php echo $reviews->pagination; ?>
					</form>
				<?php else: ?>
					<div class="empty-info">
						<?php echo Lang::txt('COM_TOOLBOX_REVIEW_NO_REVIEWS', $toolName); ?>
					</div>
				<?php endif; ?>
		</div>

	</div>
</section>
