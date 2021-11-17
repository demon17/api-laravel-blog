## Laravel Blog Rest API
This is a blog rest API project including blog features like category,article,author, vote, search , authentication using PHP Laravel framework & MySQL database.

## How to use ?
Follow these steps to get this project live

```
git clone https://github.com/demon17/api-laravel-blog.git
cd api-laravel-blog
composer install

```

## Configure your .env file

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_name
DB_USERNAME=db_username
DB_PASSWORD=db_password

```

## Final steps

```
php artisan migrate
php artisan key:generate
php artisan db:seed
php artisan serve
check localhost: http://127.0.0.1:8000
```
## Features
    1. Category (Add/Update/Remove/Search)
    2. Article (Add/Update/Remove/Search/Article wise comment)
    3. Author (Register/Login/Logout)
    4. Vote (Add/Update/Remove)

## Authorization
    1. Category -> Needs to be authenticated for add/update/remove
    2. Article -> Needs to be authenticated for add/update/remove
    3. Author -> Needs to be authenticated for details/logout
    4. Vote -> Needs to be authenticated for add/update/remove
    
## Endpoints
    * Category
        1 ) Add -> /api/category/store 
        2 ) Update -> /api/category/{id}/update 
        3 ) Remove -> /api/category/{id}/remove 
        4 ) Show -> /api/category/{id}/show 
        5 ) All -> /api/categories
        6 ) Search -> /api/category/{keyword}/search
        
    2. Article
        1 ) Add -> /api/article/store 
        2 ) Update -> /api/article/{id}/update 
        3 ) Remove -> /api/article/{id}/remove 
        4 ) Show -> /api/article/{id}/show 
        5 ) All -> /api/articles
        6 ) Search -> /api/article/{keyword}/search
        
    3. Author
        1 ) Register -> /api/register 
        2 ) Login -> /api/login
        3 ) Logout -> /api/logout
        
    4. Vote
        1 ) Add -> /api/comment/store 
        2 ) Update -> /api/comment/{id}/update 
        3 ) Remove -> /api/comment/{id}/remove 
        4 ) Show -> /api/comment/{id}/show 
        5 ) All -> /api/comment


To test this API project you can use [Postman](https://www.postman.com/) application.For authorization purpose you need to add below headers
```
‘headers’ => [
    ‘Accept’ => ‘application/json’,
    ‘Authorization’ => ‘Bearer ‘.$accessToken,
]

Defaults:
- User: admin
- Pass: 123456
- Access Token aka $accessToken: example_api_token
```

## Thanks
