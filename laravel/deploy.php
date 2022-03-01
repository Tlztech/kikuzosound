<?php
namespace Deployer;
require 'recipe/laravel.php';

set('shared_dirs', [
    'storage',
    'public/audio/stetho_sounds',
    'public/img/quizzes',
    'public/img/quiz_packs',
    'public/img/stetho_sound_images',
    'public/tmp',
]);

// Configuration
set('writable_dirs', [
  'bootstrap/cache',
  'storage',
  'public/audio/stetho_sounds',
  'public/img/quizzes',
  'public/img/quiz_packs',
  'public/img/stetho_sound_images',
  'public/tmp',
]);

set('default_stage', 'develop'); 

set('repository','git@github.com:glsw/3s_portal.git');

// ローカルサーバ
server('vagrant', '127.0.0.1', 2222)
    ->identityFile('~/.ssh/id_rsa.pub', '~/.ssh/id_rsa', '')
    ->user('vagrant')
    ->password('vagrant')
    ->set('deploy_path', '/var/www/laravel2')
    ->set('keep_releases', 5)
    ->set('branch', 'develop')
    ->stage('develop');


// 開発サーバ
 server('3sportal_dev', 'dev.telemedica.co.jp', 22)
    ->user('pharmaid')
    ->password('yakuzaishi1014')
    ->set('keep_releases', 5)
    ->set('branch', 'develop')
    ->set('deploy_path', '/var/www/html/dev3sp.telemedica.jp')
    ->stage('staging');

// 本番サーバ
server('3sportal_production', '3sportal.telemedica.co.jp', 22)
   ->identityFile('~/.ssh/id_rsa_3s_portal_github.pub', '~/.ssh/id_rsa_3s_portal_github', '')
   ->user('pharmaid')
   ->password('yakuzaishi1014')
   // ->user('gs')
   // ->password('globals_gs9680')
   ->set('keep_releases', 5)
   ->set('branch', 'master')
   ->set('deploy_path', '/var/www/html/dev3sp.telemedica.jp')
   ->stage('production');
