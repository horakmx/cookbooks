## Webpage test private instance on a physical box

This is an adaptation of a vagrant box to a physical machine using berkshelf, capistrano and roundsman.
In order to run this you will need to have ruby installed, you should be able to install the rest using bundler and the provided Gemfile.

1. Run bundler install
2. Edit the file named Capfile and change the values server and user if necessary, ideally you should be able to ssh into the server without password.
3. Run command cap provision

## License and Authors

Author:: Jean-Marc PULVAR <jm.pulvar@gmail.com>
