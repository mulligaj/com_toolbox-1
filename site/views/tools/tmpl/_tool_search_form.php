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

$this->css('toolSearchForm');
$this->js('toolsList');

Html::behavior('core');

$action = $this->action;
$query = $this->query;
$resultsUrl = Route::url(
	"index.php?option=$this->option&controller=$this->controller"
);
?>

<form method="post" action="<?php echo $action; ?>">

	<div class="row">
		<span id="form-header">
			<span id="master-caret" class="fontcon" data-visible="true">
				&#xf0d8;
			</span>
		</span>
	</div>

	<div class="row">
		<h4>
			<span>
				<?php echo Lang::txt('COM_TOOLBOX_LIST_SUBGROUP_SIZE'); ?>
			</span>
			<span class="caret fontcon">&#x2303;</span>
		</h4>

		<div class="content">
			<?php
				$this->view('_subgroup_select', 'advancedsearch')
					->set('query', $query)
					->display();
			?>
		</div>
		<hr>
	</div>

	<div class="row">
		<h4>
			<span>
				<?php echo Lang::txt('COM_TOOLBOX_LIST_EXTERNAL_COST'); ?>
			</span>
			<span class="caret fontcon">&#x2303;</span>
		</h4>

		<div class="content">
			<span class="inline-radio">
				<input type="radio" name="query[external_cost]" value="1"
					<?php
						$externalCost = $query->get('external_cost');
						if (!is_null($externalCost) && !!$externalCost):
							echo 'checked';
						endif;
					?>>
				<?php echo Lang::txt('COM_TOOLBOX_COMMON_YES'); ?>
			</span>
			<span class="inline-radio">
				<input type="radio" name="query[external_cost]" value="0"
					<?php
						$externalCost = $query->get('external_cost');
						if (!is_null($externalCost) && !$externalCost):
							echo 'checked';
						endif;
					?>>
				<?php echo Lang::txt('COM_TOOLBOX_COMMON_NO'); ?>
			</span>
		</div>
		<hr>
	</div>

	<div class="row">
		<h4>
			<span>
				<?php echo Lang::txt('COM_TOOLBOX_LIST_DURATION_MINUTES'); ?>
			</span>
			<span class="caret fontcon">&#x2303;</span>
		</h4>

		<div id="duration-fields" class="content">
			<input type="number" name="query[duration_min]" min="0"
				value="<?php echo $query->get('duration_min'); ?>"
				placeholder="min">

			<?php echo Lang::txt('COM_TOOLBOX_COMMON_TO'); ?>

			<input type="number" name="query[duration_max]" min="0"
				value="<?php echo $query->get('duration_max'); ?>"
				placeholder="max">
		</div>
		<hr>
	</div>

	<div class="row">
		<h4>
			<span>
				<?php echo Lang::txt('COM_TOOLBOX_LIST_TOOL_TYPE'); ?>
			</span>
			<span class="caret fontcon">&#x2303;</span>
		</h4>

		<div class="content">
			<?php
				$this->view('_type_select', 'advancedsearch')
					->set('query', $query)
					->set('types', $this->types)
					->display();
			?>
		</div>
		<hr>
	</div>

	<div class="row">
		<h4>
			<span>
				<?php echo Lang::txt('COM_TOOLBOX_LIST_KINESTHETIC'); ?>
			</span>
			<span class="caret fontcon">&#x2303;</span>
		</h4>

		<div class="content">
			<span class="inline-radio">
				<input type="radio" name="query[kinesthetic]" value="1"
					<?php
						$kinesthetic = $query->get('kinesthetic');
						if (!is_null($kinesthetic) && !!$kinesthetic):
							echo 'checked';
						endif;
					?>>
				<?php echo Lang::txt('COM_TOOLBOX_COMMON_YES'); ?>
			</span>
			<span class="inline-radio">
				<input type="radio" name="query[kinesthetic]" value="0"
					<?php
						$kinesthetic = $query->get('kinesthetic');
						if (!is_null($kinesthetic) && !$kinesthetic):
							echo 'checked';
						endif;
					?>>
				<?php echo Lang::txt('COM_TOOLBOX_COMMON_NO'); ?>
			</span>
		</div>
		<hr>
	</div>

	<div class="row">
		<h4>
			<span>
				<?php
					$this->view('_aacu_tooltip')
						->display();
					echo Lang::txt('COM_TOOLBOX_LIST_AACU_OUTCOMES');
				?>
				<span class="hasTip" title="<?php echo Lang::txt('COM_TOOLBOX_TIP_ONE_NOT_ALL'); ?>">
					<span class="fontcon">&#xf075;</span>
				</span>
			</span>
			<span class="caret fontcon">&#x2303;</span>
		</h4>

		<div class="content">
			<?php
				$this->view('_list_aacu_rubric_fields')
					->set('query', $query)
					->display();
			?>
		</div>
		<hr>
		<div class="hidden">
			<?php
				$blankQuery = new Components\Toolbox\Helpers\Query();
				$this->view('_list_bergs_fields')
					->set('query', $blankQuery)
					->display();
				$this->view('_list_idc_fields')
					->set('query', $blankQuery)
					->display();
				$this->view('_list_other_fields')
					->set('query', $blankQuery)
					->display();
			?>
		</div>
	</div>

	<div class="row">
		<span class="buttons">
			<input class="btn" type="submit"
				value="<?php echo Lang::txt('COM_TOOLBOX_COMMON_SEARCH'); ?>">

			<a href="<?php echo Route::url('/toolbox/advancedsearch'); ?>">
				<?php echo Lang::txt('COM_TOOLBOX_LIST_GUIDED_SEARCH'); ?>
			</a>
		</span>
	</div>

	<?php echo Html::input('token'); ?>
	<input type="hidden" name="origin" value="<?php echo $resultsUrl; ?>">
	<input type="hidden" name="forward" value="<?php echo $resultsUrl; ?>">

</form>
