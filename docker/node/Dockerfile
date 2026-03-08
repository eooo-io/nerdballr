###############################################################################
# FFIS — Node 20 / React + Vite Image
###############################################################################

FROM node:20-alpine

RUN apk add --no-cache bash git

WORKDIR /app

# Copy package files first for layer caching
COPY package*.json ./

RUN npm install

# Source is mounted via volume in dev
# node_modules stays in the container layer (not overwritten by mount)

EXPOSE 5173

CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]
