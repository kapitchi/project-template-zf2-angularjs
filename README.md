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


MySQL
-----

http://192.168.60.101/phpmyadmin  
User: root  
Pass: 123


Mailcatcher
-----------

http://192.168.60.101:1080/

In order to use mailcatcher you application mailer needs to be set up using this SMTP settings:  
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


TODO
====

* https://www.npmjs.org/package/grunt-vagrant-ssh
* https://github.com/btford/ngmin