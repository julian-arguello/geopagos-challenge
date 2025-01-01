<?php

namespace Tests\Unit\Helpers;

use App\Models\Player;
use App\Services\TournamentService;

class TournamentServiceTestHelper extends TournamentService
{
    public static function normalize($value, $minValue, $maxValue)
    {
        return parent::normalize($value, $minValue, $maxValue);
    }

    public static function assignLuck($players)
    {
        return parent::assignLuck($players);
    }

    public static function isPowerOfTwo(int $n)
    {
        return parent::isPowerOfTwo($n);
    }

    public static function scoreCalculator(Player $player, int $genderId)
    {
        return parent::scoreCalculator($player, $genderId);
    }

    public function match(Player $playerOne, Player $playerTwo, int $round, int $genderId)
    {
        return parent::match($playerOne, $playerTwo, $round, $genderId);
    }
}
