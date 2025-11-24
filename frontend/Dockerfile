FROM node:20

WORKDIR /app

# Copier package.json et installer dépendances
COPY package*.json ./
RUN npm install

# Copier le reste du code
COPY . .

# Commande pour lancer Nuxt en dev
CMD ["npm", "run", "dev"]
