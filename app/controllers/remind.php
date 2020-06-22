<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (empty($filters['hash'])) {
    if (!empty($_POST['auth_oper']) && $_POST['auth_oper'] == 'remind') {
        $hash = $user->createPasswordHash($_POST['auth_username']);
        if (!empty($hash)) {            
            $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
            try {
                //Server settings
                $mail->CharSet = 'UTF-8';
                // $mail->SMTPDebug = 2;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = $configs->email->smtp->server;  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = $configs->email->smtp->login;                 // SMTP username
                $mail->Password = $configs->email->smtp->password;                           // SMTP password
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = $configs->email->smtp->port;                                    // TCP port to connect to

                //Recipients
                $mail->setFrom($configs->email->from->address, $configs->email->from->name);
                $mail->addAddress($_POST['auth_username']);     // Add a recipient                

                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Восстановление пароля';
                $mail->Body = '<a href="'.$site->url.'/remind/hash/'.$hash->hash.'">Ccылка для восстановления пароля</a>';
                $mail->AltBody = 'Хеш для восстановления пароля: '.$hash->hash;

                $mail->send();
            } catch (Exception $e) {
                $user->message->error = 'Email не отправлен. '.$mail->ErrorInfo;
            }
            $user->message->success = 'Email для восстановления успешно отправлен.';
        }
    }
} else {
    $context['change_password_part'] = true;
    $context['page']->description = 'Смена пароля';
    if (isset($_POST['auth_oper']) && $_POST['auth_oper'] == 'new-password') {
        if (empty($_POST['auth_password'])) {
            $error = 'Пароль не должен быть пустой. ';
        } elseif (empty($_POST['re_password'])) {
            $error .= 'Пожалуйста введите повтор пароля. ';
        } elseif ($_POST['auth_password'] != $_POST['re_password']) {
            $error = 'Пароль и повторный пароль не совпадают.';
        } else {
            try {
                $user->changePassword($filters['hash'], $_POST['auth_password']);
            } catch (Exception $e) {
                $user->message->error = 'Email не отправлен. '.$mail->ErrorInfo;
            }
        }

        if (isset($error)) {
            $user->message->error = 'Email не отправлен. '.$error;
        }
    }
}
