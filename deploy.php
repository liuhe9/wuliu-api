<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', '物流api');

// Project repository
set('repository', 'git@github.com:liuhe9/wuliu-api.git');

// 保存最近5个版本
set('keep_releases', 5);

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts
host('liuhetx')
    ->stage('prod')
    ->user('ubuntu')
    ->port(22)
    ->set('deploy_path', '/data/deploy/wuliu-api')
    ->set('cachetool', '/var/run/php/php7.3-fpm.sock')
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


// nginx文件
desc('Upload nginx file');
task('nginx_conf', function () {
    upload('nginx/api.conf', '/etc/nginx/sites-enabled/');
    upload('nginx/cert/', '/etc/nginx/cert/');
    run('sudo service nginx reload');
});

// 将本地的 .env 文件上传到代码目录的 .env
desc('Upload .env file');
task('env:upload', function () {
    upload('./.env.prod', '{{release_path}}/.env');
});

after('deploy:shared', 'env:upload');

after('deploy:failed', 'deploy:unlock');
before('deploy:symlink', 'artisan:migrate');
// before('deploy:symlink', 'artisan:db:seed');
after('deploy:symlink', 'opcache_reset');
after('deploy:symlink', 'nginx_conf');
