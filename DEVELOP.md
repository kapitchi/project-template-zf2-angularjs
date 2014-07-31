Development notes
=================

PuPHPet
-------

Version from: 30/July/2014


https://puphpet.com/

__Modified__

`Vagrantfile`

Line 85: `group: 'www-data', owner: 'vagrant', mount_options: ["dmode=775", "fmode=764"]` -- `owner` is changed from www-data to vagrant


`puphpet/files/dot/.bash_aliases`

Search for '#kapitchi' section


`puphpet/files/exec-once`

some extra provisioning


Update
------

1. Copy & Paste existing manifest file `puphpet/config.yaml` onto a website https://puphpet.com/ - this should load the form with existing values

TODO