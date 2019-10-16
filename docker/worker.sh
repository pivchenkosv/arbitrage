#!/usr/bin/env bash

php ~/artisan queue:work --verbose --tries=3 --timeout=90 &

while [ true ]
    do
      php ~/artisan schedule:run --verbose --no-interaction &
      sleep 60
    done
