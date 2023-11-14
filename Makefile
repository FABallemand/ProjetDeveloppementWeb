ARCHIVE_NAME = ALLEMAND_Fabien_TRS
PROJECT_DIR := $(shell pwd)

.PHONY: reload_fixtures, clean, deep_clean, prepare_to_send, test

reload_fixtures:
	symfony console doctrine:database:drop --force
	rm -fr public/images/shoe/
	symfony console doctrine:database:create
	symfony console doctrine:schema:create
	symfony console doctrine:fixtures:load -n

clean:
	symfony console doctrine:database:drop --force
	symfony console cache:clear
	rm -fr .project .settings/

deep_clean:
	symfony console doctrine:database:drop --force
	symfony console cache:clear
	rm -fr composer.lock symfony.lock vendor/ var/cache/ public/images/
	rm -fr .project .settings/

prepare_to_send:
# Create copy in /tmp folder
	cd ..; cp -r ProjetDeveloppementWeb/ /tmp/$(ARCHIVE_NAME)
# Go to the project copy and deep clean the project copy
	cd /tmp/$(ARCHIVE_NAME); make deep_clean
# Compress project copy
	cd /tmp; zip -r $(ARCHIVE_NAME).zip $(ARCHIVE_NAME)
# Check archive
	zipinfo /tmp/$(ARCHIVE_NAME).zip
# Test archive
	mkdir /tmp/test
	cd /tmp/test; unzip ../$(ARCHIVE_NAME).zip
	cd /tmp/test/$(ARCHIVE_NAME); symfony composer install; symfony server:start &
	cd /tmp/test/$(ARCHIVE_NAME); make reload_fixtures
	firefox localhost/admin
	cd /tmp/test/$(ARCHIVE_NAME); sleep 30; symfony server:stop &
	rm -fr /tmp/$(ARCHIVE_NAME)
	rm -fr /tmp/test
# Move archive in the Download folder
	mv /tmp/$(ARCHIVE_NAME).zip /home/$$USER/Downloads/$(ARCHIVE_NAME).zip

test:
	touch test_file_1
	cp README.md /home/$$USER/Downloads/README.md
	mv test_file_1 /home/$$USER/Downloads/test_file_1
	cd /home/$$USER/Downloads/; touch test_file_2