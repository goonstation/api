#!/bin/bash

php artisan migrate:refresh
php artisan db:seed --force
php artisan ghmigrate:all

