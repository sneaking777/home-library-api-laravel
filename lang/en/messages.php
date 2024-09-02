<?php

/*
|--------------------------------------------------------------------------
| Строки локализации сообщений
|--------------------------------------------------------------------------
|
| Следующие строки используются для создания локализованных
| сообщений в вашем приложении, поддерживающих русский язык.
| Вы вольны модифицировать эти строки так, как это будет удобно
| вашему приложению. Это поможет настроить отображение информации
| таким образом, чтобы оно максимально соответствовало вашему
| приложению.
|
*/

return [
    'success' => [
        'book' => [
            'created' => 'Book successfully created.',
            'updated' => 'The book has been updated successfully.',
        ],
        'logout' => 'Successfully logged out',
        'author' => [
            'created' => 'Author successfully created.',
            'updated' => 'The author has been updated successfully.',
        ]
    ],
    'not_found' => [
        'user' => 'User not found.'
    ],
    'unauthenticated' => 'Unauthenticated.',
    'password_reset' => 'We have emailed you a password reset link!',
    'new_password' => 'Password Successfully Reset.',
    'mail' => [
        'password_reset' => [
            'subject' => 'Password Reset',
            'message' => 'You are receiving this email because we received a password reset request for your account.',
            'action' => 'Click here to reset password',
            'warning' => 'If you did not request a password reset, no further action is required.'
        ]
    ]
];
