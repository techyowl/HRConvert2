FROM ubuntu:18.04

MAINTAINER Dylan Leahy <dylan@adr3nalin3.net>

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
EXPOSE 80

RUN apt-get update && \
apt-get install -y apache2 default-jre php php-mysql \ 
php-all-dev php-zip php-gd php-curl clamav libreoffice-common \ 
unoconv p7zip-full imagemagick unrar-free ffmpeg swftools tesseract-ocr \ 
meshlab dia pandoc poppler-utils zip unzip wget && \
apt-get clean && \ 
rm -rf /var/lib/apt/lists/*

ENV APACHE_RUN_USER  www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR   /var/log/apache2
ENV APACHE_PID_FILE  /var/run/apache2/apache2.pid
ENV APACHE_RUN_DIR   /var/run/apache2
ENV APACHE_LOCK_DIR  /var/lock/apache2
ENV APACHE_LOG_DIR   /var/log/apache2

RUN mkdir -p $APACHE_RUN_DIR && \
mkdir -p $APACHE_LOCK_DIR && \
mkdir -p $APACHE_LOG_DIR


COPY uploads.ini /etc/php/7.2/apache2/conf.d/uploads.ini
CMD ["/usr/sbin/apache2", "-D", "FOREGROUND"]

RUN mkdir /home/converter && \
chmod -R 0755 /home/converter && \
chown -R www-data /home/converter && \
chgrp -R www-data /home/converter

RUN chmod -R 0755 /var/www && \
chown -R www-data /var/www && \
chgrp -R www-data /var/www

RUN wget https://raw.githubusercontent.com/zelon88/HRConvert2/master/rc.local -O /etc/rc.local && \
chmod +x /etc/rc.local
RUN /usr/bin/soffice --headless --accept="socket,host=127.0.0.1,port=$soffice_port;urp;" --nofirststartwizard &
