Usare EphpACLBundle
===================

EphpACLBundle è un bundle che si basa su:
* FOSUserBundle
* FOSFacebookBundle
Contiene l'integrazione dei bundles FOS e gli strumenti base per poter
effettuare le prime operazioni.
Si consiglia di leggere le documentazioni dei bundles succitati per usare al
meglio tutte le potenzialità

## Prerequisiti

Il bundle è disegnato per Symfony 2.1+.

### Translations

Se si desidera utilizzare i testi predefiniti forniti in questo bundle, è 
necessario assicurarsi di avere traduttore attivato nel tuo config.

``` yaml
# app/config/config.yml

framework:
    translator: ~
```

Per maggiori informazioni leggere la [Documentazione di Symfony](http://symfony.com/doc/current/book/translation.html).

## Installazione

L'installazione è veloce:

1. Scarica EphpACLBundle da composer
2. Abilitare il Bundle
3. La classe User
4. Configura il tuo security.yml
5. Configura EphpACLBundle
6. Aggiungi EphpACLBundle al routing
7. Aggiorna lo schema database

### 1: Scarica EphpACLBundle da composer

Aggiungi EphpACLBundle in composer.json:

```js
{
    "require": {
        "ephp/acl": "*"
    }
}
```

Esegui il comando:

``` bash
$ php composer.phar update ephp/acl
```

Composer installerà il bundle nel tuo progetto nella directory `ephp/acl` e i
bundle di FOS all'interno della directory `vendor/friendsofsymfony`.

### 2: Abilitare il Bundle

Abilita i bundle nel kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FOS\UserBundle\FOSUserBundle(),
        new FOS\FacebookBundle\FOSFacebookBundle(),
        new Ephp\ACLBundle\EphpACLBundle(),
    );
}
```

### 3: Configura il tuo security.yml

Fare riferimento alla documentazione di FOSUserBundle

Per quanto riguarda la creazione della classe User, il bundle offre già la
classe base /Ephp/ACLBundle/Entity/User. È però possibile usare una propria
classe User come riportato nell'esempio

``` php
<?php
// src/Acme/UserBundle/Entity/User.php

namespace Acme\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Acme\UserBundle\Entity\User
 *
 * @ORM\Table(name="acme_users")
 */
class User extends BaseUser {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    function __construct() {
        parent::__construct();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /*
     * Altri campi e metodi
     */

}
```

**Nota:**

> `User` è una parola riservata in SQL, non può essere usata come nome della
> tabella.

**Nota:**
> MongoDB, CouchDB e Propel non sono ancora supportati dal bundle, e
> probabilmente non lo saranno per molto tempo

### 4: Configura il tuo security.yml

Si consiglia di fare riferimento alla documentazione di FOSUserBundle e
FOSFacebookBundle. Ad ogni modo un esempio di configurazione è qui sotto:

``` yaml
# app/config/security.yml
security:
    encoders:
        FOS\UserBundle\Model\UserInterface: sha512

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: ROLE_ADMIN

    providers:
        chainprovider:
            chain:
                providers: [fos_user_bundle, my_fos_facebook_provider]
        fos_user_bundle:
            id: fos_user.user_provider.username
        my_fos_facebook_provider:
            id: my.facebook.user   

    firewalls:
        public:
            pattern:   ^/.*
            fos_facebook:
                app_url:              "http://apps.facebook.com/myproject/"
                server_url:           "http://www.myproject.com/"
                login_path:           /login
                check_path:           /fb/login_check
                default_target_path:  /
                provider:             my_fos_facebook_provider
            logout:                   true
            anonymous:                true
        main:
            pattern:                  ^/
            form_login:
                provider:             fos_user_bundle
                csrf_provider:        form.csrf_provider
            logout:                   true
            anonymous:                true

    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/, role: ROLE_ADMIN }
        - { path: ^/facebook/,           role: [ROLE_FACEBOOK] }
        - { path: ^/.*,                  role: [IS_AUTHENTICATED_ANONYMOUSLY] }
```

### 5: Configura EphpACLBundle

È necessario effettuare la configurazione sia di FOSUserBundle che di
FOSFacebookBundle, per questo siete nuovamente rimandatia alla documentazione
dei bundle succitati.
È comunque necessario configurare alcuni parametri di EphpACLBundle

Aggiungete le seguenti righe in `config.yml` (sono presenti anche i dati base
dei bundle FOS).

``` yaml
# app/config/config.yml
# FOS User Bundle
fos_user:
    db_driver: orm
    firewall_name: main
    user_class: Ephp\ACLBundle\Entity\User
    
# FOS Facebook Bundle
fos_facebook:
    alias:  facebook
    app_id: 0123456789
    secret: 01234567890123456789
    cookie: true
    permissions: [email, read_friendlists, user_birthday, user_photos, user_about_me, user_activities, user_location, publish_stream]

# Ephp ACL Bundle
ephp_acl:
    facebook:
        app_id: 0123456789
        app_secret: 01234567890123456789
        app_name: MyProject
        app_url: http://www.myproject.com
        app_description: Questo è il mio progetto
```

### 6: Aggiungi EphpACLBundle al routing

È neccessario aggiungere i route necessari sia a FOSUserBundle che a
EphpACLBundle al file di routing.

In YAML:

``` yaml
# app/config/routing.yml
fos_user_security:
    resource: "@FOSUserBundle/Resources/config/routing/security.xml"

fos_user_profile:
    resource: "@FOSUserBundle/Resources/config/routing/profile.xml"
    prefix: /profile

fos_user_register:
    resource: "@FOSUserBundle/Resources/config/routing/registration.xml"
    prefix: /register

fos_user_resetting:
    resource: "@FOSUserBundle/Resources/config/routing/resetting.xml"
    prefix: /resetting

fos_user_change_password:
    resource: "@FOSUserBundle/Resources/config/routing/change_password.xml"
    prefix: /profile

ephp_acl_bundle:
    resource: "@EphpACLBundle/Controller/"
    type:     annotation
    prefix:   /

```

### Step 7: Update your database schema

Ora che il bundle è configurato, l'ultima cosa che devi fare è aggiornare lo
schema del database perché è stata aggiunta una nuova entity, la classe `User`
di cui abbiamo parlato nel passaggio 4.

Lancia il comando

``` bash
$ php app/console doctrine:schema:update --force
```

### Per saperne di più..

TODO

