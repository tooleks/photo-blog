FROM node:8.1

# Install node dependencies.
RUN npm install -g pm2

# Set the application working directory.
RUN mkdir -p /data/frontend/
WORKDIR /data/frontend/

# Copy the entrypoint script.
COPY ./docker-entrypoint.sh /
RUN chmod +x /docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]

CMD ["pm2-docker", "start", "dist/server.js"]
