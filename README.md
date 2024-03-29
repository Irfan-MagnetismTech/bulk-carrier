# Bulk Carrier ERP

Bulk carrier ERP is a web application for managing bulk carrier shipping business.
It is a complete solution for managing all the business processes of a bulk carrier shipping company.
It is a web application that can be accessed from anywhere in the world.

### Business Units

Business Unit should be `enum` data type.

-   TSLL
-   PSML

### Project Environment

-   Laravel 10.\*
-   Vue.js 3
-   PHP 8.0 or later
-   Database MySQL 8.\*
-   Node 18.0 or later

## Installation

#### Backend

```bash
  cd backend
  composer install
  cp .env.example .env
  php artisan key:generate
  php artisan migrate --seed
  php artisan passport:install
```

### Notes

-   **_Models_**, **_Controllers_** and **_Routes_** should be placed according to **Laravel Module Package**.
-   Follow https://docs.laravelmodules.com/v10/artisan-commands for **Laravel Module Package** usage.

### Laravel Module's basic artisan Commands

-   Generate a new module.
    ```bash
    php artisan module:make Blog
    ```
-   Generate multiple modules at once.
    ```bash
    php artisan module:make Blog User Auth
    ```
-   Generate the given model for the specified module.
    ```bash
    php artisan module:make-model (Model Name) (Module Name)
    php artisan module:make-model Post Blog
    ```
-   Generate additional migration, controller, request, and seeder for the specified module. _This is only applicable for model creation_
    ```bash
    php artisan module:make-model Post -mcr Blog
    ```
-   Migrate the given module, or migrate all modules without a module (argument).
    ```bash
    php artisan module:migrate Blog
    php artisan module:migrate
    ```
-   Rollback the given module, or without an argument, rollback all modules.
    ```bash
    php artisan module:migrate-rollback Blog
    ```
-   Refresh the migration for the given module, or without a specified module refresh all module migrations.
    ```bash
    php artisan module:migrate-refresh Blog
    ```
-   Seed the given module, or without an argument, seed all modules
    ```bash
    php artisan module:seed Blog
    ```
-   Generate the given job for the specified module.
    ```bash
    php artisan module:make-job JobName Blog
    php artisan module:make-job JobName Blog --sync # A synchronous job class
    ```
-   Generate the given notification class name for the specified module.
    ```bash
    php artisan module:make-notification NotifyAdminOfNewComment Blog
    ```

#### Frontend

```bash
  cd frontend
  npm install
```

### Naming Conventions

-   Branch names will be in kebab-case
-   Route resource names will be in small cases with a dot to concatenate featured function names
-   Frontend routes and folder names will be in kebab-case
-   Modules, Services, Helpers, and Traits name will be in PascalCase
-   Short forms will be written in CAPITAL cases like Supply Chain Management as SCM
-   Database table names will have a prefix of relevant modules like ops*, acc*, crw*, scm, mnt*, adm\_

### Storage Information

Default `storage` => `storage/app/public`

When using **`storeAs`** or a similar method where we need to give a storage path explicitly, please use the above convention

### Branching

```
Project Repository
|
└──main
    |
    |
    └──hot-fixes
    |
    |
    └──dev
        |
        |
        └──administration
                |
                |
                └──ADM/feature-name
                        |
                        |
                        └──task-name
        |
        |
        └──operations
                |
                |
                └──OPS/feature-name
                        |
                        |
                        └──task-name
        |
        |
        └──accounts
                |
                |
                └──ACC/feature-name
                        |
                        |
                        └──task-name
        |
        |
        └──crew
              |
              |
              └──CRW/feature-name
                        |
                        |
                        └──task-name
        |
        |
        └──supply-chain
                |
                |
                └──SCM/feature-name
                        |
                        |
                        └──task-name
        |
        |
        └──maintenance
                |
                |
                └──MNT/feature-name
                        |
                        |
                        └──task-name
```

## Important notes

-   Developers should comment on necessary code points. Every function should have a definition of parameters and return value. Comment out the example of return values where a function is called so that another developer will understand without going to the definition source.
-   Resource functions should return responses in the following format: message, value, response_code

    -   For success:

        ```php
        return response()->success('Unit created successfully, $scm_unit, 200);
        ```

    -   For error:

        ```php
        return response()->error($e->getMessage(), 500);
        ```

-   File upload service:

    ```php
        <?php

          namespace Modules\SCM\Http\Controllers;

          use App\Services\FileUploadService;

          class ExampleController extends Controller
          {
              function __construct(private FileUploadService $fileUpload)
              {
              }

              public function __invoke(Request $request)
              {
                //Store new file
                $this->fileUpload->handleFile($request->attachment, 'folder name(scm/cs)');

                //Update existing file and delete old file
                $this->fileUpload->handleFile($request->attachment, 'folder name(scm/cs)', $old_file);

                //Delete file
                $this->fileUpload->deleteFile($old_file);
              }
          }
    ```

-   Validation should be done in Request files rather than Controller files.
-   Field names should be synchronized in the Database, Frontend forms, and Backend variables. In ambiguous cases, field names could be differentiated using entity names as prefixes.
-   There should be double confirmation before executing DELETE operations to ensure relational data safety. We can keep a Soft-Delete flag so that users can restore the data as necessary.
-   In Models, field names should be in Fillable Properties.
-   For the git commit message, please follow the convension as MODULE-SHORT-FORM/feature-name/task-name - short message with status. For example, "SCM/product-requisition/create-form - skeleton design DONE **OR** validation WIP **OR** data population error FIXED"
-   Short forms for modules are as below:
    -   ADM - Administration
    -   ACC - Accounts
    -   CRW - Crew
    -   MNT - Maintenance
    -   OPS - Operations
    -   SCM - Supply Chain
-   All issues will be fixed in the Module Branch via a new Branch.
-   If any table is using short form then add a table description in a comment. i.e.: Comparative Statement as cs

    ```php
    $table->comment('cs means Comparative Statement');
    ```

-   Add comment to address necessary information against a particular column name

    ```php
    $table->string('mmr_no')->comment('Material received number');
    ```

-   Response status codes and messages

    -   200 => 'Data retrieved successfully.',
    -   201 => 'Data created successfully.',
    -   202 => 'Data updated successfully.',
    -   204 => 'Data deleted successfully.'
    -   404 => "Api is not found.",
    -   422 => 'Credential error.',
    -   401 => 'Unauthorized, request is not valid.'
    -   500 => 'Internal server error.'

-   For PDF, use the Laravel Mpdf package.
    
    [Laravel Mpdf: Generate PDF](https://github.com/mccarlosen/laravel-mpdf)

-  Business Unit in edit mode will be visible but in Read-only Mode.
-  If you intend to change the business unit, it's necessary to delete the existing input data and replace it with a new entry.

## Authors

-   [@Hasan Md Shahriare](https://github.com/hasashah)
-   [@Sumon Chandra Shil](https://www.github.com/sumonchandrashil)
-   [@Muhammad Mahbubur Rahman](https://github.com/mahbub-magnetism)
-   [@Delowar Hossain](https://www.github.com/illusionist3886)
-   [@Showrav Biswas](https://github.com/Showrav-Biswas-Mtech)
-   [@Hossain Mohammad Shahidullah Jaber](https://github.com/jaberWiki)
-   [@Abdul Majid Irfan](https://github.com/irfan-majid)
