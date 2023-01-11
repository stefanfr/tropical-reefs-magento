SHELL=/bin/sh
WRITE_CONFIG=false

ifneq (,$(wildcard ./.make_env))
	include .make_env
	export
endif

ifndef PHPBIN
	WRITE_CONFIG=true
	PHPBIN := $(shell $(SHELL) -c 'read -p "Enter PHP binary (default: `which php`): " php; echo $$php')
	ifndef PHPBIN
		override PHPBIN := `which php`
	endif
endif

ifndef COMPOSERBIN
	WRITE_CONFIG=true
	COMPOSERBIN := $(shell $(SHELL) -c 'read -p "Enter Composer binary (default: `which composer`): " composer; echo $$composer')
	ifndef COMPOSERBIN
		override COMPOSERBIN := `which composer`
	endif
endif

ifndef LANGS
	WRITE_CONFIG=true
	LANGS := $(shell $(SHELL) -c 'read -p "Enter magento languages (default: nl_NL en_US): " langs; echo $$langs')
	ifndef LANGS
		override LANGS := nl_NL en_US
	endif
endif

ifndef ENVIRONMENT
	WRITE_CONFIG=true
	ENVIRONMENT := $(shell $(SHELL) -c 'read -p "Enter environment (default: local): " environment; echo $$environment')
	ifndef ENVIRONMENT
		override ENVIRONMENT := local
	endif
endif

all: deploy

write-config :
ifeq ($(WRITE_CONFIG), true)
	$(SHELL) -c 'echo "PHPBIN=$(PHPBIN)\nCOMPOSERBIN=$(COMPOSERBIN)\nLANGS=$(LANGS)\nENVIRONMENT=$(ENVIRONMENT)"' > .make_env
endif

composer :
	$(PHPBIN) $(COMPOSERBIN) install --no-dev

composer-dev :
	$(PHPBIN) $(COMPOSERBIN) install

composer-dump :
	$(PHPBIN) $(COMPOSERBIN) dumpautoload -o

clear :
	rm -rf magento/var/cache/*
	rm -rf magento/var/page_cache/*
	rm -rf magento/var/session/*
	rm -rf magento/var/view_preprocessed/*
	rm -rf magento/pub/static/*
	rm -rf magento/generated/code/*

di-compile :
	$(PHPBIN) -dmemory_limit=-1 magento/bin/magento setup:di:compile

setup-upgrade :
	$(PHPBIN) -dmemory_limit=-1 magento/bin/magento setup:upgrade --keep-generated

static-content :
	$(PHPBIN) -dmemory_limit=-1 magento/bin/magento setup:static-content:deploy $(LANGS) -f -j8

cache :
	$(PHPBIN) magento/bin/magento cache:flush
	$(PHPBIN) magento/bin/magento cache:clean

deploy-prod : write-config composer install cache composer-dump

deploy-dev : write-config composer-dev install cache

deploy : write-config
ifeq ($(ENVIRONMENT), production)
	make deploy-prod
else
	make deploy-dev
endif

install : write-config clear di-compile static-content setup-upgrade

full-clear : clear static-content

ansible-deploy : deploy
