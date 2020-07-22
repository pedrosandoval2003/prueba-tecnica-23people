#!/bin/bash
/usr/bin/mysqld_safe --skip-grant-tables &
sleep 5
mysql -u root -e "CREATE DATABASE sparkit_23people"
mysql -u root sparkit_23people < /var/www/html/pprueba-tecnica-23people/scripts/23peopledb.sql
mysql -u root -e "grant all privileges on sparkit_23people.* to 'sparkit_23people'@'%' identified by 'Asturias.171*'"