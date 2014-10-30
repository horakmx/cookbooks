# nemo-front-cookbook

Cookbook for a Nemo portable copy  

1. install chefdk 		: http://getchef.com/downloads/chef-dk
2. install berkshelf	: vagrant plugin install vagrant-berkshelf
3. install omnibus      : vagrant plugin install vagrant-omnibus

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
  <tr>
    <td><tt>['nemo-front']['bacon']</tt></td>
    <td>Boolean</td>
    <td>whether to include bacon</td>
    <td><tt>true</tt></td>
  </tr>
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
