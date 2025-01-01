<?php

namespace App\Http\Requests;

use App\Models\FemalePlayer;
use App\Models\MalePlayer;
use App\Models\Player;
use App\Services\TournamentService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreTournamentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'tournament_gender' => 'required|string|in:MALE,FEMALE',

            'players' => [
                'required',
                'array',
                'min:2',
                function ($attribute, $value, $fail) {
                    if (!TournamentService::isPowerOfTwo(count($value))) {
                        $fail('La cantidad de jugadores debe ser una potencia de 2 (por ejemplo: 2, 4, 8, 16).');
                    }
                }
            ],

            'players.*.name' => 'required|string',
            'players.*.skill_level' => 'required|integer|min:' . Player::MIN_SKILL_LEVEL . '|max:' . Player::MAX_SKILL_LEVEL,
        ];

        if ($this->input('tournament_gender') === 'MALE') {
            $rules = array_merge($rules, [
                'players.*.stength' => 'required|integer|min:' . MalePlayer::MIN_STENGTH . '|max:' . MalePlayer::MAX_STENGTH,
                'players.*.movement_speed' => 'required|integer|min:' . MalePlayer::MIN_MOVEMENT_SPEED . '|max:' . MalePlayer::MAX_MOVEMENT_SPEED,
                'players.*.reaction_time' => 'prohibited',
            ]);
        } elseif ($this->input('tournament_gender') === 'FEMALE') {
            $rules = array_merge($rules, [
                'players.*.reaction_time' => 'required|integer|min:' . FemalePlayer::MAX_REACTION_TIME . '|max:' . FemalePlayer::MIN_REACTION_TIME,
                'players.*.stength' => 'prohibited',
                'players.*.movement_speed' => 'prohibited',
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'tournament_gender.required' => 'El género del torneo es obligatorio.',
            'tournament_gender.in' => 'El género debe ser MALE, FEMALE o OTHER.',
            'players.required' => 'La lista de jugadores es obligatoria.',
            'players.min' => 'Debe haber al menos 2 jugadores.',
            'players.max' => 'No puede haber más de 16 jugadores.',
            'players.*.name.required' => 'El nombre del jugador es obligatorio.',
            'players.*.skill_level.required' => 'El nivel de habilidad del jugador es obligatorio.',
            'players.*.stength.required' => 'La fuerza del jugador es obligatoria.',
            'players.*.movement_speed.required' => 'La velocidad de movimiento del jugador es obligatoria.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422)
        );
    }
}
