#install java
include_recipe "java"

elasticsearch_user 'elasticsearch' do
username 'elasticsearch'
groupname 'elasticsearch'
shell '/bin/bash'
comment 'Elasticsearch User'
action :create
end

elasticsearch_install 'elasticsearch' do
type 'package'
version "5.0.0"
end

elasticsearch_configure 'elasticsearch' do
    allocated_memory '512m'
    configuration ({
      'cluster.name' => 'escluster',
      'node.name' => 'node01',
      'http.port' => 9201
    })
end

elasticsearch_service 'elasticsearch' do
service_actions [:enable, :start]
end

