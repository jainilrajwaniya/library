## Project Intallation Guide

Please follow below steps to install the project:

- Take git clone of the project
- run composer install
- Create DB named "library" in your DB
- Create .env file
- Copy content of env.example to .env
- run db migration : php artisan migrate
- Test api on postman using given postman collection in root folder : Library.postman_collection
- Run feature test to check the functioning of all the apis
