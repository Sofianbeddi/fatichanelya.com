# Pages légales — Fatichanelya

*Écrit le 14 septembre 2026 · Auteur : velix-copy · Langue de référence : français*
*Source juridique : `_velix/reglementaire-espagne.md` (14 septembre 2026). Contexte : `_velix/brief.md`, `_velix/architecture.md`.*

---

## NOTE INTERNE — pour Sofian, pas pour le site

**Ces textes ne sont pas un conseil juridique.** Ce sont des bases sérieuses, adossées au rapport
réglementaire du 14 septembre 2026, rédigées pour qu'un professionnel espagnol ait le moins de travail
possible en les relisant. Elles ne remplacent pas cette relecture.

**Ce qui doit impérativement être validé par un professionnel avant publication**

| Point | Par qui | Pourquoi |
|---|---|---|
| L'ensemble des CGV, article par article | **Abogado** spécialisé en commerce électronique (voir question 14 du rapport, § 13) | Un modèle générique ne couvre ni le catalogue réel ni le statut de Fati |
| La formulation de l'**exception de rétractation pour produits scellés** (art. 103 e RDL 1/2007) | **Abogado** | L'exception s'interprète de manière restrictive. Une clause générale « pas de retour sur les compléments » est **abusive** et ne tient pas. Le texte proposé ici est volontairement prudent |
| Le **libellé exact du bouton de commande** (« commande avec obligation de paiement ») | **Abogado** | Le rapport signale que le numéro d'article et la formulation littérale sont **[À VÉRIFIER]**. Sanction : la cliente n'est pas liée par le contrat |
| La **durée de prolongation du délai de rétractation** en cas de défaut d'information | **Abogado** | Le rapport la marque **[À VÉRIFIER]**. Le texte ci-dessous ne la chiffre donc pas |
| L'articulation **garantie légale 3 ans / produits périssables** | **Abogado** | Marquée **[À VÉRIFIER]** dans le rapport. Le texte ci-dessous reste factuel |
| Les **mentions ADR** encore obligatoires en 2026 (directive 2013/11/UE, Ley 7/2017) | **Abogado** | L'abrogation du règlement ODR ne supprime pas tout le cadre ADR, seulement la plateforme |
| Le **taux de TVA produit par produit** (21 % ou 10 %) | **Asesor fiscal** | Tous les produits du catalogue ne relèvent peut-être pas du même taux |
| Le **recargo de equivalencia** et son effet sur la mention de TVA affichée | **Asesor fiscal** | Question la plus technique du dossier, non tranchée par le rapport |
| La **politique de confidentialité**, et notamment les durées de conservation | Spécialiste protection des données | Les durées proposées ici sont des durées usuelles, pas des durées vérifiées |
| L'**adresse publiée** dans les mentions légales | **Gestor / abogado** | Une autónoma doit publier une adresse réelle. Une domiciliation professionnelle est possible mais sa validité au regard de la LSSI doit être vérifiée |
| Le droit de citer la **marque DXN** et ses visuels sur le site | **Abogado** + réponse écrite de DXN | Voir § 9.3 du rapport : questions 1 à 8 à DXN, par écrit |

**Ce qui a été volontairement écarté, et qu'il ne faut pas réintroduire**

- **Aucun lien vers la plateforme européenne de règlement des litiges en ligne.** Elle a fermé le
  20 juillet 2025 (règlement UE 2024/3228). Presque tous les modèles en circulation contiennent encore
  ce lien mort. S'il réapparaît dans une relecture, c'est que le relecteur a recopié un modèle périmé.
- **Aucune allégation de santé**, nulle part, y compris dans la description des produits en CGV.
- **Aucun engagement de délai, de tarif ou de pays** qui n'ait été fourni par la cliente. Tous sont en
  marqueurs.
- **Aucune date de dernière mise à jour inventée** : elle est en marqueur, à poser le jour de la
  publication.

**Rappel sur le paiement.** PayPal n'est pas actif. Les CGV et la page Livraison sont écrites pour être
correctes **aujourd'hui**, avec la commande par WhatsApp. Chaque endroit qui changera à l'activation du
paiement porte un marqueur `[[À ACTIVER AVEC PAYPAL]]`.

**Comment lire les marqueurs.** Toute occurrence de `[[À FOURNIR : …]]` est une donnée manquante.
**Aucune des quatre pages ne doit être publiée tant qu'il en reste une seule.** Ils sont volontairement
voyants : c'est leur fonction.

**Liste consolidée de ce qu'il faut obtenir de la cliente** — voir la dernière section de ce document.

---
---

# PAGE 1 — Mentions légales

**Titre** : Mentions légales
**Slug** : `mentions-legales`
**Meta description** (147 caractères) :
`Identité de l'éditrice du site Fatichanelya, coordonnées de contact, hébergeur, propriété intellectuelle et statut de distributrice indépendante.`

**Note d'intégration** : page à laisser indexable ou en `noindex`, au choix de `velix-perf-seo`.
Le lien doit rester **permanent, facile, direct et gratuit** depuis le pied de page de toutes les pages
(art. 10 LSSI-CE) — c'est déjà le cas dans le menu `pied_infos`.

