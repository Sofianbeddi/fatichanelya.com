#!/usr/bin/env python3
"""
Génère wp-content/themes/fatichanelya/languages/ar.po (puis le .mo) à partir des
chaînes du thème et du mu-plugin et du dictionnaire ci-dessous.

    python3 tools/traductions/ar/chaines.py && wp i18n make-mo wp-content/themes/fatichanelya/languages/ar.po

Les chaînes absentes du dictionnaire (libellés d'administration, en-tête du
thème, palette de l'éditeur) restent en français et sont listées en sortie.
Formes plurielles de l'arabe : six (0, 1, 2, 3-10, 11-99, autres).
"""
import re, sys, pathlib, subprocess, tempfile
F = pathlib.Path(__file__).resolve().parents[3]
S = pathlib.Path(tempfile.mkdtemp())
for dossier, nom in (("wp-content/themes/fatichanelya", "theme.pot"), ("wp-content/mu-plugins", "core.pot")):
    subprocess.run(["wp", "i18n", "make-pot", str(F / dossier), str(S / nom), "--domain=fatichanelya"], cwd=str(F), check=True, capture_output=True)

T = {
"Erreur 404": "خطأ 404",
"Cette page n'existe *plus*.": "هذه الصفحة *لم تعد* موجودة.",
"Le lien est peut-être ancien. Reprenez depuis l'accueil ou le catalogue.": "ربما يكون الرابط قديماً. عد إلى الصفحة الرئيسية أو إلى الكتالوج.",
"Retour à l'accueil": "العودة إلى الرئيسية",
"Voir le catalogue": "تصفح الكتالوج",
"Formations": "الدورات التدريبية",
"Les programmes": "البرامج",
"Voir le programme": "عرض البرنامج",
"Les formations arrivent très bientôt.": "الدورات التدريبية قادمة قريباً جداً.",
"Vous ne savez pas laquelle choisir ?": "لا تعرف أيّها تختار؟",
"Dites à Fati où vous en êtes et ce que vous voulez atteindre. Elle vous indique le programme qui correspond, sans engagement.": "أخبر فاتي أين وصلت وما الذي تريد بلوغه، وستدلّك على البرنامج المناسب، بدون أي التزام.",
"Bonjour Fati, j'hésite entre plusieurs formations. Pouvez-vous m'orienter ?": "مرحباً فاتي، أتردد بين عدة دورات تدريبية. هل يمكنك توجيهي؟",
"En parler avec Fati": "تحدّث مع فاتي",
"Boutique": "المتجر",
"La sélection Fatichanelya": "تشكيلة Fatichanelya",
"Tous les produits": "كل المنتجات",
"Rechercher dans la sélection": "ابحث في التشكيلة",
"Rechercher un produit…": "ابحث عن منتج…",
"%d produit affiché": ["%d منتج معروض", "%d منتج معروض", "منتجان معروضان", "%d منتجات معروضة", "%d منتجاً معروضاً", "%d منتج معروض"],
"Aucun produit ne correspond à cette recherche.": "لا يوجد منتج يطابق هذا البحث.",
"Réinitialiser": "إعادة ضبط",
"Prix indicatifs en euros, hors livraison. Disponibilité confirmée avec Fati avant commande.": "أسعار استرشادية باليورو، لا تشمل التوصيل. يُؤكَّد التوفر مع فاتي قبل الطلب.",
"Aucun produit dans cette catégorie.": "لا توجد منتجات في هذه الفئة.",
"Aucun contenu pour le moment.": "لا يوجد محتوى حالياً.",
"Une question avant de commander ? Écrivez-moi : je réponds moi-même, sous 24 h en semaine.": "لديك سؤال قبل الطلب؟ راسلني: أجيب بنفسي خلال 24 ساعة في أيام العمل.",
"Bonjour Fati, j'aimerais échanger avec vous.": "مرحباً فاتي، أودّ التحدث معك.",
"Écrire sur WhatsApp": "راسلني على واتساب",
"Réseaux sociaux": "مواقع التواصل الاجتماعي",
"Navigation du pied de page": "روابط أسفل الصفحة",
"Navigation": "التنقل",
"Informations": "معلومات",
"Site conçu par": "موقع من تصميم",
"Aller au contenu principal": "الانتقال إلى المحتوى الرئيسي",
"Informations générales": "معلومات عامة",
"%s, accueil": "%s، الصفحة الرئيسية",
"Navigation principale": "التنقل الرئيسي",
"Langue du site": "لغة الموقع",
"Langues du site": "لغات الموقع",
"Langue : %s. Changer de langue": "اللغة: %s. تغيير اللغة",
"Panier : 0 article": "السلة: 0 منتج",
"Ouvrir le menu": "فتح القائمة",
"Navigation mobile": "قائمة الهاتف",
"%s × %d": "%s × %d",
"Panier : %d article": "السلة: %d منتج",
"Panier : %d articles": "السلة: %d منتجات",
"%d dans votre panier": "%d في سلتك",
"%d produits affichés": "%d منتج معروض",
"Aucun produit affiché": "لا يوجد منتج معروض",
"Bonjour Fati, voici ma commande :": "مرحباً فاتي، هذا طلبي:",
"Total indicatif :": "المجموع الاسترشادي:",
"Pouvez-vous me confirmer la disponibilité et la livraison ?": "هل يمكنك تأكيد التوفر والتوصيل؟",
"Bonjour Fati, je souhaite commander.": "مرحباً فاتي، أرغب في الطلب.",
"Retirer %s du panier": "إزالة %s من السلة",
"Retirer un %s": "إنقاص %s بواحد",
"Ajouter un %s": "زيادة %s بواحد",
"Quantité de %s dans le panier": "كمية %s في السلة",
"%s l'unité": "%s للوحدة",
"prix à confirmer": "السعر يُؤكَّد لاحقاً",
"Total indicatif, hors articles à confirmer": "المجموع الاسترشادي، دون المنتجات التي يُؤكَّد سعرها لاحقاً",
"Visuel à venir pour %s": "الصورة قادمة قريباً: %s",
"Menu principal": "القائمة الرئيسية",
"Pied de page — Navigation": "أسفل الصفحة — التنقل",
"Pied de page — Informations": "أسفل الصفحة — معلومات",
"J'expédie depuis l'Espagne, vers toute l'Union européenne": "أشحن من إسبانيا إلى كل دول الاتحاد الأوروبي",
"Darija, français ou espagnol : je réponds dans votre langue": "بالدارجة أو الفرنسية أو الإسبانية: أجيبك بلغتك",
"Sur WhatsApp, c'est moi qui réponds": "على واتساب، أنا من يجيبك",
"Une sélection DXN, expédiée par moi": "تشكيلة DXN أشحنها بنفسي",
"Des produits choisis dans le catalogue DXN, avec leurs noms, prix et formats. Ils partent de mon propre stock, en Espagne.": "منتجات مختارة من كتالوج DXN، بأسمائها وأسعارها وأحجامها. تُشحن من مخزوني الخاص في إسبانيا.",
"Voir la sélection": "تصفح التشكيلة",
"Des formations à distance": "دورات تدريبية عن بُعد",
"Des programmes pour apprendre un métier du digital : marketing, contenu, vente, publicité, IA, automatisation. Le programme est publié, le format et le niveau se choisissent par message.": "برامج لتعلّم مهنة رقمية: التسويق، صناعة المحتوى، البيع، الإعلان، الذكاء الاصطناعي، الأتمتة. البرنامج منشور، أما الصيغة والمستوى فيُختاران عبر الرسائل.",
"Voir les formations": "تصفح الدورات",
"TikTok, là où on se parle": "تيك توك، حيث نتحدث",
"Je parle produits, projets et quotidien d'entrepreneure, en darija surtout, et je réponds aux questions comme je le ferais à une amie.": "أتحدث عن المنتجات والمشاريع ويوميات رائدة الأعمال، بالدارجة غالباً، وأجيب عن الأسئلة كما أجيب صديقة.",
"Suivre @fatichanelya": "تابع @fatichanelya",
"Je ne recrute personne. Ce site vend des produits et des formations, il ne propose aucune activité de distribution.": "لا أقوم بتجنيد أحد. هذا الموقع يبيع منتجات ودورات تدريبية، ولا يعرض أي نشاط توزيع.",
"Je ne promets aucun revenu, et je ne parle ni de gains ni d'équipe.": "لا أعد بأي دخل، ولا أتحدث عن أرباح ولا عن فريق.",
"Je ne donne aucun conseil médical. Les compléments alimentaires ne soignent pas et ne remplacent pas l'avis d'un professionnel de santé.": "لا أقدّم أي نصيحة طبية. المكملات الغذائية لا تعالج ولا تغني عن رأي مختص في الصحة.",
"Je ne publie aucun avis client tant que je n'en ai pas de véritables.": "لا أنشر أي آراء عملاء ما دمت لا أملك آراء حقيقية.",
"Fil d'Ariane": "مسار التصفح",
"Accueil": "الرئيسية",
"À propos": "من هي فاتي",
"À propos de Fati": "عن فاتي",
"Écrire à Fati": "راسل فاتي",
"Fati, créatrice de %s": "فاتي، مؤسِّسة %s",
"Entrepreneure · Créatrice · Mentore": "رائدة أعمال · صانعة محتوى · مرشدة",
"Ce que vous trouverez ici": "ما ستجده هنا",
"Des produits, des formations, et *quelqu'un à qui parler*.": "منتجات، ودورات تدريبية، *وشخص يمكنك التحدث إليه*.",
"Fati, souriante, en veste claire": "فاتي مبتسمة، بسترة فاتحة اللون",
"Ma façon de faire": "طريقتي في العمل",
"Comment je travaille": "كيف أعمل",
"Ce qui n'est pas confirmé n'est pas affiché : disponibilité, frais de port et total sont validés avec vous avant l'envoi.": "ما لم يُؤكَّد لا يُعرض: التوفر ومصاريف الشحن والمجموع تُعتمد معك قبل الإرسال.",
"Une relation claire": "علاقة واضحة",
"Ce que je ne fais pas": "ما لا أفعله",
"Vous hésitez entre deux produits, ou vous voulez savoir si une formation est faite pour vous ? Dites-le-moi, je vous réponds sans engagement.": "متردد بين منتجين، أو تريد أن تعرف إن كانت دورة ما مناسبة لك؟ أخبرني، وأجيبك بدون أي التزام.",
"Voir mon TikTok": "شاهد حسابي على تيك توك",
"Contact": "اتصل بفاتي",
"WhatsApp · direct": "واتساب · مباشر",
"Choisir le bon message": "اختر الرسالة المناسبة",
"Une question sur un produit": "سؤال عن منتج",
"Disponibilité, usage, composition : dites-moi lequel vous intéresse.": "التوفر، طريقة الاستعمال، التركيبة: أخبرني أي منتج يهمك.",
"Bonjour Fati, j'ai une question sur un produit.": "مرحباً فاتي، لديّ سؤال عن منتج.",
"Poser ma question": "اطرح سؤالي",
"Commander": "اطلب",
"On confirme ensemble la disponibilité, les frais de port réels et le total avant tout paiement.": "نؤكد معاً التوفر ومصاريف الشحن الفعلية والمجموع قبل أي دفع.",
"Bonjour Fati, je souhaite passer une commande.": "مرحباً فاتي، أرغب في تقديم طلب.",
"Préparer ma commande": "تجهيز طلبي",
"Une formation": "دورة تدريبية",
"Programme, dates et tarif vous sont envoyés par message, sans engagement.": "يُرسل إليك البرنامج والمواعيد والسعر عبر رسالة، بدون أي التزام.",
"Bonjour Fati, je souhaite des informations sur les formations.": "مرحباً فاتي، أرغب في معلومات عن الدورات التدريبية.",
"Demander le programme": "اطلب البرنامج",
"Réponse sous 24 h en semaine. Aucun conseil médical n'est donné par message.": "الرد خلال 24 ساعة في أيام العمل. لا تُقدَّم أي نصيحة طبية عبر الرسائل.",
"Écrire à Fati sur TikTok": "راسل فاتي على تيك توك",
"Le numéro WhatsApp arrive. Aucun conseil médical n'est donné par message.": "رقم واتساب قادم قريباً. لا تُقدَّم أي نصيحة طبية عبر الرسائل.",
"Me suivre ailleurs": "تابعني في مكان آخر",
"Le quotidien, les nouveautés et les coulisses passent surtout par TikTok.": "اليوميات والجديد والكواليس تمرّ أساساً عبر تيك توك.",
"Panier": "السلة",
"Produits dans le panier": "المنتجات في السلة",
"Produit": "المنتج",
"Prix": "السعر",
"Quantité": "الكمية",
"Sous-total": "المجموع الفرعي",
"Votre panier est vide pour le moment.": "سلتك فارغة حالياً.",
"Voir la boutique": "تصفح المتجر",
"Continuer mes achats": "متابعة التسوق",
"Vider le panier": "إفراغ السلة",
"Récapitulatif": "ملخص الطلب",
"Articles": "المنتجات",
"Livraison": "التوصيل",
"confirmée par message": "يُؤكَّد عبر رسالة",
"Les frais de port dépendent de votre pays. Fati vous donne le total exact avant tout paiement.": "مصاريف الشحن تختلف حسب بلدك. تعطيك فاتي المجموع الدقيق قبل أي دفع.",
"Commander sur WhatsApp": "اطلب عبر واتساب",
"Rien ne se paie sur le site : Fati confirme la disponibilité, les frais de port et le total, puis vous indique comment régler.": "لا يُدفع أي شيء على الموقع: تؤكد فاتي التوفر ومصاريف الشحن والمجموع، ثم تخبرك بطريقة الدفع.",
"Recherche": "البحث",
"Résultats": "النتائج",
"%1$d résultat pour « %2$s »": ["لا نتائج لـ« %2$s »", "نتيجة واحدة لـ« %2$s »", "نتيجتان لـ« %2$s »", "%1$d نتائج لـ« %2$s »", "%1$d نتيجة لـ« %2$s »", "%1$d نتيجة لـ« %2$s »"],
"Rien ne correspond à « %s »": "لا شيء يطابق « %s »",
"Formation": "دورة تدريبية",
"Page": "صفحة",
"Essayez un autre mot, ou parcourez la boutique par catégorie.": "جرّب كلمة أخرى، أو تصفح المتجر حسب الفئة.",
"Pages de résultats": "صفحات النتائج",
"Rechercher sur le site": "ابحث في الموقع",
"Rechercher…": "ابحث…",
"Programme de formation": "برنامج الدورة",
"Niveau": "المستوى",
"Durée": "المدة",
"Format": "الصيغة",
"À distance": "عن بُعد",
"Le programme détaillé, les dates et le tarif vous sont envoyés par message. Vous décidez ensuite, sans engagement.": "يُرسل إليك البرنامج المفصّل والمواعيد والسعر عبر رسالة. ثم تقرر أنت، بدون أي التزام.",
"Bonjour Fati, je souhaite le programme et le tarif de la formation « %s ».": "مرحباً فاتي، أرغب في برنامج وسعر دورة « %s ».",
"Réponse sous 24 h en semaine.": "الرد خلال 24 ساعة في أيام العمل.",
"Les autres formations": "الدورات الأخرى",
"Bonjour Fati, j'ai une question sur « %s ».": "مرحباً فاتي، لديّ سؤال عن « %s ».",
"Prix communiqué sur demande": "السعر عند الطلب",
"Diminuer la quantité": "إنقاص الكمية",
"Augmenter la quantité": "زيادة الكمية",
"Ajouter au panier": "أضف إلى السلة",
"Ajouté": "أُضيف",
"Voir le panier": "عرض السلة",
"Poser une question sur ce produit": "اطرح سؤالاً عن هذا المنتج",
"Commande par WhatsApp : on confirme ensemble la disponibilité, les frais de port réels et le total avant l'envoi.": "الطلب عبر واتساب: نؤكد معاً التوفر ومصاريف الشحن الفعلية والمجموع قبل الإرسال.",
"Nos engagements": "التزاماتنا",
"Composition et conseils d'utilisation": "التركيبة وطريقة الاستعمال",
"Composition": "التركيبة",
"Conseils d'utilisation": "طريقة الاستعمال",
"Ce que c'est": "ما هو",
"Autres produits — %s": "منتجات أخرى — %s",
"Rappel du produit": "تذكير بالمنتج",
"Rechercher dans cette catégorie": "ابحث في هذه الفئة",
"Aucun produit dans cette catégorie pour le moment.": "لا توجد منتجات في هذه الفئة حالياً.",
"Voir tout le catalogue": "عرض الكتالوج كاملاً",
"Expédié par Fati": "تشحنه فاتي",
"Vos produits partent de son propre stock.": "منتجاتك تُشحن من مخزونها الخاص.",
"Total confirmé avant paiement": "المجموع يُؤكَّد قبل الدفع",
"Disponibilité et frais de port réels, annoncés avant.": "التوفر ومصاريف الشحن الفعلية تُعلن مسبقاً.",
"Une vraie réponse": "رد حقيقي",
"Fati répond elle-même, sous 24 h en semaine.": "فاتي تجيب بنفسها، خلال 24 ساعة في أيام العمل.",
"Mon panier": "سلتي",
"Fermer le panier": "إغلاق السلة",
"Votre panier est vide.": "سلتك فارغة.",
"Total indicatif": "المجموع الاسترشادي",
"Votre commande part en message à Fati : elle confirme la disponibilité, les frais de port et le total.": "يُرسل طلبك في رسالة إلى فاتي: تؤكد التوفر ومصاريف الشحن والمجموع.",
"Ajouté au panier": "أُضيف إلى السلة",
"Filtrer par catégorie": "تصفية حسب الفئة",
"Catégories de produits": "فئات المنتجات",
"Visuel à venir": "الصورة قريباً",
"Sur demande": "عند الطلب",
"Ajouter %s au panier": "أضف %s إلى السلة",
"Ajouter": "أضف",
"Fati, créatrice de Fatichanelya": "فاتي، مؤسِّسة Fatichanelya",
"Photo de profil de Fati": "صورة فاتي",
"Suivre Fati sur TikTok": "تابع فاتي على تيك توك",
"Nos catégories": "فئاتنا",
"Trouvez ce qu'il vous *faut*.": "اعثر على *ما تحتاجه*.",
"%d produit": ["%d منتج", "منتج واحد", "منتجان", "%d منتجات", "%d منتجاً", "%d منتج"],
"Questions fréquentes": "الأسئلة الشائعة",
"Tout ce qu'il *faut savoir*.": "كل ما *يجب معرفته*.",
"Portrait de Fati, créatrice de %s": "صورة فاتي، مؤسِّسة %s",
"Vous remplissez votre panier": "تملأ سلتك",
"Ajoutez les produits qui vous intéressent, sans engagement.": "أضف المنتجات التي تهمك، بدون أي التزام.",
"On confirme ensemble par message": "نؤكد معاً عبر رسالة",
"Disponibilité, frais de port réels et total, avant tout paiement.": "التوفر ومصاريف الشحن الفعلية والمجموع، قبل أي دفع.",
"Vous recevez chez vous": "تستلم في بيتك",
"Fati expédie depuis son propre stock, en Espagne.": "تشحن فاتي من مخزونها الخاص في إسبانيا.",
"Comment ça se passe": "كيف يتم الأمر",
"Rien à payer sur le site : tout se règle avec Fati, après confirmation.": "لا شيء يُدفع على الموقع: كل شيء يُسوَّى مع فاتي بعد التأكيد.",
"Je ne recrute personne.": "لا أقوم بتجنيد أحد.",
"Je ne promets aucun résultat de santé.": "لا أعد بأي نتيجة صحية.",
"Je n'affiche aucun avis que je n'ai pas reçu.": "لا أعرض أي رأي لم أتلقَّه.",
"Je ne mets pas de compte à rebours.": "لا أضع عدّاً تنازلياً.",
"Du conseil *avant la pression d'acheter*.": "نصيحة *قبل أي ضغط للشراء*.",
"Bonjour Fati, j'hésite entre plusieurs produits, pouvez-vous m'orienter ?": "مرحباً فاتي، أتردد بين عدة منتجات، هل يمكنك توجيهي؟",
"Une question avant de choisir ?": "لديك سؤال قبل الاختيار؟",
"Poser une question": "اطرح سؤالاً",
"Livraison et retours": "التوصيل والإرجاع",
"Produits précédents": "المنتجات السابقة",
"Produits suivants": "المنتجات التالية",
"Sélection de produits, liste défilante": "تشكيلة المنتجات، قائمة قابلة للتمرير",
"Voir les %d produits": "عرض المنتجات (%d)",
"Formations à distance": "دورات تدريبية عن بُعد",
"Toutes les formations": "كل الدورات",
"Programme précédent": "البرنامج السابق",
"Programme suivant": "البرنامج التالي",
"Programmes de formation": "برامج التدريب",
# mu-plugin, côté public
"Entrepreneure, créatrice et mentore": "رائدة أعمال، صانعة محتوى ومرشدة",
"Produits": "المنتجات",
"Catégories de produit": "فئات المنتجات",
}

