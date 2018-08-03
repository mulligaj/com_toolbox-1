<?php

namespace Components\Toolbox\Admin;

$componentAdminPath = Component::path('com_toolbox') . '/admin';

require_once "$componentAdminPath/helpers/permissions.php";

use \App;
use \Lang;
use \Request;
use \Route;
use \Submenu;
use \User;

if (!User::authorise('core.manage','com_toolbox'))
{
	return App::abort(404, Lang::txt('JERROR_ALERTNOAUTHOR'));
}

$defaultControllerName = 'tools';
$controllerName = strtolower(Request::getCmd('controller'));
$taskName = Request::getCmd('task');

if (!file_exists("$componentAdminPath/controllers/$controllerName.php"))
{
	$controllerName = $defaultControllerName;
}

require_once "$componentAdminPath/controllers/$controllerName.php";

$submenuEntries = [
	[
		'text' => 'Tools',
		'url' => Route::url('index.php?option=com_toolbox&controller=tools&task=list'),
		'selectedTest' => ($controllerName === 'tools' && ($taskName === '' || $taskName === 'list'))
	],
	[
		'text' => 'Tool Types',
		'url' => Route::url('index.php?option=com_toolbox&controller=toolTypes&task=list'),
		'selectedTest' => ($controllerName === 'tooltypes' && ($taskName === '' || $taskName === 'list'))
	],
	[
		'text' => 'Reviews',
		'url' => Route::url('index.php?option=com_toolbox&controller=reviews&task=list'),
		'selectedTest' => ($controllerName === 'reviews' && $taskName === 'list')
	],
	[
		'text' => 'Archived Tools',
		'url' => Route::url('index.php?option=com_toolbox&controller=tools&task=archived'),
		'selectedTest' => ($controllerName === 'tools' && $taskName === 'archived')
	],
	[
		'text' => 'Archived Types',
		'url' => Route::url('index.php?option=com_toolbox&controller=toolTypes&task=archived'),
		'selectedTest' => ($controllerName === 'tooltypes' && $taskName === 'archived')
	]
];

foreach ($submenuEntries as $entry)
{
	Submenu::addEntry($entry['text'], $entry['url'], $entry['selectedTest']);
}

$controllerClassNameMap = [
	'reviews' => 'Reviews',
	'tools' => 'Tools',
	'tooltypes' => 'ToolTypes'
];

$controllerClassName = __NAMESPACE__ . "\\Controllers\\" . $controllerClassNameMap[$controllerName];

$controller = new $controllerClassName();

$controller->execute();
