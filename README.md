PacksAnSpiel
============

Das Spiel zu "Pack's An"


INSTALL
=======

System
------
* Make entry in /etc/hosts
    127.0.0.1 packsanspiel.isozaponol.de
* Optional: Replace IP address


Apache
------
* Create VHOST from data/apache-vhost.conf
* Adjust VHOST settings
* Install certs from data/cert

MySQL
-----
* Use latest dump data/dump_20170520.sql
* Create user

Symfony
-------

* install composer
* Create app/config/parameters.yml from app/config/parameters.yml.dist and adjust setttings
* run 
    composer install
* run
    php app/console assetic:dump --env=prod
* run
    php app/console assets:install --symlink --relative web --env=prod
* run
    php app/console doctrine:schema:update --force --env=prod
* run
    php app/console cache:clear --env=prod

Test
----

Try to login as 
* User: https://packsanspiel.isozaponol.de/login?qr=140910Johanna37589
* Game: https://packsanspiel.isozaponol.de/login?qr=game:9375db8784af1ef24c3ec559b23077a4
* Admin: https://packsanspiel.isozaponol.de/login?qr=admin:61e03f8fb5aaa883186835ff807f7ec9
