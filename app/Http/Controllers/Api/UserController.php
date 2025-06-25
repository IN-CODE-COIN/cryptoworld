<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Crypto API",
 *     description="Documentación de la API de Usuarios para la plataforma Crypto."
 * )
 *
 * @OA\Tag(
 *     name="Usuarios",
 *     description="Operaciones relacionadas con usuarios"
 * )
 */
class UserController extends Controller
{
    //* Mostrar todos los usuarios *//
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Usuarios"},
     *     summary="Listar todos los usuarios",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios"
     *     )
     * )
     */
    public function index()
    {
        return User::all();
    }

    //* Crear un nuevo usuario *//
    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Usuarios"},
     *     summary="Crear un nuevo usuario",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","rol"},
     *             @OA\Property(property="name", type="string", example="Luis"),
     *             @OA\Property(property="email", type="string", example="luis@crypto.com"),
     *             @OA\Property(property="password", type="integer", example=1234),
     *             @OA\Property(property="rol", type="string", enum={"pro", "normal"}, example="pro")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|digits:4',
            'rol' => 'required|in:pro,normal',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json(['id' => $user->id], 201);
    }

    //* Mostrar un usuario por id *//
    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Usuarios"},
     *     summary="Mostrar un usuario por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos del usuario"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function show(string $id)
    {
        $response = User::find($id);
        if (!$response) {
            return response()->json(['message' => 'usuario no encontrado'], 404);
        }
        return $response;
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Usuarios"},
     *     summary="Actualizar un usuario por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Luis Actualizado"),
     *             @OA\Property(property="email", type="string", example="luis@nuevo.com"),
     *             @OA\Property(property="password", type="integer", example=9999),
     *             @OA\Property(property="rol", type="string", enum={"pro", "normal"}, example="normal")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|digits:4',
            'rol' => 'sometimes|in:pro,normal',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json(['message' => 'Usuario actualizado correctamente'], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Usuarios"},
     *     summary="Eliminar un usuario por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }

}
