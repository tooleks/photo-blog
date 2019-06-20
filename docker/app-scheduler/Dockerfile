FROM photoblog/app

# Copy the entrypoint command.
COPY ./start.sh /usr/local/bin/start

# Add execute permission to the entrypoint command.
RUN chmod +x /usr/local/bin/start

CMD ["/usr/local/bin/start"]
