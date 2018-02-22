payproapi
=========

A Symfony project created on May 19, 2017, 1:09 pm.

Steps to set up the project:


1.- Clone this project


2.- Clone ansible project for paypro in the same folder


3.- cp PayProApi/Vagrantfile.dist PayProApi/Vagrantfile (modify the path to ansible folder)


4.- cp PayProApi/app/config/parameters.yml.dist PayProApi/app/config/parameters.yml (fill properly the blank parameters)


5.- cp PayProAnsible/group_vars/PayProApi/dev.yml.dist PayProAnsible/group_vars/PayProApi/dev.yml (fill properly the blank parameters)

important parameters in dev.yml:

ssh_keys:

  path: /home/username/.ssh/ (path to you ssh keys in git)

  public_key: id_rsa.pub (filename of your public key in git)

  private_key: id_rsa (filename of your private key of the public key in git)

git:

  email: (git email)

  username: (git username)


6.- download and install LTS versions for Vagrant, VirtualBox, and Ansible

7.- in the PayProApi vagrant file set the $ansible_path to the actual PayProAnsible project

8.- vagrant up