```html
<p>Ces mentions vous disent qui édite ce site, comment me joindre, et ce que vous avez le droit d'en faire. Elles sont publiées en application de l'article 10 de la loi espagnole 34/2002 sur les services de la société de l'information et le commerce électronique (LSSI-CE).</p>

<h2>Qui édite ce site</h2>

<p>Ce site est édité par une personne, pas par une société anonyme. La voici.</p>

<ul>
  <li><strong>Nom et prénom</strong> : [[À FOURNIR : nom civil complet de Fati, tel qu'il figure sur ses documents officiels espagnols. Un prénom seul ne suffit pas au regard de l'article 10 LSSI]]</li>
  <li><strong>Statut</strong> : [[À FOURNIR : statut exact — « travailleuse indépendante (autónoma) inscrite en Espagne » ou autre. À confirmer avec le gestor une fois l'alta effectuée]]</li>
  <li><strong>Numéro d'identification fiscale (NIF)</strong> : [[À FOURNIR : NIF / NIE espagnol]]</li>
  <li><strong>Adresse</strong> : [[À FOURNIR : adresse postale réelle et complète, avec code postal, ville et province. Une autónoma doit publier une adresse réelle. Si Fati travaille depuis chez elle, vérifier avec le gestor si une domiciliation professionnelle est acceptable au regard de la LSSI avant de publier son domicile]]</li>
  <li><strong>Adresse e-mail</strong> : [[À FOURNIR : adresse e-mail de contact, relevée régulièrement. Obligatoire : la LSSI exige un moyen de communication directe et effective]]</li>
  <li><strong>Téléphone / WhatsApp</strong> : [[À FOURNIR : numéro WhatsApp définitif au format international]]</li>
</ul>

<p>[[À FOURNIR : le cas échéant — numéro d'inscription à un registre public, ou numéro d'autorisation administrative si l'activité y est soumise. Si rien ne s'applique, supprimer cette ligne plutôt que d'écrire « sans objet »]]</p>

<h2>Comment me joindre</h2>

<p>Le plus simple est de m'écrire sur WhatsApp : [[À FOURNIR : numéro WhatsApp]]. Je réponds moi-même, il n'y a pas de service client derrière.</p>

<p>Par e-mail : [[À FOURNIR : adresse e-mail de contact]]. [[À FOURNIR : délai de réponse habituel que Fati s'engage à tenir, par exemple « sous 48 heures ouvrées ». Ne rien promettre qu'elle ne puisse tenir]]</p>

<h2>Hébergement du site</h2>

<ul>
  <li><strong>Hébergeur</strong> : [[À FOURNIR : raison sociale de l'hébergeur]]</li>
  <li><strong>Adresse</strong> : [[À FOURNIR : adresse postale de l'hébergeur]]</li>
  <li><strong>Site</strong> : [[À FOURNIR : site web de l'hébergeur]]</li>
  <li><strong>Localisation des serveurs</strong> : [[À FOURNIR : pays d'hébergement des données. À demander à l'hébergeur par écrit — cette information est reprise dans la politique de confidentialité]]</li>
</ul>

<h2>Distributrice indépendante DXN</h2>

<p>Je suis <strong>distributrice indépendante</strong> des produits de la marque DXN. Cela veut dire trois choses, et il vaut mieux qu'elles soient dites clairement.</p>

<ul>
  <li><strong>Je ne suis pas DXN.</strong> Je ne représente pas l'entreprise, je ne parle pas en son nom, et ce site n'est pas un site officiel de la marque.</li>
  <li><strong>DXN n'est pas responsable de ce site.</strong> Les textes, les visuels de présentation et les conseils que vous lisez ici sont les miens. Les informations officielles sur les produits sont celles qui figurent sur les emballages et sur les supports de la marque.</li>
  <li><strong>Je vends des produits, je ne recrute personne.</strong> Ce site ne propose aucune inscription, aucune adhésion, aucune opportunité de revenus, et ne fait la promotion d'aucun système de rémunération.</li>
</ul>

<p>DXN et les noms de produits sont des marques appartenant à leurs titulaires respectifs. Ils sont cités ici pour identifier les produits que je revends.</p>

<p>[[À FOURNIR : numéro de distributrice DXN de Fati, si elle souhaite l'afficher — c'est une preuve d'authenticité utile face aux contrefaçons. Vérifier au préalable auprès de DXN, par écrit, que son contrat autorise la vente en ligne, l'usage de la marque et des visuels produits sur un site personnel]]</p>

<h2>Ce que vous pouvez faire de ce site, et ce que vous ne pouvez pas</h2>

<p>Les textes, la structure, la mise en page et les visuels créés pour ce site m'appartiennent, sauf indication contraire. Les visuels et noms de produits appartiennent à leurs titulaires.</p>

<p><strong>Vous pouvez</strong> consulter le site, l'imprimer pour votre usage personnel, et partager un lien vers n'importe quelle page. Cela me fait plaisir.</p>

<p><strong>Vous ne pouvez pas</strong>, sans mon accord écrit, copier tout ou partie du contenu pour le republier ailleurs, l'utiliser à des fins commerciales, ou le modifier.</p>

<h2>Liens vers d'autres sites</h2>

<p>Ce site peut contenir des liens vers des sites que je ne gère pas, par exemple TikTok ou le site de la marque. Je n'ai aucun contrôle sur leur contenu et je ne peux pas en répondre. Quand vous cliquez, vous quittez ce site et vous entrez dans le cadre du leur.</p>

<h2>Données personnelles et cookies</h2>

<p>Tout ce qui concerne vos données personnelles — ce que je collecte, pourquoi, combien de temps, et comment exercer vos droits — est expliqué dans la <a href="/confidentialite/">politique de confidentialité</a>.</p>

<h2>Vente en ligne</h2>

<p>Les conditions dans lesquelles je vends — prix, commande, paiement, livraison, retours, garantie — sont détaillées dans les <a href="/conditions-generales-de-vente/">conditions générales de vente</a>.</p>

<h2>Droit applicable</h2>

<p>Ce site est édité depuis l'Espagne et relève du droit espagnol.</p>

<h2>Une erreur sur cette page ?</h2>

<p>Si vous voyez une information inexacte ou incomplète, écrivez-moi à [[À FOURNIR : adresse e-mail de contact]]. Je corrige.</p>

<p><em>Dernière mise à jour : [[À FOURNIR : date de publication réelle de la page, au format « 14 septembre 2026 ». À mettre à jour à chaque modification]]</em></p>
```

---
---

# PAGE 2 — Politique de confidentialité

**Titre** : Politique de confidentialité
**Slug** : `confidentialite`
**Meta description** (149 caractères) :
`Quelles données je collecte sur Fatichanelya, pourquoi, combien de temps je les garde, et comment exercer vos droits au titre du RGPD. Expliqué simplement.`

