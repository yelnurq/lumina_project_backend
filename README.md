ЗАПУСК
composer update
php artisan migrate
php artisan serve




// Авторизация / LOGIN, REGISTER
//             'email' => 'required|email',
//             'password' => 'required|string',

// Выход / LOGOUT
//             'bearer token',

// Фидбэк / GET запрос админа
//             'full_name',
//             'title',
//             'text',


// Фидбэк / POST запрос обычных юзеров
            // 'full_name' => 'required|string|max:255',
            // 'title' => 'nullable|string|max:255',
            // 'text' => 'required|string',

            
// Блоги / POST, UPDATE запрос админа / Delete через {SLUG}
            // 'title' => 'required|string|max:255',
            // 'description' => 'required|string|max:500',
            // 'text' => 'required|string',
            // 'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:12008',

// Блоги / GET запрос 
//             'title',
//             'description',
//             'text',
//             'image',
//             'slug', SLUG для URl
