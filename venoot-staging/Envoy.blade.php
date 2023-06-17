{{-- 
~ssh/config

Host venoot-stage.work
    User deployer
    IdentityFile "REPLACE_KEY_DIR\ndt"
    IdentitiesOnly yes 
    
php vendor/bin/envoy run deploy    
--}}

@servers(['staging' => 'deployer@venoot-stage.work'])

@setup
    $repository = 'git@gitlab.com:venoot/venoot.git';
    $releases_dir = '/var/www/venoot-stage/releases';
    $app_dir = '/var/www/venoot-stage';
    $release = date('YmdHis');
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup

@story('deploy')
    clone_repository
    run_composer
    run_yarn
    update_symlinks
    update_permissions
    migrate
@endstory

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
    git clone --branch staging --depth 1 {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('run_composer')
    echo "Starting deployment ({{ $release }})"
    cd {{ $new_release_dir }}
    composer install --prefer-dist --no-scripts -q -o
@endtask

@task('run_yarn')
    echo "Continue deployment ({{ $release }})"
    cd {{ $new_release_dir }}
    yarn
    yarn prod
@endtask

@task('update_symlinks')
    echo "Linking storage directory"
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage

    echo 'Linking .env file'
    ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env

    echo 'Linking current release'
    ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current
@endtask

@task('update_permissions')
    echo "Updating Permissions"
    cd {{ $new_release_dir }}
    sudo chown -R $USER:www-data {{ $app_dir}}/storage
    sudo chown -R $USER:www-data {{ $new_release_dir}}/bootstrap/cache
    sudo chmod -R 775 {{ $app_dir}}/storage
    sudo chmod -R 775 {{ $new_release_dir}}/bootstrap/cache
@endtask

@task('migrate')
    echo "Migrate ({{ $release }})"
    cd {{ $new_release_dir }}
    php artisan migrate
@endtask