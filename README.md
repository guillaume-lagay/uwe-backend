# Backend

## To install

composer install
composer update

* modify .env with your database properties

```
php bin/console doctrine:database:create
php bin/console doctrine:schema:update
php bin/console doctrine:fixtures:load // In order to generate entities in the database
```



## User Information 

20 students are randomly created, they all got the password 'azerty'
One student is created with email : 'glagay@localhost.fr' and password 'glagay'
One administrator is created with email : 'admin@localhost.fr' and password 'admin'


[Api endpoints list / test](https://l.messenger.com/l.php?u=https%3A%2F%2Fdocumenter.getpostman.com%2Fview%2F6880502%2FSVSKM9Cy%3Fversion%3Dlatest%23382b411b-2800-4a85-8ef1-4513f2bac61a&h=AT1vAGQKwZsXZT9t8_eTLwzR9zr3Th7PaMSgcCdtnY0-yYET3b65Ad2BfZrFEX4riMWUtgDWMRGTdlmh6GCm8naHkK2ebIK1ltHaDDVT9ErWs_WgFOtrSZ96JoVfSw)

 
 # FrontEnd
 
 ## To install
 
 move in project directory
 
 ```
 npm install
 ng-serve
```

## Access
Go to localhost
(API access is on IP : `193.70.31.117`)