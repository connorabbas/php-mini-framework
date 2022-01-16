# PHP Mini Framework

Key Features:
- Routing for GET, POST, PATCH, PUT & DELETE HTTP requests
- MVC architecture
- Class auto loading
- PDO database class
- Bootstrap 5, jQuery, and React included
  
Starter content for env.php file (create in /app directory):
```
<?php
// Enviroment specific variables
putenv("DB_HOST=127.0.0.1");
putenv("DB_USERNAME=root");
putenv("DB_PASSWORD=");
putenv("DB_NAME=");
?>
```
  
Create a controller via CLI:
``` bash command-line
php command make:controller YourControllerName
```
  
Create a model via CLI:
``` bash command-line
php command make:model YourModelName
```