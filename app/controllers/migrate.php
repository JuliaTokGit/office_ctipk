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

$migrations['2019071411'] = function () {
    $info="Таблица для Client ";
    DB::schema()->create('clients', function ($table) {
        $table->increments('id');
        $table->string('username')->index();
        $table->string('password');
        $table->string('client_type')->default('Организация')->index();
        $table->string('name');
        $table->string('lastname');
        $table->string('firstname');
        $table->string('surname');
        $table->text('fullname');
        $table->text('names');
        $table->string('address');
        $table->text('fulladdress');
        $table->string('post_address');
        $table->string('phone');
        $table->text('management');
        $table->text('dadata');
        $table->string('inn');
        $table->string('kpp');
        $table->string('ogrn');
        $table->integer('credit_limit');
        $table->boolean('email_verified');
        $table->boolean('email_hash');
        $table->unsignedInteger('bx_company_id')->index();
        $table->boolean('active')->default(true)->index();
        $table->unsignedInteger('sequence')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};


$migrations['2019071412'] = function () {
    $info="Таблица для Course ";
    DB::schema()->create('courses', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('course_group_id')->index();
        $table->string('name')->index();
        $table->string('title');
        $table->text('description');
        $table->unsignedInteger('price');
        $table->unsignedInteger('user_id')->index();
        $table->unsignedInteger('questions');
        $table->unsignedInteger('seconds_to_test')->default(900);
        $table->unsignedInteger('correct_to_pass');
        $table->unsignedInteger('bx_product_id')->index();
        $table->unsignedInteger('sequence')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071413'] = function () {
    $info="Таблица для CourseGroup ";
    DB::schema()->create('course_groups', function ($table) {
        $table->increments('id');
        $table->string('name');
        $table->unsignedInteger('sequence')->index();
        $table->unsignedInteger('bx_productsection_id')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071414'] = function () {
    $info="Таблица для Lesson ";
    DB::schema()->create('lessons', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('course_id')->index();
        $table->string('title');
        $table->text('body');
        $table->unsignedInteger('user_id')->index();
        $table->unsignedInteger('sequence')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071415'] = function () {
    $info="Таблица для Question ";
    DB::schema()->create('questions', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('course_id')->index();
        $table->text('statement');
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071416'] = function () {
    $info="Таблица для Answer ";
    DB::schema()->create('answers', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('question_id')->index();
        $table->text('sentence');
        $table->boolean('is_correct');
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071417'] = function () {
    $info="Таблица для Order ";
    DB::schema()->create('orders', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('client_id')->index();
        $table->unsignedInteger('course_id')->index();
        $table->integer('cost');
        $table->string('partner_code')->index();
        $table->string('promo_code')->index();
        $table->string('title');
        $table->date('start_date')->index();
        $table->unsignedInteger('bx_invoice_id')->index();
        $table->string('invoice_link');
        $table->unsignedInteger('bx_deal_id')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071418'] = function () {
    $info="Таблица для OrderStatus ";
    DB::schema()->create('order_statuses', function ($table) {
        $table->unsignedInteger('id')->unique();
        $table->string('name');
        $table->string('class');
        $table->unsignedInteger('step')->index();
        $table->unsignedInteger('importance')->index();
        $table->string('description');
        $table->string('bx_status_id')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071419'] = function () {
    $info="Таблица для кроссов Order & Status ";
    DB::schema()->create('cross_order_status', function ($table) {
        $table->increments('id');
        $table->integer('order_id')->unsigned();
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        $table->integer('order_status_id')->unsigned();
        $table->foreign('order_status_id')->references('id')->on('order_statuses')->onDelete('cascade');
        $table->timestamps();
    });
    return $info;
};

$migrations['2019071420'] = function () {
    $info="Таблица для Student ";
    DB::schema()->create('students', function ($table) {
        $table->increments('id');
        $table->string('lastname');
        $table->string('firstname');
        $table->string('surname');
        $table->text('passport');
        $table->text('passport_info');
        $table->string('address');
        $table->string('phone');
        $table->string('email');
        $table->unsignedInteger('photo_id')->index();
        $table->unsignedInteger('signature_id')->index();
        $table->unsignedInteger('client_id')->index();
        $table->string('company');
        $table->string('job_position');
        $table->unsignedInteger('bx_contact_id')->index();
        $table->string('code')->unique();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071421'] = function () {
    $info="Таблица для кроссов Order & Student ";
    DB::schema()->create('order_student', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('student_id')->index();
        $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        $table->unsignedInteger('order_id')->index();
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        $table->integer('result')->index();
        $table->string('code')->nullable()->index();
        $table->integer('last_lesson_id');
        $table->dateTime('started_at')->nullable()->index();
        $table->timestamps();
    });
    return $info;
};



$migrations['2019071422'] = function () {
    $info="Таблица для Exam ";
    DB::schema()->create('exams', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('student_id')->index();
        $table->unsignedInteger('order_id')->index();
        $table->boolean('is_try');
        $table->integer('questions');
        $table->integer('assessment');
        $table->boolean('is_passed');
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071423'] = function () {
    $info="Таблица для Choice ";
    DB::schema()->create('choices', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('student_id')->index();
        $table->unsignedInteger('exam_id')->index();
        $table->string('question')->index();
        $table->string('answer')->index();
        $table->string('correct');
        $table->boolean('is_correct');
        $table->string('remote_addr');
        $table->string('http_user_agent');
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};


$migrations['2019071424'] = function () {
    $info="Таблица для Handbook ";
    DB::schema()->create('handbooks', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('course_id')->index();
        $table->unsignedInteger('upload_id')->index();
        $table->unsignedInteger('sequence')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};


$migrations['2019071425'] = function () {
    $info="Таблица для Slide ";
    DB::schema()->create('slides', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('course_id')->index();
        $table->unsignedInteger('upload_id')->index();
        $table->unsignedInteger('album_id')->index();
        $table->unsignedInteger('sequence')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071426'] = function () {
    $info="Таблица для Lection ";
    DB::schema()->create('lections', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('lesson_id')->index();
        $table->unsignedInteger('upload_id')->index();
        $table->unsignedInteger('sequence')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071427'] = function () {
    $info="Таблица для Sertificate ";
    DB::schema()->create('sertificates', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('student_id')->index();
        $table->unsignedInteger('order_id')->index();
        $table->unsignedInteger('learning_id')->index();
        $table->date('issue_date');
        $table->date('expire_date');
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};


$migrations['2019071429'] = function () {
    $info="Таблица для SertificateStatus ";
    DB::schema()->create('sertificate_statuses', function ($table) {
        $table->unsignedInteger('id')->unique();
        $table->string('name');
        $table->string('description');
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071430'] = function () {
    $info="Таблица для кроссов Sertificate & SertificateStatus ";
    DB::schema()->create('cross_sertificate_status', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('sertificate_id')->index();
        $table->foreign('sertificate_id')->references('id')->on('sertificates')->onDelete('cascade');
        $table->unsignedInteger('sertificate_status_id')->index();
        $table->foreign('sertificate_status_id')->references('id')->on('sertificate_statuses')->onDelete('cascade');
        $table->timestamps();
    });
    return $info;
};

$migrations['2019071431'] = function () {
    $info="Таблица для Message ";
    DB::schema()->create('messages', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('student_id')->index();
        $table->unsignedInteger('order_id')->index();
        $table->unsignedInteger('user_id')->index();
        $table->boolean('is_readed')->index();
        $table->string('type')->index();
        $table->text('body');
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071432'] = function () {
    $info="Таблица для Organiser ";
    DB::schema()->create('organisers', function ($table) {
        $table->increments('id');
        $table->string('lastname');
        $table->string('firstname');
        $table->string('surmane');
        $table->string('phone');
        $table->string('email');
        $table->string('password');
        $table->integer('money_balance');
        $table->string('partner_code')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};


$migrations['2019071434'] = function () {
    $info="Таблица для ClientRememberHash ";
    DB::schema()->create('client_remember_hashes', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('user_id')->index();
        $table->string('hash')->index();
        $table->timestamps();
    });
    return $info;
};


$migrations['2019071435'] = function () {
    $info="Таблица для синхронизации с Bitrix24 ";
    DB::schema()->create('bitrix24s', function ($table) {
        $table->increments('id');
        $table->string('method');
        $table->unsignedInteger('model_id')->index();
        $table->text('response');
        $table->unsignedInteger('attempt')->index();
        $table->boolean('success');
        $table->timestamps();
    });
    return $info;
};

$migrations['2019071436'] = function () {
    $info="Заполняем CourseGroup ";
    CourseGroup::create(['id'=>1, 'name' => 'Электробезопасность']);
    CourseGroup::create(['id'=>2, 'name' => 'Гражданская оборона и чрезвычайные ситуации (ГО и ЧС)']);
    CourseGroup::create(['id'=>3, 'name' => 'Экологическая безопасность']);
    CourseGroup::create(['id'=>4, 'name' => 'Пожарная безопасность']);
    CourseGroup::create(['id'=>5, 'name' => 'Пожарно-технический минимум']);
    CourseGroup::create(['id'=>6, 'name' => 'Охрана труда при выполнении работ на высоте']);
    CourseGroup::create(['id'=>7, 'name' => 'Охрана труда']);
    return $info;
};

$migrations['2019071437'] = function () {
    $info="Заполняем Course ";
    Course::create(['course_group_id' => 1,'name' => 'Электробезопасность 5 группа допуска', 'user_id'=>1]);
    Course::create(['course_group_id' => 1,'name' => 'Электробезопасность 4 группа допуска', 'user_id'=>1]);
    Course::create(['course_group_id' => 1,'name' => 'Электробезопасность 3 группа допуска', 'user_id'=>1]);
    Course::create(['course_group_id' => 1,'name' => 'Электробезопасность 2 группа допуска', 'user_id'=>1]);
    Course::create(['course_group_id' => 1,'name' => 'Повышение квалификации электротехнического персонала по электробезопасности', 'user_id'=>1]);
    Course::create(['course_group_id' => 2,'name' => 'Обучению должностных лиц и специалистов органов управления и сил ГО и РСЧС', 'user_id'=>1]);
    Course::create(['course_group_id' => 2,'name' => 'Обучение должностных лиц и специалистов ГО и РСЧС организаций по ГО и защите от ЧС', 'user_id'=>1]);
    Course::create(['course_group_id' => 3,'name' => 'Обеспечение экологической безопасности при работах в области обращения с отходами I-IV класса опасности', 'user_id'=>1]);
    Course::create(['course_group_id' => 3,'name' => 'Основы обеспечения экологической безопасности в организации', 'user_id'=>1]);
    Course::create(['course_group_id' => 4,'name' => 'Проектирование систем обеспечения пожарной безопасности зданий и сооружений', 'user_id'=>1]);
    Course::create(['course_group_id' => 4,'name' => 'Монтаж, ремонт, техническое обслуживание систем обеспечения пожарной безопасности зданий и сооружений', 'user_id'=>1]);
    Course::create(['course_group_id' => 4,'name' => 'Монтаж, ремонт, техническое обслуживание и перезарядка технических средств пожаротушения', 'user_id'=>1]);
    Course::create(['course_group_id' => 4,'name' => 'Монтаж, наладка, техническое обслуживание и ремонт установок пожаротушения, пожарной, охранной и пожарно-охранной сигнализации', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для работников культовых сооружений', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для механизаторов, рабочих и служащих сельскохозяйственных объектов', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для руководителей сельскохозяйственных организаций и ответственных за пожарную безопасность', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для киномехаников', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для ответственных за пожарную безопасность вновь строящихся и реконструируемых объектов', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для руководителей подразделений и сотрудников, осуществляющих круглосуточную охрану организаций', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для руководителей и ответственных за пожарную безопасность жилых домов', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для газоэлектросварщиков', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для руководителей подразделений пожароопасных производств', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для рабочих, осуществляющих пожароопасные работы', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для руководителей и лиц, ответственных за пожарную безопасность пожароопасных производств', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для руководителей и ответственных за пожарную безопасность организаций бытового обслуживания', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для руководителей и ответственных за ПБ организаций торговли, общественного питания, баз и складов', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для руководителей и ответственных за ПБ театрально-зрелищных и культурно-просветительских учреждений', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для руководителей и ответственных за пожарную безопасность лечебных учреждений', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для воспитателей дошкольных учреждений', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для руководителей и ответственных за пожарную безопасность образовательных учреждений', 'user_id'=>1]);
    Course::create(['course_group_id' => 5,'name' => 'ПТМ для руководителей и ответственных за пожарную безопасность в учреждениях (офисах)', 'user_id'=>1]);
    Course::create(['course_group_id' => 6,'name' => 'Безопасные методы и приемы выполнения работ на высоте с применением средств подмащивания, а также на площадках с защитными ограждениями высотой 1,1 м и более', 'user_id'=>1]);
    Course::create(['course_group_id' => 6,'name' => 'Безопасные методы и приемы выполнения работ на высоте для работников 3 группы', 'user_id'=>1]);
    Course::create(['course_group_id' => 6,'name' => 'Безопасные методы и приемы выполнения работ на высоте для работников 2 группы', 'user_id'=>1]);
    Course::create(['course_group_id' => 6,'name' => 'Безопасные методы и приемы выполнения работ на высоте для работников 1 группы', 'user_id'=>1]);
    Course::create(['course_group_id' => 7,'name' => 'Профессиональная переподготовка специалистов, осуществляющих работы в области охраны труда', 'user_id'=>1]);
    Course::create(['course_group_id' => 7,'name' => 'Охрана труда руководителей и специалистов нефтебаз, складов ГСМ, АЗС', 'user_id'=>1]);
    Course::create(['course_group_id' => 7,'name' => 'Охрана труда руководителей и специалистов организаций - субъектов малого предпринимательства', 'user_id'=>1]);
    Course::create(['course_group_id' => 7,'name' => 'Охрана труда руководителей и специалистов организаций сельского хозяйства', 'user_id'=>1]);
    Course::create(['course_group_id' => 7,'name' => 'Охрана труда руководителей и специалистов промышленных предприятий', 'user_id'=>1]);
    Course::create(['course_group_id' => 7,'name' => 'Охрана труда руководителей и специалистов строительных организаций', 'user_id'=>1]);
    Course::create(['course_group_id' => 7,'name' => 'Охрана труда руководителей и специалистов учреждений здравоохранения и социального обеспечения', 'user_id'=>1]);
    Course::create(['course_group_id' => 7,'name' => 'Охрана труда руководителей и специалистов учреждений образования, культуры и спорта', 'user_id'=>1]);
    Course::create(['course_group_id' => 7,'name' => 'Охрана труда руководителей и специалистов', 'user_id'=>1]);
    Course::query()->update(['price' => 1000,'description'=>'<p>Определители второго и третьего порядков, их свойства. Алгебраические дополнения и миноры. Вычисление определителя разложением по строке (столбцу). Правило треугольника, вычисление определителя третьего порядка.</p>']);
    return $info;
};

$migrations['2019071438'] = function () {
    $info="Заполняем OrderStatus ";
    OrderStatus::create(['id'=>0, 'name' => 'Выбор курса', 'step'=>0, 'importance'=>0, 'bx_status_id'=>'NEW', 'description'=>'','class'=>'label-success']);
    OrderStatus::create(['id'=>1, 'name' => 'Выбор сотрудников для обучения', 'step'=>1, 'importance'=>0,  'bx_status_id'=>'NEW','description'=>'','class'=>'label-success']);
    OrderStatus::create(['id'=>2, 'name' => 'Выбор адреса доставки', 'step'=>2, 'importance'=>0,  'bx_status_id'=>'NEW','description'=>'','class'=>'label-success']);
    OrderStatus::create(['id'=>3, 'name' => 'Выбор способа получения документов', 'step'=>3, 'importance'=>0,  'bx_status_id'=>'NEW','description'=>'','class'=>'label-success']);
    OrderStatus::create(['id'=>4, 'name' => 'Ожидает размещения', 'step'=>4, 'importance'=>1, 'bx_status_id'=>'NEW','description'=>'','class'=>'label-success']);
     OrderStatus::create(['id'=>5, 'name' => 'Подготовка документов', 'step'=>5, 'importance'=>1, 'bx_status_id'=>'PREPARATION','description'=>'','class'=>'label-success']);
    OrderStatus::create(['id'=>6, 'name' => 'Идет обучение', 'step'=>6, 'importance'=>2, 'bx_status_id'=>'EXECUTING','description'=>'','class'=>'label-success']);
    OrderStatus::create(['id'=>7, 'name' => 'Закончено обучение', 'step'=>7, 'importance'=>0, 'bx_status_id'=>'2','description'=>'','class'=>'label-success']);
    OrderStatus::create(['id'=>8, 'name' => 'Ожидает оплаты', 'step'=>8, 'importance'=>4, 'bx_status_id'=>'2','description'=>'','class'=>'label-success']);
    OrderStatus::create(['id'=>9, 'name' => 'Изготовление документов', 'step'=>9, 'importance'=>0, 'bx_status_id'=>'FINAL_INVOICE','description'=>'','class'=>'label-success']);
    OrderStatus::create(['id'=>10, 'name' => 'Доставка документов', 'step'=>10, 'importance'=>0, 'bx_status_id'=>'4','description'=>'','class'=>'label-success']);
    OrderStatus::create(['id'=>11, 'name' => 'Ожидает подписанный акт', 'step'=>11, 'importance'=>3, 'bx_status_id'=>'3','description'=>'','class'=>'label-success']);
    OrderStatus::create(['id'=>12, 'name' => 'Архивный', 'step'=>12, 'importance'=>0, 'bx_status_id'=>'WON','description'=>'','class'=>'label-success']);
    OrderStatus::create(['id'=>13, 'name' => 'Архивный', 'step'=>13, 'importance'=>0, 'bx_status_id'=>'LOSE','description'=>'','class'=>'label-success']);
    return $info;
};


$migrations['2019071439'] = function () {
    $info="Таблица для Album ";
    DB::schema()->create('albums', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('course_id')->index();
        $table->string('title');
        $table->unsignedInteger('sequence')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};


$migrations['2019071440'] = function () {
    $info="Таблица для Video ";
    DB::schema()->create('videos', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('course_id')->index();
        $table->string('title');
        $table->string('vimeo_id');
        $table->unsignedInteger('sequence')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};


$migrations['2019071443'] = function () {
    $info="Таблица для Invoice ";
    DB::schema()->create('invoices', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('order_id')->index();
        $table->string('summa');
        $table->unsignedInteger('upload_id')->index();
        $table->boolean('is_paid');
        $table->unsignedInteger('sequence')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};


$migrations['2019071444'] = function () {
    $info="Добавим срок обучения в Курсы ";
    DB::schema()->table('courses', function ($table) {
        $table->unsignedInteger('hours');
    });
    return $info;
};



$migrations['2019071445'] = function () {
    $info="Таблица для Contract ";
    DB::schema()->create('contracts', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('order_id')->index();
        $table->string('summa');
        $table->unsignedInteger('upload_id')->index();
        $table->boolean('is_paid');
        $table->unsignedInteger('sequence')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};


$migrations['2019071446'] = function () {
    $info="Добавим стиль уведомлений в Статус Заказа  ";
    DB::schema()->table('order_statuses', function ($table) {
        $table->string('style');
    });
    return $info;
};



$migrations['2019071447'] = function () {
    $info="Добавим первые буквы сертификата в Курс  ";
    DB::schema()->table('courses', function ($table) {
        $table->string('sertificate_number_letters');
    });
    return $info;
};


$migrations['2019071448'] = function () {
    $info="Добавим цвет в Статус Заказа  ";
    DB::schema()->table('order_statuses', function ($table) {
        $table->string('color');
    });
    return $info;
};


$migrations['2019071449'] = function () {
    $info="Добавим тип доставки и адрес в Заказ  ";
    DB::schema()->table('orders', function ($table) {
        $table->string('delivery_type');
        $table->text('delivery_address');
    });
    return $info;
};

$migrations['2019071450'] = function () {
    $info="Добавим стоимость доставки в Заказ  ";
    DB::schema()->table('orders', function ($table) {
        $table->integer('delivery_cost');
    });
    return $info;
};

$migrations['2019071451'] = function () {
    $info="Добавим указатели на файлы шаблонов в Группы Курсов  ";
    DB::schema()->table('course_groups', function ($table) {
        $table->unsignedInteger('protocol_template_id');
    });
    return $info;
};

$migrations['2019071452'] = function () {
    $info="Таблица для Template ";
    DB::schema()->create('templates', function ($table) {
        $table->increments('id');
        $table->string('name')->index();
        $table->string('description');
        $table->unsignedInteger('upload_id')->index();
        $table->string('str_id')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['2019071453'] = function () {
    $info="Добавим указатели на клиента в Контракте  ";
    DB::schema()->table('contracts', function ($table) {
        $table->unsignedInteger('client_id');
    });
    return $info;
};

$migrations['20190715'] = function () {
    $info="Таблица для ClientLog";
    DB::schema()->create('client_logs', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('client_id')->index();
        $table->string('remote_addr');
        $table->string('http_user_agent');
        $table->timestamps();
    });
    return $info;
};


$migrations['20190716'] = function () {
    $info="Таблица для Deal";
    DB::schema()->create('deals', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('client_id')->index();
        $table->boolean('is_paid');
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['201907161'] = function () {
  $info="Добавим связь сделки и заказа  ";
  DB::schema()->table('orders', function ($table) {
      $table->unsignedInteger('deal_id');
  });
    return $info;
};


$migrations['201907162'] = function () {
  $info="Добавим связь сделки и счета  ";
  DB::schema()->table('invoices', function ($table) {
      $table->unsignedInteger('deal_id');
  });
    return $info;
};


$migrations['201907163'] = function () {
  $info="Таблица для AdmitOrder";
  DB::schema()->create('admit_orders', function ($table) {
      $table->increments('id');
      $table->string('name');
      $table->unsignedInteger('contract_id')->index();
      $table->unsignedInteger('client_id')->index();
      $table->unsignedInteger('order_id')->index();
      $table->unsignedInteger('deal_id')->index();
      $table->unsignedInteger('upload_id')->index();
      $table->unsignedInteger('sequence')->index();
      $table->timestamps();
      $table->softDeletes();
  });
  return $info;
};

$migrations['201907164'] = function () {
  $info="Таблица для ContractApplication";
  DB::schema()->create('contract_applications', function ($table) {
      $table->increments('id');
      $table->string('name');
      $table->unsignedInteger('contract_id')->index();
      $table->unsignedInteger('client_id')->index();
      $table->unsignedInteger('deal_id')->index();
      $table->unsignedInteger('upload_id')->index();
      $table->unsignedInteger('sequence')->index();
      $table->timestamps();
      $table->softDeletes();
  });
  return $info;
};

$migrations['20190721'] = function () {
  $info="Добавим секрет Клиенту  ";
  DB::schema()->table('clients', function ($table) {
      $table->string('secret');
  });
    return $info;
};

$migrations['20190722'] = function () {
  $info="Таблица для ExpelOrder";
  DB::schema()->create('expel_orders', function ($table) {
      $table->increments('id');
      $table->string('name');
      $table->unsignedInteger('order_id')->index();
      $table->unsignedInteger('upload_id')->index();
      $table->unsignedInteger('sequence')->index();
      $table->timestamps();
      $table->softDeletes();
  });
  return $info;
};

$migrations['201907221'] = function () {
  $info="Таблица для Act";
  DB::schema()->create('acts', function ($table) {
      $table->increments('id');
      $table->string('name');
      $table->unsignedInteger('deal_id')->index();
      $table->unsignedInteger('upload_id')->index();
      $table->unsignedInteger('sequence')->index();
      $table->timestamps();
      $table->softDeletes();
  });
  return $info;
};

$migrations['201907222'] = function () {
  $info="Таблица для Protocol";
  DB::schema()->create('protocols', function ($table) {
      $table->increments('id');
      $table->string('name');
      $table->unsignedInteger('order_id')->index();
      $table->unsignedInteger('upload_id')->index();
      $table->unsignedInteger('sequence')->index();
      $table->timestamps();
      $table->softDeletes();
  });
  return $info;
};

$migrations['201907223'] = function () {
  $info="Добавим нумератор Приложению к договору  ";
  DB::schema()->table('contract_applications', function ($table) {
      $table->integer('enum');
  });
    return $info;
};



$migrations['201907224'] = function () {
  $info="Добавим флаг отправки к договору  ";
  DB::schema()->table('contracts', function ($table) {
      $table->boolean('is_sent');
  });
    return $info;
};


$migrations['20190723'] = function () {
  $info="Добавим внешние ключи к разным штукам";
  DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('students', function ($table) {
    // $table->dropForeign(['client_id']);
    $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['201907231'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('contracts', function ($table) {
    // $table->dropForeign(['client_id']);
    $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
  });

  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['201907232'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('contract_applications', function ($table) {
    // $table->dropForeign(['deal_id']);
    // $table->dropForeign(['contract_id']);
    $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
    $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['201907233'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('client_logs', function ($table) {
    // $table->dropForeign(['client_id']);
    $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['201907234'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('answers', function ($table) {
    // $table->dropForeign(['question_id']);
    $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['201907235'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('lessons', function ($table) {
    // $table->dropForeign(['course_id']);
    $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['201907236'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('exams', function ($table) {
    // $table->dropForeign(['order_id']);
    // $table->dropForeign(['student_id']);
    $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
    $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['201907237'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('choices', function ($table) {
    // $table->dropForeign(['exam_id']);
    // $table->dropForeign(['student_id']);
    $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
    $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['201907238'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('slides', function ($table) {
    // $table->dropForeign(['album_id']);
    $table->foreign('album_id')->references('id')->on('albums')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['201907239'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('lections', function ($table) {
    // $table->dropForeign(['lesson_id']);
    $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['2019072310'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('sertificates', function ($table) {
    // $table->dropForeign(['order_id']);
    // $table->dropForeign(['student_id']);
    // $table->dropForeign(['learning_id']);
    $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
    $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
    // $table->foreign('learning_id')->references('id')->on('order_student')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['2019072311'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('messages', function ($table) {
    // $table->dropForeign(['student_id']);
    $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['2019072312'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('albums', function ($table) {
    // $table->dropForeign(['course_id']);
    $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['2019072313'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('videos', function ($table) {
    // $table->dropForeign(['course_id']);
    $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['2019072314'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('deals', function ($table) {
    // $table->dropForeign(['client_id']);
    $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['2019072315'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('invoices', function ($table) {
    // $table->dropForeign(['deal_id']);
    $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['2019072316'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('acts', function ($table) {
    // $table->dropForeign(['deal_id']);
    $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['2019072317'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('orders', function ($table) {
    // $table->dropForeign(['client_id']);
    // $table->dropForeign(['deal_id']);
    $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
    $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['2019072318'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('admit_orders', function ($table) {
    // $table->dropForeign(['order_id']);
    $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };


  $migrations['2019072320'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('expel_orders', function ($table) {
    // $table->dropForeign(['order_id']);
    $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
  });
  DB::schema()->enableForeignKeyConstraints();
      return $info;
  };

  $migrations['2019072321'] = function () {
    $info="Добавим внешние ключи к разным штукам";
    DB::schema()->disableForeignKeyConstraints();
  DB::schema()->table('protocols', function ($table) {
    // $table->dropForeign(['order_id']);
    $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
  });
DB::schema()->enableForeignKeyConstraints();
    return $info;
};

$migrations['20190726'] = function () {
  $info="Добавим upload в сертификат  ";
  DB::schema()->table('sertificates', function ($table) {
      $table->unsignedInteger('upload_id');
  });
    return $info;
};



$migrations['20190728'] = function () {
  $info="Добавим связь курса и сертификата  ";
  DB::schema()->table('sertificates', function ($table) {
      $table->unsignedInteger('course_id');
  });
    return $info;
};


$migrations['20190803'] = function () {
  $info="Добавим признак активности студента  ";
  DB::schema()->table('students', function ($table) {
      $table->boolean('active')->default(true)->index();
  });
    return $info;
};



$migrations['20190808'] = function () {
  $info="Добавим Softdelete в learning ";
  DB::schema()->table('order_student', function ($table) {
      $table->softDeletes();
  });
    return $info;
};


$migrations['20190810'] = function () {
  $info="Добавим полный почтовый адрес dadata в клиента ";
  DB::schema()->table('clients', function ($table) {
      $table->text('full_post_address');
  });
    return $info;
};


$migrations['201908101'] = function () {
  $info="Добавим полный адрес доставки dadata в заказ ";
  DB::schema()->table('orders', function ($table) {
      $table->text('full_delivery_address');
  });
    return $info;
};

$migrations['20190820'] = function () {
  $info="Добавим сроки доставки в заказ ";
  DB::schema()->table('orders', function ($table) {
      $table->unsignedInteger('delivery_period_min')->default(1);
      $table->unsignedInteger('delivery_period_max')->default(5);
  });
    return $info;
};



$migrations['20190826'] = function () {
    $info="Добавим тип доставки и адрес в Сделку  ";
    DB::schema()->table('deals', function ($table) {
        $table->string('delivery_type');
        $table->text('delivery_address');
        $table->text('full_delivery_address');
    });
    return $info;
};


$migrations['20190913'] = function () {
    $info="Добавим флаги заданных данных, подписи и фото и адрес в Сертификат ";
    DB::schema()->table('sertificates', function ($table) {
        $table->boolean('is_data_checked')->default(false);
        $table->boolean('is_photo_checked')->default(false);
        $table->boolean('is_signature_checked')->default(false);
    });
    return $info;
};


$migrations['20190915'] = function () {
    $info="Таблица для SmsTemplate ";
    DB::schema()->create('sms_templates', function ($table) {
        $table->increments('id');
        $table->string('name')->index();
        $table->string('str_id')->index();
        $table->text('text');
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['20190916'] = function () {
    $info="Таблица для EmailTemplate ";
    DB::schema()->create('email_templates', function ($table) {
        $table->increments('id');
        $table->string('name');
        $table->string('str_id')->index();
        $table->string('subject');
        $table->text('text');
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
};

$migrations['20190918'] = function () {
  $info="Добавим тип сертификата в курс  ";
  DB::schema()->table('courses', function ($table) {
      $table->boolean('need_photo')->default(true)->index();
      $table->boolean('need_signature')->default(true)->index();
      $table->text('expiration_period');
      $table->unsignedInteger('sertificate_template_id');
      $table->unsignedInteger('protocol_template_id');
  });
    return $info;
};

$migrations['20191007'] = function () {
  $info="Добавим флаг видимости в группы курсов  ";
  DB::schema()->table('course_groups', function ($table) {
      $table->boolean('visible')->default(true)->index();
  });
    return $info;
};


$migrations['20191017'] = function () {
    $info="Добавим флаг экспорта 1с в Клиенты";
    DB::schema()->table('clients', function ($table) {
        $table->boolean('1c')->default(false);
    });
    return $info;
};

$migrations['201910171'] = function () {
    $info="Добавим флаг экспорта 1с в Счета";
    DB::schema()->table('invoices', function ($table) {
        $table->boolean('1c')->default(false);
    });
    return $info;
};


$migrations['20191024'] = function () {
  $info="Добавим флаг видимости в курсы  ";
  DB::schema()->table('courses', function ($table) {
      $table->boolean('visible')->default(true)->index();
  });
    return $info;
};

$migrations['20191107'] = function () {
  $info="Добавим демодокумент в курсы  ";
  DB::schema()->table('courses', function ($table) {
      $table->string('demo_document')->default('ksiva');
  });
    return $info;
};

$migrations['201911101'] = function () {
  $info="Добавим цену в заказ";
  DB::schema()->table('orders', function ($table) {
      $table->double('price');
  });
    return $info;
};

$migrations['201911102'] = function () {
  $info="Поменяем поле промокодов в заказ";
  DB::schema()->table('orders', function ($table) {
      $table->dropColumn('promo_code');
      $table->unsignedInteger('promo_code_id')->index();
  });
    return $info;
};


$migrations['20191130'] = function () {
  $info="Таблица для Promocode ";
  DB::schema()->create('promo_codes', function ($table) {
      $table->increments('id');
      $table->string('code')->index();
      $table->integer('value');
      $table->double('multiplicator')->default('1');
      $table->boolean('is_personal');
      $table->string('inn')->nullable()->index();
      $table->dateTime('expired_at')->index();
      $table->boolean('active')->default(true);
      $table->timestamps();
      $table->softDeletes();
  });
  return $info;
};


$migrations['20191202'] = function () {
  $info="Сделаем inn клиента nullable ";
  DB::schema()->table('clients', function ($table) {
      $table->string('inn')->nullable()->default(NULL)->index()->change();
  });
    return $info;
};



$migrations['20191204'] = function () {
  $info="Добавим флаг повышения квалификации в курсы  ";
  DB::schema()->table('courses', function ($table) {
      $table->boolean('skills_development')->default(false);
  });
    return $info;
};


$migrations['20191208'] = function () {
  $info="Добавим шаблоны приказов о зачислении и об отчислении в курсы  ";
  DB::schema()->table('courses', function ($table) {
      $table->string('admit_order_template')->default('admit_order');
      $table->string('expel_order_template')->default('expel_order');
  });
    return $info;
};


$migrations['20191212'] = function () {
    $info="Удалим тип доставки и адрес из Сделки  ";
    DB::schema()->table('deals', function ($table) {
        $table->dropColumn('delivery_type');
        $table->dropColumn('delivery_address');
        $table->dropColumn('full_delivery_address');
    });
    return $info;
};

$migrations['20191213'] = function () {
  $info="Таблица для Delivery ";
  DB::schema()->create('deliveries', function ($table) {
      $table->increments('id');
      $table->decimal('cost')->nullable();
      $table->string('type')->default('pochta');
      $table->unsignedInteger('deal_id')->index();
      $table->text('address');
      $table->text('full_address');
      $table->unsignedInteger('period_min')->default(1);
      $table->unsignedInteger('period_max')->default(5);
      $table->timestamps();
      $table->softDeletes();
  });
  return $info;
};


$migrations['20200121'] = function () {
  $info="Добавим текстовое описание в курсы  ";
  DB::schema()->table('courses', function ($table) {
      $table->string('text_description');
  });
    return $info;
};

$migrations['202001211'] = function () {
  $info="Добавим страничку и картинку в группы курсов  ";
  DB::schema()->table('course_groups', function ($table) {
      $table->string('url');
      $table->string('picture');
  });
    return $info;
};

$migrations['20200209'] = function () {
  $info="Таблица для AbortOrder";
  DB::schema()->create('abort_orders', function ($table) {
      $table->increments('id');
      $table->string('name');
      $table->unsignedInteger('order_id')->index();
      $table->unsignedInteger('upload_id')->index();
      $table->unsignedInteger('sequence')->index();
      $table->timestamps();
      $table->softDeletes();
  });
  return $info;
};

$migrations['202002091'] = function () {
  $info="Добавим флаг удаляемости к заказу  ";
  DB::schema()->table('orders', function ($table) {
      $table->boolean('is_removable')->default(true);
  });
    return $info;
};

$migrations['20200325'] = function () {
    $info="Добавим документ у студента  ";
    DB::schema()->table('students', function ($table) {
        $table->unsignedInteger('document_id')->index();
    });
      return $info;
  };



  $migrations['20200407'] = function () {
    $info="Добавим флаг анонима в Клиенты";
    DB::schema()->table('clients', function ($table) {
        $table->boolean('anonymous')->default(false);
    });
    return $info;
};


$migrations['20200413'] = function () {
    $info="Добавим шаблон книги учета выданных удостоверений в курс";
    DB::schema()->table('courses', function ($table) {
        $table->string('sertificate_logbook_header');
    });
    return $info;
};


$migrations['20200414'] = function () {
    $info="Таблица для SertificateLogBook ";
    DB::schema()->create('sertificate_log_books', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('order_id')->index();
        $table->unsignedInteger('upload_id')->index();
        $table->timestamps();
        $table->softDeletes();
    });
    return $info;
  };


  $migrations['20200518'] = function () {
    $info="Флаги подписанности для Contract ContractApplication Act ";
    DB::schema()->table('contracts', function ($table) {
        $table->boolean('signed')->default(true)->index();
    });
    DB::schema()->table('contract_applications', function ($table) {
        $table->boolean('signed')->default(true)->index();
    });
    DB::schema()->table('acts', function ($table) {
        $table->boolean('signed')->default(true)->index();
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
