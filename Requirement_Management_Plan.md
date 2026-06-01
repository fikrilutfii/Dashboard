# Requirement Management Plan
**Proyek: Sistem Informasi Manajemen ERP Mini (Percetakan & Konveksi) - Abadi Sentosa**
**Tanggal Dokumen:** 31 Maret 2026

---

## 1. Introduction
Dokumen *Requirement Management Plan* (Rencana Manajemen Kebutuhan) ini mendefinisikan pedoman dan proses administratif tentang bagaimana seluruh *requirements* (kebutuhan operasional dan sistem) aplikasi ERP Mini Abadi Sentosa akan dikumpulkan, dianalisis, diprioritaskan, didokumentasikan, dan dilacak sepanjang siklus hidup proyek.

Tujuan utama penyusunan dokumen ini adalah untuk memastikan bahwa produk aplikasi yang dikembangkan dapat secara presisi menyelesaikan permasalahan yang dihadapi oleh divisi Percetakan dan Konveksi, tanpa adanya penyimpangan fungsionalitas. Dokumen ini menjadi referensi utama bagi *Project Manager*, *Lead Developer*, dan Manajemen Abadi Sentosa dalam mengukur apakah *output* akhir (sistem ERP) sudah memenuhi ekspektasi bisnis di awal.

## 2. Requirement Collection Process (Proses Pengumpulan Kebutuhan)
Pengumpulan kebutuhan sistem tidak dilakukan secara sepihak oleh pengembang, melainkan melalui pendekatan hibrida yang melibatkan pengguna akhir secara langsung. Teknik pengumpulan yang digunakan meliputi:

1. **Wawancara Semi-Terstruktur:** Menggali arus kerja operasional secara mendalam dari masing-masing level jabatan (Kasir Faktur, Kepala Divisi Percetakan, Kepala Divisi Konveksi, hingga Tim Keuangan Pusat).
2. **Analisis Dokumen Objek Fisik:** Membedah spesifikasi data *input-output* dari dokumen operasional yang selama ini menggunakan kertas/manual, seperti:
   - Nota / Faktur Penjualan rangkap manual.
   - Buku besar piutang pelanggan dan utang perusahaan.
   - Slip kasbon karyawan dan rekap absensi/gaji konvensional.
3. **Prototyping & Feedback Iteratif:** Pengembang merepresentasikan pemahaman mereka ke dalam bentuk antarmuka (UI) web awal, kemudian pengguna diminta memberikan umpan balik terkait kenyamanan letak tombol, pemahaman bahasa antarmuka, dan efisiensi klik.

## 3. Requirement Categories (Kategorisasi Kebutuhan)
Untuk menjaga agar fokus penyelesaian kode (*coding*) lebih tertata, setiap kebutuhan (requirement) yang dikumpulkan diklasifikasikan ke dalam empat kategori utama:

*   **Business Requirements (Kebutuhan Bisnis tingkat tinggi):** 
    Sistem harus mampu menyajikan sentralisasi pelaporan neraca/arus kas dari dua divisi (Percetakan & Konveksi) yang berbeda secara terpusat, demi menunjang keakuratan pengambilan keputusan direksi.
*   **Stakeholder Requirements (Kebutuhan Pemangku Kepentingan):** 
    Adanya segregasi (pemisahan) menu yang ketat. Pihak kasir atau staf (*Role: faktur*) tidak boleh melihat hak prerogatif keuangan pusat pelunasan *company debts*.
*   **Solution / Functional Requirements (Kebutuhan Fungsional Teknis):**
    - Sistem memiliki fitur sinkronisasi *Shared Stock* (satu stok barang nyata dapat diakses otomatis oleh dua/lebih kode barang berbeda di aplikasi).
    - Sistem dapat memotong otomatis nominal uang muka (kasbon) karyawan pada saat penerbitan Slip Gaji bulanan.
    - Sistem sanggup menghasilkan dokumen cetak (PDF/Print) untuk Lembar Laporan Keuangan, Slip Gaji, dan Dashboard Stok Barang.
*   **Non-Functional Requirements (Kebutuhan Non-Fungsional):**
    - Waktu toleransi pemuatan laporan keuangan kompleks maksimal 3 detik.
    - Sistem diamankan menggunakan otentikasi sesi Laravel (dilengkapi proteksi pelumpuhan sesi paksa *419 Page Expired*).
    - Antarmuka web harus 100% responsif (dapat diakses mulus lewat PC admin maupun layar tablet saat presentasi laporan).

## 4. Requirement Prioritization (Prioritas Kebutuhan)
Sistem penentuan prioritas pengembangan fitur menggunakan kerangka kerja **MoSCoW** guna menyesuaikan jadwal pengerjaan proyek terhadap keterbatasan waktu operasional Abadi Sentosa:

