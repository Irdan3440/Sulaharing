<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\EmergencyContact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmergencyNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        $contact = EmergencyContact::where('user_id', $this->user->id)->first();

        if (!$contact) {
            Log::info("No emergency contact defined for user {$this->user->id}");
            return;
        }

        $msg = "🚨 *SULAHARING EMERGENCY ALERT* 🚨\n\n";
        $msg .= "Halo {$contact->name},\n\n";
        $msg .= "Sistem mendeteksi detak jantung yang anomali/terindikasi *Panic Attack* pada:\n";
        $msg .= "👤 *Nama:* {$this->user->name}\n";
        $msg .= "⏳ *Waktu:* " . now()->format('d M Y, H:i') . "\n\n";
        $msg .= "Harap segera periksa kondisi {$this->user->name}. Terima kasih.";

        $token = env('FONNTE_TOKEN');

        if (!$token) {
            Log::warning("[Fonnte] API Token belum diatur di .env. Pesan yang harusnya dikirim: \n{$msg}");
            return;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $contact->phone,
                'message' => $msg,
            ]);

            if ($response->successful()) {
                Log::info("[Fonnte] Pesan darurat sukses terkirim ke {$contact->phone}");
            } else {
                Log::error("[Fonnte] Gagal mengirim pesan ke {$contact->phone}. Response: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("[Fonnte] Error koneksi: " . $e->getMessage());
        }
    }
}
