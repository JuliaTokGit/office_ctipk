<?php

use Httpful\Request;

$context['downloaded'] = [];
$list = [];
$files = [];
$m = [];

foreach ($cfg->layouts->find() as $l) {
    $pl = empty($l['plugins']) ? [] : (array) $l['plugins'];
    $list = array_unique(array_merge($list, $pl));
}
foreach ($cfg->pages->find() as $l) {
    $pl = empty($l['plugins']) ? [] : (array) $l['plugins'];
    $list = array_unique(array_merge($list, $pl));
}
$response = Request::get($page->vault.'/mapping-plugins.json')->expectsJson()->send();
$map = $response->body;

$types = ['scripts', 'styles'];
foreach ($types as $type) {
    $mapped = array_map(
        function ($name) use ($map, $type) {
            if (!empty($map->{$type}->{$name})) {
                return [$name => $map->{$type}->{$name}];
            }
        }, $list);
    $plugins_map[$type] = array_reduce($mapped,
        function ($carry, $item) {
            return array_merge((array) $carry, (array) $item);
        }
    );
}
$cfg->common->save('mapping-plugins', $plugins_map);
// $files=array_merge($list, $pl)
$files = arrayFlatten($plugins_map);

exec('rm -f '.$path['root'].$path['assets'].'/plugins/*');

foreach ($files as $file) {
    passthru('cd '.$path['root'].$path['assets'].' && wget -x -nH '.$page->vault.'/'.$file.' > /dev/null &');
    $context['downloaded'][] = $file;
}

/////////

function arrayFlatten($array, &$newArray = array())
{
    foreach ($array as $value) {
        if (is_array($value)) {
            $newArray = arrayFlatten($value, $newArray);
        } else {
            if ($value != '') {
                $newArray[] = $value;
            }
        }
    }

    return $newArray;
}
