<?php

namespace Tests\Feature;

use App\Models\FemalePlayer;
use App\Models\Gender;
use App\Models\MalePlayer;
use App\Models\Player;
use Tests\TestCase;
use Tests\Unit\Helpers\TournamentServiceTestHelper;
use Mockery;

class TournamentServiceTest extends TestCase
{

    public function testNormalize()
    {
        $value = 50;
        $minValue = 0;
        $maxValue = 100;

        $result = TournamentServiceTestHelper::normalize($value, $minValue, $maxValue);

        $this->assertEquals(0.5, $result);
    }

    public function testAssignLuck()
    {
        $playerOne = Mockery::mock(Player::class);
        $playerTwo = Mockery::mock(Player::class);

        $luckyPlayer = TournamentServiceTestHelper::assignLuck([$playerOne, $playerTwo]);

        $this->assertContains($luckyPlayer, [$playerOne, $playerTwo]);
    }

    public function testIsPowerOfTwo()
    {
        $this->assertTrue(TournamentServiceTestHelper::isPowerOfTwo(4));
        $this->assertTrue(TournamentServiceTestHelper::isPowerOfTwo(16));

        $this->assertFalse(TournamentServiceTestHelper::isPowerOfTwo(5));
        $this->assertFalse(TournamentServiceTestHelper::isPowerOfTwo(7));
    }

    public function testScoreCalculatorMalePlayer()
    {
        $player = $this->createMalePlayer(75, 5, 80);

        $genderId = Gender::MALE_ID;
        $score = TournamentServiceTestHelper::scoreCalculator($player, $genderId);

        $normalizedSkill = (80 - Player::MIN_SKILL_LEVEL) / (Player::MAX_SKILL_LEVEL - Player::MIN_SKILL_LEVEL);
        $normalizedStength = (75 - MalePlayer::MIN_STENGTH) / (MalePlayer::MAX_STENGTH - MalePlayer::MIN_STENGTH);
        $normalizedSpeed = (5 - MalePlayer::MIN_MOVEMENT_SPEED) / (MalePlayer::MAX_MOVEMENT_SPEED - MalePlayer::MIN_MOVEMENT_SPEED);

        $expectedScore = ($normalizedSkill + $normalizedStength + $normalizedSpeed) * 100;

        $this->assertEquals($expectedScore, $score);
    }

    public function testScoreCalculatorFemalePlayer()
    {
        $player = $this->createFemalePlayer(1.2, 90);

        $genderId = Gender::FEMALE_ID;
        $score = TournamentServiceTestHelper::scoreCalculator($player, $genderId);

        $normalizedSkill = (90 - Player::MIN_SKILL_LEVEL) / (Player::MAX_SKILL_LEVEL - Player::MIN_SKILL_LEVEL);
        $normalizedReaction = (1.2 - FemalePlayer::MIN_REACTION_TIME) / (FemalePlayer::MAX_REACTION_TIME - FemalePlayer::MIN_REACTION_TIME);

        $expectedScore = ($normalizedSkill + $normalizedReaction) * 100;

        $this->assertEquals($expectedScore, $score);
    }

    private function createMalePlayer($stength, $speed, $skill)
    {
        $player = new Player();
        $malePlayer = new MalePlayer();
        $malePlayer->stength = $stength;
        $malePlayer->movement_speed = $speed;
        $player->malePlayer = $malePlayer;
        $player->skill_level = $skill;

        return $player;
    }

    private function createFemalePlayer($reactionTime, $skill)
    {
        $player = new Player();
        $femalePlayer = new FemalePlayer();
        $femalePlayer->reaction_time = $reactionTime;
        $player->femalePlayer = $femalePlayer;
        $player->skill_level = $skill;

        return $player;
    }
}
