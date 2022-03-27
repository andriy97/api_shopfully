
## Installation 

Clone the repository

    git clone git@github.com:andriy97/api_shopfully.git

Switch to the repo folder

    cd api_shopfully

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Generate a new JWT authentication secret key

    php artisan jwt:generate
    
Create a database named 'shopfully_db' on your local server 
    
    I advise using xampp

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate
    
Import the .csv file into your local database 
    
    import .csv file to shopfully_db

Start the local development server

    php artisan serve

Start Apache and MySQL on xampp

    
    
You can now access the api at http://localhost:8000/flyers.json
 
