# MarocExplore - Plateforme d'Itinéraires Touristiques au Maroc

## À propos du projet

MarocExplore est une API REST développée avec Laravel permettant aux voyageurs de créer, partager et découvrir des itinéraires touristiques personnalisés à travers le Maroc. Cette plateforme met en valeur la richesse touristique du pays en offrant une expérience utilisateur intuitive pour planifier des voyages mémorables.

## Fonctionnalités principales

- **Authentification sécurisée** avec Laravel Sanctum pour protéger les données utilisateurs
- **Gestion complète des itinéraires** (création, modification, partage)
- **Organisation par destinations** avec détails sur l'hébergement et les activités
- **Recherche avancée** par catégorie, durée et mots-clés
- **Liste personnelle** d'itinéraires favoris "À visiter"
- **Statistiques** sur la popularité des destinations

## Technologies utilisées

- **Backend**: PHP 8.x, Laravel 10.x
- **Base de données**: MySQL
- **Authentification**: Laravel Sanctum
- **Documentation**: Postman
- **Tests**: PHPUnit pour les tests unitaires, Postman pour les tests d'intégration

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- MySQL 5.7 ou supérieur
- Extension PHP : BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## Installation

```bash
# Cloner le dépôt
git clone https://github.com/votre-utilisateur/maroc-explore.git
cd maroc-explore

# Installer les dépendances
composer install

# Variables d'environnement
cp .env.example .env
# Modifiez le fichier .env avec vos configurations

# Générer la clé d'application
php artisan key:generate

# Exécuter les migrations et les seeders
php artisan migrate --seed

# Démarrer le serveur de développement
php artisan serve
```



## Endpoints API

### Authentification
- `POST /api/auth/register` - Inscription
- `POST /api/auth/login` - Connexion
- `POST /api/auth/logout` - Déconnexion

### Utilisateurs
- `GET /api/user` - Profil de l'utilisateur connecté
- `GET /api/user/itineraires` - Itinéraires créés par l'utilisateur
- `GET /api/user/a-visiter` - Liste des itinéraires à visiter

### Itinéraires
- `GET /api/itineraires` - Liste de tous les itinéraires
- `GET /api/itineraires/{id}` - Détails d'un itinéraire
- `POST /api/itineraires` - Créer un itinéraire
- `PUT /api/itineraires/{id}` - Modifier un itinéraire
- `DELETE /api/itineraires/{id}` - Supprimer un itinéraire
- `POST /api/itineraires/{id}/favoris` - Ajouter à la liste "À visiter"
- `DELETE /api/itineraires/{id}/favoris` - Retirer de la liste "À visiter"

### Recherche & Filtres
- `GET /api/itineraires/recherche` - Recherche par mot-clé
- `GET /api/itineraires/filtre` - Filtrer par catégorie et durée
- `GET /api/itineraires/populaires` - Itinéraires les plus populaires
- `GET /api/statistiques/categories` - Nombre d'itinéraires par catégorie

## Query Builder

Le système utilise l'Eloquent Query Builder de Laravel pour effectuer les opérations suivantes:

```php
// Récupérer tous les itinéraires avec leurs destinations
$itineraires = Itineraire::with('destinations.pointsInteret')->get();

// Filtrer les itinéraires par catégorie et durée
$itineraires = Itineraire::where('categorie', $categorie)
                        ->where('duree', '<=', $duree)
                        ->get();

// Ajouter un itinéraire à la liste "À visiter"
$user->itinerairesAVisiter()->attach($itineraire->id);

// Recherche d'itinéraires contenant un mot-clé
$itineraires = Itineraire::where('titre', 'LIKE', "%{$keyword}%")->get();

// Récupérer les itinéraires les plus populaires
$itineraires = Itineraire::orderBy('favoris', 'desc')->take(10)->get();

// Statistiques : Nombre d'itinéraires par catégorie
$stats = Itineraire::select('categorie', DB::raw('count(*) as total'))
                  ->groupBy('categorie')
                  ->get();
```

## Tests

```bash
# Exécuter tous les tests
php artisan test

# Exécuter les tests avec couverture
./vendor/bin/phpunit --coverage-html reports/
```

## Documentation API

La documentation complète de l'API est disponible via Postman:
- Importez la collection Postman fournie dans le dossier `/docs/MarocExplore.postman_collection.json`
- Les environnements Postman pour le développement et la production sont également disponibles dans le dossier `/docs/environments/`

Vous pouvez utiliser Postman pour:
- Tester tous les endpoints de l'API
- Voir des exemples de requêtes et réponses
- Explorer les différents scénarios d'utilisation
- Vérifier les codes de statut et les messages d'erreur
- Exécuter des tests automatisés sur l'API

Pour installer Postman:
1. Téléchargez Postman depuis [postman.com](https://www.postman.com/downloads/)
2. Importez la collection MarocExplore
3. Sélectionnez l'environnement approprié (dev/prod)
4. Commencez à tester l'API

## Déploiement

```bash
# Optimisation pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migration en production
php artisan migrate --force
```

## Configuration de l'environnement

Le fichier `.env` doit contenir les variables suivantes:

```
APP_NAME=MarocExplore
APP_ENV=production
APP_KEY=base64:votre_cle_app
APP_DEBUG=false
APP_URL=https://api.marocexplore.ma

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=maroc_explore
DB_USERNAME=utilisateur
DB_PASSWORD=mot_de_passe

SANCTUM_STATEFUL_DOMAINS=marocexplore.ma
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
```

## Contribution

1. Fork le projet
2. Créer une branche (`git checkout -b feature/amelioration`)
3. Commit les changements (`git commit -m 'Ajout d'une fonctionnalité'`)
4. Push vers la branche (`git push origin feature/amelioration`)
5. Ouvrir une Pull Request

## Gestion des versions

Nous utilisons [SemVer](http://semver.org/) pour la gestion des versions. Pour les versions disponibles, consultez les [tags sur ce dépôt](https://github.com/votre-utilisateur/maroc-explore/tags).

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## Contact

Pour toute question ou suggestion, n'hésitez pas à nous contacter à l'adresse support@marocexplore.ma

## Remerciements

- À tous les contributeurs qui ont participé à ce projet
- À l'Office National Marocain du Tourisme pour leur soutien
- À la communauté Laravel pour leur excellent framework et documentation

---

Développé avec ❤️ pour promouvoir le tourisme au Maroc
