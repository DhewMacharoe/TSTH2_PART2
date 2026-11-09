import sys
import subprocess

def install_and_import(package_name, import_name):
    try:
        __import__(import_name)
    except ImportError:
        print(f"Installing {package_name}...")
        subprocess.check_call([sys.executable, "-m", "pip", "install", package_name])
    finally:
        globals()[import_name] = __import__(import_name)

install_and_import('python-pptx', 'pptx')

from pptx import Presentation
from pptx.util import Inches, Pt
from pptx.enum.shapes import MSO_SHAPE
from pptx.dml.color import RGBColor

prs = Presentation()

# Helper function to draw wireframe boxes
def draw_box(slide, left, top, width, height, text):
    shape = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, left, top, width, height)
    shape.fill.solid()
    shape.fill.fore_color.rgb = RGBColor(240, 240, 240)  # Light gray
    shape.line.color.rgb = RGBColor(100, 100, 100)       # Dark gray border
    shape.text = text
    for paragraph in shape.text_frame.paragraphs:
        for run in paragraph.runs:
            run.font.color.rgb = RGBColor(50, 50, 50)
            run.font.size = Pt(11)

# Slide 1: Title
title_slide_layout = prs.slide_layouts[0]
slide = prs.slides.add_slide(title_slide_layout)
title = slide.shapes.title
subtitle = slide.placeholders[1]
title.text = "TSTH2 Web Redesign & Warehouse System"
subtitle.text = "Rancangan Desain, Arsitektur, dan Perencanaan Sistem\nOleh: Antigravity AI\nFokus: Modernisasi UI/UX & Digitalisasi Inventaris"

# Slide 2: Latar Belakang
bullet_slide_layout = prs.slide_layouts[1]
slide = prs.slides.add_slide(bullet_slide_layout)
shapes = slide.shapes
title_shape = shapes.title
body_shape = shapes.placeholders[1]
title_shape.text = "Latar Belakang & Tujuan (Objective)"
tf = body_shape.text_frame
tf.text = "Solusi & Tujuan:"
p = tf.add_paragraph()
p.text = "Modernisasi UI/UX dengan gaya desain Glassmorphism."
p.level = 1
p = tf.add_paragraph()
p.text = "Sistem Terpadu (Single Portal) antara Company Profile dan Dashboard."
p.level = 1
p = tf.add_paragraph()
p.text = "Manajemen Konten dan Data Tanaman / Gudang secara real-time."
p.level = 1

# Slide 3: Wireframe Halaman Publik
title_only_layout = prs.slide_layouts[5]
slide = prs.slides.add_slide(title_only_layout)
slide.shapes.title.text = "Wireframe: Halaman Publik (Landing Page)"

# Draw Wireframes for Public Page
draw_box(slide, Inches(1), Inches(1.5), Inches(8), Inches(0.5), "Navbar (Logo | Home | Program | Research | Login)")
draw_box(slide, Inches(1), Inches(2.1), Inches(8), Inches(1.8), "Hero Section (Image Slider)\n\n[ Judul Utama Web ]\n[ Tombol CTA / Aksi ]")
draw_box(slide, Inches(1), Inches(4.0), Inches(3.8), Inches(1), "Visi & Misi (Teks Paragraf)")
draw_box(slide, Inches(5.2), Inches(4.0), Inches(3.8), Inches(1), "Gambar Ilustrasi Fasilitas")
draw_box(slide, Inches(1), Inches(5.1), Inches(2.5), Inches(1.3), "Card 1\n(Fasilitas Biotek)\nIcon & Teks")
draw_box(slide, Inches(3.75), Inches(5.1), Inches(2.5), Inches(1.3), "Card 2\n(Greenhouse)\nIcon & Teks")
draw_box(slide, Inches(6.5), Inches(5.1), Inches(2.5), Inches(1.3), "Card 3\n(Ekstraksi)\nIcon & Teks")
draw_box(slide, Inches(1), Inches(6.5), Inches(8), Inches(0.5), "Footer (Copyright & Tautan Penting)")

# Slide 4: Wireframe Dashboard
slide = prs.slides.add_slide(title_only_layout)
slide.shapes.title.text = "Wireframe: Dashboard & Panel Admin"

# Draw Wireframes for Dashboard
draw_box(slide, Inches(0.5), Inches(1.5), Inches(2.2), Inches(5.5), "SIDEBAR\n\n[ Logo TSTH2 ]\n\n- Dashboard Utama\n- Data Tanaman\n- Manajemen Web\n- Berita\n\n\n\n- Kembali ke Web")
draw_box(slide, Inches(2.9), Inches(1.5), Inches(6.6), Inches(0.6), "TOP HEADER ( Kotak Pencarian...                  | Profil Pengguna | [Logout] )")
draw_box(slide, Inches(2.9), Inches(2.3), Inches(6.6), Inches(4.7), "MAIN CONTENT AREA\n\nJudul Halaman: Data Tanaman\n\n[ Total Tanaman: 1200 ]     [ Kategori: 12 ]     [ Lokasi Blok: 5 ]\n\n---------------------------------------------------------------------------------\n TABEL DATA TANAMAN / INVENTARIS\n---------------------------------------------------------------------------------\n| Foto | ID | Nama Tanaman | Kategori | Blok | Stok | Aksi (Edit/Hapus) |\n| ...  | ...| ...          | ...      | ...  | ...  | ...               |")

# Slide 5: RBAC & Arsitektur
slide = prs.slides.add_slide(bullet_slide_layout)
shapes = slide.shapes
title_shape = shapes.title
body_shape = shapes.placeholders[1]
title_shape.text = "Matriks Hak Akses (Role-Based Access Control)"
tf = body_shape.text_frame
tf.text = "Pemisahan wewenang akses dalam sistem:"
p = tf.add_paragraph()
p.text = "Web Admin:"
p.level = 1
p = tf.add_paragraph()
p.text = "Akses penuh ke Manajemen Konten Web."
p.level = 2
p = tf.add_paragraph()
p.text = "PIC Gudang:"
p.level = 1
p = tf.add_paragraph()
p.text = "Akses penuh ke Data Tanaman (Tambah, Edit, Hapus)."
p.level = 2
p = tf.add_paragraph()
p.text = "Staff Gudang:"
p.level = 1
p = tf.add_paragraph()
p.text = "Hanya dapat melihat data tanaman dan mengunduh laporan (Read-only)."
p.level = 2

prs.save("Rancangan_TSTH2_Web_Lengkap.pptx")
print("Presentation generated successfully at Rancangan_TSTH2_Web_Lengkap.pptx")
