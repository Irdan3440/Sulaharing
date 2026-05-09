import json
import random
import time
from datetime import datetime
import paho.mqtt.client as mqtt

BROKER = "localhost"
TOPIC  = "sulaharing/biometric/sensor"
CLIENT_ID = f"sulaharing-sim-{random.randint(0, 9999)}"
QOS = 1

def connect_client():
    client = mqtt.Client(mqtt.CallbackAPIVersion.VERSION2, CLIENT_ID)
    client.connect(BROKER, 1883, 60)
    return client

def get_topic(user_id):
    return f"sulaharing/biometric/user/{user_id}"

def payload(user_id, bpm, spo2, hrv):
    return json.dumps({
        "heart_rate": bpm,
        "spO2": spo2,
        "hrv": hrv,
        "timestamp": datetime.utcnow().isoformat() + "Z"
    })

def simulate_normal():
    bpm = random.randint(70, 85)
    spo2 = random.randint(96, 99)
    hrv = random.randint(45, 80)
    return bpm, spo2, hrv

def simulate_panic():
    bpm = random.randint(101, 130)
    spo2 = random.randint(90, 95)
    hrv = random.randint(10, 29)
    return bpm, spo2, hrv

def main():
    print("\n=== SulaHaring IoT Simulator (Huawei Band 10) ===")
    print("[1] Simulasi Normal")
    print("[2] Simulasi Panic-Attack / Stress")
    mode = input("Pilih mode (1/2): ").strip()
    if mode not in ("1", "2"):
        print("Pilihan tidak valid – keluar.")
        return

    user_id = input("Masukkan USER_ID yang ingin disimulasikan: ").strip() or "demo_user"
    client = connect_client()
    print("\nMulai mengirim data tiap 2 detik… Tekan Ctrl-C untuk berhenti.\n")

    try:
        while True:
            if mode == "1":
                bpm, spo2, hrv = simulate_normal()
            else:
                bpm, spo2, hrv = simulate_panic()

            msg = payload(user_id, bpm, spo2, hrv)
            topic = get_topic(user_id)
            client.publish(topic, msg, qos=QOS)
            print(f"[{datetime.now().strftime('%H:%M:%S')}] → Topik: {topic} | Data: {msg}")
            time.sleep(2)
    except KeyboardInterrupt:
        print("\nSimulator dihentikan.")
    finally:
        client.disconnect()

if __name__ == "__main__":
    main()
