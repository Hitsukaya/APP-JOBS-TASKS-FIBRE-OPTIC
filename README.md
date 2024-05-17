# App Jobs-Tasks-Fibre-Optic -- Filament

A demo application to illustrate how Filament Admin works.

## Installation

Clone the repo locally:

```sh
git clone https://github.com/Hitsukaya/APP-JOBS-TASKS-FIBRE-OPTIC.git app-jobs-task-fibre-optic && cd app-jobs-task-fibre-optic
```

Install PHP dependencies:

```sh
composer install
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Create an SQLite database. You can also use another database (MySQL, Postgres), simply update your configuration accordingly.

```sh
touch database/database.sqlite
```
Edit .env

```sh
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=app-jobs-task-fibre-optic
DB_USERNAME=root
DB_PASSWORD=1234567
```

Run database migrations:

```sh
php artisan migrate
```

> **Note**  
> If you get an "Invalid datetime format (1292)" error, this is probably related to the timezone setting of your database.  
> Please see https://dba.stackexchange.com/questions/234270/incorrect-datetime-value-mysql


Create a symlink to the storage:

```sh
php artisan storage:link
```

Run the dev server (the output will give the address):

```sh
php artisan serve
```

You're ready to go! Visit the url in your browser, and login with:

-   **Username:** 
-   **Password:**
-   **Run Command: php artisan make:filament-user**
-   **Edit Databases: Role Admin/Manager/Engineer**

## Features to explore

### Relations

#### BelongsTo
- JobResource
- TaskResource

#### BelongsToMany
- CategoryResource\RelationManagers\JobsRelationManager

#### MorphOne
- TaskResource -> Address

#### MorphMany
- JobResource\RelationManagers\CommentsRelationManager

#### MorphToMany
- BrandResource\RelationManagers\AddressRelationManager
