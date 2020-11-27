<?php

namespace App\HelperFunctions;

use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Functions extends AbstractController
{


    /**
     * générer une clé de securite pour le mot de passe oublier
     * 
     */
    public function key_generator(int $i)
    {

        $letter = "abcdefghijklmnopqrstuvwxuz";
        $number = "0123456789";
        $new_lettre = $letter . $number;
        return  substr(str_shuffle(str_repeat($new_lettre, 60)), 0, $i);
    }

    /**
     * récuperer les ville de france d'un fichier csv
     * 
     */
    public function cities_from_csv()
    {

        // dump($ArticleRipo->findLastArticle());
        // exit();
        $finder = new Finder();

        $file = $finder->files()->in('../var')->name('cities.csv');
        foreach ($file as $files) {
            $paths =  $files->getRealPath();
        }
        $newFile = file($paths);


        for ($i = 1; $i <= 2000; $i++) {

            $newVille[] = explode(',', $newFile[$i])[4];
        }
        return $newVille;
    }
}
