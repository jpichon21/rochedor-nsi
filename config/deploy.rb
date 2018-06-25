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

set :deploy_to, "/home/#{account}/nsi_staging" if ENV['on'] == 'staging'
set :deploy_to, "/home/#{account}/nsi_dev" if ENV['on'] == 'dev'

set :shared_dirs, ['vendor', 'web/uploads', 'web/biblio', 'web/kiwi', 'web/site', 'var/cache', 'var/logs', 'node_modules']
set :shared_files, ['web/.htaccess', 'web/robots.txt', 'web/kiwi.php', 'web/adminer-lrdo.php', 'app/config/parameters.yml']

task :deploy => :environment do
	deploy do
		invoke :'git:clone'
		invoke :'deploy:cleanup'
		invoke :'deploy:link_shared_paths'
		on :launch do
			command "export SYMFONY_ENV=prod"
			command "ea-php72 composer.phar install --optimize-autoloader"
			command "yarn install"
			command "ea-php72 bin/console doctrine:schema:update --dump-sql > schema-update.sql"
			command "ea-php72 bin/console assets:install --env=prod"
			command "chmod +x ./node_modules/.bin/encore"
			command "./node_modules/.bin/encore production"
			command "find #{fetch(:deploy_to)} -type d -exec chmod 755 {} +"
			command "find #{fetch(:deploy_to)} -type f -exec chmod 644 {} +"
			command "chown -R #{account}:#{account} #{fetch(:deploy_to)}"
		end
	end
end