**Note d'intégration** : le paragraphe sur les cookies est écrit pour l'état actuel du site
(**aucun traceur installé**, confirmé par l'architecture). Il devra être réécrit **avant** la première
campagne publicitaire, en même temps que la pose du bandeau de consentement. Marqueur en place.

```html
<p>Cette page vous dit ce que je fais de vos informations. Je l'ai écrite pour qu'elle soit lisible, pas pour qu'elle soit longue. Si un point n'est pas clair, écrivez-moi et je vous répondrai.</p>

<h2>Qui est responsable de vos données</h2>

<p>C'est moi, personnellement.</p>

<ul>
  <li><strong>Responsable du traitement</strong> : [[À FOURNIR : nom civil complet de Fati]]</li>
  <li><strong>NIF</strong> : [[À FOURNIR : NIF / NIE espagnol]]</li>
  <li><strong>Adresse</strong> : [[À FOURNIR : adresse postale complète, la même que dans les mentions légales]]</li>
  <li><strong>E-mail</strong> : [[À FOURNIR : adresse e-mail de contact]]</li>
</ul>

<p>Je n'ai pas désigné de délégué à la protection des données : l'activité ne l'exige pas. Vous vous adressez donc directement à moi.</p>

<h2>Le principe, en trois phrases</h2>

<p>Je ne collecte que ce dont j'ai besoin pour vous répondre et pour vous livrer. Je ne vends vos données à personne, jamais. Je ne les utilise pas pour de la publicité sans votre accord.</p>

<h2>Ce que je collecte, et pourquoi</h2>

<h3>Quand vous m'écrivez sur WhatsApp</h3>

<p><strong>Données</strong> : votre numéro de téléphone, votre nom ou pseudonyme tel qu'il apparaît, et le contenu de nos échanges.</p>
<p><strong>Pourquoi</strong> : pour répondre à votre question, vous conseiller et, si vous le souhaitez, prendre votre commande.</p>
<p><strong>Base légale</strong> : votre démarche volontaire — vous m'écrivez pour obtenir une réponse (mesures précontractuelles à votre demande, puis exécution du contrat si vous commandez).</p>
<p><strong>À savoir</strong> : WhatsApp est un service de Meta Platforms Ireland Limited. Quand vous m'écrivez par ce canal, vos échanges transitent par leurs serveurs et sont soumis à leur propre politique de confidentialité, que je ne maîtrise pas. Si vous préférez ne pas passer par WhatsApp, écrivez-moi par e-mail : c'est exactement pareil pour moi.</p>

<h3>Quand vous passez commande</h3>

<p><strong>Données</strong> : nom, prénom, adresse de livraison, adresse e-mail, téléphone, détail de la commande, montant, et — [[À ACTIVER AVEC PAYPAL]] une fois le paiement en ligne actif — la confirmation de paiement transmise par le prestataire.</p>
<p><strong>Pourquoi</strong> : préparer votre colis, l'expédier, vous tenir informée, émettre la facture et tenir ma comptabilité.</p>
<p><strong>Base légale</strong> : l'exécution du contrat de vente, et pour la facturation, une obligation légale.</p>
<p><strong>Important</strong> : je ne vois jamais vos données bancaires. Aucun numéro de carte ne transite par ce site et je n'en conserve aucun. [[À ACTIVER AVEC PAYPAL : quand le paiement sera actif, c'est PayPal qui traitera le paiement sur ses propres serveurs, et je ne recevrai qu'une confirmation]]</p>

<h3>Quand vous vous inscrivez à la lettre d'information</h3>

<p><strong>Données</strong> : votre adresse e-mail, et votre prénom si vous le donnez.</p>
<p><strong>Pourquoi</strong> : vous envoyer mes nouveautés et mes conseils.</p>
<p><strong>Base légale</strong> : votre consentement. Une case cochée par vous, jamais pré-cochée. Vous pouvez vous désinscrire à tout moment avec le lien présent dans chaque envoi, ou en m'écrivant. C'est immédiat et sans justification.</p>
<p>[[À FOURNIR : l'outil d'envoi retenu (Brevo, Mailchimp, autre) et son pays d'établissement. Tant qu'aucun outil n'est connecté, le formulaire de newsletter ne doit pas collecter d'adresses — voir le risque « newsletter sans service derrière » du brief. Si le formulaire reste affiché sans outil, supprimer ce bloc de la page]]</p>

<h3>Quand vous visitez simplement le site</h3>

<p><strong>Données</strong> : les informations techniques que votre navigateur transmet automatiquement à tout site — adresse IP, type de navigateur, pages consultées, date et heure. Elles sont enregistrées dans les journaux de connexion du serveur.</p>
<p><strong>Pourquoi</strong> : faire fonctionner le site, le sécuriser et détecter les incidents.</p>
<p><strong>Base légale</strong> : mon intérêt légitime à maintenir un site en état de marche et protégé.</p>

<h2>Cookies</h2>

<p>Un cookie est un petit fichier déposé sur votre appareil par un site.</p>

<p><strong>Aujourd'hui, ce site n'utilise aucun cookie de mesure d'audience ni de publicité.</strong> Seuls sont utilisés les cookies strictement nécessaires à son fonctionnement — par exemple pour garder en mémoire la sélection de produits que vous composez pendant votre visite. Ceux-là ne demandent pas votre consentement, parce que sans eux le site ne marche pas.</p>

<p>[[À FOURNIR / À METTRE À JOUR AVANT LA PREMIÈRE CAMPAGNE PUBLICITAIRE : dès qu'un outil de mesure d'audience ou le pixel TikTok sera installé, cette section doit être entièrement réécrite et un bandeau de consentement conforme aux exigences de l'AEPD doit être posé — bouton « Refuser » aussi visible et aussi facile que « Accepter », choix par catégories, et aucun script publicitaire déclenché avant acceptation. Ne pas publier de campagne avant que ce soit fait]]</p>

<h2>Qui d'autre voit vos données</h2>

<p>Je ne vends, ne loue et n'échange vos données avec personne. Elles sont transmises uniquement à ceux qui en ont besoin pour que le service fonctionne.</p>

<table>
  <tr>
    <th>Destinataire</th>
    <th>Pourquoi</th>
    <th>Où sont les données</th>
  </tr>
  <tr>
    <td>Mon hébergeur</td>
    <td>Le site et sa base de données sont stockés chez lui</td>
    <td>[[À FOURNIR : nom de l'hébergeur et pays d'hébergement]]</td>
  </tr>
  <tr>
    <td>Le transporteur</td>
    <td>Pour vous livrer : nom, adresse, et selon le cas téléphone ou e-mail pour le suivi</td>
    <td>[[À FOURNIR : nom du ou des transporteurs utilisés]]</td>
  </tr>
  <tr>
    <td>PayPal</td>
    <td>Pour traiter votre paiement, une fois le paiement en ligne activé</td>
    <td>PayPal (Europe) S.à r.l. et Cie, S.C.A., Luxembourg. [[À ACTIVER AVEC PAYPAL]]</td>
  </tr>
  <tr>
    <td>WhatsApp / Meta</td>
    <td>Si vous choisissez de m'écrire par ce canal</td>
    <td>Meta Platforms Ireland Limited, Irlande</td>
  </tr>
  <tr>
    <td>Mon comptable</td>
    <td>Pour la tenue de ma comptabilité et mes obligations fiscales</td>
    <td>[[À FOURNIR : cabinet du gestor, ou supprimer cette ligne si Fati tient sa comptabilité seule]]</td>
  </tr>
  <tr>
    <td>L'outil d'envoi de la lettre d'information</td>
    <td>Pour vous envoyer les e-mails auxquels vous vous êtes abonnée</td>
    <td>[[À FOURNIR : outil retenu et pays, ou supprimer cette ligne si la newsletter n'est pas active]]</td>
  </tr>
</table>

<p>Vos données peuvent aussi être communiquées à une administration ou à une autorité judiciaire si la loi m'y oblige.</p>

<h2>Vos données sortent-elles de l'Union européenne ?</h2>

<p>Dans certains cas, oui. Je préfère vous le dire franchement.</p>

<ul>
  <li><strong>PayPal</strong> — l'entité européenne est établie au Luxembourg, mais le groupe PayPal est américain et certains traitements peuvent avoir lieu hors de l'Union. PayPal encadre ces transferts par les clauses contractuelles types approuvées par la Commission européenne. [[À ACTIVER AVEC PAYPAL : vérifier et citer le document de référence de PayPal au moment de l'activation du compte marchand]]</li>
  <li><strong>WhatsApp / Meta</strong> — l'entité européenne est irlandaise, mais le groupe est américain et des transferts vers les États-Unis existent. Ils sont encadrés par le cadre de protection des données UE–États-Unis et par des clauses contractuelles types. Si vous préférez éviter ce canal, l'e-mail est toujours possible.</li>
  <li><strong>TikTok</strong> — je publie du contenu sur TikTok, mais <strong>ce site ne contient aujourd'hui aucun traceur TikTok</strong>. Si vous arrivez ici depuis un lien TikTok, c'est TikTok qui sait que vous avez cliqué, pas moi. Ce qui se passe dans l'application relève de la politique de confidentialité de TikTok, que je ne maîtrise pas. [[À METTRE À JOUR si le pixel TikTok est installé un jour : il faudra décrire ce traitement, sa base légale — le consentement — et les transferts hors UE de TikTok]]</li>
  <li><strong>L'hébergeur</strong> — [[À FOURNIR : confirmer par écrit auprès de l'hébergeur que les serveurs et les sauvegardes sont situés dans l'Union européenne. Si ce n'est pas le cas, décrire ici le transfert et son encadrement]]</li>
</ul>

<h2>Combien de temps je garde vos données</h2>

<table>
  <tr>
    <th>Donnée</th>
    <th>Durée</th>
  </tr>
  <tr>
    <td>Échanges WhatsApp ou e-mail sans commande</td>
    <td>[[À FOURNIR : durée retenue, par exemple 1 an après le dernier échange. À valider]]</td>
  </tr>
  <tr>
    <td>Données de commande et de livraison</td>
    <td>Le temps nécessaire à l'exécution de la commande, puis conservées au titre de la garantie légale et des éventuelles réclamations. [[À FOURNIR : durée précise, à valider avec le gestor ou l'abogado]]</td>
  </tr>
  <tr>
    <td>Factures et pièces comptables</td>
    <td>La durée imposée par la loi espagnole. [[À FOURNIR : durée exacte, à confirmer par le gestor — le rapport réglementaire la marque comme à vérifier]]</td>
  </tr>
  <tr>
    <td>Inscription à la lettre d'information</td>
    <td>Jusqu'à votre désinscription, puis une trace de votre désinscription pour ne plus vous écrire</td>
  </tr>
  <tr>
    <td>Journaux de connexion du serveur</td>
    <td>[[À FOURNIR : durée de rétention des logs appliquée par l'hébergeur. À lui demander]]</td>
  </tr>
</table>

<p>Passé ces délais, vos données sont supprimées ou rendues anonymes.</p>

<h2>Vos droits</h2>

<p>Le règlement général sur la protection des données (RGPD) et la loi espagnole 3/2018 (LOPDGDD) vous donnent des droits sur vos informations. Ils sont réels et vous pouvez les exercer sans vous justifier.</p>

<ul>
  <li><strong>Accès</strong> — savoir si je détiens des données sur vous, lesquelles, et en obtenir une copie.</li>
  <li><strong>Rectification</strong> — faire corriger ce qui est faux ou incomplet.</li>
  <li><strong>Suppression</strong> — demander l'effacement de vos données, sauf lorsque la loi m'oblige à les garder, par exemple une facture.</li>
  <li><strong>Opposition</strong> — vous opposer à un traitement fondé sur mon intérêt légitime.</li>
  <li><strong>Limitation</strong> — demander que j'arrête d'utiliser vos données sans les supprimer, le temps qu'un point soit réglé.</li>
  <li><strong>Portabilité</strong> — recevoir vos données dans un format lisible par une machine, ou me demander de les transmettre à quelqu'un d'autre.</li>
  <li><strong>Retirer votre consentement</strong> — à tout moment, quand c'est lui qui fonde le traitement, sans que cela remette en cause ce qui a été fait avant.</li>
</ul>

<h3>Comment les exercer</h3>

<p>Écrivez-moi à [[À FOURNIR : adresse e-mail de contact]], ou par courrier à [[À FOURNIR : adresse postale]]. Dites-moi simplement ce que vous voulez.</p>

<p>Je peux avoir besoin de vérifier que c'est bien vous — c'est une protection pour vous, pas un obstacle. Je réponds dans un délai d'un mois. Si votre demande est complexe, ce délai peut être prolongé de deux mois, et je vous préviens dans ce cas. C'est gratuit.</p>

<h3>Si ma réponse ne vous satisfait pas</h3>

<p>Vous pouvez déposer une réclamation auprès de l'autorité espagnole de protection des données, l'<strong>Agencia Española de Protección de Datos (AEPD)</strong>.</p>

<ul>
  <li>Site : <a href="https://www.aepd.es" rel="noopener">www.aepd.es</a></li>
  <li>Adresse : C/ Jorge Juan, 6, 28001 Madrid, Espagne</li>
</ul>

<p>Vous n'êtes pas obligée de me contacter d'abord, mais si le problème peut se régler en un message, cela ira plus vite pour vous.</p>

<h2>Sécurité</h2>

<p>Le site est servi en HTTPS, ce qui chiffre les échanges entre votre navigateur et le serveur. L'accès à l'administration est protégé et réservé. Les sauvegardes sont régulières.</p>

<p>Je ne vous dirai pas que c'est inviolable, parce que ce serait faux pour n'importe quel site. Je m'engage à prendre les mesures raisonnables, et à vous prévenir si un incident affectait vos données.</p>

<h2>Mineurs</h2>

<p>Ce site n'est pas destiné aux mineurs. Je ne collecte pas sciemment de données concernant des personnes de moins de 14 ans. Si cela arrivait, écrivez-moi et je supprimerai.</p>

<h2>Modifications</h2>

<p>Cette politique peut évoluer, par exemple si j'active le paiement en ligne ou un outil de mesure d'audience. La date ci-dessous vous dit de quand date la version que vous lisez.</p>

<p><em>Dernière mise à jour : [[À FOURNIR : date de publication réelle de la page]]</em></p>
```

---
---

# PAGE 3 — Conditions générales de vente

**Titre** : Conditions générales de vente
**Slug** : `conditions-generales-de-vente`
**Meta description** (150 caractères) :
`Conditions de vente de Fatichanelya : produits, prix, commande, paiement, livraison, droit de rétractation de 14 jours, garantie et réclamations.`

**Note d'intégration** : page **à créer** dans WordPress (elle n'existe pas). À ajouter au menu
`pied_infos`. Attention : l'architecture prévoyait un slug `/cgv/` ; le slug retenu ici est
`conditions-generales-de-vente`, plus explicite pour la cliente et pour la recherche. Si `/cgv/` est
préféré, le décider **avant** publication — pas de changement d'URL après.

