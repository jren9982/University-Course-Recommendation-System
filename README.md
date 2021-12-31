# University Course Recommendation System
Web Application and Database Team Project, completed for ICT2103. Implemented for both MySQL and MongoDB.

### To login to Admin Page:

- Username: user@email.com

- Password: u$er

RELATIONAL DATABASE(MariaDB)
### When running on your own computer: 

Check under 'private' 'dbconn.php' and under root dir 'connection_settings.php' and change to your DB settings accordingly (e.g. password)

### How to import database into your own MariaDB:

in MariaDB console, type this command:

`source <full path of data.sql>`

e.g. `source C:\Users\Nativ\Documents\GitHub\ICT2103Project\data.sql`

and MariaDB will import database automatically.


NON RELATIONAL DATABASE(MongoDB)

### When running on your computer:

Install 3 tools:
MongoDB driver version 1.11.1,
MongoDB PHP library 1.9,
Composer to use autoload.php required for MongoDB PHP library 1.9

edit your mongoDB connection to your local host under database.inc.php

https://pecl.php.net/package/mongodb/1.11.1/windows (mongoDB driver download the one with your php version)
https://www.php.net/manual/en/mongodb.tutorial.library.php (how to use php library 1.9 with composer tutorial)
https://getcomposer.org/download/ (composer)

add "extension=php_mongodb.dll" into your php.ini file 

Using COMPASS GUI to create a database with 6 collections 
Uni_cca
Uni_cca_categories
Uni_courses
Uni_list
Uni_user
Uni_vacancies

Import all the JSON files in 2103_Project_Collection folder into the allocated created collection through COMPASS GUI