| Prioritas | Kriteria (MoSCoW) | Contoh Fungsionalitas Aplikasi |
| :--- | :--- | :--- |
| **M** | **Must Have** (Kritis, tanpa ini sistem gagal/tidak bisa launching) | CRUD Faktur, Master Barang, Pemisahan Divisi Percetakan & Konveksi, Pengaturan Otentikasi Pengguna, Laporan Keuangan Baku. |
| **S** | **Should Have** (Penting, tapi ada *workaround* manual sementara) | Fitur sinkronisasi kode *Shared Stock*, Cetak Slip Gaji terintegrasi pemotongan Kasbon, Dashboard peringatan stok menipis. |
| **C** | **Could Have** (Fitur *nice-to-have*, dikerjakan jika ada ekstra waktu) | Ekspor filter Laporan Keuangan ke format Excel statis. |
| **W** | **Won't Have** (Ditolak/Diabaikan pada versi Rilis V1 saat ini) | Modul pendaftaran absensi karyawan via *fingerprint* / biometrik, Aplikasi Native Smartphone. |

## 5. Requirement Traceability Matrix (RTM)
RTM adalah metodologi penelusuran yang menjamin bahwa tidak ada satupun kebutuhan bisnis yang dijanjikan namun luput diprogram (*missing feature*). Di dalam proyek ERP Mini ini, penelusuran divalidasi dengan menghubungkan langsung ID Requirement dengan ID Modul/Menu di *sidebar* aplikasi.
*(Contoh Struktur Logika RTM)*:
- **[REQ-FUNC-01]** "*Integrasi Kasbon ke Payroll*" --> Ditelusuri melalui modul `PayrollController.php` --> Divalidasi melalui menu UAT *Penggajian*.
- **[REQ-FUNC-02]** "*Notifikasi Stok Habis*" --> Ditelusuri melalui modul `ProductController@stockReport` --> Divalidasi melalui menu UAT *Dashboard Stok Barang*.

Dengan cara ini, apabila kelak ditemukan kecacatan (bug) pada modul Stok, pengembang tahu persis REQUIREMENT spesifik mana yang terdampak.

## 6. Requirement Configuration & Change Management
Setiap persyaratan (*requirement*) yang telah ditetapkan dan disetujui di kategorisasi **"Must Have"** akan dikunci (*baselined*). Jika Pemangku Kepentingan bisnis (misal: Kepala Divisi) meminta penggantian alur fungsional (seperti contoh: "Bagaimana jika *Shared Stock* diganti tidak menggunakan referensi kode, melainkan ID database langsung?"), maka:

1. Modifikasi tersebut tidak akan langsung diretas (*hard-coded*) oleh pemrogram.
2. Permintaan tersebut dikategorikan sebagai *Change Request* (merujuk pada mekanisme *Scope Management Plan*).
3. Jika *Change Request* disetujui Sponsor Proyek, maka *Project Manager* akan mengubah status [*Requirement LAMA*] menjadi *Obsolete* di dalam RTM, dan menambah baris [*Requirement BARU*] untuk dikerjakan.

## 7. Roles and Responsibilities
Personel utama dan batas wewenang mereka terhadap pengolahan *Requirements*:

*   **Business / System Analyst (merangkap Lead Developer):** Bertugas menguraikan ucapan ambigu pengguna ke dalam bahasa spesifikasi logika basis data/kode *(Requirement engineering)*, serta merancang rancang bangun awal solusi fungsi tersebut.
*   **Project Manager:** Bertanggung jawab mengatur sesi penggalian (*interview/workshop*) kebutuhan, menyalurkan setiap RTM ke tugas program mingguan, memperbarui status prioritas MoSCoW, dan menyaring agar tak terjadi pembengkakan fitur (*Scope Creep*).
*   **Division Heads (Percetakan & Konveksi):** Berperan sebagai "*Subject Matter Expert*" (Narasumber Utama) yang mempresentasikan masalah lapangan, serta bertugas menginspeksi hasil perangkat lunak *(User Acceptance Testing)*, apakah program buatan *Developer* sudah sesuai dengan kebutuhan pesanan mereka di tahap RTM.
*   **Project Sponsor:** Eksekutif penentu akhir (*Gatekeeper*) atas pembatalan atau tambahan drastis dari daftar persyaratan fungsional (khususnya persyaratan "*Must Have*").

&nbsp;

**Disetujui Oleh:**

&nbsp;

______________________________  
**( Nama Lengkap Project Manager )**  
*Manajemen Pengembangan Proyek*  
**Tanggal:** _______________  

&nbsp;

______________________________  
**( Nama Lengkap Representative Stakeholder )**  
*Divisi Operasional & Manajemen Keuangan*  
**Tanggal:** _______________