```html
<p>Ces conditions décrivent comment j'achète, prépare et expédie vos commandes, et quels sont vos droits. Elles sont écrites pour être lues. Si un point vous paraît obscur, posez-moi la question avant de commander : c'est plus simple pour tout le monde.</p>

<p>Elles s'appliquent à toute commande passée auprès de moi via ce site. Vous les acceptez au moment où vous validez votre commande. Je vous conseille de les enregistrer ou de les imprimer.</p>

<h2>1. Qui vend</h2>

<ul>
  <li><strong>Vendeuse</strong> : [[À FOURNIR : nom civil complet de Fati]]</li>
  <li><strong>Statut</strong> : [[À FOURNIR : statut exact, par exemple « travailleuse indépendante (autónoma) inscrite en Espagne »]]</li>
  <li><strong>NIF</strong> : [[À FOURNIR : NIF / NIE espagnol]]</li>
  <li><strong>Adresse</strong> : [[À FOURNIR : adresse postale complète]]</li>
  <li><strong>E-mail</strong> : [[À FOURNIR : adresse e-mail de contact]]</li>
  <li><strong>WhatsApp</strong> : [[À FOURNIR : numéro WhatsApp définitif]]</li>
</ul>

<p>Je suis <strong>distributrice indépendante</strong> des produits DXN. Je ne suis pas la marque et je ne parle pas en son nom. Je revends des produits que j'achète et que je stocke moi-même. Voir les <a href="/mentions-legales/">mentions légales</a>.</p>

<h2>2. À qui je vends</h2>

<p>Je vends à des particuliers majeurs, pour un usage personnel, dans les pays listés à l'article 7.</p>

<p>Je ne vends pas pour la revente. Si vous souhaitez commander des quantités importantes, écrivez-moi d'abord, nous verrons ensemble si c'est possible.</p>

<h2>3. Ce que je vends, et ce que ce n'est pas</h2>

<p>Deux familles de produits :</p>

<ul>
  <li><strong>Des compléments alimentaires</strong> — gélules, poudres, boissons instantanées.</li>
  <li><strong>Des cosmétiques et produits d'hygiène</strong> — savons, shampooings, soins, déodorants.</li>
</ul>

<h3>Un point important sur les compléments alimentaires</h3>

<p><strong>Les compléments alimentaires ne sont pas des médicaments.</strong> Ils ne sont destinés ni à prévenir, ni à traiter, ni à guérir une maladie.</p>

<p>Ils ne remplacent pas une alimentation variée, un mode de vie sain, ni l'avis d'un professionnel de santé. Respectez les conseils d'utilisation et les doses indiqués sur l'emballage. Tenez les produits hors de portée des enfants.</p>

<p>Si vous êtes enceinte ou si vous allaitez, si vous suivez un traitement, ou si vous avez une question de santé, parlez-en à votre médecin ou à votre pharmacien avant d'utiliser un complément alimentaire. Je ne suis pas professionnelle de santé et je ne donne aucun conseil médical, ni sur le site, ni par message.</p>

<h3>Les descriptions</h3>

<p>Les descriptions, compositions et conseils d'utilisation que je publie reprennent les informations du fabricant. Les visuels sont des photographies de présentation : l'emballage réel peut différer légèrement, notamment si le fabricant le fait évoluer.</p>

<p>Avant d'utiliser un produit, <strong>lisez toujours l'étiquette</strong>. C'est elle qui fait foi, pas la fiche du site.</p>

<h2>4. Les prix</h2>

<p>Les prix sont affichés en euros, <strong>taxes comprises</strong>, hors frais de livraison.</p>

<p>Les frais de livraison sont indiqués séparément avant que vous ne validiez votre commande. Le montant total que vous payez est toujours affiché avant validation, frais de livraison inclus. Aucune somme ne s'ajoute après.</p>

<p>[[À FOURNIR : mention de TVA exacte. Le rapport réglementaire signale que le taux applicable doit être validé produit par produit par un asesor fiscal (21 % ou 10 %), et que le régime du recargo de equivalencia peut changer ce qui doit être affiché. Ne rien écrire ici avant cette validation]]</p>

<p>Les prix peuvent changer à tout moment, mais le prix qui vous engage est celui qui était affiché au moment où vous avez validé votre commande.</p>

<p>Si une erreur de prix manifeste se glissait sur une fiche — un prix absurdement bas par exemple — je vous préviendrais avant d'expédier, et vous seriez libre de confirmer au prix corrigé ou d'annuler sans frais.</p>

<h2>5. Comment passer commande</h2>

<h3>Aujourd'hui : par WhatsApp</h3>

<p><strong>Le paiement en ligne n'est pas encore activé sur ce site.</strong> Je préfère vous le dire plutôt que de faire semblant.</p>

<p>Voici comment cela se passe :</p>

<ol>
  <li>Vous composez votre sélection sur le site. Le bouton « Ma sélection » garde vos produits en mémoire pendant votre visite.</li>
  <li>Vous m'envoyez cette sélection sur WhatsApp, en un clic. Le message est prérempli, vous pouvez le modifier.</li>
  <li>Je vous confirme la disponibilité, le montant total et les frais de livraison pour votre adresse.</li>
  <li>Vous confirmez, ou non. <strong>Rien n'est engagé tant que vous n'avez pas confirmé.</strong></li>
  <li>Nous convenons du moyen de paiement. [[À FOURNIR : moyens de paiement réellement acceptés aujourd'hui — virement bancaire, Bizum, autre. Ne rien écrire ici tant que ce n'est pas décidé, et ne jamais demander de coordonnées bancaires par message non sécurisé]]</li>
  <li>Je prépare et j'expédie votre colis, et je vous envoie votre facture.</li>
</ol>

<p>La sélection que vous composez sur le site <strong>n'est pas une commande</strong> et ne vous engage à rien. Le contrat de vente n'est formé qu'au moment où vous confirmez explicitement votre commande et son montant total.</p>

<h3>[[À ACTIVER AVEC PAYPAL]] Demain : paiement en ligne</h3>

<p>[[À ACTIVER AVEC PAYPAL : lorsque le compte marchand sera actif, remplacer l'article 5 par le parcours réel — panier, saisie de l'adresse, affichage du total avec frais de port, récapitulatif avant validation, bouton de commande portant une mention sans ambiguïté sur l'obligation de payer, puis e-mail de confirmation. Le libellé exact de ce bouton doit être validé par un abogado : le rapport réglementaire signale que la formulation littérale reste à vérifier, et qu'un bouton disant seulement « Confirmer » n'est pas conforme. Ajouter aussi l'article sur la confirmation de commande par e-mail]]</p>

<h2>6. Paiement</h2>

<p>[[À FOURNIR : liste des moyens de paiement réellement acceptés à la mise en ligne]]</p>

<p>[[À ACTIVER AVEC PAYPAL : une fois le compte marchand actif, ajouter — « Le paiement s'effectue par PayPal, qui accepte le compte PayPal et la carte bancaire. Vos données bancaires sont saisies directement sur les serveurs de PayPal. Je ne les vois jamais et je n'en conserve aucune. » Ajouter également la mention de la réserve de propriété si l'abogado la recommande]]</p>

<p>Votre commande est préparée une fois le paiement reçu.</p>

<p>Une facture vous est remise pour chaque commande. [[À FOURNIR : préciser si elle est envoyée par e-mail, jointe au colis, ou les deux]]</p>

<h2>7. Livraison</h2>

<h3>Où je livre</h3>

<p>[[À FOURNIR : liste exacte des pays livrés. Le brief prévoit l'Espagne d'abord, puis l'Union européenne. Attention : le rapport réglementaire recommande de ne pas ouvrir la vente de compléments alimentaires vers le Maroc tant que le statut AMMPS/ONSSA n'est pas clarifié. N'écrire ici que des pays confirmés par Fati]]</p>

<h3>En combien de temps</h3>

<p>[[À FOURNIR : délai de préparation, par exemple « j'expédie sous 1 à 3 jours ouvrés après réception du paiement », puis délai d'acheminement par zone]]</p>

<p>En tout état de cause, et sauf accord différent entre nous, votre commande vous est livrée <strong>au plus tard 30 jours</strong> après la conclusion de la vente. Si je ne peux pas tenir ce délai, je vous préviens et vous pouvez annuler et être remboursée intégralement.</p>

<h3>Combien coûte la livraison</h3>

<p>[[À FOURNIR : grille des frais de port par zone ou par pays, et éventuel seuil de livraison offerte. Sans cette grille, le prix total ne peut pas être annoncé avant commande, ce qui est une obligation d'information précontractuelle]]</p>

<p>Le montant exact vous est indiqué avant que vous ne validiez votre commande.</p>

<h3>Suivi</h3>

<p>[[À FOURNIR : transporteur utilisé, et indiquer si un numéro de suivi est fourni. Si oui : « Je vous envoie votre numéro de suivi dès l'expédition. » Si non, le dire honnêtement plutôt que de le laisser deviner]]</p>

<h3>À la réception</h3>

<p>Vérifiez l'état du colis devant le livreur. S'il est abîmé ou ouvert, notez-le sur le bon de livraison et prenez une photo avant de l'ouvrir. Puis écrivez-moi dans les meilleurs délais. Avec une photo, je règle la situation beaucoup plus vite.</p>

<p>Si vous êtes absente, le transporteur laisse un avis de passage. Un colis retourné parce qu'il n'a pas été réclamé peut entraîner de nouveaux frais d'expédition pour un second envoi.</p>

<h3>Si une adresse est incorrecte</h3>

<p>Vérifiez votre adresse avant de valider. Un colis parti à une mauvaise adresse est souvent perdu, et les frais d'un second envoi restent à votre charge.</p>

<h2>8. Votre droit de changer d'avis : 14 jours</h2>

<p>Vous avez <strong>14 jours</strong> pour renoncer à votre achat, sans avoir à vous justifier et sans pénalité. C'est un droit que la loi vous donne, je ne fais que l'appliquer.</p>

<h3>À partir de quand comptent les 14 jours</h3>

<p>À partir du jour où vous — ou une personne que vous désignez — recevez physiquement le colis. Ce sont des jours calendaires : week-ends et jours fériés compris.</p>

<p>Si votre commande est livrée en plusieurs colis, le délai part de la réception du dernier.</p>

<h3>Comment m'en informer</h3>

<p>Avant la fin des 14 jours, dites-le-moi clairement, par l'un de ces moyens :</p>

<ul>
  <li>par e-mail à [[À FOURNIR : adresse e-mail de contact]] ;</li>
  <li>par WhatsApp au [[À FOURNIR : numéro WhatsApp]] ;</li>
  <li>par courrier à [[À FOURNIR : adresse postale]].</li>
</ul>

<p>Vous pouvez utiliser le formulaire ci-dessous, mais ce n'est pas obligatoire : une phrase claire suffit. Je vous confirme la réception de votre demande.</p>

<h3>Renvoyer le produit</h3>

<p>Renvoyez-moi le produit <strong>dans les 14 jours</strong> qui suivent votre demande, à [[À FOURNIR : adresse de retour, si elle est différente de l'adresse postale]].</p>

<p><strong>Les frais de renvoi sont à votre charge</strong>, sauf si le produit était défectueux ou non conforme — dans ce cas, ils sont pour moi.</p>

<p>Choisissez un envoi suivi. Tant que le colis n'est pas arrivé, c'est vous qui en avez la responsabilité, et un colis perdu sans preuve d'envoi est difficile à traiter pour nous deux.</p>

<h3>Dans quel état</h3>

<p>Le produit doit revenir <strong>complet, dans son emballage d'origine, avec tous ses accessoires et sa notice</strong>.</p>

<p>Vous avez le droit de l'examiner comme vous l'auriez fait en magasin. Mais si vous l'avez manipulé au-delà de ce qui était nécessaire pour vérifier sa nature et son bon fonctionnement, je peux retenir une somme correspondant à la perte de valeur.</p>

<h3>Mon remboursement</h3>

<p>Je vous rembourse <strong>au plus tard 14 jours</strong> après avoir été informée de votre décision, par le même moyen de paiement que celui que vous aviez utilisé, sauf si nous convenons d'autre chose. Le remboursement ne vous coûte rien.</p>

<p>Je rembourse le prix des produits <strong>et les frais de livraison standard</strong> que vous aviez payés à l'aller. Si vous aviez choisi une livraison plus rapide que l'option standard, je ne rembourse que le montant de l'option standard.</p>

<p>Je peux attendre d'avoir reçu le produit, ou une preuve que vous l'avez renvoyé, avant de procéder au remboursement.</p>

<h3>Les produits qui ne peuvent pas être repris</h3>

<p>Il y a une exception, et je l'applique strictement, sans l'étendre.</p>

<p><strong>Un produit scellé pour des raisons d'hygiène ou de protection de la santé ne peut pas être repris s'il a été descellé après la livraison.</strong> C'est le cas d'un pot de complément alimentaire dont l'opercule a été retiré, ou d'un cosmétique dont le film de sécurité a été ouvert. Une fois ce sceau rompu, je ne peux ni le revendre, ni garantir sa sécurité à quelqu'un d'autre.</p>

<p><strong>En revanche, si le produit n'a pas été ouvert et que son sceau est intact, vous pouvez le renvoyer normalement.</strong> Le fait que ce soit un complément alimentaire ou un cosmétique ne supprime pas votre droit de rétractation.</p>

<p>Cette exception ne s'applique pas non plus à un produit qui vous arriverait défectueux, abîmé, périmé ou différent de ce que vous aviez commandé : dans ce cas, c'est l'article 10 qui s'applique, et c'est moi qui prends tout en charge.</p>

<h2>9. Formulaire type de rétractation</h2>

<p>Remplissez et renvoyez ce formulaire uniquement si vous souhaitez vous rétracter. Vous n'êtes pas obligée de l'utiliser : un message clair suffit.</p>

<hr>

<p>À l'attention de :<br>
[[À FOURNIR : nom civil complet de Fati]]<br>
[[À FOURNIR : adresse postale complète]]<br>
[[À FOURNIR : adresse e-mail de contact]]</p>

<p>Je vous notifie par la présente ma rétractation du contrat portant sur la vente du ou des biens ci-dessous :</p>

<p>— Produit(s) commandé(s) : ________________________________________</p>

<p>— Commandé le : ____ / ____ / ________</p>

<p>— Reçu le : ____ / ____ / ________</p>

<p>— Numéro de commande (si vous l'avez) : ________________________________</p>

<p>— Nom de la consommatrice : ________________________________________</p>

<p>— Adresse de la consommatrice : ____________________________________</p>

<p>— Signature de la consommatrice (uniquement si ce formulaire est envoyé sur papier) :</p>

<p>— Date : ____ / ____ / ________</p>

<hr>

<h2>10. Si le produit ne va pas</h2>

<h3>Produit abîmé, périmé ou non conforme</h3>

<p>Si vous recevez un produit cassé, ouvert, périmé, ou qui n'est pas celui que vous aviez commandé, écrivez-moi <strong>avec une photo</strong>. Je vous renvoie le bon produit ou je vous rembourse, et les frais de retour sont pour moi. Vous n'avez rien à avancer.</p>

<h3>Garantie légale</h3>

<p>Vous bénéficiez de la <strong>garantie légale de conformité</strong> prévue par le droit espagnol, qui est de <strong>trois ans</strong> à compter de la livraison pour les défauts de conformité existant au moment de celle-ci.</p>

<p>Cela dit, soyons concrets : les compléments alimentaires et les cosmétiques ont une <strong>date de durabilité</strong> imprimée sur l'emballage, de quelques mois à quelques années. Ce qui compte réellement pour ces produits, c'est qu'ils vous arrivent conformes, non endommagés et pas périmés. C'est sur ce point que je m'engage, et c'est ce que vous devez vérifier à la réception.</p>

<p>En cas de défaut de conformité, vous pouvez demander la mise en conformité du produit — remplacement — et, si ce n'est pas possible, une réduction du prix ou le remboursement.</p>

<h2>11. Une réclamation, une question, un désaccord</h2>

<p>Écrivez-moi en premier. La plupart des situations se règlent en quelques messages, et c'est la voie la plus rapide pour vous.</p>

<ul>
  <li>E-mail : [[À FOURNIR : adresse e-mail de contact]]</li>
  <li>WhatsApp : [[À FOURNIR : numéro WhatsApp]]</li>
  <li>Courrier : [[À FOURNIR : adresse postale]]</li>
</ul>

<p>[[À FOURNIR : engagement de délai de réponse aux réclamations, par exemple « je réponds sous 48 heures ouvrées ». Ne rien promettre qui ne puisse être tenu]]</p>

<p>Si nous ne trouvons pas de solution, vous conservez naturellement tous vos recours de consommatrice. En Espagne, vous pouvez notamment vous adresser à l'<strong>Oficina Municipal de Información al Consumidor (OMIC)</strong> de votre commune, ou aux services de consommation de votre Communauté autonome. Si vous résidez dans un autre pays de l'Union européenne, votre organisme national de protection des consommateurs peut vous orienter.</p>

<p>[[À FOURNIR / À VALIDER PAR L'ABOGADO : quelles mentions relatives à la résolution alternative des litiges (directive 2013/11/UE, Ley 7/2017) restent obligatoires en 2026, et si Fati adhère à un système d'ADR. NE PAS ajouter de lien vers la plateforme européenne de règlement en ligne des litiges : elle a été supprimée le 20 juillet 2025 et le lien est mort]]</p>

<h2>12. Force majeure</h2>

<p>Je ne peux pas être tenue responsable d'un retard ou d'une impossibilité de livrer dû à un événement indépendant de ma volonté — grève des transporteurs, catastrophe naturelle, blocage douanier, panne généralisée. Si cela arrive, je vous préviens, et si la situation dure, vous pouvez annuler et être remboursée.</p>

<h2>13. Si une clause pose problème</h2>

<p>Si une partie de ces conditions se révélait contraire à la loi, seule cette partie serait écartée. Le reste continuerait de s'appliquer.</p>

<h2>14. Modifications</h2>

<p>Je peux modifier ces conditions. Celles qui s'appliquent à votre commande sont celles qui étaient en ligne au moment où vous l'avez validée. La date ci-dessous vous dit de quand date la version que vous lisez.</p>

<h2>15. Droit applicable et tribunaux</h2>

<p>Ces conditions et nos ventes sont régies par le <strong>droit espagnol</strong>.</p>

<p>Si vous êtes consommatrice et que vous résidez dans un autre pays de l'Union européenne, cela ne vous prive pas de la protection que vous donnent les règles impératives de votre propre pays. Vous pouvez également saisir les tribunaux de votre lieu de résidence.</p>

<p><em>Dernière mise à jour : [[À FOURNIR : date de publication réelle de la page]]</em></p>
```

