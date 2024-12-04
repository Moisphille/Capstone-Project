import sys
import cv2
from deepface import DeepFace

def verify_face(image_path):
    # Memulai verifikasi wajah menggunakan gambar referensi yang diberikan
    print(f"Verifying face using the reference image: {image_path}")
    
    # Buka kamera
    cap = cv2.VideoCapture(1)
    if not cap.isOpened():
        print("Tidak dapat membuka kamera.")
        return "Error: Cannot open camera."
    
    while True:
        ret, frame = cap.read()
        if not ret:
            print("Gagal membaca frame dari kamera.")
            break
        
        # Verifikasi wajah dengan gambar referensi
        result = DeepFace.verify(frame, image_path, enforce_detection=False)
        similarity = result['distance']
        
        # Menampilkan hasil verifikasi di frame
        cv2.putText(frame, f"Similarity: {similarity:.2f}", (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 2)
        
        cv2.imshow("Face Verification", frame)
        
        # Tekan 'q' untuk keluar
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    # Tutup kamera dan jendela
    cap.release()
    cv2.destroyAllWindows()
    return f"Face verification completed. Similarity: {similarity:.2f}"

if __name__ == "__main__":
    image_path = sys.argv[1]  # Mendapatkan path gambar dari argumen
    result = verify_face(image_path)
    print(result)  # Menampilkan hasil verifikasi
