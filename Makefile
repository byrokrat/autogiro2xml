BEHAT=vendor/bin/behat
PHPSTAN=vendor/bin/phpstan
PHPCS=vendor/bin/phpcs
BOX=vendor/bin/box
SECURITY_CHECKER=vendor/bin/security-checker

COMPOSER_CMD=composer

TARGET=autogiro2xml.phar
DESTDIR=/usr/local/bin

SRC_FILES:=$(shell find src/ -type f -name '*.php')

.DEFAULT_GOAL=all

.PHONY: all build clean

all: test analyze build check

build: preconds $(TARGET)

$(TARGET): vendor-bin/installed $(SRC_FILES) bin/autogiro2xml box.json.dist
	$(COMPOSER_CMD) install --prefer-dist --no-dev
	$(BOX) compile
	$(COMPOSER_CMD) install

clean:
	rm $(TARGET) --interactive=no -f
	rm -rf vendor
	rm -rf vendor-bin

#
# Install/uninstall
#

.PHONY: install uninstall

install: $(TARGET)
	mkdir -p $(DESTDIR)
	cp $< $(DESTDIR)/autogiro2xml

uninstall:
	rm -f $(DESTDIR)/autogiro2xml

#
# Build preconditions
#

.PHONY: preconds dependency_check security_check

preconds: dependency_check security_check

dependency_check: vendor/installed
	$(COMPOSER_CMD) validate --strict
	$(COMPOSER_CMD) outdated --strict --minor-only

security_check: vendor-bin/installed
	$(SECURITY_CHECKER) security:check composer.lock

#
# Tests and analysis
#

.PHONY: analyze test check phpstan phpcs

analyze: phpstan phpcs

test: vendor-bin/installed
	$(BEHAT) --stop-on-failure --suite=default

check: vendor-bin/installed $(TARGET)
	$(BEHAT) --stop-on-failure --suite=phar

phpstan: vendor-bin/installed
	$(PHPSTAN) analyze -c phpstan.neon -l 7 src

phpcs: vendor-bin/installed
	$(PHPCS) src --standard=PSR2

#
# Dependencies
#

composer.lock: composer.json
	@echo composer.lock is not up to date

vendor/installed: composer.lock
	$(COMPOSER_CMD) install
	touch $@

vendor-bin/installed: vendor/installed
	$(COMPOSER_CMD) bin behat require behat/behat:^3
	$(COMPOSER_CMD) bin phpstan require "phpstan/phpstan:<2"
	$(COMPOSER_CMD) bin phpcs require squizlabs/php_codesniffer:^3
	$(COMPOSER_CMD) bin box require humbug/box:^3
	$(COMPOSER_CMD) bin security-checker require sensiolabs/security-checker
	touch $@
