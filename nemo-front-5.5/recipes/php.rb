
#updating packages before installing memcache modules otherwise it fails
#bash "update packages" do
#user "root"
#code <<-EOF
# apt-get update --fix-missing
# EOF
#end

include_recipe "php"

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
