#!/bin/bash

php artisan migrate:refresh
php artisan db:seed --force
php artisan job:dispatchNow UpdateGeoLite
php artisan ghmigrate:all

