

all: public/judge_client.zip
	pwd

# Build the judging packages
remove-pyc:
	find app/library/scripts/ -name *.pyc -exec rm {} \;

public/judge_client.zip: remove-pyc
	cd app/library/scripts && zip -r ../../../public/judge_client.zip .

clean:
	rm -f public/judge_client.zip