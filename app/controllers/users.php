<?php

$context['user_types'] = UserType::where('id', '>=', $user->type->id)->get();
