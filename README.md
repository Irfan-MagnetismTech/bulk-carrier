# bulk-carrier-erp

Bulk carrier erp is a web application for managing bulk carrier shipping business.
It is a complete solution for managing all the business processes of a bulk carrier shipping company.
It is a web application that can be accessed from anywhere in the world.

### Project Environment

- Laravel 10.*
- Vue.js 3
- PHP 8.0 or later
- Database MySQL 8.*
- Node 18.0 or later

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
- Generate a new module.
  ```bash
  php artisan module:make Blog
  ```
- Generate multiple modules at once.
  ```bash
  php artisan module:make Blog User Auth
  ```
- Generate the given model for the specified module.
  ```bash
  php artisan module:make-model (Model Name) (Module Name)
  php artisan module:make-model Post Blog
  ```
- Generate additional migration, controller, request, and seeder for the specified module. *This is only applicable for model creation*
  ```bash
  php artisan module:make-model Post -mcrs Blog
  ```
- Migrate the given module, or without a module (argument), migrate all modules.
  ```bash
  php artisan module:migrate Blog
  php artisan module:migrate
  ```
- Rollback the given module, or without an argument, rollback all modules.
  ```bash
  php artisan module:migrate-rollback Blog
  ```
- Refresh the migration for the given module, or without a specified module refresh all module migrations.
  ```bash
  php artisan module:migrate-refresh Blog
  ```
- Seed the given module, or without an argument, seed all modules
  ```bash
  php artisan module:seed Blog
  ```
- Generate the given job for the specified module.
  ```bash
  php artisan module:make-job JobName Blog
  php artisan module:make-job JobName Blog --sync # A synchronous job class
  ```
- Generate the given notification class name for the specified module.
  ```bash
  php artisan module:make-notification NotifyAdminOfNewComment Blog
  ```

#### Frontend
```bash
  cd frontend
  npm install
```

### Naming Conventions

- Branch names will be in kebab-case 
- Route resource names will be in small cases with a dot to concatenate featured function names
- Frontend routes and folder names will be in kebab-case
- Modules, Services, Helpers, and Traits name will be in PascalCase 
- Short forms will be written in CAPITAL cases like Supply Chain Management as SCM
- Database table names will have a prefix of relevant modules like ops_, acc_, crw_, scm, mnt_, adm_


### Storage Information

Default `storage` => `storage/app/public`

When using **`storeAs`** or a similar method where we need to explicitly give a storage path, please use the above convention

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

- Developers should comment on necessary code points. Every function should have a definition of parameters and return value. Comment out the example of return values where a function is called so that another developer will understand without going to the definition source. 
- Resource functions should return responses in the following format: message, value, response_code
    - For success: return response()->success('Unit created succesfully', $scm_unit, 200);
    - For error: return response()->error($e->getMessage(), 500);
- Validation should be done in Request files rather than Controller files.
- Field names should be synchronized in the Database, Frontend forms, and Backend variables. In ambiguous cases, field names could be differentiated using entity names as prefixes.
- Before executing DELETE operations there should be double confirmation to ensure relational data safety. We can keep a Soft-Delete flag so that users can restore the data as necessary.
- In Models, field names should be in Fillable properties.
- For the git commit message, please follow the convension as MODULE-SHORT-FORM/feature-name/task-name - short message with status. For example, "SCM/product-requisition/create-form - skeleton design DONE **OR** validation WIP **OR** data population error FIXED"
- Short forms for modules are as below:
  - ADM - Administration
  - ACC - Accounts
  - CRW - Crew
  - MNT - Maintenance
  - OPS - Operations
  - SCM - Supply Chain
- All types of issues will be fixed in the Module Branch via a new Branch.

## Authors

- [@Hasan Md Shahriare](https://github.com/hasashah)
- [@Sumon Chandra Shil](https://www.github.com/sumonchandrashil)
- [@Muhammad Mahbubur Rahman](https://github.com/mahbub-magnetism)
- [@Delowar Hossain](https://www.github.com/illusionist3886)
- [@Showrav Biswas](https://github.com/Showrav-Biswas-Mtech)
- [@Hossain Mohammad Shahidullah Jaber](https://github.com/jaberWiki)
