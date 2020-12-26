COMPOSER_CMD=composer
PHIVE_CMD=phive

BEHAT_CMD=tools/behat
BOX_CMD=tools/box
PHPCS_CMD=tools/phpcs
PHPSTAN_CMD=tools/phpstan

TARGET=autogiro2xml.phar
DESTDIR=/usr/local/bin

SRC_FILES:=$(shell find src/ -type f -name '*.php')

.DEFAULT_GOAL=all

.PHONY: all build clean

all: test analyze build check

build: $(TARGET)

$(TARGET): $(SRC_FILES) bin/autogiro2xml box.json $(BOX_CMD)
	$(COMPOSER_CMD) install --prefer-dist --no-dev
	$(BOX_CMD) compile
	$(COMPOSER_CMD) install

clean:
	rm $(TARGET) --interactive=no -f
	rm -rf vendor
	rm -rf tools

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
# Tests and analysis
#

.PHONY: analyze test check phpstan phpcs

analyze: phpstan phpcs

test: $(BEHAT_CMD)
	$(BEHAT_CMD) --stop-on-failure --suite=default

check: $(TARGET) $(BEHAT_CMD)
	$(BEHAT_CMD) --stop-on-failure --suite=phar

phpstan: $(PHPSTAN_CMD)
	$(PHPSTAN_CMD) analyze -l 8 src

phpcs: $(PHPCS_CMD)
	$(PHPCS_CMD) src --standard=PSR2

#
# Dependencies
#

composer.lock: composer.json
	@echo composer.lock is not up to date

vendor/installed: composer.lock
	$(COMPOSER_CMD) install
	touch $@

tools/installed: .phive/phars.xml
	$(PHIVE_CMD) install --force-accept-unsigned --trust-gpg-keys CF1A108D0E7AE720,31C7E470E2138192,0FD3A3029E470F86
	touch $@

$(BEHAT_CMD): tools/installed
$(BOX_CMD): tools/installed
$(PHPCS_CMD): tools/installed
$(PHPSTAN_CMD): tools/installed
