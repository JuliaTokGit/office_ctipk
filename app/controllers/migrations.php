<?php

use Illuminate\Database\Capsule\Manager as DB;

$migrations = [];

$migrations['201907141'] = function () {
    $info="Таблица для ChangeLog (Лог изменений в базе реализованный через trait Logged)";
    DB::schema()->create('change_logs', function ($table) {
        $table->increments('id');
        $table->string('name');
        $table->string('title');
        $table->string('class')->index();
        $table->unsignedInteger('object_id')->index();
        $table->text('diff');
        $table->unsignedInteger('user_id')->nullable()->index();
        $table->timestamps();
    });
    return $info;
};

$migrations['201907142'] = function () {
    $info="Таблица для UserType ";
    DB::schema()->create('user_types', function ($table) {
        $table->unsignedInteger('id')->unique();
        $table->string('name');
        $table->string('str_id')->index();
    });
    return $info;
};

$migrations['201907143'] = function () {
    $info="Заполняем UserType ";
    UserType::create(['id' => 0, 'name' => 'Разработчик', 'str_id' => 'developer']);
    UserType::create(['id' => 1, 'name' => 'Администратор', 'str_id' => 'admin']);
    return $info;
};

$migrations['201907144'] = function () {
    $info="Таблица для User ";
    DB::schema()->create('users', function ($table) {
        $table->increments('id');
        $table->string('username')->index();
        $table->string('password');
        $table->string('firstname');
        $table->string('lastname');
        $table->string('description');
        $table->unsignedInteger('upload_id')->nullable;
        $table->unsignedInteger('user_type_id')->default(1);
        $table->unsignedInteger('sequence')->index();
        $table->boolean('active');
        $table->timestamps();
    });
    return $info;
};

$migrations['201907145'] = function () {
    $info="Таблица для UserRememberHash ";
    DB::schema()->create('user_remember_hashes', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('user_id')->index();
        $table->string('hash')->index();
        $table->timestamps();
    });
    return $info;
};

$migrations['201907146'] = function () use ($user) {
    $info="Добавляем дефолтного юзера и логинимся под ним";
    User::create([
        'lastname' => 'System',
        'username' => 'spacewind@arx.ru',
        'password' => $user->hashPassword('spacewind'),
        'user_type_id' => 0, 'active' => true,
    ]);
    $user = $user->init([
            'auth_username' => 'spacewind@arx.ru',
            'auth_password' => 'spacewind',
            'auth_oper' => 'login',
            ]);
    $user = $user->loadRelation('type');
    return $info;
};

$migrations['201907147'] = function () use ($user) {
    $info="Добавляем тестового юзера";
    User::create([
        'firstname'=>'Тестовый',
        'lastname' => 'пользователь',
        'username' => 'user@arx.ru',
        'password' => $user->hashPassword('test'),
        'user_type_id' => 0, 'active' => true,
    ]);
    return $info;
};

$migrations['201907148'] = function () {
    $info="Таблица для UploadType (Типы файлов)";
    DB::schema()->create('upload_types', function ($table) {
        $table->increments('id');
        $table->string('name')->index();
        $table->string('icon')->index();
    });
    return $info;
};

$migrations['201907149'] = function () {
    $info="Заполняем UploadType ";
    UploadType::create(['name' => 'image/jpeg', 'icon' => 'fa fa-file-image-o']);
    UploadType::create(['name' => 'image/png', 'icon' => 'fa fa-file-image-o']);
    UploadType::create(['name' => 'image/gif', 'icon' => 'fa fa-file-image-o']);
    UploadType::create(['name' => 'application/pdf', 'icon' => 'fa  fa-file-pdf-o']);
    UploadType::create(['name' => 'application/msword', 'icon' => 'fa  fa-file-word-o']);
    UploadType::create(['name' => 'application/vnd.ms-excel', 'icon' => 'fa fa-file-excel-o']);
    UploadType::create(['name' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'icon' => 'fa fa-file-word-o']);
    UploadType::create(['name' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'icon' => 'fa fa-file-excel-o']);
    UploadType::create(['name' => 'application/x-zip-compressed', 'icon' => 'fa fa-file-archive-o']);
    UploadType::create(['name' => 'application/x-rar', 'icon' => 'fa fa-file-archive-o']);
    return $info;
};

$migrations['2019071410'] = function () {
    $info="Таблица для Upload (Загруженные файлы)";
    DB::schema()->create('uploads', function ($table) {
        $table->increments('id');
        $table->string('filename');
        $table->string('content_type')->index();
        $table->string('subdir');
        $table->bigInteger('file_size');
        $table->string('description');
        $table->unsignedInteger('user_id')->index();
        $table->string('full_path');
        $table->string('original_name');
        $table->integer('width');
        $table->integer('height');
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['20200216'] = function () {
    $info="Таблица для Request ";
    DB::schema()->create('requests', function ($table) {
        $table->increments('id');
        $table->string('name');        
        $table->unsignedInteger('user_id')->index();        
        $table->integer('quantity');
        $table->date('requested_at');        
        $table->timestamps();
    });
    DB::table('requests')->insert(['id' => 1000, 'name' => 'whatever']);
    DB::table('requests')->where('id', 1000)->delete();
    return $info;
};

$migrations['202002161'] = function () {
    $info="Таблица для Source ";
    DB::schema()->create('sources', function ($table) {
        $table->increments('id');
        $table->string('name');
        $table->unsignedInteger('request_id')->index();
        $table->unsignedInteger('upload_id')->index();
        $table->unsignedInteger('user_id')->index();
        $table->string('info');
        $table->string('guid');
        $table->integer('quantity');
        $table->boolean('parcels_xml');
        $table->timestamps();
    });
    return $info;
};

$migrations['20200311'] = function () {
    $info="Таблица для Results ";
    DB::schema()->create('results', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('upload_id')->index();
        $table->unsignedInteger('user_id')->index();
        $table->unsignedInteger('request_id')->index();
        $table->timestamps();
    });
    return $info;
};


$migrations['20200312'] = function () {
    $info="Таблица для Parcels ";
    DB::schema()->create('parcels', function ($table) {
        $table->increments('id');        
        $table->string('cadastral_number');
        $table->string('type');
        $table->string('cadastral_cost');
        $table->string('specific_cadastral_cost');
        $table->string('usage');
        $table->string('method');
        $table->date('determination_date');
        $table->unsignedInteger('user_id')->index();
        $table->unsignedInteger('result_id')->index();
        $table->unsignedInteger('source_id')->index();
        $table->unsignedInteger('group_id')->index();
        $table->timestamps();
    });
    return $info;
};

function execute($id){
    global $migrations, $migrated, $filters, $cfg;
    echo 'Executing migration: '.$id.': ';
    try {
        $migrated->{$id}=(object)['info'=>call_user_func($migrations[$id]),'date'=>date("Y-m-d H:i:s")];
        echo $migrated->{$id}->info."\n";
    } catch (Exception $e) {
        echo 'Ошибка: ',  $e->getMessage(), "\n";
    } finally {
        $cfg->common->save('migrated', $migrated);
    }
}

ob_start();
$migrated=$configs->migrated??(object)[];

echo'<pre>';
if (empty($filters['id'])) {
    foreach ($migrations as $key => $migration) {
        if (!isset($migrated->{$key})){
            execute($key);
        }
    }
} else {
    execute($filters['id']);
}
echo'</pre>';
$context['output'] = ob_get_contents();
ob_end_clean();