---
---

# PAGE 4 — Livraison et retours

**Titre** : Livraison et retours
**Slug** : `livraison-retours`
**Meta description** (146 caractères) :
`Où je livre, en combien de temps, à quel prix, et comment retourner un produit sous 14 jours. Les réponses claires, avant que vous ne commandiez.`

**Note d'intégration** : c'est la seule des quatre pages qui **doit** rester indexable et qui se lit
avant l'achat. Elle est écrite pour rassurer, pas pour se couvrir. Les CGV font foi, c'est dit à la fin.
Un lien vers cette page depuis la fiche produit et depuis le tiroir « Ma sélection » vaut mieux qu'un
paragraphe de plus.

```html
<p>Vous hésitez à commander parce que vous ne savez pas où j'expédie, en combien de temps, ni ce qui se passe si le produit ne vous convient pas. Voici les réponses, sans détour.</p>

<h2>Où je livre</h2>

<p>[[À FOURNIR : liste exacte des pays livrés, par zone si possible. Par exemple : « Espagne péninsulaire », « Baléares et Canaries » — attention, les Canaries sont hors territoire TVA de l'UE et les envois y passent la douane —, « France métropolitaine », « autres pays de l'Union européenne ». N'annoncer que des pays confirmés par Fati. Le rapport réglementaire recommande de ne pas ouvrir la vente de compléments alimentaires vers le Maroc tant que le statut AMMPS/ONSSA n'est pas clarifié]]</p>

<p>Vous ne voyez pas votre pays ? Écrivez-moi, je vous dis si c'est possible et à quel prix.</p>

<h2>Combien coûte la livraison</h2>

<table>
  <tr>
    <th>Destination</th>
    <th>Frais de livraison</th>
    <th>Délai estimé</th>
  </tr>
  <tr>
    <td>[[À FOURNIR : zone 1]]</td>
    <td>[[À FOURNIR : tarif]]</td>
    <td>[[À FOURNIR : délai]]</td>
  </tr>
  <tr>
    <td>[[À FOURNIR : zone 2]]</td>
    <td>[[À FOURNIR : tarif]]</td>
    <td>[[À FOURNIR : délai]]</td>
  </tr>
  <tr>
    <td>[[À FOURNIR : autant de lignes que de zones réelles]]</td>
    <td>[[À FOURNIR : tarif]]</td>
    <td>[[À FOURNIR : délai]]</td>
  </tr>
</table>

<p>[[À FOURNIR : existe-t-il un seuil de livraison offerte ? Par exemple « Livraison offerte à partir de 60 € d'achat ». Si oui, l'écrire ici en évidence : c'est un argument de panier moyen. Si non, ne rien inventer]]</p>

<p>Le montant exact vous est indiqué avant que vous ne validiez votre commande. Aucun frais ne s'ajoute après.</p>

<h2>En combien de temps</h2>

<h3>Préparation</h3>

<p>[[À FOURNIR : délai de préparation réel, par exemple « Je prépare et je dépose votre colis sous 1 à 3 jours ouvrés après réception du paiement. »]]</p>

<p>J'expédie les produits depuis mon propre stock, en Espagne. Ce n'est pas un entrepôt automatisé : c'est moi qui prépare votre colis.</p>

<h3>Acheminement</h3>

<p>[[À FOURNIR : délais d'acheminement par zone, cohérents avec le tableau ci-dessus]]</p>

<p>Ce sont des délais estimés, communiqués par le transporteur. Un pic d'activité ou un jour férié peut les allonger de quelques jours.</p>

<p>Ce qui est certain, en revanche : <strong>vous êtes livrée au plus tard 30 jours après votre commande</strong>. Si je ne peux pas tenir, je vous préviens et vous pouvez annuler et être remboursée intégralement.</p>

<h2>Comment suivre mon colis</h2>

<p>[[À FOURNIR : transporteur utilisé et existence ou non d'un numéro de suivi. Si un suivi existe : « Dès que votre colis part, je vous envoie le numéro de suivi par [e-mail / WhatsApp]. Vous pouvez le suivre sur le site du transporteur. » Si aucun suivi n'est fourni, le dire franchement plutôt que de laisser la question sans réponse]]</p>

<p>Et si vous vous posez une question sur votre colis, écrivez-moi. C'est moi qui réponds.</p>

<h2>À la réception du colis</h2>

<p>Deux gestes qui vous évitent des complications :</p>

<ul>
  <li><strong>Regardez le colis avant de signer.</strong> S'il est abîmé, écrasé ou ouvert, notez-le sur le bon de livraison et prenez une photo avant de l'ouvrir.</li>
  <li><strong>Vérifiez le contenu et les dates.</strong> Si quelque chose ne va pas, écrivez-moi avec une photo. Avec une photo, je règle la situation tout de suite.</li>
</ul>

<h2>Retourner un produit : vous avez 14 jours</h2>

<p>Vous pouvez changer d'avis, sans avoir à vous expliquer. C'est votre droit, et je l'applique.</p>

<h3>Comment faire</h3>

<ol>
  <li><strong>Prévenez-moi dans les 14 jours suivant la réception</strong> de votre colis, par WhatsApp ou par e-mail. Un message suffit : « Je souhaite retourner tel produit. » Pas de formulaire compliqué. Un formulaire type existe dans les <a href="/conditions-generales-de-vente/">conditions générales de vente</a> si vous préférez l'utiliser, mais il n'est pas obligatoire.</li>
  <li><strong>Renvoyez le produit dans les 14 jours</strong> qui suivent votre message, à l'adresse que je vous indique. Prenez un envoi suivi : tant que le colis n'est pas arrivé, c'est vous qui en avez la responsabilité.</li>
  <li><strong>Je vous rembourse dans les 14 jours</strong> suivant votre demande, sur le même moyen de paiement.</li>
</ol>

<h3>Ce que je rembourse</h3>

<p>Le prix des produits <strong>et les frais de livraison standard</strong> que vous aviez payés à l'aller. Si vous aviez choisi une livraison express, je rembourse le montant de l'option standard.</p>

<p><strong>Les frais de renvoi restent à votre charge</strong>, sauf si le produit était abîmé, périmé ou non conforme — dans ce cas c'est moi qui paie, et vous n'avancez rien.</p>

<p>[[À FOURNIR : adresse de retour, si elle est différente de l'adresse indiquée dans les mentions légales]]</p>

<h2>Ce qui peut être retourné, et ce qui ne peut pas</h2>

<p>Il y a une seule vraie limite, et elle tient à l'hygiène.</p>

<table>
  <tr>
    <th>Situation</th>
    <th>Retour possible ?</th>
  </tr>
  <tr>
    <td>Produit fermé, sceau ou opercule intact, emballage d'origine</td>
    <td><strong>Oui.</strong> Sans discussion</td>
  </tr>
  <tr>
    <td>Complément alimentaire ouvert, opercule retiré</td>
    <td><strong>Non.</strong> Une fois le sceau rompu, je ne peux ni le revendre ni en garantir la sécurité pour quelqu'un d'autre</td>
  </tr>
  <tr>
    <td>Cosmétique ouvert ou entamé, film de sécurité retiré</td>
    <td><strong>Non</strong>, pour la même raison</td>
  </tr>
  <tr>
    <td>Produit abîmé, périmé, ou différent de celui que vous aviez commandé</td>
    <td><strong>Oui, toujours</strong>, même ouvert. Et c'est moi qui paie le retour</td>
  </tr>
</table>

<p>Autrement dit : le fait qu'un produit soit un complément alimentaire ou un cosmétique ne vous fait pas perdre votre droit de retour. <strong>Ce qui compte, c'est le sceau.</strong> S'il est intact, vous pouvez renvoyer.</p>

<h2>Le colis n'est jamais arrivé</h2>

<p>Écrivez-moi. Je vérifie auprès du transporteur et j'ouvre une réclamation. [[À FOURNIR : délai indicatif de traitement d'une réclamation transporteur, si Fati souhaite s'engager]]</p>

<p>Vous ne restez pas seule avec un colis perdu.</p>

<h2>Une question avant de commander ?</h2>

<p>C'est le plus simple. Écrivez-moi sur WhatsApp : je réponds moi-même, en français, en espagnol ou en darija.</p>

<p>[[À FOURNIR : lien WhatsApp cliquable, construit sur le numéro définitif]]</p>

<h2>Pour aller plus loin</h2>

<p>Cette page résume l'essentiel pour vous éviter une lecture longue. Les règles complètes et détaillées — et ce sont elles qui font foi — sont dans les <a href="/conditions-generales-de-vente/">conditions générales de vente</a>.</p>

<p><em>Dernière mise à jour : [[À FOURNIR : date de publication réelle de la page]]</em></p>
```

