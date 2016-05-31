FROM debian
ADD . /var/www/html/words
WORKDIR /root
RUN apt-get update && apt-get install --assume-yes apache2 php5 libapache2-mod-php5
CMD /usr/sbin/apache2ctl -D FOREGROUND

