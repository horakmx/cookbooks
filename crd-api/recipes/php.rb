
include_recipe "php"

include_recipe "curl"

package "php5-curl" do
   action :install
end

package "php5-memcache" do
   action :install
end

#installing building tools
package "build-essential" do
   action :install
   end

#php_pear "apc" do
#  action :install
#      directives(:shm_size => 128, :enable_cli => 1)
#end
