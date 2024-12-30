<?php

namespace App\Services;

use App\Models\FemalePlayer;
use App\Models\Gender;
use App\Models\MalePlayer;
use App\Models\Player;
use App\Models\Tournament;
use App\Models\TournamentStatus;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Esta clase manjea la logica que requiere el torneo. 
 */
class TournamentService
{

    const LUCK_FACTOR = 1.15;

    protected $tournament;
    protected $genderId;

    /**
     * Runs the tournament, handling the rounds and updating the tournament status.
     *
     * @param Tournament $tournament The tournament to be executed.
     * @return Player The winning player of the tournament.
     * @throws \Throwable Throws an exception if any error occurs during execution.
     */
    public function run(Tournament $tournament)
    {
        self::isPlayable($tournament);
        $this->tournament = $tournament;
        $this->genderId = $tournament->gender->id;
        $players = $tournament->players;

        DB::beginTransaction();
        try {

            $playerWinner = $this->round($players);
            $this->tournament->status_id = TournamentStatus::FINISHED;
            $this->tournament->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception("Error al ejecutar el torneo: " . $th->getMessage(), 0, $th);
        }

        return $playerWinner;
    }

    /**
     * Executes a round of matches between players, dividing them into two teams
     * and calling itself recursively until a single winner is determined.
     *
     * @param \Illuminate\Support\Collection $players Collection of players to compete.
     * @return Player The winner of the round.
     */
    protected function round($players)
    {
        $newPlayers = collect();
        $half = $players->count() / 2;
        $teamA = $players->take($half)->values();
        $teamB = $players->skip($half)->values();
        $index = 0;
        $round = 1;

        while ($index < $half) {
            $playerWinner = $this->match($teamA[$index], $teamB[$index], $round);
            $newPlayers->push($playerWinner);
            $index++;
            $round++;
        }

        if ($newPlayers->count() > 1) {
            $playerWinner = $this->round($newPlayers);
        }

        $this->setWinner($playerWinner, $half);
        return $playerWinner;
    }

    /**
     * Conducts a match between two players, 
     * calculating their scores and determining the winner and loser,
     * taking the luck factor into account.
     *
     * @param Player $playerOne The first player.
     * @param Player $playerTwo The second player.
     * @param int $round The round number in which the match takes place.
     * @return Player The winning player.
     */
    protected function match(Player $playerOne, Player $playerTwo, int $round)
    {
        $luckyPlayer = self::assignLuck([$playerOne, $playerTwo]);
        $scorePlayerOne = $this->scoreCalculator($playerOne, $this->genderId);
        $scorePlayerTwo = $this->scoreCalculator($playerTwo, $this->genderId);

        $scorePlayerOne = ($playerOne === $luckyPlayer) ? $scorePlayerOne * self::LUCK_FACTOR : $scorePlayerOne;
        $scorePlayerTwo = ($playerTwo === $luckyPlayer) ? $scorePlayerTwo * self::LUCK_FACTOR : $scorePlayerTwo;

        if ($scorePlayerOne === $scorePlayerTwo) {
            $playerWinner = ($playerOne === $luckyPlayer) ? $playerOne : $playerTwo;
            $playerLoser = ($playerTwo === $luckyPlayer) ? $playerTwo : $playerOne;
        } else {
            $playerWinner = ($scorePlayerOne > $scorePlayerTwo) ? $playerOne : $playerTwo;
            $playerLoser = ($scorePlayerOne > $scorePlayerTwo) ? $playerTwo : $playerOne;
        }

        $this->setLastOpponentAndLastRound($playerWinner, $playerLoser, $round);

        return $playerWinner;
    }

    /**
     * Sets the last opponent and the last round for a player.
     *
     * @param Player $playerWinner The player who won (last opponent).
     * @param Player $playerLoser The player whose data is being updated.
     * @param int $lastRound The number of the last round in which the player competed.
     * @return void
     */
    protected function setLastOpponentAndLastRound(Player $playerWinner, Player  $playerLoser, int $LastRound)
    {
        $pivot = $playerLoser->tournaments->find($this->tournament->id)?->pivot;
        $pivot->last_opponent_id = $playerWinner->id;
        $pivot->last_round = $LastRound;
        $pivot->save();
    }

