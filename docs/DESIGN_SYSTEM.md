# Design — Clinixora

## Ce qui est décidé (stack)

- **Framework UI** : **Tailwind CSS** (utility-first, responsive).
- **Interactivité légère** : **Alpine.js** (modales, onglets, listes dynamiques sans SPA).
- **Serveur** : **Blade** pour les pages (pas React/Vue pour la base).

## Ce qui n’est pas encore figé (à compléter avec vous)

Il n’existe **pas encore** de maquette Figma ni de charte graphique validée (couleurs exactes, logo Clinixora, typo officielle). Ce fichier fixe des **principes** ; les valeurs visuelles pourront être remplacées en centralisant les tokens Tailwind (`tailwind.config.js`).

## Principes UX

- **Clarté avant tout** : libellés métier, peu de jargon technique ; erreurs explicites.
- **Densité maîtrisée** : tableaux lisibles (alternance de lignes, colonnes alignées à droite pour les montants).
- **Actions primaires visibles** : un bouton principal par écran quand c’est possible.
- **Accessibilité de base** : contrastes suffisants, focus visible, formulaires avec labels.

## Direction esthétique proposée (provisoire)

- Style **application métier moderne** (type SaaS santé / admin) : sidebar sombre ou claire, zone contenu claire, **accent une couleur** (bleu ou vert sobres — à valider).
- **Pas de surcharge décorative** ; icônes discrètes (Heroicons ou équivalent compatible Tailwind).

## Composants à standardiser

- Layout : sidebar + topbar + zone titre + actions.
- Cartes KPI pour le dashboard.
- Tableaux avec pagination, filtres, états vides.
- Formulaires : groupes de champs, validation inline.

## Tableau de bord personnalisable

Pour éviter la surcharge visuelle tout en gardant la puissance métier :

- **Bouton « Personnaliser le tableau de bord »** (ou icône engrenage) ouvrant un panneau / modale.
- Liste des **blocs-modules** (Spécialistes, Services, Salles, Caisses, Graphiques, Assistant, etc.) avec cases à cocher ou interrupteurs.
- Action **« Tout afficher »** qui réactive tous les modules en une fois.
- Préférences **enregistrées par utilisateur** (table SQL ou JSON `user_dashboard_preferences`) ; chargement au rendu du dashboard.
- Optionnel plus tard : **réordonnancement** par glisser-déposer des cartes.

**Implémentation Laravel** : endpoint `PATCH /api/v1/me/dashboard-layout` ou formulaire POST web ; policy `dashboard.customize` selon rôle si besoin.

## Zone graphiques + assistant Clinixora

- **Graphiques** : zone dédiée (une ou deux cartes larges) — courbes / barres (activité, encaissements, RDV) via Chart.js ou équivalent ; données depuis API agrégée.
- **Assistant** : panneau fixe ou repliable **à droite** ou **en bas** avec zone de saisie multiligne, historique des messages, bouton envoyer ; même backend orchestrateur que les autres canaux.

Ces éléments doivent être **des modules activables** dans la personnalisation ci-dessus.

**Liste détaillée des graphiques prévus** (ids, titres, options, MVP) : voir **`docs/DASHBOARD_GRAPHIQUES.md`** pour ne rien oublier à l’implémentation.

## Maquettes de référence (générées)

Inspirées de l’existant **WIN‑CLINIC** / captures **Clinixora** (structure, sections KPI, barre Stock/Péremption/notifications, caisses) et de la **charte CliniXora** (bleu marine + cyan, logo type flyers).

| Fichier | Contenu |
|---------|---------|
| `docs/images/clinixora-login-mockup.png` | Page **connexion** : carte centrée, champs identifiant / mot de passe, CTA, bande **Nos contacts** (Facebook, LinkedIn, WhatsApp) comme l’ancienne page WIN Technology. |
| `docs/images/clinixora-dashboard-full-mockup.png` | **Tableau de bord** : sidebar menu métier, header badges, sections Spécialistes / Patients-Produits-Fournisseurs / Services en grille / Salles et blocs / aperçu caisses. |
| `docs/images/clinixora-dashboard-mockup.png` | Première variante dashboard (focus KPI + RDV + assistant), toujours utile comme référence complémentaire. |
| `docs/images/clinixora-dashboard-v2-widgets-charts-assistant.png` | Dashboard **v2** : personnalisation (bouton + hint), **graphiques**, panneau **Assistant Clinixora** avec champ de saisie. |

## Prochaine étape design

1. Choisir **palette** + **police** (ex. Inter, Source Sans 3) — affiner avec les codes hex du flyer si mesurés précisément.
2. Produire **3 écrons filaires** : connexion, tableau de bord, liste patients.
3. Reporter les tokens dans Tailwind et un fichier `resources/css/app.css` ou `@theme` selon la config Laravel + Vite.

*Document vivant — à mettre à jour après validation graphique.*
