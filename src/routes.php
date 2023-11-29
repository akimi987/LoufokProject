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
  ['POST', '/administrateur/demarrerCadavre', 'administrateur@demarrerCadavre'],
];
