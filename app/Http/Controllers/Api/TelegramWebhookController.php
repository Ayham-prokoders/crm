<?php

namespace App\Http\Controllers\Api;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use NotificationChannels\Telegram\TelegramUpdates;
use App\Http\Controllers\Controller;

class TelegramWebhookController extends Controller
{

    public function handle(Request $request)
    {
        $updates = $request->all();
        \Log::info('Received Telegram update:', $updates);

        if (isset($updates['message']['text'])) {
            $text = $updates['message']['text'];
            $chatId = $updates['message']['chat']['id'];
            $username = $updates['message']['chat']['username']??null;

            $user = User::where('telegram_chat_id', $chatId)->first();

            switch ($text) {
                case '/start':
                    $this->sendMessage($chatId, "chat id: " . $chatId." username:".$username);

                    if (!$user) {
                        $newUser=User::where('name', $username)->first();
                        if($newUser)
                        {
                            $newUser->telegram_chat_id = $chatId;
                            $newUser->save();
                        }
                    }
                    break;
                default:
                    $this->sendMessage($chatId, 'Unknown command.');
                    break;
            }
        }

        return response()->json(['status' => 'ok']);
    }

    private function sendMessage($chatId, $text)
    {
        $token=env('TELEGRAM_BOT_TOKEN');
        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }



}
