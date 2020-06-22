<?php

if (isset($filters['user_id'])) {
    $user = User::find($filters['user_id']);
    if ($user) {
        $user->active = 1;
        $user->save();
    }
    header('Location: /activate-users');
}
$context['user_types'] = UserType::where('id', '>=', $user->type->id)->get();
