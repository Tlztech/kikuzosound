#!/bin/sh

# 通常のキャッシュクリア
php artisan cache:clear

# .envやconfigのキャッシュクリア
php artisan config:cache

