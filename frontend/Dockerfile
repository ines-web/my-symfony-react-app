FROM node:18

WORKDIR /app

# Copie des fichiers de configuration npm
COPY package*.json ./

# Installation des dépendances
RUN npm install

# Copie du code source
COPY . .

EXPOSE 3000

# Démarrage en mode développement
CMD ["npm", "start"]