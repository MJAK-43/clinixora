# Sécurité — Clinixora

- Jamais de mot de passe ou clé API dans le dépôt.
- Compte MySQL dédié en prod ; pas `root` sans mot de passe hors machine locale dev.
- Données patients : minimiser ce qui est envoyé à un LLM externe ; contrats DPA si cloud.
- HTTPS obligatoire en production.
- Rate limiting sur login et endpoints agent / webhook WhatsApp.
