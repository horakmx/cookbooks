# nemo-front-cookbook

Cookbook for a Nemo portable copy  

1. install chefdk 		: http://getchef.com/downloads/chef-dk
2. install berkshelf	: vagrant plugin install vagrant-berkshelf
3. install omnibus      : vagrant plugin install vagrant-omnibus
4. Run vagrant up

## Supported Platforms

Ubuntu 12.04 LTS

## Attributes

<table>
  <tr>
    <th>Key</th>
    <th>Type</th>
    <th>Description</th>
    <th>Default</th>
  </tr>
  <tr> <td><tt>['nemo']['user']</tt></td> <td>String</td> <td>User under which the application will run</td> <td><tt></tt></td> </tr>
  <tr> <td><tt>['nemo']['group']</tt></td> <td>String</td> <td>User Group under which the application will run</td> <td><tt></tt></td> </tr>
  <tr> <td><tt>['nemo']['root_directory']</tt></td> <td>String</td> <td>Apache root directory (where the application files will be held)</td> <td><tt>/home/username</tt></td> </tr>
  <tr> <td><tt>['nemo']['apache_host']</tt></td> <td>String</td> <td>Apache Listening Ip address</td> <td><tt>127.0.0.1</tt></td> </tr>
  <tr> <td><tt>['nemo']['apache_port']</tt></td> <td>String</td> <td>Apache Listening Port</td> <td><tt>8080</tt></td> </tr>
  <tr> <td><tt>['nemo']['varnish_host']</tt></td> <td>String</td> <td>Varnish Listening Ip address</td> <td><tt>127.0.0.1</tt></td> </tr>
  <tr> <td><tt>['nemo']['varnish_port']</tt></td> <td>String</td> <td>Varnish Listening Port</td> <td><tt>80</tt></td> </tr>
  <tr> <td><tt>['nemo']['svn_user']</tt></td> <td>String</td> <td>Svn User for checking out application files</td> <td><tt>User name filled in ['nemo']['user']</tt></td> </tr>
  <tr> <td><tt>['nemo']['svn_password']</tt></td> <td>String</td> <td>Svn Password for checking out application files</td> <td><tt></tt></td> </tr>
  <tr> <td><tt>['nemo']['svn_currys_branch']</tt></td> <td>String</td> <td>Full branch Path for Svn checkout</td> <td><tt></tt></td> </tr>
</table>

## Usage

### nemo-front::default

Include `nemo-front` in your node's `run_list`:

```json
{
  "run_list": [
	 "recipe[nemo-front::webserver]",
     "recipe[nemo-front::php-fpm]",
	 "recipe[nemo-front::memcached]",
	 "recipe[nemo-front::sqlrelay]",
	 "recipe[nemo-front::varnish]"
	 "recipe[nemo-front::websites]"
  ]
}
```

## License and Authors

Author:: Jean-Marc PULVAR <jm.pulvar@gmail.com>