    /**
     * Marks a player as the winner and updates their round in the database.
     *
     * @param Player $playerWinner The winning player.
     * @param int $round The round number in which the player won.
     * @return void
     */
    protected function setWinner(Player $playerWinner, int $round)
    {
        $pivot = $playerWinner->tournaments->find($this->tournament->id)?->pivot;
        $pivot->is_winner = true;
        $pivot->last_round = $round;
        $pivot->save();
    }

    /**
     * Calculates a player's score based on their skill level, 
     * physical attributes, and gender.
     * 
     * For male players, strength and movement speed are included. 
     * For female players, reaction time is considered.
     *
     * @param Player $player The player to evaluate.
     * @param int $genderId The player's gender ID (male or female).
     * @return float The calculated score.
     */
    protected static function scoreCalculator(Player $player, int $genderId)
    {
        $normalizedSkill  = self::normalize($player->skill_level, Player::MIN_SKILL_LEVEL, Player::MAX_SKILL_LEVEL);

        if ($genderId === Gender::MALE_ID) {

            $normalizedStength = self::normalize($player->malePlayer->stength, MalePlayer::MIN_STENGTH, MalePlayer::MAX_STENGTH);
            $normalizedSpeed = self::normalize($player->malePlayer->movement_speed, MalePlayer::MIN_MOVEMENT_SPEED, MalePlayer::MAX_MOVEMENT_SPEED);
            $score = ($normalizedSkill + $normalizedStength + $normalizedSpeed) * 100;
        } else {
            $normalizedReaction = self::normalize($player->femalePlayer->reaction_time, FemalePlayer::MIN_REACTION_TIME, FemalePlayer::MAX_REACTION_TIME);
            $score = ($normalizedSkill + $normalizedReaction) * 100;
        }

        return $score;
    }

    /**
     * Randomly assigns luck to a player by selecting one from an array of players.
     * 
     * @param array $players An array of `Player` objects.
     * @return Player The randomly selected player.
     */
    protected static function assignLuck(array $players)
    {
        $luck = random_int(0, count($players) - 1);
        return $players[$luck];
    }

    /**
     * Normalizes a value to a range of 0 to 1, given an input value range.
     *
     * @param float|int $value The value to normalize.
     * @param float|int $minValue The minimum value of the input range.
     * @param float|int $maxValue The maximum value of the input range.
     * @return float The normalized value in the range of 0 to 1.
     */
    protected static function normalize($value, $minValue, $maxValue)
    {
        return (($value - $minValue) / ($maxValue - $minValue));
    }

    /**
     * Checks if a number is a power of 2.
     *
     * @param int $n The number to check.
     * @return bool `true` if the number is a power of 2, otherwise `false`.
     */
    protected static function isPowerOfTwo(int $n)
    {
        return ($n > 0) && (($n & ($n - 1)) == 0);
    }

    /**
     * Verifies that the tournament is ready to play, 
     * meeting the following requirements:
     * 
     * - The tournament's status must be "playable".
     * - There must be at least 2 players.
     * - The number of players must be a power of 2.
     *
     * @param Tournament $tournament The tournament to check.
     * @return void
     */
    protected static function isPlayable(Tournament $tournament)
    {
        if ($tournament->status->id != TournamentStatus::PLAYABLE) {
            throw new Exception("El estado del torneo debe ser jugable");
        }

        if ($tournament->players->count() <= 1) {
            throw new Exception("El torneo debe tener una cantidad mínima de 2 jugadores");
        }

        if (!self::isPowerOfTwo($tournament->players->count())) {
            throw new Exception("El torneo debe tener una cantidad de jugadores equivalente a una potencia de 2");
        }
    }
}
