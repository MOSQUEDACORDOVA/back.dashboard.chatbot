<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Obtener los parámetros de paginación de la solicitud
            $perPage = $request->query('perPage', 10); // Cantidad de elementos por página, predeterminado es 10
        
            // Obtener los registros paginados automáticamente
            $users = User::paginate($perPage);
        
            // Retornar la respuesta paginada
            return response()->json($users, 200);
        } catch (\Exception $e) {
            // Loguear el error
            Log::error('Error al obtener los registros: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener los registros. '], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $User = User::findOrFail($id);
            $User->update($request->all());

            return response()->json(['message' => 'Registro actualizado con éxito', 'data' => $User], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar registro: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar registro'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['message' => 'Registro eliminado con éxito'], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error al eliminar registro: ' . $e->getMessage());
            return response()->json(['error' => 'Error al eliminar registro '], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
