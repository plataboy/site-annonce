<?php

namespace App\HelperFunctions;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Functions extends AbstractController
{




    public function key_generator(int $i)
    {

        $letter = "abcdefghijklmnopqrstuvwxuz";
        $number = "0123456789";
        $new_lettre = $letter . $number;
        return  substr(str_shuffle(str_repeat($new_lettre, 60)), 0, $i);
    }
}
