<?php

// Reponsable de charger les autres fichier et configurer le projet
// Attention, on call pas la method run de l'instance App. Démarrer l'appli c'est le taff de index.php 


declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

use Framework\App;
use App\Config\Paths;

use function App\Config\registerRoutes;

$app = new App(Paths::SOURCE . "App/container-definitions.php");

registerRoutes($app);

return $app;
