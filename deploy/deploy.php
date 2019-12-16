<?php

    namespace Deployer;
    
    require 'recipe/composer.php';
    require 'recipe/common.php';

    set('repository', 'git@gitlab.com:logomotion/rochedor-nsi.git');
    set('git_tty', false);
    set('default_stage', 'dev');
    set('shared_files', ['web/.htaccess', 'web/robots.txt', 'app/config/parameters.yml']);
    set('shared_dirs', ['vendor', 'web/uploads', 'var/cache', 'var/logs', 'node_modules']);
    set('bin/php', '/usr/local/bin/ea-php72 -c deploy/deploy.ini');

    host('staging.rochedor.fr')
    ->stage('staging')
    ->user('root')
    ->hostname('rochedor.fr')
    ->set('account_dir', 'rochedor')
    ->set('branch', 'staging')
    ->set('encore_config_name', 'configDev')
    ->set('deploy_path', '/home/{{account_dir}}/nsi_staging');

    host('nsi.rochedor.fr')
    ->stage('dev')
    ->user('root')
    ->hostname('rochedor.fr')
    ->set('account_dir', 'rochedor')
    ->set('branch', 'dev')
    ->set('encore_config_name', 'configDev')
    ->set('deploy_path', '/home/{{account_dir}}/nsi_dev');
    
    host('demo-front.rochedor.fr')
    ->stage('demo_front2')
    ->user('root')
    ->hostname('rochedor.fr')
    ->set('account_dir', 'rochedor')
    ->set('branch', 'demo_front2')
    ->set('encore_config_name', 'configDev')
    ->set('deploy_path', '/home/{{account_dir}}/nsi_demo_front');

    host('rochedor.fr')
    ->stage('prod')
    ->user('root')
    ->hostname('rochedor.fr')
    ->set('account_dir', 'rochedor')
    ->set('branch', 'master')
    ->set('encore_config_name', 'configProd')
    ->set('deploy_path', '/home/{{account_dir}}/nsi_prod');

    task('deploy', [
        'deploy:info',
        'deploy:prepare',
        'deploy:lock',
        'deploy:release',
        'deploy:update_code',
        'deploy:shared',
        'deploy:writable',
        'deploy:clear_paths',
        'deploy:symlink',
        'deploy:unlock',
        'install',
        'permissions',
        'project-cleanup',
        'cleanup',
        'success'
    ]);

    task('project-cleanup', function () {
        run('rm -rf script/');
        run('rm -rf deploy/');
        run('rm -f docker-compose.yml Dockerfile settings.json .gitlab-ci.yml .gitignore .env-dist');
    });

    task('install', function () {
        run('export SYMFONY_ENV=prod');
        run('cd {{release_path}} && {{bin/php}} composer.phar install --optimize-autoloader');
        run('cd {{release_path}} && {{bin/php}} composer.phar dump-autoload --optimize --classmap-authoritative');
        run('cd {{release_path}} && yarn install');
        run('cd {{release_path}} && {{bin/php}} bin/console cache:clear --env=prod');
        run('cd {{release_path}} && {{bin/php}} bin/console assets:install --env=prod');
        run('cd {{release_path}} && chmod +x ./node_modules/.bin/encore');
        run('cd {{release_path}} && ./node_modules/.bin/encore production --config-name {{encore_config_name}}');
        run('cd {{release_path}} && {{bin/php}} bin/console doctrine:schema:update --dump-sql > ../../update.sql');
    });

    task('permissions', function () {
        run('find {{deploy_path}} -type d -exec chmod 755 {} +');
        run('find {{deploy_path}} -type f -exec chmod 644 {} +');
        run('chown -R {{account_dir}}:{{account_dir}} {{deploy_path}}');
    });

    after('deploy:failed', 'deploy:unlock');
