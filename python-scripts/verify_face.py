import sys

# Fungsi placeholder untuk verifikasi wajah
def verify_face(image_path):
    # Gantilah dengan logika deteksi wajah
    return "Hadir"  # Simulasi bahwa wajah terdeteksi sebagai hadir

if __name__ == "__main__":
    image_path = sys.argv[1]  # Menerima path gambar dari PHP
    result = verify_face(image_path)
    print(result)  # Mengembalikan hasil verifikasi
