FROM node:10

# Install Google Chrome browser.
RUN wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add -
RUN sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list'
RUN apt-get update && apt-get install -y google-chrome-stable

# Change the working directory.
WORKDIR /home/node/app

# Install the application dependencies.
COPY ./package.json ./package.json
COPY ./package-lock.json ./package-lock.json
RUN npm install --only=production

# Copy the application source code.
COPY . .

# Change a default user.
USER node

# Start the application.
CMD ["node", "server.js"]