---
---

## Ce qu'il faut obtenir de la cliente avant de publier ces quatre pages

Aucune des quatre pages ne se publie tant qu'un seul marqueur `[[À FOURNIR : …]]` subsiste.
Liste consolidée, par ordre de blocage.

### Bloquant absolu — les quatre pages sont impubliables sans

1. **Nom civil complet de Fati**, tel qu'il figure sur ses documents espagnols. Un prénom ou un nom de
   marque ne satisfait pas l'article 10 de la LSSI.
2. **NIF ou NIE espagnol.**
3. **Adresse postale réelle et complète.** Point sensible : une autónoma doit publier une adresse
   réelle. Si Fati travaille depuis chez elle, cela signifie publier son domicile — pour quelqu'un qui
   s'expose sur TikTok, ce n'est pas neutre. **Question à poser au gestor avant de publier quoi que ce
   soit** : une domiciliation professionnelle est-elle acceptable au regard de la LSSI ?
4. **Statut exact** (autónoma inscrite ? depuis quand ?), à confirmer une fois l'alta effectuée.
5. **Adresse e-mail de contact**, relevée régulièrement. Obligatoire.
6. **Numéro WhatsApp définitif**, au format international.
7. **Hébergeur** : raison sociale, adresse, site, **et pays où sont situés les serveurs et les
   sauvegardes** — à demander par écrit à l'hébergeur, l'information alimente la confidentialité.

