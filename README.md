payproapi
=========

A Symfony project created on May 19, 2017, 1:09 pm.

Steps to set up the project:



1.- Clone this project
2.- Clone ansible project for paypro in the same folder
3.- cp payproapi/Vagrantfile.dist payproapi/Vagrantfile
4.- cp payproapi/app/config/parameters.yml.dist payproapi/app/config/parameters.yml (fill properly the blank parameters)
5.- cp ansible/group_vars/PayProApi/dev.yml.dist ansible/group_vars/PayProApi/dev.yml (fill properly the blank parameters)
6.- vagrant up
