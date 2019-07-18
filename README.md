To install

composer install
composer update

* modify .env with your database properties

php bin/console doctrine:database:create
php bin/console doctrine:schema:update
php bin/console doctrine:fixtures:load // In order to generate entities in the database



** User Information **

20 students are randomly created, they all got the password 'azerty'
One student is created with email : 'glagay@localhost.fr' and password 'glagay'
One administrator is created with email : 'admin@localhost.fr' and password 'admin'
