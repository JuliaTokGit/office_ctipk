<?php

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint as Blueprint;

$migrations = [];

$migrations['1'] = function () {
    $info="Миграция Sentinel";
    DB::schema()->create('activations', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned();
        $table->string('code');
        $table->boolean('completed')->default(0);
        $table->timestamp('completed_at')->nullable();
        $table->timestamps();

        $table->engine = 'InnoDB';
    });

    DB::schema()->create('persistences', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned();
        $table->string('code');
        $table->timestamps();

        $table->engine = 'InnoDB';
        $table->unique('code');
    });

    DB::schema()->create('reminders', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned();
        $table->string('code');
        $table->boolean('completed')->default(0);
        $table->timestamp('completed_at')->nullable();
        $table->timestamps();

        $table->engine = 'InnoDB';
    });

    DB::schema()->create('roles', function (Blueprint $table) {
        $table->increments('id');
        $table->string('slug');
        $table->string('name');
        $table->text('permissions')->nullable();
        $table->timestamps();

        $table->engine = 'InnoDB';
        $table->unique('slug');
    });

    DB::schema()->create('role_users', function (Blueprint $table) {
        $table->integer('user_id')->unsigned();
        $table->integer('role_id')->unsigned();
        $table->nullableTimestamps();

        $table->engine = 'InnoDB';
        $table->primary(['user_id', 'role_id']);
    });

    DB::schema()->create('throttle', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned()->nullable();
        $table->string('type');
        $table->string('ip')->nullable();
        $table->timestamps();

        $table->engine = 'InnoDB';
        $table->index('user_id');
    });

    DB::schema()->create('users', function (Blueprint $table) {
        $table->increments('id');
        $table->string('email');
        $table->string('password');
        $table->text('permissions')->nullable();
        $table->timestamp('last_login')->nullable();
        $table->string('first_name')->nullable();
        $table->string('last_name')->nullable();
        $table->timestamps();

        $table->engine = 'InnoDB';
        $table->unique('email');
    });
    return $info;
};

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
        $table->text('name');
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
