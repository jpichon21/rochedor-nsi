require 'mina/bundler'
require 'mina/rails'
require 'mina/git'

account = 'rochedor'

set :repository, 'git@gitlab.com:logomotion/rochedor-nsi'
set :branch, 'master'

set :domain, 'rochedor.fr'
set :user, 'root'

set :forward_agent, true
set :term_mode, nil

set :deploy_to, "/home/#{account}/nsi_prod"

set :shared_dirs, ['vendor', 'web/uploads', 'var/cache', 'var/logs', 'node_modules']
set :shared_files, ['web/.htaccess', 'web/robots.txt', 'app/config/parameters.yml']

task :deploy => :environment do
	deploy do
		invoke :'git:clone'
		invoke :'deploy:cleanup'
		invoke :'deploy:link_shared_paths'
		on :launch do
			command "export SYMFONY_ENV=prod"
			command "ea-php70 composer.phar install --optimize-autoloader"
			command "yarn install"
			command "ea-php70 bin/console doctrine:schema:update --force"
			command "ea-php70 bin/console assets:install --env=prod"
			command "chmod +x ./node_modules/.bin/encore"
			command "./node_modules/.bin/encore production"
			command "find #{fetch(:deploy_to)} -type d -exec chmod 755 {} +"
			command "find #{fetch(:deploy_to)} -type f -exec chmod 644 {} +"
			command "chown -R #{account}:#{account} #{fetch(:deploy_to)}"
		end
	end
end