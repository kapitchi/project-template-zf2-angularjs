ZF2 and AngularJS project template
==================================

Scaffolding for a project with a set up below.

Server:

* ZF2 (Apigility)

Client:

* AngularJS
* RequireJS
* SASS
* Bootstrap 3


Vagrant box (Ubuntu 12.04 Precise 64bit):

Box is based on https://puphpet.com/ with some extra provisioning (/puphpet/files/exec-once).

Default configuration can be modified in /puphpet/config.yaml. This file can be uploaded to https://puphpet.com/ to be modified using an UI.

* Apache
* PHP 5.5 - xdebug
* MySQL
* mailcatcher


Installation
============

1. Clone project

```
git clone https://github.com/kapitchi/project-template-zf2-angularjs.git
```

2. Provision vagrant box

```
vagrant up
```

3. Add `myapp.local` to hosts file

```
192.168.60.101 myapp.local
```

4. Create local default settings (optional)

Copy `config/autoload/local.php.dist` to `config/autoload/local.php`
This creates Apigility MySql DB adapter


PHP Debugging
-------------

1. Install xdebug browser extension

Find an extension for your browser here:
http://xdebug.org/docs/remote

2. Set up your IDE.

TODO Netbeans



Services
========

Application
-----------

http://myapp.local


Apigility Admin
---------------

http://192.168.60.101:8080/apigility/ui

Make sure Apigility server is running and it's set to development mode (see Development section below).


MySQL
-----

http://192.168.60.101/phpmyadmin  
User: root  
Pass: 123


Mailcatcher
-----------

http://192.168.60.101:1080/

In order to use mailcatcher your application mailer needs to be set up using this SMTP settings:  
Host: 127.0.0.1  
Port: 1025



Development
===========

```
vagrant ssh
cd /vagrant
composer install
bower install
compass compile
```

Apigility Admin
---------------

Set devel mode
```
php public/index.php development enable
```

Apigility Admin is devel tool and needs write permission on certain folders/files (e.g. `config/autoload/global.php`, `config/autoload/local.php`, `module` folder, etc).
Instead of messing up with write permissions on project folders it's better to run it using PHP build-in server using vagrant user.

vagrant ssh into a box and run:
```
php -S 0.0.0.0:8080 -t public public/index.php
```


To consider
===========

* https://www.npmjs.org/package/grunt-vagrant-ssh
* https://github.com/btford/ngmin


Contributing
============

Contact me on mz@kapitchi.com with any comments/feedback/ideas.

Things to keep an eye on in order to maintain this project template up-to-date:

* Apigility skeleton app - https://github.com/zfcampus/zf-apigility-skeleton