# RestAPI
 Simple CRUD RestAPI for Movies written using ( Symfony4) 
 
  The arguments used to define a movie are :
  
 * An ID ( Integer ) which is auto generated
 * A Title ( Text ) that requires user imput 
 * A Description ( Text ) that also requires user imput 

 * To perfom CRUD methods consider using Postman REST CLIENT

Routs : 

  bin/console debug:router

 
 |   Name     |  Method  | Scheme |  Host  |  Path
 | ---------- | -------- | ------ | ------ | ---------------
 | get_posts  |   GET    |  ANY   |   ANY  | /api/posts       |
 | get_post   |   GET    |  ANY   |   ANY  |  /api/posts/{id} |
 | post_post  |   POST   |  ANY   |   ANY  |   /api/posts     |
 | patch_post |  PATCH   |  ANY   |   ANY  |  /api/posts/{id} |
 |delete_post |  DELETE  |  ANY   |    ANY |  /api/posts/{id} |
 
 
To install it add the following bundles :

* composer require swiftmailer-bundle
* composer require symfony/translation
* composer require validator
* composer require --dev symfony/maker-bundle
* composer require friendsofsymfony/rest-bundle

 For documentation add :
* composer require nelmio/api-doc-bundle

 If you face any problem with postman Rest client consider using **Curl** <Enter>
* composer requires ext-curl

To add authentifcation layer consider using JWT system :
* composer require lexik/jwt-authentication-bundle
( requires creating a user in your data base ) 
 
To add token refrech system add : 
* composer require gesdinet/jwt-refresh-token-bundle :  

# ========> **Enjoy** <========
