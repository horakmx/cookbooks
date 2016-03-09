# CRD demo API

1. install chefdk 		: http://getchef.com/downloads/chef-dk
2. install berkshelf	: vagrant plugin install vagrant-berkshelf
3. install omnibus      : vagrant plugin install vagrant-omnibus
4. edit attribute file  : attributes/default.rb 
5. Run vagrant up
6; Run composer install

## Supported Platforms

All platforms supporting vagrant

## Attributes

<table>
  <tr>
    <th>Key</th>
    <th>Type</th>
    <th>Description</th>
    <th>Default</th>
  </tr>
  <tr> <td><tt>['crd-api']['app_name']</tt></td> <td>String</td> <td>Application name, will be used for local domain name</td> <td><tt></tt></td> </tr>
  <tr> <td><tt>['crd-api']['system_user']</tt></td> <td>String</td> <td>System User which will run the application</td> <td><tt></tt></td> </tr>
  <tr> <td><tt>['crd-api']['system_group']</tt></td> <td>String</td> <td>Above user group</td> <td><tt></tt></td> </tr>
  <tr> <td><tt>['crd-api']['base_directory']</tt></td> <td>String</td> <td>Apache base directory (files that should be accessible within the app)</td> <td><tt>/vagrant/app/</tt></td> </tr>
  <tr> <td><tt>['crd-api']['root_directory']</tt></td> <td>String</td> <td>Apache web root directory (files that should be accessible through web)</td> <td><tt>/vagrant/app/www</tt></td> </tr>
  <tr> <td><tt>['crd-api']['apache_host']</tt></td> <td>String</td> <td>Apache Listening Ip address</td> <td><tt>127.0.0.1</tt></td> </tr>
  <tr> <td><tt>['crd-api']['apache_port']</tt></td> <td>String</td> <td>Apache Listening Port</td> <td><tt>8080</tt></td> </tr>
</table>

## Usage

### crd-api::default

Include `crd-api` in your node's `run_list`:

```json
{
  "run_list": [
    "recipe[crd-api::vim]",
    "recipe[crd-api::apache2]",
    "recipe[crd-api::php]",
    "recipe[crd-api::php-fpm]",
    "recipe[crd-api::xdebug]",
    "recipe[crd-api::composer]",
    "recipe[crd-api::php-ci]",
    "recipe[crd-api::memcached]",
    "recipe[crd-api::phalcon]",
    "recipe[crd-api::bamboo]"
    ]
}
```

## License and Authors

Author:: Jean-Marc PULVAR <jm.pulvar@gmail.com>
