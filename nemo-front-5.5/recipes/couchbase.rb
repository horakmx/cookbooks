php_pear "couchbase" do
      action :install
end

include_recipe "couchbase::server"
include_recipe "couchbase::client"
