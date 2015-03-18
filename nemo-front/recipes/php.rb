
#updating packages before installing memcache modules otherwise it fails
bash "update packages" do
user "root"
code <<-EOF
 apt-get update --fix-missing
 EOF
end

#node.default['php']['install_method'] = 'source'
#node.default['php']['version'] = '5.4.36'
#node.default['php']['checksum'] = 'e11851662222765d6ab6e671adc983c657d5358a183856b43a5bad0c612d2959'

#node.default['php']['configure_options'] = %W{--prefix=#{node['php']['prefix_dir']}
#                                         --with-libdir=lib
#                                         --with-config-file-path=#{node['php']['conf_dir']}
#                                         --with-config-file-scan-dir=#{node['php']['ext_conf_dir']}
#                                         --with-pear
#                                         --with-zlib
#                                         --with-openssl
#                                         --with-kerberos
#                                         --with-bz2
#                                         --with-curl
#                                         --enable-ftp
#                                         --enable-zip
#                                         --enable-exif
#                                         --with-gd
#                                         --enable-gd-native-ttf
#                                         --with-gettext
#                                         --with-gmp
#                                         --with-mhash
#                                         --with-iconv
#                                        --with-imap
#                                         --with-imap-ssl
#                                         --enable-sockets
#                                         --enable-soap
#                                         --with-xmlrpc
#                                         --with-libevent-dir
#                                         --with-mcrypt
#                                         --enable-mbstring
#                                         --with-t1lib
#                                         --with-sqlite3
#                                         }
include_recipe "php"

#installing building tools
package "build-essential" do
   action :install
end

package "libpcre3-dev" do
   action :install
end

# install zend opcache  with directives
#php_pear "zendopcache" do
#  action :install
#    directives(	:memory_consumption 	=> 128, 
#		  		:interned_string_buffer	=> 8,
#				:max_accelerated_files	=> 4000,
#				:revalidate_freq		=> 60,
#				:fast_shutdown			=> 1,
#				:enable_cli => 1)
#end

php_pear "apc" do
  action :install
    directives(:shm_size => 128, :enable_cli => 1)
end

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
