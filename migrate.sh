#!/bin/bash

php artisan ghmigrate:gameadmins
php artisan ghmigrate:gamerounds
php artisan ghmigrate:players
php artisan ghmigrate:playerslegacy
php artisan ghmigrate:antags
php artisan ghmigrate:bans
php artisan ghmigrate:jobbans
php artisan ghmigrate:notes
php artisan ghmigrate:mapswitches
php artisan ghmigrate:vpnchecks
php artisan ghmigrate:metadata
php artisan ghmigrate:clouddata
php artisan ghmigrate:events
