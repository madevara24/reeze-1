<?php

namespace App\Helpers;

use App\Model\Card;

class CardHelper
{
    public static function randomNumber()
    {
        $randomNumber = mt_rand(100000000, 999999999);

        return CardHelper::checkUnique($randomNumber);
    }

    private static function checkUnique($randomNumber)
    {
        $card = Card::where('id', $randomNumber)->first();

        if ($card !== null) {
            CardHelper::randomNumber($randomNumber);
        }

        return $randomNumber;
    }
}
