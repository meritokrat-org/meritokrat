#!/usr/bin/env bash

curl 'http://polithub.org/profile/edit?type=photo&submit=1&id=11752' \
    -H 'Pragma: no-cache' \
    -H 'Origin: http://polithub.org' \
    -H 'Accept-Encoding: gzip, deflate' \
    -H 'Accept-Language: uk,ru;q=0.8,en;q=0.6' \
    -H 'Upgrade-Insecure-Requests: 1' \
    -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36' \
    -H 'Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryl7AWhzCpNqfen5Cd' \
    -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' \
    -H 'Cache-Control: no-cache' \
    -H 'Referer: http://polithub.org/profile/edit?id=11752' \
    -H 'Cookie: fa53b91ccc1b78668d5af58e1ed3a485=till20052%40gmail.com%7C2bdd526587617ffbf91980b857decde2; PHPSESSID=egsjr6mir1e3pfnse6tiit1606; __utmt=1; __utma=217365527.1248567516.1476094733.1477554542.1477648019.26; __utmb=217365527.65.10.1477648019; __utmc=217365527; __utmz=217365527.1476094733.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)' \
    -H 'Connection: keep-alive' \
    --data-binary $'------WebKitFormBoundaryl7AWhzCpNqfen5Cd\r\nContent-Disposition: form-data; name="file"; filename="IMG_0001.JPG"\r\nContent-Type: image/jpeg\r\n\r\n\r\n------WebKitFormBoundaryl7AWhzCpNqfen5Cd--\r\n' \
    --compressed
    