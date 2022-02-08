# API Photos Fetcher

This system will self-provide with data from https://jsonplaceholder.typicode.com/photos

For use it, you will have to set the database for it to use in *.env* file.

You will also have to run the migrations using the command *php artisan migrate* for it to have
the required DB tables available.

After you have set up this, you will have to run the command *php artisan fetch:photos* to import data from the provided API
