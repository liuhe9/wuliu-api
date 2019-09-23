<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', '物流api');

// Project repository
set('repository', 'git@github.com:liuhe9/wuliu-api.git');

// 保存最近5个版本
set('keep_releases', 5);

// 不用sudo
set('writable_use_sudo', false);

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts
host('liuhetx')
    ->stage('dev')
    ->user('ubuntu')
    ->port(22)
    ->set('deploy_path', '/data/deploy/wuliu-api')
    ->forwardAgent(true)
    ->multiplexing(true)
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->addSshOption('StrictHostKeyChecking', 'no');

// 自定义任务：重置 opcache 缓存
task('opcache_reset', function () {
    run('{{bin/php}} -r \'opcache_reset();\'');
});

// Lumen重置任务
task('artisan:storage:link', function () {})->desc('artisan:storage:link nothing todo');
task('artisan:view:cache', function () {})->desc('artisan:view:cache nothing todo');
task('artisan:config:cache', function () {})->desc('artisan:config:cache nothing todo');
task('artisan:optimize', function () {})->desc('artisan:optimize nothing todo');


// nginx
task('nginx_conf', function () {
    run('cd {{release_path}} && cp nginx/api.conf /etc/nginx/sites-enabled/');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');
after('deploy:symlink', 'opcache_reset');

// after('deploy:update_code', 'artisan:migrate');
after('deploy:update_code', 'nginx_conf');
