## How To Install??

- composer install
- npm install
- cp .env.example .env (Don't forget to configure for DB & MAIL)
- php artisan key:generate
- php artisan migrate
- php artisan db:seed (Optional)
______________________________________________
- php artisan serve
- npm run dev (or npm run build)
- If you don't wanna type "php artisan serve" or "npm run dev (or npm run build)", you can just run "composer run dev" instead.
______________________________________________
- php artisan storage:link (The storage/app/public directory may be used to store user-generated files, such as profile avatars, that should be publicly accessible. You should create a symbolic link at public/storage which points to this directory. You may create the link using the php artisan storage:link Artisan command.)
