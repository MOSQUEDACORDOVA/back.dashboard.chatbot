<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeneralCRUDController extends Controller
{
    // Mapa de alias a modelos
    protected $modelsMap = [
        'users' => \App\Models\User::class,
        'property' => \App\Models\Property::class,
        // Agrega más modelos según tus necesidades
    ];

    public function createRecord(Request $request, $alias)
    {
        // Verificar si el alias corresponde a un modelo válido
        if (!array_key_exists($alias, $this->modelsMap)) {
            return response()->json(['error' => 'Invalid alias'], 404);
        }

        // Obtener el nombre de la clase del modelo
        $modelClass = $this->modelsMap[$alias];

        // Validar los datos de acuerdo al modelo
        $validator = Validator::make($request->all(), $modelClass::validationRules());
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Crear una instancia del modelo y rellenarlo con los datos
        $model = new $modelClass;
        $model->fill($request->all());
        $model->save();

        return response()->json(['message' => 'Record created successfully', 'data' => $model], 201);
    }

    public function deleteRecord($alias, $id)
    {
        // Verificar si el alias corresponde a un modelo válido
        if (!array_key_exists($alias, $this->modelsMap)) {
            return response()->json(['error' => 'Invalid alias'], 404);
        }

        // Obtener el nombre de la clase del modelo
        $modelClass = $this->modelsMap[$alias];

        // Buscar el registro por ID
        $record = $modelClass::find($id);
        
        if (!$record) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // Eliminar el registro
        $record->delete();

        return response()->json(['message' => 'Record deleted successfully'], 200);
    }

    public function getRecord($alias, Request $request, $id = null)
    {
        if (!array_key_exists($alias, $this->modelsMap)) {
            return response()->json(['error' => 'Invalid alias'], 404);
        }
    
        $modelClass = $this->modelsMap[$alias];
    
        // Si se proporciona un ID, buscar ese registro específico
        if ($id) {
            $record = $modelClass::find($id);
            if (!$record) {
                return response()->json(['error' => 'Record not found'], 404);
            }
            return response()->json(['data' => $record], 200);
        }
    
        // Obtener los registros con paginación
        $query = $modelClass::query();
    
        // Búsqueda genérica
        if ($request->has('search.value') && !empty($request->input('search.value'))) {
            $searchValue = $request->input('search.value');

            // Obtener las columnas del modelo User
            $columns = \Schema::getColumnListing((new $modelClass)->getTable());

            $query->where(function ($query) use ($columns, $searchValue) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', "%$searchValue%");
                }
            });
        }
    
        // Paginar los resultados
        $perPage = $request->input('length', 10); // Número de registros por página
        $currentPage = $request->input('start', 0) / $perPage; // Calcular la página actual
        $records = $query->paginate($perPage, ['*'], 'page', $currentPage + 1); // Cambia a 1-based index

        // Formatear la respuesta para DataTables
        return response()->json([
            'draw' => intval($request->input('draw')), // Para manejar el número de petición de DataTables
            'recordsTotal' => $modelClass::count(), // Total de registros sin filtros
            'recordsFiltered' => $records->total(), // Total de registros filtrados
            'data' => $records->items() // Datos de la página actual
        ]);
    }
    



}
