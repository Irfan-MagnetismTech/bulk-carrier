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
  
#### Frontend
```bash
  cd frontend
  npm install
```

### Naming Conventions

- Branch names will be in kebab-case 
- Route resources names will be in small case with dot to concatenate featured function names
- Frontend routes and folder name will be in kebab-case
- Modules, Services, Helpers, Traits name will be in PascalCase 
- Short forms will be written in CAPITAL case like Supply Chain Management as SCM
- Database table names will have a prefix of relevant modules like ops_, acc_, crw_, scm, mnt_, adm_


### Storage Information

Default `storage` => `storage/app/public`

When using **`storeAs`** or similar method where we need to explicitly give storage path, please use the above convention

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
                └──feature-name  
                        |
                        |
                        └──task-name       
        |
        |
        └──operations
                |
                |
                └──feature-name
                        |
                        |
                        └──task-name
        |
        |
        └──accounts
                |
                |
                └──feature-name
                        |
                        |
                        └──task-name
        |
        |
        └──crew
              |
              |
              └──feature-name
                        |
                        |
                        └──task-name
        |
        |
        └──supply-chain
                |
                |
                └──feature-name
                        |
                        |
                        └──task-name
        |
        |
        └──maintenance
                |
                |
                └──feature-name
                        |
                        |
                        └──task-name
```
## Important notes

- Developers should comment in necessary code points. Every function should have defination of parameters and return value. Comment out the example of return values where a function is called so that another developer will understand without going to the definition source. 
- Resource functions should return responses as following format: response_code, message, value
- Validation should be done in Request files rather then Controller files.
- Field names should be synchronized in Database, Frontend forms and Backend variables. In ambiguous cases, field names could be differentiate using entity name as prefix.
- Before execute DELETE operations there should be double confirmation to ensure relational data safety. We can keep a Soft-Delete flag so that user can restore the data as necessery.
- In Models, fields name should be in Filleable properties.
- For git commit message, please follow the convension as MODULE-SHORT-FORM/feature-name/task-name - short message with status. For example, "SCM/product-requisition/create-form - skeleton design DONE **OR** validation WIP **OR** data population error FIXED"
- Short forms for modules are as below:
  - ADM - Administration
  - ACC - Accounts
  - CRW - Crew
  - MNT - Maintenance
  - OPS - Operations
  - SCM - Supply Chain
- All types of issues will be fixed into Module Branch via a new Branch.

## Authors

- [@Hasan Md Shahriare](https://github.com/hasashah)
- [@Sumon Chandra Shil](https://www.github.com/sumonchandrashil)
- [@Muhammad Mahbubur Rahman](https://github.com/mahbub-magnetism)
- [@Delowar Hossain](https://www.github.com/illusionist3886)
- [@Showrav Biswas](https://github.com/Showrav-Biswas-Mtech)
- [@Hossain Mohammad Shahidullah Jaber](https://github.com/jaberWiki)
