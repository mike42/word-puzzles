FROM debian:buster
ADD . /var/www/html/words
WORKDIR /root
RUN apt-get update && apt-get install --assume-yes apache2 php libapache2-mod-php
CMD /usr/sbin/apache2ctl -D FOREGROUND

