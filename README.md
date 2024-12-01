# RPO-Projekt
Backend part of a school project for a shoe store.
## Prerequisites
  - PHP version at least php-8.2
  - Composer
  - Docker
## Installation

```bash
#Clone the repository
git clone git@github.com:Voluharji/RPO-backend.git

cd RPO-backend
# install necessary packages
composer install
# You will also need to migrate Doctrine to MySQL
php bin/console doctrine:migrations:migrate 
# Launch compose stack
docker-compose up
```


