COMPOSER=composer

BEHAT=vendor/bin/behat
PHPSTAN=vendor/bin/phpstan
PHPCS=vendor/bin/phpcs
BOX=vendor/bin/box
SECURITY_CHECKER=vendor/bin/security-checker

SRC_FILES=$(shell find src/ -type f -name '*.php')

TARGET=autogiro2xml.phar

.DEFAULT_GOAL := all

.PHONY: all build clean

all: test analyze build check

build: preconds $(TARGET)

$(TARGET): vendor $(BOX) $(SRC_FILES) bin/autogiro2xml box.json.dist
	$(COMPOSER) install --prefer-dist --no-dev
	$(BOX) compile
	$(COMPOSER) install

clean:
	rm $(TARGET) --interactive=no -f
	rm -rf vendor
	rm -rf vendor-bin

#
# Build preconditions
#

.PHONY: preconds dependency_check security_check

preconds: dependency_check security_check

dependency_check: vendor
	$(COMPOSER) validate --strict
	$(COMPOSER) outdated --strict --minor-only

security_check: vendor $(SECURITY_CHECKER)
	$(SECURITY_CHECKER) security:check composer.lock

#
# Tests and analysis
#

.PHONY: analyze test check phpstan phpcs

analyze: phpstan phpcs

test: vendor $(BEHAT)
	$(BEHAT) --stop-on-failure --suite=default

check: vendor $(BEHAT) $(TARGET)
	$(BEHAT) --stop-on-failure --suite=phar

phpstan: vendor $(PHPSTAN)
	$(PHPSTAN) analyze -l 7 src

phpcs: vendor $(PHPCS)
	$(PHPCS) src --standard=PSR2

#
# Dependencies
#

composer.lock: composer.json
	@echo composer.lock is not up to date

vendor: composer.lock
	composer install

$(BEHAT):
	$(COMPOSER) bin behat require behat/behat:^3

$(PHPSTAN):
	$(COMPOSER) bin phpstan require "phpstan/phpstan:<2"

$(PHPCS):
	$(COMPOSER) bin phpcs require squizlabs/php_codesniffer:^3

$(BOX):
	$(COMPOSER) bin box require humbug/box:^3

$(SECURITY_CHECKER):
	$(COMPOSER) bin security-checker require sensiolabs/security-checker
