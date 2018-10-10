<?php

namespace Bob\BuildConfig;

task('default', ['test', 'phpstan', 'sniff', 'phar', 'test-phar']);

desc('Run behat feature tests');
task('test', ['update_container'], function() {
    shell('behat --stop-on-failure --suite=default');
    println('Behat feature tests passed');
});

desc('Run behat feature tests using phar');
task('test-phar', ['giroapp.phar'], function() {
    shell('behat --stop-on-failure --suite=phar');
    println('Behat feature tests using PHAR passed');
});

desc('Run statical analysis using phpstan feature tests');
task('phpstan', function() {
    shell('phpstan analyze -l 7 src');
    println('Phpstan analysis passed');
});

desc('Run php code sniffer');
task('sniff', function() {
    shell('phpcs src --standard=PSR2');
    println('Syntax checker on src/ passed');
});

desc('Build phar');
task('phar', function() {
    build_phar();
});

fileTask('autogiro2xml.phar', ['autogiro2xml.phar'], function () {
    build_phar();
});

function build_phar()
{
    shell('composer install --prefer-dist --no-dev');
    shell('box compile');
    shell('composer install');
    println('Phar generation done');
}

desc('Globally install development tools');
task('install_dev_tools', function() {
    shell('composer global require consolidation/cgr');
    shell('cgr behat/behat');
    shell('cgr phpstan/phpstan');
    shell('cgr squizlabs/php_codesniffer');
    shell('cgr humbug/box --stability dev');
});

function shell(string $command)
{
    return sh($command, null, ['failOnError' => true]);
}
