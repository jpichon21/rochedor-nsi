require 'mina/bundler'
require 'mina/rails'
require 'mina/git'

account = 'rochedor'

set :repository, 'git@gitlab.com:logomotion/rochedor-nsi'
set :branch, 'dev' if ENV['on'] == 'dev'
set :branch, 'staging' if ENV['on'] == 'staging'

set :domain, 'rochedor.fr'
set :user, 'root'

set :forward_agent, true
set :term_mode, nil

set :deploy_to, "/home/#{account}/nsi_staging" if ENV['on'] == 'staging'
set :deploy_to, "/home/#{account}/nsi_dev" if ENV['on'] == 'dev'

set :shared_dirs, ['vendor', 'web/uploads', 'web/biblio', 'web/kiwi', 'web/site', 'var/cache', 'var/logs']
set :shared_files, ['web/.htaccess', 'web/robots.txt', 'web/kiwi.php', 'web/adminer-lrdo.php', 'app/config/parameters.yml']

task :deploy => :environment do
	deploy do
		invoke :'git:clone'
		invoke :'deploy:cleanup'
		invoke :'deploy:link_shared_paths'
		on :launch do
			command "export SYMFONY_ENV=prod"
			command "ea-php72 composer.phar install --optimize-autoloader"
			command "ea-php72 composer.phar dump-autoload --optimize --classmap-authoritative"
			command "/scripts/restartsrv_apache_php_fpm"
			command "yarn install"
			command "ea-php72 bin/console assets:install --env=prod"
			command "ea-php72 bin/console cache:clear --env=prod"
			command "chmod +x ./node_modules/.bin/encore"
			command "./node_modules/.bin/encore production --config-name configDev" if ENV['on'] == 'dev' || ENV['on'] == 'staging'
			command "./node_modules/.bin/encore production --config-name configProd" if ENV['on'] == 'prod'
			command "find #{fetch(:deploy_to)} -type d -exec chmod 755 {} +"
			command "find #{fetch(:deploy_to)} -type f -exec chmod 644 {} +"
			command "chown -R #{account}:#{account} #{fetch(:deploy_to)}"
			command "ea-php72 bin/console doctrine:schema:update --dump-sql > ../../update.sql"
		end
	end
end