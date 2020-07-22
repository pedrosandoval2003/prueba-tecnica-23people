FROM centos:7.3.1611
# Add repos
RUN yum install -y epel-release yum-utils -y
RUN yum install -y http://rpms.remirepo.net/enterprise/remi-release-7.rpm
# update centos
RUN yum update -y
# tools
RUN yum install -y curl wget unzip yum-utils python-setuptools git
RUN yum-config-manager --enable remi-php73
# pacjages
RUN yum install -y php php-common php-opcache php-mcrypt php-cli php-gd php-curl php-mysqlnd  php-intl httpd*  php-fpm php-gd php-json php-mbstring php-mysqlnd php-xml php-xmlrpc php-op elinks php-pdo
RUN yum install -y mariadb-server phpmyadmin mariadb-client

## clean sys	
RUN package-cleanup --dupes
RUN package-cleanup --cleandupes
RUN yum clean all
RUN cd /var/www/html/ && git clone https://github.com/pedrosandoval2003/prueba-tecnica-23people.git && mv prueba-tecnica-23people/proyecto-empleados /var/www/html/
RUN mysql -e "create database sparkit_23people"
RUN mysql sparkit_23people < /var/www/html/prueba-tecnica-23people/scripts/23peopledb.sql
RUN mysql -e "grant all privileges on sparkit_23people.* to 'sparkit_23people'@'%' identified by 'Asturias.171*'"

EXPOSE 80 3306