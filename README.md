# Kapps Appstore

## 1. About

## 2. Prerequisite

## 3. Install

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


## 4. Register an Azure App

### 4.1 Configure the app

## 5. Docker-compose:

### Some notes for the docker-compose file

#### Environment-variables

## 6. Update

## 7. Populate database


## 8. Security, dataflow and storage


## 9. License
Copyright 2021 Fosen IKT, Indre Fosen Kommune

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.