### Bloquant pour les CGV et la page Livraison

8. **Liste exacte des pays livrés.** Ne pas déduire du brief.
9. **Grille des frais de port** par zone ou par pays, et seuil éventuel de livraison offerte.
10. **Délai de préparation** (combien de jours entre paiement et dépôt) et **délais d'acheminement**
    par zone.
11. **Transporteur(s) utilisé(s)**, et **existence ou non d'un numéro de suivi**.
12. **Adresse de retour**, si elle diffère de l'adresse publiée.
13. **Moyens de paiement réellement acceptés aujourd'hui**, tant que PayPal n'est pas actif.
    Attention : ne jamais demander de coordonnées bancaires par message non sécurisé.
14. **Comment la facture est remise** : par e-mail, dans le colis, ou les deux.
15. **Délai de réponse** que Fati s'engage à tenir sur les réclamations. Ne rien promettre qu'elle ne
    puisse tenir.

### Bloquant pour la confidentialité

16. **Outil d'envoi de la lettre d'information**, s'il y en a un, et son pays. Tant qu'aucun outil
    n'est connecté, **le formulaire de newsletter ne doit pas collecter d'adresses** : soit on branche
    l'outil, soit on retire le formulaire. Collecter des adresses qui se perdent est le pire des deux.
17. **Cabinet du gestor**, s'il reçoit des données de facturation.
18. **Durées de conservation** des données de commande et des pièces comptables, à faire confirmer par
    le gestor.
