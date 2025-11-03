# 🍃 Kodama’s Kitchen

> **Un restaurant en ligne inspiré de l’univers de Studio Ghibli**, où gastronomie et poésie japonaise se rencontrent.  
> Développé avec **Symfony 6.4**, **Bootstrap 5** et **Twig**, ce projet allie design, logique métier et expérience utilisateur immersive.

---

## 🖼️ Aperçu du site

![Aperçu Kodama’s Kitchen](https://raw.githubusercontent.com/ninie225/kodama/main/assets/images/uploads/apercu.png)

---

## 🚀 Fonctionnalités principales

### 👤 Utilisateurs
- Création de compte avec **validation par email**
- Connexion / déconnexion sécurisée
- Gestion des rôles :  
  - 🧑‍🍳 **Chef** : gère les commandes  
  - 👩‍💻 **Admin** : gère les utilisateurs et les plats  
  - 🍽️ **Client** : commande et suit ses repas

### 🛒 Panier & Commandes
- Ajout / suppression / modification des quantités  
- Calcul automatique du **total** et des **frais de livraison**  
- Validation du panier → création de commande en base  
- Envoi d’un **email de confirmation** avec récapitulatif détaillé  
- Historique de commandes accessible depuis le compte client  

### 🍱 Gestion des plats
- Liste de plats avec photo, description et prix  
- Interface d’administration complète  

---

## 🧩 Technologies utilisées

| Catégorie | Technologies |
|------------|---------------|
| **Backend** | PHP 8.3+, Symfony 6.4 |
| **Base de données** | MySQL / MariaDB via Doctrine ORM |
| **Frontend** | Twig, Bootstrap 5, CSS personnalisé |
| **Email** | Symfony Mailer + Mailpit |
| **Outils** | Composer, Symfony CLI, GitHub, VS Code |

---

## ⚙️ Installation locale

### 1️⃣ Cloner le dépôt
```bash
git clone https://github.com/ninie225/kodama.git
cd kodama
```

### 2️⃣ Installer les dépendances
```bash
composer install
```

### 3️⃣ Configurer l’environnement
Créer un fichier `.env.local` :
```dotenv
DATABASE_URL="mysql://root:@127.0.0.1:3306/kodama"
MAILER_DSN=smtp://localhost:1025
```

### 4️⃣ Créer la base et charger les données
```bash
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
```

### 5️⃣ Démarrer le serveur
```bash
symfony serve
```
📍 Accès : [http://localhost:8000](http://localhost:8000)

---

## 🧑‍🍳 Rôles et accès

| Rôle | Accès |
|------|-------|
| **Client** | Voir les plats, gérer son panier et ses commandes |
| **Chef** | Voir les commandes à préparer |
| **Admin** | Gérer les plats et les utilisateurs |

---

## 💌 Tests des emails

Lancer Mailpit :
```bash
mailpit
```
📬 Accès à la boîte mail locale : [http://localhost:8025](http://localhost:8025)

---

## 🌿 Auteure

👩‍💻 **Marine RUNAVOT**  
Développement complet du projet — Backend, Frontend, UI/UX  
Projet réalisé dans le cadre de la formation **Concepteur Développeur d’Applications**

🔗 [Profil GitHub](https://github.com/ninie225)

---

## 📜 Licence

Projet open-source distribué sous licence **MIT**
