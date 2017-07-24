payproapi
=========

A Symfony project created on May 19, 2017, 1:09 pm.

Steps to set up the project:



1.- Clone this project
2.- Clone ansible project for paypro in the same folder
3.- cp payproapi/Vagrantfile.dist payproapi/Vagrantfile
4.- cp payproapi/app/config/parameters.yml.dist payproapi/app/config/parameters.yml (fill properly the blank parameters)

5.- cp ansible/group_vars/PayProApi/dev.yml.dist ansible/group_vars/PayProApi/dev.yml (fill properly the blank parameters)

important parameters in dev.yml:

ssh_keys:
  path: /home/username/.ssh/ (path to you ssh keys in git)
  public_key: id_rsa.pub (filename of your public key in git)
  private_key: id_rsa (filename of your private key of the public key in git)

git:
  email: (git email)
  username: (git username)


6.- vagrant up
