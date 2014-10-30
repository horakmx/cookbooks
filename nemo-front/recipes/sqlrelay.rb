#required for sql relay
package "libssl0.9.8" do
   action :install
end

bash "intall_libraries_from_github" do
user "root"

code <<-EOF
echo "Fetching EM Rudiments binary from github"
curl -Lo /usr/lib/librudiments-0.32-3~e-merchant4.so.1.0.0 "https://raw.github.com/jnoun/libraries/master/sqlrelay/librudiments-0.32-3~e-merchant4.so.1.0.0" 
ln -fs /usr/lib/librudiments-0.32-3~e-merchant4.so.1.0.0 /usr/lib/librudiments-0.32-3~e-merchant4.so.1   
echo "Done."

echo "Fectching EM Sqlrelay  binary from github"
curl -Lo /usr/lib/libsqlrclient-0.41-2~e-merchant24.so.1.0.0 "https://raw.github.com/jnoun/libraries/master/sqlrelay/libsqlrclient-0.41-2~e-merchant24.so.1.0.0" 
ln -fs /usr/lib/libsqlrclient-0.41-2~e-merchant24.so.1.0.0 /usr/lib/libsqlrclient-0.41-2~e-merchant24.so.1 
echo "Done."

echo "Fectching php module Sqlrelay binary from github"
curl -Lo  /usr/lib/php5/20090626/sql_relay.so  "https://raw.github.com/jnoun/libraries/master/sqlrelay/sql_relay.so"
echo "Done."

echo "copying Sqlrelay config file"
curl -Lo /etc/php5/conf.d/sqlrelay.ini "https://raw.github.com/jnoun/libraries/master/sqlrelay/sqlrelay.ini" 
echo "Done."

EOF

end
