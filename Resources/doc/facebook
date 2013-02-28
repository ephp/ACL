Configurare Facebook
====================

## Come attivare il pulsanti per l'accesso a Facebook

Inserire il seguente codice nel twig

``` <div style="float: right;">
    {{ facebook_initialize({'xfbml': true, 'fbAsyncInit': 'onFbInit();'}) }}
    {{ facebook_login_button({'label': 'Accedi con Facebook', 'autologoutlink': true, 'showFaces': true, 'registrationUrl': path('fb_reg')}) }}
</div>
```
è importante il parametro 'registrationUrl' che punta alla action di 
registrazione di EphpACLBundle

## Il Javascript

Il Javascript di gestione è dinamico. Non è possibile integrarlo in assetic, 
poiché dipende da vari fattori, tra cui anche lo stato dell'utente.
Per includere il javascript inserire nel twig questa riga:

``` <script src="{{ path('fb_js') }}"></script>
```

Le funzioni a disposizione sono:

### goLogIn(data)
Verifica l'utente

### onFbInit()
Funzione per l'accesso a Facebook in modo asincrono

### fb_friends(user_id)
Recupero l'elenco degli amici occorre includere il twig
{% include "EphpACLBundle:Facebook:fbHtml.html.twig" %}
per il corretto funzionamento della funzione js

### fb_pictures(user_id)
Recupera le ultime foto in cui l'utente è stato taggato
{% include "EphpACLBundle:Facebook:fbHtml.html.twig" %}
per il corretto funzionamento della funzione js

### sendMessage(user_id)
Invia un messaggio nella chat di Facebook

### sendInviteJs(id)
Invia un invito a visitare il sito
