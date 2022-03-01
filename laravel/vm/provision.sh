#!/bin/bash

# タイムゾーンをJSTに設定
# http://qiita.com/azusanakano/items/b39bd22504313884a7c3
cp /etc/sysconfig/clock /etc/sysconfig/clock.org
echo -e 'ZONE="Asia/Tokyo"\nUTC=false' > /etc/sysconfig/clock
cp /etc/localtime /etc/localtime.org
ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

# vim
yum -y install vim

# yum-utils
yum -y install yum-utils

# git
yum -y install git

# MySQL 5.5
yum -y install https://dev.mysql.com/get/mysql57-community-release-el6-9.noarch.rpm
sudo yum-config-manager --disable mysql57-community
sudo yum-config-manager --disable mysql56-community
sudo yum-config-manager --enable mysql55-community
yum -y install mysql mysql-community-server mysql-community-devel
sudo service mysqld start
sudo chkconfig mysqld on

# 外部からのアクセス用のユーザを追加
mysql -uroot -e 'grant all privileges on *.* to pharmaid@"%" identified by "qZRdepJLpcTK" with grant option;'
mysql -uroot -e 'grant all privileges on *.* to pharmaid@"localhost" identified by "qZRdepJLpcTK" with grant option;'
# 開発初期段階に使用してたユーザ
# mysql -uroot -e 'grant all privileges on *.* to gs@"%" identified by "password" with grant option;'
# mysql -uroot -e 'grant all privileges on *.* to gs@"localhost" identified by "password" with grant option;'
mysql -ugs -ppassword -e "CREATE DATABASE IF NOT EXISTS 3s_portal DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_unicode_ci";

# httpd(Apache 2.2)
yum -y install httpd
sudo service httpd start
sudo chkconfig httpd on

# httpd.confの修正
# 「DocumentRoot "/var/www/html"」 →「DocumentRoot "/var/www/laravel/public"」
# 「<Directory "/var/www/html">」→「「<Directory "/var/www/laravel/public">」」
# 「Options Indexes FollowSymLinks」→「# Options Indexes FollowSymLinks」
# 「AllowOverride None」→「AllowOverride All」
cp /etc/httpd/conf/httpd.conf /etc/httpd/conf/httpd.conf.org
cp /vagrant/httpd.conf /etc/httpd/conf/httpd.conf

# PHP 5.6
# http://qiita.com/ozawan/items/caf6e7ddec7c6b31f01e
rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-6.noarch.rpm
rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
yum -y install --enablerepo=remi,remi-php56 php php-devel php-mbstring php-pdo php-gd php-openssl php-tokenizer php-mysqlnd php-mcrypt php-xml

# Composer
cd ~/
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# firewall無効
/etc/rc.d/init.d/iptables stop
chkconfig iptables off

# laravel Installer (不要だと思う、バージョン指定でインストールする場合下記コマンド)
# composer create-project laravel/laravel blog "5.1.*"
cd ~/
composer global require "laravel/installer"
cp ~/.bash_profile ~/.bash_profile.org
echo -e "export PATH=$PATH:~/.composer/vendor/bin" >> ~/.bash_profile
source ~/.bash_profile
