# bulk-carrier-erp

Bulk carrier erp is a web application for managing bulk carrier shipping business.
It is a complete solution for managing all the business processes of a bulk carrier shipping company.
It is a web application that can be accessed from anywhere in the world.

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

## Branching #
```
Main
|
└──hotfix
|
└──dev

```
## Authors

- [@Hasan Md Shahriare](https://github.com/hasashah)
- [@Sumon Chandra Shil](https://www.github.com/sumonchandrashil)
- [@Delowar Hossain](https://www.github.com/illusionist3886)
- [@Showrav Biswas](https://github.com/Showrav-Biswas-Mtech)
