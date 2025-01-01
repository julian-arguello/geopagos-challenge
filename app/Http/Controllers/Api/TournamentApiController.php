<?php

namespace App\Http\Controllers\Api;

use App\Models\Gender;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreTournamentRequest;
use App\Models\FemalePlayer;
use App\Models\MalePlayer;
use App\Models\TournamentStatus;
use App\Services\TournamentService;
use Illuminate\Support\Facades\DB;


/**
 * @OA\Tag(
 *     name="Tournaments",
 *     description="Endpoints relacionados con Torneos"
 * )
 */
class TournamentApiController extends ApiController
{

    protected $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {
        $this->tournamentService  = $tournamentService;
    }

    /**
     * @OA\Get(
     *     path="/tournaments",
     *     summary="Obtener lista de torneos",
     *     description="Devuelve la lista de torneos con sus géneros y estados asociados. 
     *                  Puedes filtrar los torneos por género utilizando el parámetro de consulta `gender` 
     *                  con valores `MALE` o `FEMALE`.",
     *     tags={"Tournaments"},
     *     @OA\Parameter(
     *         name="gender",
     *         in="query",
     *         description="Filtro opcional por género de los torneos (MALE o FEMALE)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"MALE", "FEMALE"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de torneos encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success", description="Estado de la operación"),
     *             @OA\Property(property="message", type="string", example="ok", description="Mensaje de la operación"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     oneOf={
     *                         @OA\Schema(ref="#/components/schemas/Tournament"),
     *                         @OA\Schema(ref="#/components/schemas/Tournament")
     *                     }
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'gender' => 'nullable|string|in:MALE,FEMALE',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'El valor de gender no es válido. Se espera "MALE" o "FEMALE".',
                422
            );
        }

        $genderKey = $request->query('gender');
        $gender = $genderKey
            ? [Gender::getGenderOptions()[$genderKey]]
            : [Gender::MALE_ID, Gender::FEMALE_ID];

        $tournaments = Tournament::with('status', 'gender')
            ->whereIn('gender_id', $gender)
            ->get();

        return $this->successResponse(
            $tournaments
        );
    }

    /**
     * @OA\Get(
     *     path="/tournaments/{id}",
     *     summary="Obtener detalles de un torneo",
     *     description="Devuelve los detalles de un torneo por ID, incluyendo género, estado y jugadores",
     *     tags={"Tournaments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del torneo a obtener",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del torneo encontrados",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success", description="Estado de la operación"),
     *             @OA\Property(property="message", type="string", example="ok", description="Mensaje de la operación"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Detalles del torneo",
     *                 @OA\Property(property="id", type="integer", example=1, description="ID único del torneo"),
     *                 @OA\Property(property="gender_id", type="integer", example=1, description="ID del género asociado al torneo"),
     *                 @OA\Property(property="status_id", type="integer", example=3, description="ID del estado del torneo"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T16:31:13.000000Z", description="Fecha de creación del torneo"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T16:31:13.000000Z", description="Fecha de última actualización del torneo"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null, description="Fecha de eliminación del torneo"),
     *                 @OA\Property(property="status", ref="#/components/schemas/TournamentStatus", description="Estado del torneo"),
     *                 @OA\Property(property="gender", ref="#/components/schemas/Gender", description="Género del torneo"),
     *                 @OA\Property(
     *                     property="players",
     *                     type="array",
     *                     description="Lista de jugadores asociados al torneo",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1, description="ID único del jugador"),
     *                         @OA\Property(property="name", type="string", example="John Doe", description="Nombre del jugador"),
     *                         @OA\Property(property="skill_level", type="integer", example=85, description="Nivel de habilidad del jugador"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T16:31:13.000000Z", description="Fecha de creación"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T16:31:13.000000Z", description="Fecha de última actualización"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null, description="Fecha de eliminación"),
     *                         @OA\Property(
     *                             property="pivot",
     *                             type="object",
     *                             description="Información adicional del jugador en el torneo",
     *                             @OA\Property(property="tournament_id", type="integer", example=1, description="ID del torneo asociado"),
     *                             @OA\Property(property="player_id", type="integer", example=1, description="ID del jugador asociado"),
     *                             @OA\Property(property="is_winner", type="boolean", example=false, description="Indica si el jugador es el ganador del torneo"),
     *                             @OA\Property(property="last_opponent_id", type="integer", nullable=true, example=null, description="ID del último oponente del jugador"),
     *                             @OA\Property(property="last_round", type="integer", example=0, description="Última ronda alcanzada por el jugador")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Torneo no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error", description="Estado de la operación"),
     *             @OA\Property(property="message", type="string", example="No existe un torneo con el ID proporcionado.", description="Mensaje de error")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $tournament = Tournament::with('status', 'gender', 'players')->find($id);

        if (is_null($tournament)) {
            return $this->errorResponse('No existe un torneo con el ID proporcionado.');
        }

        return $this->successResponse(
            $tournament
        );
    }

    /**
     * @OA\Post(
     *     path="/tournaments",
     *     summary="Crear y jugar un torneo",
     *     description="Crea un torneo con jugadores asignados, inicia el torneo y devuelve los resultados.",
     *     tags={"Tournaments"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para crear un torneo",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"tournament_gender", "players"},
     *                 @OA\Property(
     *                     property="tournament_gender",
     *                     type="string",
     *                     description="Género del torneo ('MALE' o 'FEMALE')"
     *                 ),
     *                 @OA\Property(
     *                     property="players",
     *                     type="array",
     *                     minItems=2,
     *                     description="Lista de jugadores para el torneo",
     *                     @OA\Items(
     *                         type="object",
     *                         required={"name", "skill_level", "stength", "movement_speed"},
     *                         @OA\Property(property="name", type="string", description="Nombre del jugador"),
     *                         @OA\Property(property="skill_level", type="integer", description="Nivel de habilidad del jugador"),
     *                         @OA\Property(property="stength", type="integer", description="Fuerza del jugador"),
     *                         @OA\Property(property="movement_speed", type="integer", description="Velocidad de movimiento del jugador"),
     *                         @OA\Property(property="reaction_time", type="integer", nullable=true, description="Tiempo de reacción (opcional para jugadores femeninos)"),
     *                         @OA\Property(property="hooho", type="integer", nullable=true, description="Atributo adicional opcional")
     *                     )
     *                 )
     *             ),
     *             @OA\Examples(
     *                 example="maleTournament",
     *                 summary="Ejemplo de torneo masculino",
     *                 value={
     *                     "tournament_gender": "MALE",
     *                     "players": {
     *                         {"name": "Lucas Gonzalez", "skill_level": 70, "stength": 230, "movement_speed": 6},
     *                         {"name": "Julián Ramirez", "skill_level": 68, "stength": 216, "movement_speed": 5},
     *                         {"name": "Carlos Ramoz", "skill_level": 78, "stength": 228, "movement_speed": 8},
     *                         {"name": "Juan Mirez", "skill_level": 56, "stength": 210, "movement_speed": 7}
     *                     }
     *                 }
     *             ),
     *             @OA\Examples(
     *                 example="femaleTournament",
     *                 summary="Ejemplo de torneo femenino",
     *                 value={
     *                     "tournament_gender": "FEMALE",
     *                     "players": {
     *                         {"name": "Maria Lopez", "skill_level": 88, "reaction_time": 900},
     *                         {"name": "Ana Saucedo", "skill_level": 72, "reaction_time": 850},
     *                         {"name": "Elina Perez", "skill_level": 68, "reaction_time": 260},
     *                         {"name": "Jessica Bonnefon", "skill_level": 62, "reaction_time": 200}
     *                     }
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Torneo jugado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Torneo jugado exitosamente"),
     *             @OA\Property(property="data", type="object", description="Resultados del torneo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos inválidos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error en los datos proporcionados"),
     *             @OA\Property(property="errors", type="object", description="Detalles de los errores")
     *         )
     *     )
     * )
     */
    public function store(StoreTournamentRequest $request)
    {

        $data = $request->validated();
        $genderKey = $data['tournament_gender'];
        $genderId = Gender::getGenderOptions()[$genderKey];
        DB::beginTransaction();

        try {

            $tournament = Tournament::create([
                'gender_id' => $genderId,
                'status_id' => TournamentStatus::PLAYABLE,
            ]);

            foreach ($data['players'] as $player) {
                if ($genderId === Gender::MALE_ID) {
                    MalePlayer::createAndAssignToTournament($player, $tournament);
                } else {
                    FemalePlayer::createAndAssignToTournament($player, $tournament);
                }
            };

            $tournamentResult = $this->tournamentService->run($tournament);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Error al Procesar el Torneo ' . $th->getMessage());
        }
        DB::commit();

        return $this->successResponse(
            $tournamentResult,
            'Torneo jugado exitosamente'
        );
    }
}