19. **Durée de rétention des journaux de connexion**, à demander à l'hébergeur.

### À obtenir de DXN, par écrit, avant de publier les mentions légales

20. Le contrat autorise-t-il la **vente en ligne sur un site personnel** ?
21. Autorise-t-il l'**usage de la marque, du logo et des visuels produits** ?
22. Quelle entité **facture** Fati : DXN Internacional Spain SLU (NIF B30877195) ou une entité hors UE ?
    C'est la question la plus lourde du dossier — elle décide si Fati est revendeuse ou importatrice.
23. **Numéro de distributrice** de Fati, si elle souhaite l'afficher comme preuve d'authenticité.

### À faire valider par un professionnel

24. **Relecture complète des CGV par un abogado** spécialisé en commerce électronique.
25. **Taux de TVA produit par produit** par un asesor fiscal, et effet du recargo de equivalencia sur
    la mention de TVA affichée.
26. **Mentions ADR** encore obligatoires en 2026.
27. **Libellé du bouton de commande** — à traiter au moment de l'activation du paiement.

### À traiter avant la première campagne publicitaire

28. **Bandeau de consentement conforme AEPD** et réécriture de la section Cookies. Le pixel TikTok ne
    doit pas se déclencher avant acceptation. Tant que ce n'est pas fait, pas de campagne.

### Actions d'intégration côté site

- **Créer la page `conditions-generales-de-vente`** (elle n'existe pas) et l'ajouter au menu
  `pied_infos`, à côté des trois autres.
- **Vérifier le slug** : l'architecture évoquait `/cgv/`. Trancher **avant** publication.
- **Supprimer le brouillon WordPress `politique-de-confidentialite`**, doublon de `/confidentialite/`
  déjà signalé par l'architecte.
- **Lier la page Livraison et retours depuis la fiche produit et depuis le tiroir « Ma sélection »** :
  c'est là que naît l'objection, c'est là que la réponse doit être à un clic.
- **Traductions ES / EN / AR** : ne pas traduire ces pages tant que la version française n'est pas
  validée par le professionnel. Une page légale traduite à partir d'un texte non validé double l'erreur.
