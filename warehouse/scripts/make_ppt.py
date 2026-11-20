from pptx import Presentation
from pptx.util import Inches, Pt
from pptx.enum.text import PP_ALIGN
import os

# Create presentation
prs = Presentation()

# Standard blank template gives a white background as requested
title_slide_layout = prs.slide_layouts[0]
bullet_slide_layout = prs.slide_layouts[1]
blank_slide_layout = prs.slide_layouts[6]

# SLIDE 1: Title
slide = prs.slides.add_slide(title_slide_layout)
title = slide.shapes.title
subtitle = slide.placeholders[1]
title.text = "Transformasi Digital TSTH2"
subtitle.text = "Sistem Manajemen Warehouse Data & Desain Ulang UI\nKonsep & Implementasi"

# Function to add a slide with title, bullets and an image
def add_content_slide(title_text, bullets, img_path=None):
    slide = prs.slides.add_slide(bullet_slide_layout)
    shapes = slide.shapes
    
    title_shape = shapes.title
    body_shape = shapes.placeholders[1]
    
    title_shape.text = title_text
    
    tf = body_shape.text_frame
    tf.text = bullets[0]
    for bullet in bullets[1:]:
        p = tf.add_paragraph()
        p.text = bullet
        p.level = 0
        
    # Resize text box to make room for image on the right
    body_shape.width = Inches(4.5)
    
    if img_path and os.path.exists(img_path):
        # Insert image on the right side
        left = Inches(5.0)
        top = Inches(2.0)
        width = Inches(4.5)
        # Add picture (will scale proportionally to fit width)
        slide.shapes.add_picture(img_path, left, top, width=width)

# SLIDE 2: Konsep UI/UX
landing_img = r"C:\Users\DEWA\.gemini\antigravity-ide\brain\eb451a02-7bf5-4993-baf0-841a32e410b9\wireframe_landing_1788405409079.png"
add_content_slide(
    "1. Konsep UI/UX & Landing Page",
    [
        "Desain Premium (Glassmorphism): Memberikan kesan modern dan elegan.",
        "Navigasi Dinamis: Pemisahan antar fitur (Program, Research) secara modular.",
        "Pendekatan Clean UI: Penggunaan ruang putih (white space) yang optimal.",
        "Responsif: Struktur visual yang mudah diadaptasi ke berbagai perangkat."
    ],
    landing_img
)

# SLIDE 3: Keamanan & Autentikasi
login_img = r"C:\Users\DEWA\.gemini\antigravity-ide\brain\eb451a02-7bf5-4993-baf0-841a32e410b9\wireframe_login_1788405433961.png"
add_content_slide(
    "2. Keamanan & Role-Based Access Control",
    [
        "Perlindungan Berlapis: Rute warehouse diproteksi oleh Autentikasi ketat.",
        "Role Admin (Full Access): Wewenang penuh untuk Edit, Tambah, Hapus data.",
        "Role Staf (Read-Only): Akses terbatas untuk pencarian, melihat grafik, dan unduh laporan tanpa resiko data rusak.",
        "UI Adaptif: Tombol aksi disembunyikan secara otomatis sesuai wewenang akun yang login."
    ],
    login_img
)

# SLIDE 4: Dashboard Warehouse
dash_img = r"C:\Users\DEWA\.gemini\antigravity-ide\brain\eb451a02-7bf5-4993-baf0-841a32e410b9\wireframe_dashboard_1788405422041.png"
add_content_slide(
    "3. Fitur Utama Warehouse Dashboard",
    [
        "Live Search: Penyaringan data tanaman instan tanpa reload.",
        "Chart.js Analytics: Visualisasi data stok (Pie & Bar) secara real-time.",
        "Manajemen Gambar: Fasilitas unggah foto tanaman pada form pendaftaran.",
        "Ekspor CSV: Ekstraksi seluruh laporan stok ke Excel dalam satu klik."
    ],
    dash_img
)

# SLIDE 5: Penutup
slide = prs.slides.add_slide(title_slide_layout)
title = slide.shapes.title
subtitle = slide.placeholders[1]
title.text = "Terima Kasih"
subtitle.text = "Siap untuk fase pengembangan selanjutnya."

# Save PPTX
output_path = r"e:\magang\redesign dan warehouse\Presentasi_Konsep_TSTH2.pptx"
prs.save(output_path)
print(f"PPTX saved to: {output_path}")
