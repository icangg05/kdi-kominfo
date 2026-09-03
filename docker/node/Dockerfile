FROM node:24-alpine AS deps
WORKDIR /srv
COPY package.json package-lock.json* ./
RUN npm install

FROM node:24-alpine AS build
WORKDIR /srv
COPY --from=deps /srv/node_modules ./node_modules
COPY . .
RUN npm run build

# SSR butuh node_modules saat berjalan, jadi runtime tetap membawa dependensinya.
FROM node:24-alpine
WORKDIR /srv
ENV NODE_ENV=production HOST=0.0.0.0 PORT=4321
COPY --from=deps /srv/node_modules ./node_modules
COPY --from=build /srv/dist ./dist
COPY package.json ./

EXPOSE 4321
CMD ["node", "./dist/server/entry.mjs"]
