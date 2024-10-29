<?php

namespace App\Http\Controllers;

use App\Models\ConversationHistory;
use Illuminate\Http\Request;

class ConversationHistoryController extends Controller
{
    public function getUniqueContacts()
    {
        $contacts = ConversationHistory::select('user_phone', \DB::raw('MAX(name) as name'))
                    ->groupBy('user_phone')
                    ->get();

        return response()->json($contacts);
    }

    public function getMessagesByUserPhone($userPhone)
    {
        // Obtiene todos los mensajes de la persona específica
        $messages = ConversationHistory::where('user_phone', $userPhone)->get();

        // Retorna los mensajes en formato JSON
        return response()->json($messages);
    }

}
