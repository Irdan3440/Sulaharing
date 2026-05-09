<?php
// Scratch script to view all database tables

use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "  SulaHaring Database Overview\n";
echo "========================================\n\n";

// 1. Users
echo "--- USERS ---\n";
$users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
foreach ($users as $u) {
    echo "  ID:{$u->id} | {$u->name} | {$u->email} | Role:{$u->role}\n";
}
echo "  Total: " . count($users) . "\n\n";

// 2. Consultation Results
echo "--- CONSULTATION RESULTS ---\n";
$consults = DB::table('consultation_results')->select('id', 'user_id', 'total_score_bdi', 'classification', 'cf_percentage', 'consulted_at')->get();
foreach ($consults as $c) {
    $uid = $c->user_id ?? 'guest';
    echo "  ID:{$c->id} | User:{$uid} | BDI:{$c->total_score_bdi} | {$c->classification} | CF:{$c->cf_percentage}% | {$c->consulted_at}\n";
}
echo "  Total: " . count($consults) . "\n\n";

// 3. Biometric Data
echo "--- BIOMETRIC DATA ---\n";
$bio = DB::table('biometric_data')->latest('id')->limit(10)->get();
foreach ($bio as $b) {
    echo "  ID:{$b->id} | User:{$b->user_id} | HR:{$b->heart_rate} | HRV:{$b->hrv} | SpO2:{$b->spo2} | {$b->recorded_at}\n";
}
echo "  Total: " . DB::table('biometric_data')->count() . " (showing last 10)\n\n";

// 4. IoT Alerts
echo "--- IOT ALERTS ---\n";
$alerts = DB::table('iot_alerts')->latest('id')->limit(10)->get();
foreach ($alerts as $a) {
    echo "  ID:{$a->id} | User:{$a->user_id} | {$a->alert_type} | Severity:{$a->severity} | {$a->alerted_at}\n";
}
echo "  Total: " . DB::table('iot_alerts')->count() . "\n\n";

// 5. Emergency Contacts
echo "--- EMERGENCY CONTACTS ---\n";
$contacts = DB::table('emergency_contacts')->get();
foreach ($contacts as $ec) {
    echo "  ID:{$ec->id} | User:{$ec->user_id} | {$ec->name} | {$ec->relationship} | {$ec->phone}\n";
}
echo "  Total: " . count($contacts) . "\n\n";

// 6. Diseases
echo "--- DISEASES ---\n";
$diseases = DB::table('diseases')->get();
foreach ($diseases as $d) {
    echo "  ID:{$d->id} | {$d->kode} | {$d->nama}\n";
}
echo "  Total: " . count($diseases) . "\n\n";

// 7. Symptoms
echo "--- SYMPTOMS ---\n";
$symptoms = DB::table('symptoms')->get();
foreach ($symptoms as $s) {
    echo "  ID:{$s->id} | {$s->kode} | {$s->nama}\n";
}
echo "  Total: " . count($symptoms) . "\n";