def lire_pot(chemin):
    s = chemin.read_text(encoding='utf-8')
    entrees = []
    for bloc in s.split('\n\n'):
        m = re.search(r'^msgid "(.*)"$', bloc, re.M)
        if not m or m.group(1) == '': continue
        ctx = re.search(r'^msgctxt "(.*)"$', bloc, re.M)
        pl = re.search(r'^msgid_plural "(.*)"$', bloc, re.M)
        comm = '\n'.join(l for l in bloc.splitlines() if l.startswith('#'))
        entrees.append((ctx.group(1) if ctx else None, m.group(1), pl.group(1) if pl else None, comm))
    return entrees

def de(s):  # décoder les échappements du PO
    return s.encode().decode('unicode_escape').encode('latin-1').decode('utf-8') if '\\' in s else s
def enc(s):
    return s.replace('\\', '\\\\').replace('"', '\\"').replace('\n', '\\n')

vus = set(); out = []; manquantes = []
for pot in (S / 'theme.pot', S / 'core.pot'):
    for ctx, mid, pl, comm in lire_pot(pot):
        cle = (ctx, mid)
        if cle in vus: continue
        vus.add(cle)
        src = de(mid)
        tr = T.get(src)
        if tr is None:
            manquantes.append(src); continue
        bloc = [comm] if comm else []
        if ctx: bloc.append(f'msgctxt "{ctx}"')
        bloc.append(f'msgid "{mid}"')
        if pl:
            bloc.append(f'msgid_plural "{pl}"')
            formes = tr if isinstance(tr, list) else [tr] * 6
            for i, f in enumerate(formes): bloc.append(f'msgstr[{i}] "{enc(f)}"')
        else:
            bloc.append(f'msgstr "{enc(tr if isinstance(tr, str) else tr[0])}"')
        out.append('\n'.join(bloc))

entete = '''msgid ""
msgstr ""
"Project-Id-Version: Fatichanelya 1.7.1\\n"
"Language: ar\\n"
"MIME-Version: 1.0\\n"
"Content-Type: text/plain; charset=UTF-8\\n"
"Content-Transfer-Encoding: 8bit\\n"
"Plural-Forms: nplurals=6; plural=(n==0 ? 0 : n==1 ? 1 : n==2 ? 2 : n%100>=3 && n%100<=10 ? 3 : n%100>=11 ? 4 : 5);\\n"
"X-Generator: tools/traductions/ar (chaines.py)\\n"
'''
dest = F / 'wp-content/themes/fatichanelya/languages/ar.po'
dest.parent.mkdir(exist_ok=True)
dest.write_text(entete + '\n' + '\n\n'.join(out) + '\n', encoding='utf-8')
print('traduites', len(out), '| non traduites (admin ou en-tête) :', len(manquantes))
for m in manquantes: print('   -', m[:70])
