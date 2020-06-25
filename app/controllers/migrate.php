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

$migrations['20200625'] = function () {
    $info="Таблица для Form ";
    DB::schema()->create('forms', function ($table) {
        $table->increments('id');
        $table->string('data_url');
        $table->text('data');
        $table->timestamps();
    });
    return $info;
};



function execute($id) {
    global $migrations;
    echo 'Executing migration: '.$id.': ';
    try {
        $info = call_user_func($migrations[$id]);

        DB::connection()->table('migrations')->insertOrIgnore([
            'key' => $id,
            'info' => $info,
            'created_at' => DB::connection()->raw('now()')
        ]);

        echo $info."<br/>";
        flush();
		ob_flush();
    } catch (Exception $e) {
        echo 'Ошибка: ',  $e->getMessage(), "<br/>";
    }
}

if (!DB::schema()->hasTable('migrations')) {
    $info = "Таблица миграций";
    DB::schema()->create('migrations', function ($table) {
        $table->increments('id');
        $table->string('key')->unique();
        $table->string('info');
        $table->boolean('is_harmful')->default(false);
        $table->timestamps();
    });
}

$options = getopt("n:");

if (empty($options['n'])) {
    foreach ($migrations as $key => $migration) {
        if(stripos($key,'harmful') !== false) {
            throw new \Exception("Migrations consists harmful entries, please, run script manually.");
        }

        $migrated = DB::connection()->table('migrations')->where('key', $key)->get();

        if ($migrated->isEmpty()){
            execute($key);
        }
    }
} else {
    execute($options['n']);
}
