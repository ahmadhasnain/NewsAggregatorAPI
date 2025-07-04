
## About Project

This is the backend system for News Aggregator App made on laravel. This document will show the description of the project and how to run the project. 



### Steps

- Create .env file in main folder and copy contents from .env.example
- Enter database credentials (postgres or mysql) in .env file to connect with database.
- Run 'php artisan optimize' to clear cache
- Run 'php artisan migrate' to run the migrations
- Run 'php artisan app:fetch-news' or use the api/artisan/app/fetch-news in postman to run the command that will fetch news from sources and fill in the database


### Apis

api.php file contains all the apis.

#### api/user/signup (POST)

- This api is created for the signup of user which will create a user record in the database. Request validation class is also created for that route. 

#### api/user/login (POST)

- This api is created for the login of user which will match username and password and if user is found then return that user with the created access token. Request validation class is also created for that route. 

#### api/user/logout (GET)

- This api is created for the logout of user which will delete the current token of user and the user will logout.

#### api/artisan/app/fetch-news (GET)

- This api is created if you want to run the command to fetch news from different sources using an api call.

#### api/news/sources/get (GET)

- This api is created to fetch the unique sources so using that sources list user can select any source from frontend and then the filters will be applied according to that.

#### api/news/authors/get (GET)

- This api is created to fetch the unique authors so using that authors list user can select any author from frontend and then the filters will be applied according to that.

#### api/news/get (GET)

- This api is created to fetch the news based on author filter, source filter and search filter which will search the keyword in title, description and content.

### Commands

#### app:fetch-news 

- This command will fetch news from different sources and then save it in the database in news table and is schedules to run daily.

### Others

#### Sanctum

- Sanctum is used for token creating and authentication.

#### ApiResponser

- A trait is used that consists of 2 functions success and error which returns the json response. In the project this trait is used where we need to send the response either success or error.

#### Exception Handling

- Exception handling is done in bootstrap app.php where 500 and other exceptions are handled so that we don't need to add try catch everywhere.

#### Postman

- Postman requests collection for apis is attached if you want to test the apis using postman.

#### Can Have

- We can also use docker environment using docker files that will run the backend and database server.
- For now i have not used the token middleware on news apis so that any one can test without the token and without signup and login.
- We can also write unit test cases using PHPUnit
- We can also use Cors and CSRF middlewares for security
- We can also use php libraries for news_api, guardian_news_api and for other sources as well as the libraries for different languages are also available
- We can also use Mongodb as it stores data in document form and also offers full text search



