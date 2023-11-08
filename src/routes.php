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

  
  ['GET', '/joeuur/{id}', 'joueur@index'],
  ['GET', '/joueur/addContrib/{idGamer}/{idCadavre}', 'joueur@addContribution'],
  /*   ['GET', '/etudiant/{id}', 'etudiant@index'],
  ['GET', '/etudiant/delete/{idEntreprise}/{idEtudiant}', 'etudiant@delete'],
  ['GET', '/etudiant/add/{idEntreprise}/{idEtudiant}', 'etudiant@add'],
  ['GET', '/etudiant/{id:\d+}/profil', 'etudiant@profil'],

  ['GET', '/responsable/{id:\d+}/edit', 'responsable@edit'],
  ['POST', '/responsable/{id:\d+}/edit', 'responsable@update'],
  ['GET', '/responsable/{id:\d+}/profil', 'responsable@profil'],
  ['GET', '/responsable/{id}', 'responsable@index'],
  ['GET', '/generate-pdf', 'responsable@generatePDFAction', 'generate_pdf'], */

];