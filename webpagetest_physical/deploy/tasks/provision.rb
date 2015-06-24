# deploy/tasks/provision.rb
require 'roundsman/capistrano'

set :application, 'webpagetest'
server 'webpagetest.ddns.net', :app
set :user, 'jnoun'

set :cookbooks_directory, ['tmp/cookbooks']
set :chef_version, '12.3.0'

namespace :provision do
  desc 'Install cookbooks and provision server'
  task :default do
    install
    apply
  end

  desc 'Install cookbooks with berkshelf'
  task :install do
    run_local "bundle exec berks install --path #{cookbook_directory}"
  end

  desc 'Provision server'
  task :apply do
    roundsman.run_list  "recipe[webpagetest::yasm]", "recipe[webpagetest::apache2]", "recipe[webpagetest::php]", "recipe[webpagetest::php-fpm]", "recipe[webpagetest::ffmpeg]", "recipe[webpagetest::imagemagick]", "recipe[webpagetest::imagelibs]"
  end
end

def run_local(command)
  system(command)
  if($?.exitstatus != 0) then
    puts 'exit code: ' + $?.exitstatus.to_s
    exit
  end
end
