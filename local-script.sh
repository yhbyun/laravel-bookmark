#!/usr/bin/env bash

echo ">>> Create MySQL homestead database and user"

MYSQL=`which mysql`

Q1="CREATE DATABASE homestead;"
Q2="GRANT ALL ON homestead.* TO 'homestead'@'%' IDENTIFIED BY 'secret' WITH GRANT OPTION;"
Q3="FLUSH PRIVILEGES;"
SQL="${Q1}${Q2}${Q3}"

$MYSQL -uroot -proot -e "$SQL"

