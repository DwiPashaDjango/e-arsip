## About E-Arsip

E-Arsip adalah aplikasi yang menghimpun informasi yang tertera pada fisik arsip. Aplikasi ini memungkinkan menyimpan file arsip sekolah dan dapat dilihat kembali kapan saja setiap dibutuhkan.

## Instalation

-   clone https://github.com/DwiPashaDjango/e-arsip.git
-   buka direktori project di terminal anda.
-   ketikan command : cp .env.example .env (copy paste file .env.example)
-   buat database

Lalu ketik command dibawah ini

-   composer install
-   php artisan optimize:clear
-   php artisan key:generate (generate app key)
-   php artisan migrate (migrasi database)
-   php artisan db:seed

## Requiretments

-   PHP Version 7.3
-   Laravel 8
-   Jquery
-   Bootstrap Version 4.0.3
-   Stisla Admin Bootstrap
-   Datatable
-   Yajra Datatable Server Side
-   SweetAlert
-   IndoRegion
