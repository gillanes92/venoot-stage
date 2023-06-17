<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'venoot');

// Project repository
set('repository', 'git@gitlab.com:venoot/venoot.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys
add('shared_files', ['php/cacert.pem', 'venoot']);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);

// Hosts
host('159.203.105.38')
    ->stage('staging')
    ->user('deployer')
    ->identityFile('~/.ssh/deployerkey')
    ->multiplexing(false)
    ->set('deploy_path', '/var/www/venoot-stage.work/html')
    ->set('branch', 'staging');

host('134.209.32.151')
    ->stage('production')
    ->user('deployer')
    ->identityFile('~/.ssh/deployerkey')
    ->multiplexing(false)
    ->set('deploy_path', '/var/www/html/venoot')
    ->set('branch', 'production');

// Tasks
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'yarn:install',
    'yarn:production',
    'deploy:writable',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    'artisan:optimize',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

task('yarn:install', function () {
    run('cd {{release_path}} && yarn');
});

task('yarn:production', function () {
    run('cd {{release_path}} && yarn prod');
});

task('artisan:view:cache', function () {
    $needsVersion = 5.6;
    $currentVersion = get('laravel_version');

    run('mkdir {{release_path}}/vendor/pragmarx/datatables/src/Bllim/Datatables/views');

    if (version_compare($currentVersion, $needsVersion, '>=')) {
        run('{{bin/php}} {{release_path}}/artisan view:cache');
    }
});

task('supervisor:restart', function () {
    run('supervisorctl restart all');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');

// Restart Supervisor to point to new release
before('cleanup', 'supervisor:restart');
