#!/usr/bin/env bash

curl 'https://meritokrat.org/admin/cron_internallist' \
  -H 'authority: meritokrat.org' \
  -H 'pragma: no-cache' \
  -H 'cache-control: no-cache' \
  -H 'upgrade-insecure-requests: 1' \
  -H 'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_2_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36' \
  -H 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9' \
  -H 'sec-fetch-site: none' \
  -H 'sec-fetch-mode: navigate' \
  -H 'sec-fetch-user: ?1' \
  -H 'sec-fetch-dest: document' \
  -H 'accept-language: uk-UA,uk;q=0.9,ru;q=0.8,en;q=0.7' \
  -H 'cookie: __utmz=169589437.1613991879.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); fa53b91ccc1b78668d5af58e1ed3a485=till20052%40gmail.com%7C3fc0a7acf087f549ac2b266baf94b8b1; PHPSESSID=d7pdi7bsv4bhkq402tal6nsv75; __utmc=169589437; __utma=169589437.923513186.1613991879.1614684412.1614687149.18; __utmt=1; __utmb=169589437.20.10.1614687149' \
  --compressed \
  --insecure