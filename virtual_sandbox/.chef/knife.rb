log_level                :info
log_location             STDOUT
node_name                'jm.pulvar'
client_key               '/Users/jm.pulvar/.chef/jm.pulvar.pem'
validation_client_name   'chef-validator'
validation_key           '/etc/chef-server/chef-validator.pem'
chef_server_url          'https://125-UX.local:443'
syntax_check_cache_path  '/Users/jm.pulvar/.chef/syntax_check_cache'
cookbook_path [ './cookbooks' ]
