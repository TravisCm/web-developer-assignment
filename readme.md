Please read the ENTIRE read me file before proceeding.

How To Run:
1. Start the containers by running "docker-compose up -d" in the project root.
2. Install the composer packages by running "docker-compose exec laravel composer install".
3. Run command "docker exec -it assignment01-laravel bash" to run the docker container 
4. Also ensure the mysql:8 container is also running (I use Docker extention for VS code to ensure this)
5. Access the Laravel instance on http://localhost/books

Testing:
1. To run unit/feature tests run command "vendor/bin/phpunit". 
Note: When running the unit/feature tests it will clear the database that appears on the 
http://localhost/books page. I recommend running these tests prior to adding entries to 
the table on http://localhost/books or you should run these tests at the very end.

Security: 
Some security features have been added to protect against malicious users. These features include Cross-site request forgery tokens (csvf) and the use of parameterization to protect from SQL injections. 

Assumptions:
1. For the purposes of this assignment I am assuming that the requirements for functions laid out in the homework.txt is 
is the majority of what is required, therefore no large unnecessary features such as a user sign in page etc were created.
some smaller features were created such as displaying the number of books from any given search or creating a popup window
for the update author function to improve user experience. 
2. I assume that the update author function was the only update feature required and that an update title is not wanted 
as per the instructions on homework.txt otherwise one would have been added. 
3. I assume all features were to remain on the same page/view, therefore all routes lead back to the books page.

Other Notes or Reasonings:
1. This project was created on php:7.2 and laravel/framework:6.0 and coded using Visual Studio Code
2. These are the MySQL database credentials if needed: 
   COMPOSE_PROJECT_NAME=assignment01
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=root
   DB_PASSWORD=secret
3. Please note that csvf tokens are disabled for the unit/feature tests. From my reasearch, this is standard practice.
4. Please note that I understand the importance of separating the javascript and css from the html file as part of common coding practice.
I have done this for the project and linked the files back in the books.blade.php file. The css file works perfectly fine but the Javascript file
does not. It is linked correctly as you can see that in the chrome developer tools under the "source" tab where you can see the 
app.js file is recognized. Despite this any calls of the javascript file in books.blade.php results in for example "Uncaught ReferenceError: openUpdateModal is not defined
at HTMLButtonElement.onclick".This is despite all the javascript code was only moved out the blade file and not further changed.
I believe this to be an error caused from certain dependencies but after trying to add them it still has not worked. As a result I have 
left a copy of the Javascript logic inside the books.blade file to ensure it works correctly.



## Requirements
- [Docker](https://docs.docker.com/install)
- [Docker Compose](https://docs.docker.com/compose/install)

## Setup
1. Clone the repository.
1. Start the containers by running `docker-compose up -d` in the project root.
1. Install the composer packages by running `docker-compose exec laravel composer install`.
1. Access the Laravel instance on `http://localhost` (If there is a "Permission denied" error, run `docker-compose exec laravel chown -R www-data storage`).

Note that the changes you make to local files will be automatically reflected in the container. 

## Persistent database
If you want to make sure that the data in the database persists even if the database container is deleted, add a file named `docker-compose.override.yml` in the project root with the following contents.
```
version: "3.7"

services:
  mysql:
    volumes:
    - mysql:/var/lib/mysql

volumes:
  mysql:
```
Then run the following.
```
docker-compose stop \
  && docker-compose rm -f mysql \
  && docker-compose up -d
``` 
