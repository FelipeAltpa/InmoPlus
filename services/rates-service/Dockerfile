FROM node:22-alpine
WORKDIR /srv
COPY package.json ./
COPY src ./src
ENV NODE_ENV=production PORT=8091
EXPOSE 8091
USER node
CMD ["node", "src/server.js"]
