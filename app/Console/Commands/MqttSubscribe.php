<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Models\BiometricData;
use App\Models\IotAlert;
use App\Events\BiometricUpdated;
use App\Models\User;
use App\Jobs\SendEmergencyNotificationJob;
class MqttSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to SulaHaring IoT MQTT broker to receive biometric data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $server   = 'localhost';
        $port     = 1883;
        $clientId = 'sulaharing-server-' . rand(1000, 9999);

        $this->info("Connecting to MQTT broker at $server:$port...");

        try {
            $mqtt = new MqttClient($server, $port, $clientId);

            $settings = (new ConnectionSettings())
                ->setKeepAliveInterval(60)
                ->setConnectTimeout(5);

            $mqtt->connect($settings, true);
            $this->info("Connected successfully!");

            $topic = 'sulaharing/biometric/user/+';
            
            $mqtt->subscribe($topic, function (string $topic, string $message) {
                $this->info("Received message on topic [$topic]: $message");
                
                // Extract user ID from topic
                $parts = explode('/', $topic);
                $userId = end($parts);

                try {
                    $payload = json_decode($message, true);
                    
                    if (isset($payload['heart_rate']) && isset($payload['spO2']) && isset($payload['hrv'])) {
                        // Save Biometric Data
                        $bio = BiometricData::create([
                            'user_id' => $userId,
                            'heart_rate' => $payload['heart_rate'],
                            'hrv' => $payload['hrv'],
                            'spO2' => $payload['spO2'],
                            'recorded_at' => now(),
                        ]);

                        // Broadcast the event
                        \App\Events\BiometricUpdated::dispatch($bio, $userId);

                        // Check for alerts (Abnormal thresholds)
                        if ($payload['heart_rate'] > 100 || $payload['heart_rate'] < 50) {
                            IotAlert::create([
                                'user_id' => $userId,
                                'alert_type' => 'Abnormal Heart Rate',
                                'message' => "Detak jantung terdeteksi tidak normal: {$payload['heart_rate']} BPM",
                                'severity' => 'high',
                                'alerted_at' => now(),
                                'is_read' => false,
                            ]);
                        }

                        if ($payload['heart_rate'] > 100 && $payload['hrv'] < 30) {
                            $user = User::find($userId);
                            if ($user) {
                                SendEmergencyNotificationJob::dispatch($user);
                            }
                        }

                        // Broadcast event for real-time UI updates
                        broadcast(new BiometricUpdated($bio, $userId));
                        
                        $this->info("Data processed and broadcasted for User $userId.");
                    } else {
                        $this->warn("Invalid payload format.");
                    }
                } catch (\Exception $e) {
                    $this->error("Error processing message: " . $e->getMessage());
                }
            }, config('mqtt.qos', 0));

            $this->info("Subscribed to $topic. Listening for messages...");
            
            $mqtt->loop(true);
            $mqtt->disconnect();
            
        } catch (\Exception $e) {
            $this->error("MQTT connection failed: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
