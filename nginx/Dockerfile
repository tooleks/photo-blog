FROM pb-app as pb-app

FROM nginx:1.15

# Change the working directory.
WORKDIR /var/www/app

# Copy the application artifacts.
COPY --from=pb-app /var/www/app .

# Copy webserver configuration files.
COPY ./sites /etc/nginx/conf.d
