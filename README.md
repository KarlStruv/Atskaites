# Reports System

## Requirements
- XAMPP (Apache, MySQL, PHP 8.2.12)
- Composer 2.8.5
- Git

## Installation
1. **Start XAMPP**
   - Open Xampp Control Panel, start Apache and MySQL.
   - For Excel export functionality, enable "gd" and "zip" extensions in "php.ini" file and restart the Apache server.
       ```bash
       C:/xampp/php/php.ini
       ```

2. **Clone Repository**
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/KarlStruv/Atskaites.git
   cd kpdc_atskaites
   ```
3. **Install Dependencies**
   ```bash
   composer install
   ```
4. **Configure .env File** 
   - If your MySQL User has default login("root") and no password, leave it as it is:
   ```bash
   DATABASE_URL="mysql://root:@127.0.0.1:3306/kpdc_uzdevums?serverVersion=8.0.37"
   ```
5. **Set Up Database** 
    - Create database:
   ```bash
   php bin/console doctrine:database:create
   ```
   - Import SQL files using phpMyAdmin or MySQL:
     - **sql/kpdc_registrs.sql**
     - **sql/kpdc_registrs_ind.sql**
     - **sql/kpdc_registrs_grup.sql**
6. **Clear Cache** 
   ```bash
   php bin/console cache:clear
   ```
7. **Run and access the Project** 
    - Run in terminal:
    ```bash
    - php -S localhost:8000 -t public
    ```
    - Access the project in a modern Web Browser(Chrome, Firefox, etc.):
    ```bash
    - http://localhost:8000/
    ```
