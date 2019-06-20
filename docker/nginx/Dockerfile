FROM photoblog/app as app

FROM nginx:1.15

# Change the working directory.
WORKDIR /var/www/app

# Copy the application source code.
COPY --from=app /var/www/app .

# Copy webserver configuration files.
COPY ./sites /etc/nginx/conf.d
