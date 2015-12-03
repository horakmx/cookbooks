# Webpagetest virtual instance

1. install chefdk 		: http://getchef.com/downloads/chef-dk
2. install berkshelf	: vagrant plugin install vagrant-berkshelf
3. install omnibus      : vagrant plugin install vagrant-omnibus
4. edit attribute file  : attributes/default.rb 
5. Run vagrant up

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
  <tr> <td><tt>['webpagetest']['app_name']</tt></td> <td>String</td> <td>Application name, will be used for local domain name</td> <td><tt></tt></td> </tr>
  <tr> <td><tt>['webpagetest']['system_user']</tt></td> <td>String</td> <td>System User which will run the application</td> <td><tt></tt></td> </tr>
  <tr> <td><tt>['webpagetest']['system_group']</tt></td> <td>String</td> <td>Above user group</td> <td><tt></tt></td> </tr>
  <tr> <td><tt>['webpagetest']['root_directory']</tt></td> <td>String</td> <td>Apache root directory (where the application files will be held)</td> <td><tt>/home/username</tt></td> </tr>
  <tr> <td><tt>['webpagetest']['apache_host']</tt></td> <td>String</td> <td>Apache Listening Ip address</td> <td><tt>127.0.0.1</tt></td> </tr>
  <tr> <td><tt>['webpagetest']['apache_port']</tt></td> <td>String</td> <td>Apache Listening Port</td> <td><tt>8080</tt></td> </tr>
</table>

## Usage

### webpagetes::default

Include `webpagetest` in your node's `run_list`:

```json
{
  "run_list": [
	 "recipe[webpagetest::webserver]",
     "recipe[webpagetest::php-fpm]",
	 "recipe[webpagetest::memcached]",
	 "recipe[webpagetest::sqlrelay]",
	 "recipe[webpagetest::varnish]"
	 "recipe[webpagetest::websites]"
  ]
}
```

## License and Authors

Author:: Jean-Marc PULVAR <jm.pulvar@gmail.com>
