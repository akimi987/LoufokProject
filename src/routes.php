<?php

declare (strict_types = 1);
/*
-------------------------------------------------------------------------------
les routes
-------------------------------------------------------------------------------
 */

return [

  // accueil et affichage pour les avatars
  ['GET', '/', 'avatar@index'],
  ['GET', '/list', 'avatar@index'],

  // afficher le formulaire d'ajouter un nouvel avatar
  ['GET', '/avatar/ajouter', 'avatar@add'],
  // enregistrer les données soumises d'un nouvel avatar
  ['POST', '/avatar/ajouter', 'avatar@save'],

  // afficher le formulaire d'édition un nouvel existant
  // à compléter ...

  // enregistrer les données soumises d'un avatar existant
  // à compléter ...

  // effacer un avatar
  // à compléter ...
  ['GET', '/avatar/effacer/{id:\d+}', 'avatar@delete'],

];
