<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Delivery;
use App\Models\Client;
use App\Models\Feature;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    try {
        // Obtener el usuario autenticado
        $user = $request->user();

        // Obtener los parámetros de paginación de la solicitud
        $perPage = $request->query('perPage', 10); // Cantidad de elementos por página, predeterminado es 10

        // Construir la consulta base con las relaciones 'clients' y 'features'
        $query = Delivery::with(['clients.features']);

        // Si el usuario tiene 'type' 2, filtrar por 'id_user'
        if ($user->type == 2) {
            $query->where('id_user', $user->id);
        }

        // Obtener los registros paginados
        $deliveries = $query->paginate($perPage);

        // Retornar la respuesta paginada con los datos relacionados
        return response()->json($deliveries, 200);

    } catch (\Exception $e) {
        // Loguear el error
        Log::error('Error al obtener los registros: ' . $e->getMessage());
        return response()->json(['error' => 'Error al obtener los registros. '], 500);
    }
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Obtener todos los datos del request
        $data = $request->all();
        // Log::info("Datos recibidos del request", ['data' => $data]); // Registrar datos de entrada

        try {
            // Validar los datos del request
            $validatedData = $request->validate([
                'date' => 'required|date',
                'arrivee' => 'required',
                'depart' => 'required',
                'duree' => 'required',
                'arrets' => 'required|integer',
                'plaque' => 'required|string',
                'camion' => 'required|string',
                'chauffeur' => 'required|string',
                'territoire' => 'required|string',
                'initial' => 'required|integer',
                'final' => 'required|integer',
                'parcour' => 'required|integer',
                'cycle' => 'required|integer',
                'quar' => 'required|in:AM,PM',
                'clients' => 'required|array|min:1',
                'clients.*.client_code' => 'required_with:clients.*.num_du_client|string|nullable',
                'clients.*.num_du_client' => 'required_with:clients.*.client_code|string|nullable',
            ]);

            // Log::info("Datos validados correctamente", ['validatedData' => $validatedData]);

            // Obtener el usuario autenticado
            $user = $request->user();
            // Log::info("Usuario autenticado", ['user' => $user]);

            // Crear una nueva entrega (Delivery)
            $delivery = new Delivery();
            $delivery->id_user = $user->id; // ID del usuario autenticado

            // Asignar valores validados a los campos del modelo Delivery
            $delivery->fill($validatedData);

            // Guardar la nueva entrega en la base de datos
            $delivery->save();
            // Log::info("Entrega creada con éxito", ['delivery' => $delivery]);

            // Procesar los clientes
            foreach ($data['clients'] as $clientData) {
                // Log::info("Procesando cliente", ['clientData' => $clientData]); // Registrar datos del cliente

                // Validar que los campos requeridos no sean null para el cliente
                if ($clientData['client_code'] !== null && $clientData['num_du_client'] !== null) {
                    // Asociar el cliente al delivery
                    $client = new Client([
                        'client_code' => $clientData['client_code'],
                        'num_du_client' => $clientData['num_du_client'],
                        'hr_arrivee' => $clientData['hr_arrivee'],
                        'hr_depart' => $clientData['hr_depart'],
                        'nom_imprime' => $clientData['nom_imprime'],
                        'ref' => $clientData['ref'],
                        'retour_marchandise' => $clientData['retour_marchandise'],
                        'id_delivery' => $delivery->id, // Asignar la relación de entrega
                    ]);

                    $client->save(); // Guardar cliente

                    // // Debug: Verificar si el cliente se guarda correctamente
                    // if ($client->id_client) {
                    //     Log::info("Cliente creado correctamente con ID: " . $client->id_client);
                    //     // dump($client); // Puedes activar esto si estás depurando localmente
                    // } else {
                    //     Log::error("Error al guardar el cliente. Cliente no tiene ID.", ['client' => $client]);
                    //     // dd($client); // Puedes activar esto si estás depurando localmente
                    // }

                    // Validar y asociar features (factures) al cliente recién creado
                    if (isset($clientData['factures']) && is_array($clientData['factures'])) {
                        foreach ($clientData['factures'] as $featureData) {
                            // Log::info("Procesando factura para cliente", ['featureData' => $featureData, 'client_id' => $client->id_client]);

                            if ($featureData['code_feature'] !== null && $featureData['colis'] !== null) {
                                Feature::create([
                                    'code_feature' => $featureData['code_feature'],
                                    'colis' => $featureData['colis'],
                                    'id_client' => $client->id_client, // ID del cliente recién creado
                                    'id_delivery' => $delivery->id, // ID del delivery recién creado
                                ]);
                            }
                        }
                    }
                }
            }

            // Retorna una respuesta de éxito
            return response()->json(['message' => 'Registro creado con éxito', 'delivery' => $delivery, 'request' => $data], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar y devolver errores de validación
            // Log::error('Error de validación', ['errors' => $e->errors()]);
            return response()->json(['error' => 'Error de validación', 'errors' => $e->errors(), 'request' => $data], 422);
        } catch (\Exception $e) {
            // Loguea el error
            // Log::error('Error al guardar el registro: ' . $e->getMessage(), ['request' => $data]);
            return response()->json(['error' => 'Error al guardar el registro', 'message' => $e->getMessage(), 'request' => $data], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        try {
            // Validación de los datos
            $validatedData = $request->validate([
                'date' => 'required|date', // Fecha requerida y debe ser una fecha válida
                'arrivee' => 'required', // Hora de llegada requerida en formato HH:MM
                'depart' => 'required', // Hora de salida requerida en formato HH:MM
                'duree' => 'required', // Duración requerida en formato HH:MM
                'arrets' => 'required|integer', // Número de paradas requerido y debe ser un número entero
                'plaque' => 'required|string', // Placa de matrícula requerida y debe ser una cadena de texto
                'camion' => 'required|string', // Camión requerido y debe ser una cadena de texto
                'chauffeur' => 'required|string', // Conductor requerido y debe ser una cadena de texto
                'territoire' => 'required|string', // Territorio requerido y debe ser una cadena de texto
                'initial' => 'required|integer', // Inicial requerido y debe ser un número entero
                'final' => 'required|integer', // Final requerido y debe ser un número entero
                'parcour' => 'required|integer', // Recorrido requerido y debe ser un número entero
                'cycle' => 'required|integer', // Ciclo requerido y debe ser un número entero
                'quar' => 'required|in:AM,PM', // Cuarto requerido y debe ser AM o PM
                'clients' => 'required|array|min:1', // Se requiere al menos un cliente
                'clients.*.client_code' => 'required_with:clients.*.num_du_client|string|nullable', // Client Code requerido si num_du_client no es nulo
                'clients.*.num_du_client' => 'required_with:clients.*.client_code|string|nullable', // Nombre del cliente requerido si client_code no es nulo
            ]);

            // Buscar el registro existente
            $delivery = Delivery::findOrFail($id);

            // **Eliminar Features Asociados a los Clientes del Delivery**
            DB::table('features')->whereIn('id_client', function ($query) use ($delivery) {
                $query->select('id_client')->from('clients')->where('id_delivery', $delivery->id);
            })->delete();

            // **Eliminar Clientes Asociados al Delivery**
            DB::table('clients')->where('id_delivery', $delivery->id)->delete();

            // Actualizar el registro con los datos validados
            $delivery->update($validatedData);

            // Procesar los nuevos clientes y sus features
            foreach ($data['clients'] as $clientData) {
                // Validar que los campos requeridos no sean null para el cliente
                if ($clientData['client_code'] !== null && $clientData['num_du_client'] !== null) {
                    $client = Client::create([
                        'client_code' => $clientData['client_code'],
                        'num_du_client' => $clientData['num_du_client'],
                        'hr_arrivee' => $clientData['hr_arrivee'],
                        'hr_depart' => $clientData['hr_depart'],
                        'nom_imprime' => $clientData['nom_imprime'],
                        'ref' => $clientData['ref'],
                        'retour_marchandise' => $clientData['retour_marchandise'],
                        'id_delivery' => $delivery->id, // Asociar el cliente con el delivery actual
                    ]);

                    // Validar y asociar features (factures) al cliente recién creado
                    if (isset($clientData['factures']) && is_array($clientData['factures'])) {
                        foreach ($clientData['factures'] as $featureData) {
                            if ($featureData['code_feature'] !== null && $featureData['colis'] !== null) {
                                Feature::create([
                                    'code_feature' => $featureData['code_feature'],
                                    'colis' => $featureData['colis'],
                                    'id_client' => $client->id_client, // ID del cliente recién creado
                                    'id_delivery' => $delivery->id, // ID del delivery asociado
                                ]);
                            }
                        }
                    }
                }
            }

            // return response()->json(['message' => 'Registro actualizado con éxito', 'data' => $delivery, 'request' => $data], 200);
            return response()->json(['message' => 'Registro actualizado con éxito', 'data' => $delivery], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar registro: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar registro: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $Delivery = Delivery::findOrFail($id);
            $Delivery->delete();

            return response()->json(['message' => 'Registro eliminado con éxito'], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error al eliminar registro: ' . $e->getMessage());
            return response()->json(['error' => 'Error al eliminar registro '], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
