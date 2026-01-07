#!/usr/bin/env bash

USERNAME="polithub"
DATABASE="polithub-prod"
SUFFIX=$(php -r "print(date('Hidmy', microtime(true)));")

pg_dump \
    --username=${USERNAME} \
    --host=127.0.0.1 \
    --dbname=${DATABASE} \
    --exclude-table-data=mailing_send_mails \
    --exclude-table-data=messages \
    --exclude-table-data=messages_threads \
    --exclude-table-data=user_visits_log \
    > ./data/dump/${DATABASE}-${SUFFIX}.dev.sql

pg_dump \
    --username=${USERNAME} \
    --host=127.0.0.1 \
    --dbname=${DATABASE} \
    > ./data/dump/${DATABASE}-${SUFFIX}.prod.sql