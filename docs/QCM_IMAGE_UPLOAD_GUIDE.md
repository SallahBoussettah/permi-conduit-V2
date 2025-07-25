# Guide d'utilisation - Upload d'images pour les questions QCM

## Vue d'ensemble

Le système QCM permet maintenant d'ajouter des images (panneaux de signalisation, symboles) aux questions pour les examens de conduite. Cette fonctionnalité est particulièrement utile pour les questions qui nécessitent l'identification de panneaux routiers.

## Fonctionnalités

### 1. Upload d'images amélioré

-   Interface glisser-déposer intuitive
-   Aperçu en temps réel de l'image
-   Support des formats PNG, JPG, GIF (jusqu'à 2MB)
-   Conseils intégrés pour optimiser les images

### 2. Affichage optimisé

-   **Dans l'examen**: L'image apparaît en haut de la question, centrée et bien mise en valeur
-   **Dans les résultats**: L'image est affichée avec la question pour faciliter la révision
-   **Dans la liste des questions**: Indicateur visuel pour les questions avec images

### 3. Gestion des images

-   Possibilité de remplacer une image existante
-   Option pour supprimer une image
-   Stockage sécurisé dans `storage/app/public/qcm-questions/`

## Comment utiliser

### Ajouter une image à une nouvelle question

1. Aller dans **Inspecteur > QCM Papers > [Votre QCM] > Ajouter une question**
2. Remplir le texte de la question (ex: "Ce signal indique un arrêt d'autobus, Vrai ou faux?")
3. Dans la section "Panneau de signalisation / Symbole":
    - Cliquer sur "Télécharger un panneau" ou glisser-déposer l'image
    - L'aperçu s'affiche automatiquement
    - Vérifier que l'image est claire et bien visible
4. Compléter les réponses et sauvegarder

### Ajouter une image à une question existante

1. Aller dans la liste des questions du QCM
2. Cliquer sur "Modifier" pour la question concernée
3. Dans la section image:
    - Si une image existe déjà, elle s'affiche avec l'option de suppression
    - Télécharger une nouvelle image pour remplacer l'ancienne
    - Ou cocher "Supprimer cette image" pour la retirer
4. Sauvegarder les modifications

### Import CSV avec images

Lors de l'import CSV, les questions sont créées sans images. Pour ajouter des images:

1. Importer d'abord les questions via CSV
2. Modifier individuellement chaque question nécessitant une image
3. Ajouter les images une par une

## Conseils pour les images

### Qualité optimale

-   **Résolution**: 300-800px de largeur recommandée
-   **Format**: PNG avec fond transparent de préférence
-   **Taille**: Moins de 1MB pour des temps de chargement rapides

### Panneaux de signalisation

-   Utiliser des images officielles des panneaux
-   S'assurer que le panneau est bien centré
-   Éviter les images floues ou pixelisées
-   Préférer un fond blanc ou transparent

### Accessibilité

-   L'image doit être suffisamment claire pour être vue sur mobile
-   Le texte de la question doit rester compréhensible même sans l'image
-   Ajouter une description dans le texte si nécessaire

## Affichage pendant l'examen

Quand un candidat passe l'examen:

1. **L'image apparaît en premier**, dans un cadre centré et mis en valeur
2. **La question suit en dessous**, bien lisible
3. **Les réponses sont présentées clairement** sous la question
4. **Sur mobile**, l'image s'adapte automatiquement à la taille de l'écran

## Affichage des résultats

Après l'examen:

1. **Chaque question avec image** montre le panneau utilisé
2. **La réponse correcte** est mise en évidence
3. **L'explication** (si fournie) apparaît sous les réponses

## Dépannage

### L'image ne s'affiche pas

-   Vérifier que le lien symbolique storage existe: `php artisan storage:link`
-   S'assurer que le dossier `storage/app/public/qcm-questions/` est accessible en écriture
-   Vérifier la taille du fichier (max 2MB)

### Erreur d'upload

-   Vérifier les paramètres PHP: `upload_max_filesize` et `post_max_size`
-   S'assurer que le format de fichier est supporté (PNG, JPG, GIF)
-   Vérifier l'espace disque disponible

### Image de mauvaise qualité

-   Utiliser une image de meilleure résolution
-   Éviter les formats compressés (JPEG de faible qualité)
-   Préférer PNG pour les panneaux avec texte

## Structure technique

```
storage/
├── app/
│   └── public/
│       └── qcm-questions/          # Images des questions QCM
│           ├── image1.png
│           ├── image2.jpg
│           └── ...
└── ...

public/
└── storage/                        # Lien symbolique vers storage/app/public
    └── qcm-questions/
        ├── image1.png
        └── ...
```

Les images sont accessibles via: `https://votre-domaine.com/storage/qcm-questions/nom-image.png`
