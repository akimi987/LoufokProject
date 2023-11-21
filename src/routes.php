<?php

declare(strict_types=1);
/*
-------------------------------------------------------------------------------
les routes
-------------------------------------------------------------------------------
 */

return [

  ['GET', '/', 'connexion@index'],
  ['POST', '/connexion', 'connexion@index'],
  ['GET', '/disconnect', 'connexion@disconnect'],

  ['GET', '/inscription', 'inscription@index'],
  ['POST', '/inscription/register', 'inscription@register'],


  ['GET', '/administrateur/{id}', 'administrateur@index'],
  ['POST', '/administrateur/demarrerNouveauCadavre', 'administrateur@demarrerCadavre'],






  //['GET', '/joueur/{idGamer}', 'joueur@index'],
  //['GET', '/joueur/addContribution/{idGamer}/{idCadavre}', 'joueur@addContribution'],

  // ['GET', '/administrateur/{id}', 'administrateur@index'],
  //['POST', '/administrateur/create', 'administrateur@createCadavreSubmit'],
];
