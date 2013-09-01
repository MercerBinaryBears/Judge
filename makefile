

all: composer.lock migrations public/judge_client.zip

composer.lock:
	composer selfupdate
	composer install

migrations:
	php artisan migrate

tests:
	touch app/database/testing.sqlite
	php artisan migrate --env=testing
	php artisan db:seed --env=testing
	phpunit
	# if tests didn't fail, touch the file, so the build passes
	if [ $$? -eq 0 ] ; then touch tests ; fi

# Build the judging packages
remove-pyc:
	find app/library/scripts/ -name *.pyc -exec rm {} \;

public/judge_client.zip: remove-pyc
	cd app/library/scripts && zip -r ../../../public/judge_client.zip .


# the production server, as per my ssh config file
PRODUCTION_SERVER = Judge
PARENT_DIR = ~/html
DEPLOY_DIR = $(PARENT_DIR)/Judge

deploy: backup-production clone-clean production-upload production-unpack production-migrate

backup-production:
	ssh $(PRODUCTION_SERVER) "[ -d $(DEPLOY_DIR) ] && mv $(DEPLOY_DIR) $(DEPLOY_DIR).old"

# clone a clean copy of the file
clone-clean:
	cd /tmp && rm -rf Judge.zip Judge && git clone git@github.com:chipbell4/Judge && zip -r Judge.zip Judge

production-upload:
	scp /tmp/Judge.zip $(PRODUCTION_SERVER):$(PARENT_DIR)

production-unpack:
	ssh $(PRODUCTION_SERVER) "cd $(PARENT_DIR) && unzip Judge.zip && rm Judge.zip"

production-migrate:
	ssh $(PRODUCTION_SERVER) "cd $(DEPLOY_DIR) && cp $(DEPLOY_DIR).old/app/database/production.sqlite app/database/production.sqlite"

production-bake:
	ssh $(PRODUCTION_SERVER) "cd $(DEPLOY_DIR) && ~/bin/composer install && artisan migrate"

clean:
	rm -f public/judge_client.zip
	rm -f tests
	rm -f app/database/testing.sqlite