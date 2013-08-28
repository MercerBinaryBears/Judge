

all: migrations public/judge_client.zip
	pwd

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

clean:
	rm -f public/judge_client.zip
	rm -f tests
	rm -f app/database/testing.sqlite