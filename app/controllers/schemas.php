<?php

use Spacewind\FilesDB\Database as FDB;

function render($folder, $scheme)
{
    global $context, $path, $site;
    $engine = new Mustache_Engine(array(
        'cache' => $site->cache,
        'loader' => new Mustache_Loader_FilesystemLoader($folder, ['extension' => '.json']),
        'pragmas' => [Mustache_Engine::PRAGMA_FILTERS],
    ));
    if (!empty($site->mustache_helpers)) {
        $helpers = new Spacewind\MustacheHelpers($engine, $context);
        $helpers->add($site->mustache_helpers);
    }
    if (is_file($folder.'/'.$scheme.'.json')) {
        return $engine->render($scheme, $context);
    } else {
        return '{}';
    }
}

function generate_context($app, $scheme)
{
    global $context, $path;

    $config_db = new FDB($path['configs']);

    switch ($scheme) {
        case 'plugins':
            $plugins = (object) $config_db->common->findOne(array('_id' => 'mapping-plugins'));
            $context['plugins'] = json_encode(array_merge(array_keys($plugins->scripts), array_keys($plugins->styles)));
            break;

        case 'layouts':
            $layouts = $config_db->layouts->items;
            $context['layouts'] = json_encode($layouts);
            break;

        case 'scripts':
            $scripts = (array) $config_db->common->findOne(array('_id' => 'mapping-assets'))['scripts'];
            $context['scripts'] = json_encode(array_keys($scripts));
            break;

        case 'styles':
            $styles = (array) $config_db->common->findOne(array('_id' => 'mapping-assets'))['styles'];
            $context['styles'] = json_encode(array_keys($styles));
            break;

        default:
            // code...
            break;
    }
}

generate_context('service', $filters['id']);
$body = render($path['configs'].'schemas/', $filters['id']);

echo $body;

die();
