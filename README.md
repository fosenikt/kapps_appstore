# Kapps Appstore

## 1. About
kApps is just a database (with backend/api and frontend) ment for categorizing applications (like an appstore).
It's goal is to share applications and services, and make them more accessible for government/counties/municipalities.



## 2. Prerequisite
- Any Linux docker environment ([How To Install and Use Docker on Ubuntu 18.04](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-18-04))

### Recommended
- Azure Enterprise Application for O365-login.


## 3. Install
1. Register two DNS with an A-record to your environment (e.g. apps.kapps.no and appsapi.kapps.no).
2. Register and configure the Azure app to get keys (see 4. Register an Azure App)
3. Create docker-compose.yaml and paste example from *5.Docker-compose* on your Linux Docker server.
4. Insert your own variables into docker-compose.yaml file
5. Run "docker compose up" (add a -d for detached, but it's good to look for any error messages on first run)
6. Populate database (See section 7. Populate database)

*This install steps is how we run the application. You could use IP:Port insted of DNS, an existing Azure app, run it directly without docker-compose, use your own database, etc...*



### 3.1 Environtment
If you use DNS, you will need and proxy like [jwilder/nginx-proxy](https://hub.docker.com/r/jwilder/nginx-proxy) or [træfik](https://doc.traefik.io/traefik/).

We have used [jwilder/nginx-proxy](https://hub.docker.com/r/jwilder/nginx-proxy) with the [Let's encrypt companion](https://github.com/nginx-proxy/docker-letsencrypt-nginx-proxy-companion).
The VIRTUAL_HOST environment-variable is used both in app and for jwilder/nginx-proxy - so if you use another proxy, you still need the VIRTUAL_HOST in your docker-compose file.

**Example to run jwilder/nginx-proxy with Let's encrypt companion**
```
docker run --detach \
    --name nginx-proxy \
    --publish 80:80 \
    --publish 443:443 \
    --volume /etc/nginx/certs \
    --volume /etc/nginx/vhost.d \
    --volume /usr/share/nginx/html \
    --volume /var/run/docker.sock:/tmp/docker.sock:ro \
	--volume /root/proxy/my_proxy.conf:/etc/nginx/conf.d/my_proxy.conf:ro \
    jwilder/nginx-proxy
	
	
docker run --detach \
    --name nginx-proxy-letsencrypt \
    --volumes-from nginx-proxy \
    --volume /var/run/docker.sock:/var/run/docker.sock:ro \
    --volume /etc/acme.sh \
    --env "DEFAULT_EMAIL=___CONTACT_MAIL___" \
    jrcs/letsencrypt-nginx-proxy-companion
```

### 3.2 Portainer
[Portainer](https://www.portainer.io/products/community-edition) is a great tool to get a GUI for your docker environment.




## 4. Register an Azure App for Office365 Login

1. Go to https://aad.portal.azure.com/
2. Select All services in left sidebar menu
3. Click on App registrations under indentity
4. Click on New registration
5. Give an appropriate name, like FIPO Calendar
6. Select single-tenant (Should be default selected)
7. Redirect URL should be https://CALENDAR_DOMAIN/login/microsoft/getToken.php

### 4.1 Configure the app

1. Go to Certificates & secrets and click on New client secret
2. Copy the secret (Important, as the secret will be hidden after you navigate to another page)
3. Go to API permissions and click on Add a permission
4. Select Microsoft Graph
5. Select Application permissions
6. Search fpr calendar and check the Calendars.Read
7. Click on Add permissions button at the bottom
8. Then you will need to click the Grant permission for my_tenant button
9. Under Expose an API you will need to click the expose button on the top. After clicking this, you will see the Application ID URI.

Under *Overview* in the application sidebar, you will see the:

Application (client) ID
Directory (tenant) ID
This keys, with the secret key you copied, will need to be entered in your docker-compose file.




## 5. Docker-compose:

```
version: '3.2'
services:

  ## FRONTEND
  kapps-appstore-frontend:
    container_name: kapps-appstore-frontend
    image: fosenikt/kapps-appstore-frontend:latest
    restart: always
    ports:
      - "8070:80"
    volumes:
      - "/etc/timezone:/etc/timezone:ro"
      - "/etc/localtime:/etc/localtime:ro"
    environment:
        - VIRTUAL_HOST=apps.kapps.no
        - API_URL=appsapi.kapps.no
        - LETSENCRYPT_HOST=apps.kapps.no
        - LETSENCRYPT_EMAIL=___INSERT_HERE___
    networks:
      - default

  ## API
  kapps-appstore-api:
    container_name: kapps-appstore-api
    image: fosenikt/kapps-appstore-api:latest
    restart: always
    ports:
      - "8071:80"
    volumes:
      - kapps-appstore-api-vendor:/var/www/html/vendor/
      - kapps-appstore-api-db:/var/www/html/db
      - kapps-appstore-data:/var/www/html/data
      - "/etc/timezone:/etc/timezone:ro"
      - "/etc/localtime:/etc/localtime:ro"
    environment:
        - VIRTUAL_HOST=appsapi.kapps.no
        - LETSENCRYPT_HOST=appsapi.kapps.no
        - LETSENCRYPT_EMAIL=___INSERT_HERE___
        - FRONTEND_HOST=apps.kapps.no
        - DB_NAME=kapps_appstore
        - DB_USER=kapps_appstore
        - DB_PASSWORD=___INSERT_HERE___
        - DB_HOST=___INSERT_HERE___
        - DB_PORT=___INSERT_HERE___
        - MEMCACHED_HOST=kapps-appstore-memcached
        - MEMCACHED_PORT=11211
        - O365_TENANT_ID=___INSERT_HERE___
        - O365_APP_ID=___INSERT_HERE___
        - O365_APP_SECRET=___INSERT_HERE___
        - JWT_ISSUER=FosenIKT
        - JWT_SECRET=___INSERT_HERE___
        - UPLOAD_FOLDER=/data
        - IPGEOLOCATION_API_KEY=___INSERT_HERE___
        - SMTP_HOST=smtp.office365.com
        - SMTP_USERNAME=___INSERT_HERE___
        - SMTP_PASSWORD=___INSERT_HERE___
        - SMTP_PORT=587
        - SMTP_SENDER_MAIL=___INSERT_HERE___
        - SMTP_SENDER_NAME=Kapps.no
    networks:
      - default




  ## Memcached
  kapps-appstore-memcached:
    container_name: kapps-appstore-memcached
    image: memcached:latest
    restart: always
    ports:
        - "8034:11211"
    networks:
      - default

  ## Scheduler
  kapps-appstore-scheduler:
    container_name: kapps-appstore-scheduler
    image: fosenikt/kapps-appstore-scheduler:latest
    restart: always
    volumes:
      - "/etc/timezone:/etc/timezone:ro"
      - "/etc/localtime:/etc/localtime:ro"
    environment:
      - API_URL=appsapi.kapps.no
      - TOKEN=___CREATE_AND_INSERT_HERE___
      - CRON_SCHEDULE=* * * * *
      - CRON_COMMAND=php /var/www/html/run.php
    networks:
      - default

## VOLUMES
volumes:
    kapps-appstore-api-vendor:
    kapps-appstore-api-db:
    kapps-appstore-data:

networks:
  default:
    external:
      name: webproxy
```

### Other

Depending on your environment...

```
  kapps-appstore-composer:
    container_name: kapps-appstore-composer
    image: composer:latest
    command: sh -c "composer install --ignore-platform-reqs"
    volumes:
      - kapps-appstore-api:/app
    depends_on:
      - kapps-appstore-api

  ## Database
  kapps-appstore-db:
    container_name: kapps-appstore-db
    build:
      context: ./db/
      dockerfile: ./Dockerfile
    restart: always
    command: --default-authentication-plugin=mysql_native_password
    restart: always
    environment:
      MYSQL_DATABASE: 'kapps'
      # So you don't have to use root, but you can if you like
      MYSQL_USER: 'kapps'
      # You can use whatever password you like
      MYSQL_PASSWORD: '___INSERT_HERE___'
      # Password for root access
      MYSQL_ROOT_PASSWORD: '___INSERT_HERE___'
      TZ: Europe/Oslo
    ports:
      # <Port exposed> : < MySQL Port running inside container>
      - '8033:3306'
      # Where our data will be persisted
    volumes:
      - ./db:/docker-entrypoint-initdb.d
      - kapps-appstore-db:/var/lib/mysql
      - "/etc/timezone:/etc/timezone:ro"
      - "/etc/localtime:/etc/localtime:ro"

  ## PHPmyadmin
  kapps-appstore-phpmyadmin:
    container_name: kapps-appstore-phpmyadmin
    image: phpmyadmin/phpmyadmin
    links:
      - kapps-appstore-db
    environment:
      PMA_HOST: kapps-appstore-db
      PMA_PORT: 3306
    ports:
      - '8032:80'
    volumes: 
      - /sessions
```

### Some notes for the docker-compose file

#### Environment-variables

**VIRTUAL_HOST**
*Required*
Used for creating config-files and for [NGINX-Proxy](https://hub.docker.com/r/jwilder/nginx-proxy)

**LETSENCRYPT_HOST**
*Not Required*
Used for the [Let's Encrypt Companion](https://github.com/nginx-proxy/docker-letsencrypt-nginx-proxy-companion)

**LETSENCRYPT_EMAIL**
*Not Required*
Used for the [Let's Encrypt Companion](https://github.com/nginx-proxy/docker-letsencrypt-nginx-proxy-companion)

**FRONTEND_HOST**
*Required*
Used in backend to redirect user.

**API_URL**
*Required*
Used in frontend to connect to API

**VIRTUAL_PORT**
*Not Required*
See docs for [NGINX-Proxy](https://hub.docker.com/r/jwilder/nginx-proxy)

**DB_NAME**
*Required*
Database name

**DB_USER**
*Required*
Database user

**DB_PASSWORD**
*Required*
Database password

**DB_HOST**
*Required*
Database host

**DB_PORT**
*Required*
Database port

**MEMCACHED_HOST**
?
At this moment memcache is not used for this application, but has connection variables in backend. Not tested without them.

**MEMCACHED_PORT**
?
At this moment memcache is not used for this application, but has connection variables in backend. Not tested without them.

**O365_TENANT_ID**
*Required*
O365 Connection used for login.

**O365_APP_ID**
*Required*
O365 Connection used for login.

**O365_APP_SECRET**
*Required*
O365 Connection used for login.

**JWT_ISSUER**
*Required*
Name of your company, who issues the JWT tokens. This are keys generated on login, that authorize the browser when connecting to the backend/api.

**JWT_SECRET**
*Required*
This JWT library imposes strict secret security as follows: the secret must be at least 12 characters in length; contain numbers; upper and lowercase letters; and one of the following special characters *&!@%^#$.

**API_SOURCE_DIR**
*Not Required*

**UPLOAD_FOLDER**
*Not Required*

**IPGEOLOCATION_API_KEY**
*Not Required*
Not used in this application. Used for tracking position of user-login and usage of system-user-tokens in other FIPO-applications.





## 6. Update
To update FIPO-calender to a new version, you need to pull the latest image.

```
$ docker pull fosenikt/fipo-calendar-frontend:latest
```

You can also open the container in Portainer, click the Recreate-button and check for "Pull latest image".





## 7. Populate database
Database file is located under /db folder.


## 8. Security, dataflow and storage
Login is limited to domains linked to companies in the database.
As long as the user has a valid domain, the login will be granted.

First time login will create a new user with ID and E-mail.
O365 login will prefill firstname, lastname, title and fetch profile photo.

Login will create a JWT-token and store it in browsers localstorage. Token is used to validate user in backend/API.

 - All logged in users can edit all application/services created by their domain/company.
 - All users can browse all applications and companies.
 - All users can view users in other companies.

Admin can be set under user and will allow creating new companies, users (e.g. system users for m2m communication).

**All information in this application should already be public.
The only reason for login is to make it simpler for new users to share and create company ownership for the application.
It will also minimize damage is anyone shares non-public content by mistake.**



## 9. License
Copyright (c) 2021 Fosen IKT

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


## Troubleshoot

### Error: exec user process caused „no such file or directory

```
$ dos2unix /var/www/apps.kapps.local/frontend/.docker/entrypoint.sh
dos2unix: converting file /var/www/apps.kapps.local/frontend/.docker/entrypoint.sh to Unix format...

$ docker-compose up --build kapps-appstore-frontend
```