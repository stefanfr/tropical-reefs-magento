RANDOMNR := $(shell php -r 'echo rand(10000000,99999999);')

all: deploy

composer :
	composer install --no-dev

composer-dev :
	composer install

composer-dump :
	composer dumpautoload -o

clear :
	rm -rf magento/var/cache/*
	rm -rf magento/var/page_cache/*
	rm -rf magento/var/session/*
	rm -rf magento/var/view_preprocessed/*
	rm -rf magento/pub/static/*
	rm -rf magento/generated/code/*

di-compile :
	php magento/bin/magento setup:di:compile

setup-upgrade :
	php magento/bin/magento setup:upgrade --keep-generated

static-content :
	php magento/bin/magento setup:static-content:deploy --area=adminhtml en_US -f -j11

cache :
	php magento/bin/magento cache:flush
	php magento/bin/magento cache:clean

deploy-prod : composer install cache composer-dump

deploy-dev : composer-dev install cache

deploy : deploy-prod

install : clear di-compile static-content setup-upgrade

full-clear : clear static-content
