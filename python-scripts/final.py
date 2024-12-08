import cv2
import os
import numpy as np
import sys
from datetime import datetime

def verify_face_with_camera(upload_dir='C:/laragon/www/Capstone-Project-dev/uploads', threshold=50, signal_file='stop_signal.txt'):
    # Cek apakah folder uploads ada
    if not os.path.exists(upload_dir):
        print(f"Folder {upload_dir} tidak ditemukan.")
        return

    # Dapatkan semua file gambar dalam folder uploads
    reference_images_paths = [os.path.join(upload_dir, f) for f in os.listdir(upload_dir) if f.endswith(('.jpg', '.png'))]

    # Cek apakah ada gambar referensi
    if not reference_images_paths:
        print("Tidak ada gambar referensi dalam folder uploads.")
        return

    # Buka kamera (ubah 0 menjadi 1 jika menggunakan kamera eksternal)
    cap = cv2.VideoCapture(0)
    if not cap.isOpened():
        print("Tidak dapat membuka kamera.")
        return
    
    print("Tekan 'q' untuk keluar.")
    
    # Membuat model wajah menggunakan Local Binary Patterns Histograms (LBPH)
    recognizer = cv2.face.LBPHFaceRecognizer_create()

    # Latih recognizer dengan gambar referensi
    images = []
    labels = []
    for i, path in enumerate(reference_images_paths):
        img = cv2.imread(path, cv2.IMREAD_GRAYSCALE)
        if img is not None:
            images.append(img)
            labels.append(i)
        else:
            print(f"Gagal membaca gambar {path}")

    if len(images) == 0:
        print("Tidak ada gambar yang valid untuk pelatihan.")
        return

    recognizer.train(images, np.array(labels))
    
    start_time = datetime.now()
    while True:
        # Baca frame dari kamera
        ret, frame = cap.read()
        if not ret:
            print("Gagal membaca frame dari kamera.")
            break

        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + "haarcascade_frontalface_default.xml")
        faces = face_cascade.detectMultiScale(gray, scaleFactor=1.1, minNeighbors=5, minSize=(30, 30))
        
        for (x, y, w, h) in faces:
            roi_gray = gray[y:y+h, x:x+w]
            label, confidence = recognizer.predict(roi_gray)
            
            if confidence > threshold:
                text = "Wajah Cocok"
                color = (0, 255, 0)  # Hijau buat cocok
            else:
                text = "Wajah Tidak Cocok"
                color = (0, 0, 255)  # Merah buat tidak cocok

            # Tampilkan hasil verifikasi wajah
            cv2.putText(frame, f"Kesamaan: {confidence:.2f}", (x, y-10), cv2.FONT_HERSHEY_SIMPLEX, 1, color, 2)
            cv2.putText(frame, text, (x, y+h+30), cv2.FONT_HERSHEY_SIMPLEX, 1, color, 2)
            cv2.rectangle(frame, (x, y), (x+w, y+h), color, 2)
            
        # Menambahkan waktu di kiri atas pada frame
        waktu = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        cv2.putText(frame, waktu, (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 255, 255), 1)
            
        cv2.imshow("Verifikasi", frame)

        # Tekan 'q' buat keluar dari frame
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break
        # Hentikan kamera setelah 5 detik
        if (datetime.now() - start_time).seconds >= 5:
            print("Kamera dimatikan setelah 5 detik.")
            break

    # Tutup kamera dan jendela
    cap.release()
    cv2.destroyAllWindows()

# Jalankan fungsi
if __name__ == "__main__":
    verify_face_with_camera()
