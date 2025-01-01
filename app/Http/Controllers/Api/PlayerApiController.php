<?php

namespace App\Http\Controllers\Api;

use App\Models\Player;

/**
 * @OA\Tag(
 *     name="players",
 *     description="Endpoints relacionados con jugadores"
 * )
 */
class PlayerApiController extends ApiController
{

    /**
     * @OA\Get(
     *     path="/players",
     *     summary="Obtener lista de jugadores",
     *     description="Devuelve una lista de jugadores agrupados por género",
     *     tags={"Players"},
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", description="Estado de la respuesta", example="success"),
     *             @OA\Property(property="message", type="string", description="Mensaje de la respuesta", example="ok"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="femalePlayers",
     *                     type="array",
     *                     description="Lista de jugadoras femeninas",
     *                     @OA\Items(ref="#/components/schemas/FemalePlayerData")
     *                 ),
     *                 @OA\Property(
     *                     property="malePlayers",
     *                     type="array",
     *                     description="Lista de jugadores masculinos",
     *                     @OA\Items(ref="#/components/schemas/MalePlayerData")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $femalePlayers = Player::whereHas('femalePlayer')
            ->with('femalePlayer')
            ->get();

        $malePlayers = Player::whereHas('malePlayer')
            ->with('malePlayer')
            ->get();

        return $this->successResponse([
            'femalePlayers' => $femalePlayers,
            'malePlayers' => $malePlayers,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/profiles/{id}",
     *     summary="Obtener los detalles de un jugador",
     *     description="Devuelve los detalles de un jugador por ID, incluyendo información adicional si es masculino o femenino",
     *     tags={"Players"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del jugador a obtener",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Jugador encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success", description="Estado de la operación"),
     *             @OA\Property(property="message", type="string", example="ok", description="Mensaje de la operación"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/MalePlayerData")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Jugador no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error", description="Estado de la operación"),
     *             @OA\Property(property="message", type="string", example="No existe un Jugador con el ID proporcionado.", description="Mensaje de error")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $player = Player::with(['malePlayer', 'femalePlayer'])->find($id);

        if (is_null($player)) {
            return $this->errorResponse('No existe un Jugador con el ID proporcionado.');
        }

        return $this->successResponse([
            $player
        ]);
    }
}
