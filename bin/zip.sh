#!/usr/bin/env bash

BACKUP_DIR='/var/backups/meritokrat'
BACKUP_FILE="${BACKUP_DIR}/BP$(date +'%y%m').zip"

zip -r /var/backups/meritokrat/.zip . -x \
    "./backups/*" \
    "./data/storage/*" \
    "./data/sql/*" \
    "./data/dump/*" \
    "./data/debug/debugmails/2016/*" \
    "./data/debug/mails/2016/*" \
    "./webapp/node_modules/*" \
    "./webapp/bower_components/*" \
    "./www/zz-app/node_modules/*" \
    "./www/zz-app/bower_components/*"
