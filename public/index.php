<?php

require_once '../vendor/autoload.php'; // Автоподгрузчик классов (Composer)

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Events\Dispatcher;
use Spacewind\FilesDB\Database as FDB;
use Spacewind\Config;
use Spacewind\Auth;
use Spacewind\Page;
use Spacewind\Layout;
use Spacewind\Navigator;
use Spacewind\CustomLoader;
// use Cartalyst\Sentinel\Native\Facades\Sentinel;

/*

    Инициализация путей

 */
$path = array(
 'configs' => __DIR__.'/../app/configs/',
 'models' => __DIR__.'/../app/models/',
 'views' => __DIR__.'/../app/views/',
 'controllers' => __DIR__.'/../app/controllers/',
 'root' => __DIR__,
 'assets' => '/assets',
 'upload' => __DIR__.'/assets/upload/',
 'xsd' => __DIR__.'/assets/xsd/',
 'base' => '',
);

/*

    Контейнер для DI

 */
$container = Container::getInstance();

/*

    Загрузка конфигов

*/
setlocale(LC_ALL, 'ru_RU.utf8');

$cfg = new FDB($path['configs']);

$configs = new Config($cfg->common, false);
$pages = new Config($cfg->pages, false);
$layouts = new Config($cfg->layouts, false);
$site = $configs->{'site-'.$_SERVER['SERVER_NAME']};


/*

    Показ ошибок и дебаггер

*/
if (empty($site->debug)) {
    function debug($object)
    {
        return true;
    }
    ini_set('display_errors', 'Off');
    error_reporting(0);
} else {
    function debug($object)
    {
        ChromePhp::log($object);

        return true;
    }
    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);
}


/*

    Инициализация базы

*/

$db = new DB();

$db->getDatabaseManager()->extend('mongodb', function($config, $name) {
    $config['name'] = $name;
    return new Jenssegers\Mongodb\Connection($config);
});

foreach ($site->connections as $key => $connection) {
  $db->addConnection((array) $connection, $key);
}
// $db->getDatabaseManager()->extend('mongodb', function($config)
// {
//     return new Jenssegers\Mongodb\Connection($config);
// });

// $db->addConnection((array)$site->mongo_connection,'mongodb');

// $db->addConnection((array) $site->connections->default);
$db->setEventDispatcher(new Dispatcher(new Container()));
$db->setAsGlobal();
$db->bootEloquent();
$db::connection()->enableQueryLog();




/*

    Авторизация

*/
// define('PERMISSION_CREATE', 1);
// define('PERMISSION_READ', 2);
// define('PERMISSION_UPDATE', 4);
// define('PERMISSION_DELETE', 8);
//
// define('PERMISSION_READ_OWNED', 16);  // "Only Own" Scope
// define('PERMISSION_UPDATE_OWNED', 32);  // "Only Own" Scope
// define('PERMISSION_DELETE_OWNED', 64);  // "Only Own" Scope
//
// $user = $container->make(Auth::class)->setSalt($site->hash_code)->init($_POST);
// $user = $user->loadRelation('type')->loadRelation('photo');

$container->sentinel = (new \Cartalyst\Sentinel\Native\Facades\Sentinel())->getSentinel();

/*

    Подготовка парсеров

*/

$page = new Page();



// if (!in_array($page->name, ['login', 'register', 'remind', 'migrate']) && !$user->logged) {
//     $page = new Page('login');
// }

// if ($user->logged && empty($configs->access->{$user->type->str_id}->pages->{$page->name}) && $user->type->id > 0) {
//     // Если страница не в списке разрешенных и не юзер не админ посылаем.
//     exit(header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden'));
// }

// if (($user->logged) && !empty($configs->access->{$user->type->str_id}->navigation)) {
//     $navigation = new Navigator($configs->{$configs->access->{$user->type->str_id}->navigation});
// } else {
    $navigation = new Navigator($configs->nav);
// }

$nav = $navigation->create($page);
$layout = new Layout($page->layout);
$filters = $page->filters;



/*

    Подготовка контекста

*/

setlocale(LC_TIME, 'ru');
date_default_timezone_set('Europe/Moscow');

$context = array(
    'page' => $page,
    // 'user' => $user,
    'layout' => $layout,
    'nav' => $navigation,
    'path' => $path,
    'site' => $site,
    'breadcrumbs' => $navigation->breadcrumbs,
    'post' => $_POST,
    'filters' => $page->filters,
);

// debug($context);

if (isset($page->includes)) {
    foreach ($page->includes as $include) {
        include_once $path['controllers'].'includes/'.$include.'.php';
    }
}

if (isset($page->controller)) {
    include_once $path['controllers'].$page->controller.'.php';
} elseif (file_exists($path['controllers'].$page->name.'.php')) {
    include_once $path['controllers'].$page->name.'.php';
}

/*

    Теперь отображаем страничку

*/

// $debug($context['page']);
// $debug(DB::getQueryLog());

$engine = new Mustache_Engine(array(
    'cache' => $site->cache,
    'pragmas' => [Mustache_Engine::PRAGMA_FILTERS],
    'partials_loader' => new CustomLoader($path['views'], array('page' => $page->name, 'layout' => $page->layout)),
));

if (!empty($site->mustache_helpers)) {
    $helpers = new Spacewind\MustacheHelpers($engine, $context);
    $helpers->add($site->mustache_helpers);
}

echo $engine->render($layout->getTemplate(), $context);
