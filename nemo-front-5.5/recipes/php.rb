

include_recipe "php"

#php_pear "apc" do
#  action :install
#    directives(:shm_size => 128, :enable_cli => 1)
#end


package "php5-memcache" do
   action :install
 end

package "apache2-suexec" do
   action :install
 end

include_recipe "curl"

package "php5-curl" do
   action :install
end
