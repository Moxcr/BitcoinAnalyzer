## Bitcoin Analyzer Documentation

Bitcoin Analyzer is a Dockerized PHP CodeIgniter application created for a code challenge.

**Features:**
* Displays daily Bitcoin data
* Built on the Bitnami CodeIgniter docker image
* Interactive Chart.js line graph display
* Responsive bootstrap front-end
* Scrapes public historical Bitcoin data from Yahoo
* Stores historical Bitcoin data in MariaDB table for subsequent requests
* JavaScript validated date range fields
* Dynamic jQuery AJAX graph updates on date range change
* CodeIgniter standard MVC architecture utilizing singular responsibility principles and separation of library logic

**Requirements:**
* Docker
* PHP 7.1+ (Included with Docker image)
* MariaDB (Included with Docker image)
* cURL-compatible network. The application must be able to pull public webpages with cURL. cURL is installed as part of the image.


**Note: At the time of this writing, there is something misconfigured with the Bitnami-codeigniter Docker image and it is unable to connect to the mariadb container.**


**Docker Instructions (Ignore for now until docker mariadb configuration is fixed):**

To start up the application, cd into the project base folder in your console. Then run "docker-compose up --build".

If it worked correctly, you should be able to see the application at localhost:8000

There is one extra step you must do to complete installation after building the docker container, that is a bit of a quirk with simple CodeIgniter applications. You must go to "localhost:8000/migration" to create the table necessary for the application to run.

Although not really necessary for this sample application, it's worth mentioning that once you've run the migration, you may secure the migration command if you wish in /application/config/migration.php. Change $config['migration_enabled'] to false.


**Without Docker:**

If you decide to setup the application without Docker, you will need to change /application/config/database.php to point to your database.

Setup Apache (or your preferred server), PHP, MySQL (or MariaDB), and ensure cURL is working on your system.

Create the "bitnami_app" schema in your database.

Setup the database.php file to your MySQL credentials as mentioned above.

If you're using Apache, open up its vhosts.conf file and add a new vhost entry for the application.

Add your local vhost name (for example, bitcoin.local) to your system's hosts file.

Ensure codeigniter can connect to your database and follow directions to run the data migration described in the Docker instructions above.


**Files of Interest:**
* /application/controllers/Graph.php
* /application/libraries/Bitcoin_api.php
* /application/migrations/001_create_bitcoin_data.php
* /application/models/Bitcoin_data_model.php
* /application/views/graph.php
* /application/config/bitcoin_api.php

The front-end javascript is fairly simple and under-engineered at this time to save time. If I come back to it I may create some more generic ES6 components.