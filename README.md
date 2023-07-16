# GDSCMMU

## Pre-requisite

You will need PHP, Apache, MySQL, phpMyAdmin to run this program <br/>
Get XAMPP stack package to eliminate all the hassles <br/>
[https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html)

## How to Run the Program

In terminal (Without Using XAMPP)

```
composer install
npm install
php -S localhost:8080
```

Now you can view the page in [http://localhost:8080](http://localhost:8080)

Or, you can open up the XAMPP GUI app you just installed, and start Apache, MySQL, ProFTPD services
Now it will serve the default htdocs directory/folder on localhost

htdocs location:

```
Windows : C:\xampp\htdocs
Mac : Open XAMPP GUI, Go to Volumes, Mount /opt/lampp, Click Explore
Linux : /opt/lampp/htdocs
```

## Things to Do (Front-End)

Files to Focus on: in views/ and public/ directories

1. Bring design of homepage, home.php to life
2. Add pictures, roles and descriptions in team.php
3. Add details of email and phone numbers in contacts.php

## Things to Do (Back-End)

1. Connect Database (MySQL)
2. Define Schemas in models
3. Define CRUD in controllers
4. Add User Login and Register feature
5. Add new articles to database and load articles from database
6. Add new comments to database and load comments from database
7. Password Salting and Hashing
8. Sanitize and Validate input/url queries for better security
9. User Login Save Sessions
10. Cookie to auto store and load username
11. Publish to Hostinger
