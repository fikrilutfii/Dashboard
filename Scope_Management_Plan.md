# Scope Management Plan
**Proyek: Sistem Informasi Manajemen ERP Mini (Percetakan & Konveksi) - Abadi Sentosa**
**Tanggal Dokumen:** 30 Maret 2026

---

## 1. Introduction

Dokumen *Scope Management Plan* ini disusun untuk memberikan panduan komprehensif mengenai bagaimana ruang lingkup (scope) dari proyek pengembangan Sistem Informasi Manajemen ERP Mini untuk Abadi Sentosa akan didefinisikan, dikembangkan, dipantau, dikendalikan, dan divalidasi. Tujuan utama dari manajemen ruang lingkup dalam proyek ini adalah untuk memastikan bahwa proyek mencakup semua pekerjaan yang diperlukan, dan hanya pekerjaan yang diperlukan, demi menyelesaikan proyek secara sukses tanpa adanya pembengkakan atau penyimpangan pekerjaan yang tidak direncanakan.

Pengelolaan ruang lingkup sangat penting mengingat kompleksitas operasional Abadi Sentosa yang menaungi dua divisi utama, yakni Percetakan dan Konveksi. Tanpa adanya batasan yang jelas, risiko penggabungan alur kerja yang tidak relevan antar divisi dapat terjadi, yang berpotensi menghambat peluncuran sistem. Secara umum, proyek ini bertujuan untuk mendigitalisasi proses bisnis operasional yang mencakup pencatatan faktur penjualan, pembelian bahan baku, manajemen stok terpusat (shared stock), pengelolaan utang piutang perusahaan, penyusunan laporan keuangan otomatis, serta manajemen sumber daya manusia termasuk penggajian dan kasbon karyawan. Melalui dokumen ini, seluruh pemangku kepentingan memiliki satu pemahaman yang seragam mengenai batasan produk akhir yang akan diserahterimakan.

## 2. Scope Management Approach

Pendekatan manajemen ruang lingkup pada proyek ini bertumpu pada kolaborasi proaktif dan terdokumentasi antara tim pengembang dan pihak manajemen Abadi Sentosa. Secara struktural, Project Manager bertanggung jawab penuh atas pelaksanaan komitmen ruang lingkup, mengawasi setiap elemen pekerjaan agar tetap sejalan dengan *Project Scope Statement*.

Ruang lingkup proyek didefinisikan secara iteratif melalui serangkaian sesi analisis kebutuhan bisnis yang melibatkan perwakilan dari Divisi Percetakan, Divisi Konveksi, dan tim Keuangan Pusat. Hasil dari analisis ini kemudian didokumentasikan dalam spesifikasi teknis dan fungsional sistem. Setelah fase pengembangan dimulai, evaluasi dan verifikasi ruang lingkup akan diukur berdasarkan prototipe dan hasil uji coba modul yang mendemonstrasikan fungsionalitas sistem sesuai kriteria penerimaan yang disetujui.

Apabila di tengah pelaksanaan proyek terdapat kebutuhan fitur tambahan atau perubahan alur (seperti penyesuaian logika sinkronisasi stok atau penambahan format cetak faktur), maka perubahan tersebut wajib melalui mekanisme *Change Request*. Step-by-step proses perubahan tersebut adalah:
1. **Identifikasi & Pengajuan:** Pihak yang mengusulkan perubahan menyusun dokumen *Change Request Form* yang menjelaskan detail fitur tambahan beserta alasan bisnisnya.
2. **Evaluasi Dampak:** Project Manager dan Lead Developer mengevaluasi dampak pengajuan tersebut terhadap jadwal (timeline), biaya, dan arsitektur sistem yang ada.
3. **Persetujuan (Approval):** Evaluasi dipresentasikan kepada Project Sponsor. Tidak ada perubahan ruang lingkup yang boleh diimplementasikan sebelum mendapatkan persetujuan tertulis dari pihak Project Sponsor.

Otoritas tertinggi terkait penyetujuan dan penolakan perubahan ruang lingkup proyek dipegang sepenuhnya oleh Project Sponsor selaku representasi eksekutif Abadi Sentosa.

## 3. Roles and Responsibilities

Tabel berikut menguraikan peran dan tanggung jawab spesifik dari setiap anggota kunci dalam mengelola ruang lingkup proyek:

| Role | Tanggung Jawab Terkait Scope Management |
| :--- | :--- |
| **Project Sponsor** | Mengesahkan kelayakan *Project Scope Statement*, menyetujui ketersediaan anggaran, mengevaluasi dan menolak/menyetujui *Change Request* yang berdampak pada anggaran dan jadwal, serta memberikan penerimaan final (Sponsor Acceptance). |
| **Project Manager** | Mengkoordinasikan seluruh proses pengumpulan kebutuhan, menyusun dan mengawasi implementasi *Scope Management Plan*, bertindak sebagai jembatan komunikasi antara tim bisnis dan pengembang, serta menganalisis dampak dari setiap *Change Request*. |
| **Lead Developer** | Memberikan penilaian kelayakan teknis atas setiap ruang lingkup yang diusulkan, memastikan bahwa arsitektur sistem Mampu mengakomodasi scope yang disetujui, dan memantau kode agar terhindar dari pengembangan fitur yang melenceng (*gold plating*). |
| **Division Head (Percetakan & Konveksi)** | Menyediakan informasi rinci mengenai proses bisnis divisinya, membantu menyusun definisi kebutuhan, dan melakukan validasi kelayakan *deliverables* pada tahap akhir. |

## 4. Scope Definition

Proses penentuan ruang lingkup proyek ini bermula dari analisis mendalam terhadap metode operasional Abadi Sentosa saat ini yang masih terfragmentasi. Kumpulan *requirement* (kebutuhan sistem) didapatkan melalui serangkaian diskusi tatap muka dan wawancara semi-terstruktur dengan para kepala divisi (Percetakan dan Konveksi) serta tim administrasi keuangan. Fokus utama diskusi adalah menemukan irisan (overlap) dan batasan antar divisi, seperti perbedaan alur layanan namun dengan entitas keuangan yang bermuara pada kas pusat.

Metode analisis dokumen juga digunakan, di mana formulir faktur penjualan manual, catatan kasbon karyawan, dan buku besar utang-piutang perusahaan diekstraksi ke dalam bentuk *User Stories* dan alur data sistem. 

Setiap *User Story* dan kebutuhan bisnis tersebut kemudian diterjemahkan menjadi batasan ruang lingkup dengan cara memilah mana yang bersifat esensial (sebagai *Minimum Viable Product* wajib) dan mana yang merupakan fitur mewah (*nice-to-have*). Misalnya, kebutuhan pengelolaan inventori untuk barang dengan identitas fisik yang sama namun berbeda kode, diakomodasi melalui fitur sinkronisasi stok bersama (*Shared Stock*). Semua hasil saringan fitur wajib ini didefinisikan ke dalam *Project Scope Statement* yang mengikat.

## 5. Project Scope Statement

### Deskripsi Produk
Aplikasi ERP Mini berbasis web berbantuan arsitektur Laravel dan Vite yang berfungsi sebagai pusat manajemen terpadu bagi operasional harian percetakan dan konveksi. Sistem ini dirancang dengan akses berbasis otorisasi khusus (contoh: Admin Pusat, Admin Konveksi, dan Kasir Faktur), di mana alur data keuangan divisikan secara logis namun dapat dikompilasi menjadi laporan komprehensif tanpa menghilangkan detail per transaksi.

### Daftar Deliverables Utama
1. **Modul Autentikasi & Otorisasi:** Sistem login dengan rute dan sidebar antarmuka yang menyesuaikan visibilitas berdasarkan hak akses masing-masing pengguna.
2. **Modul Master Data:** Sistem manajemen data pelanggan, supplier, dan data barang (beserta pengaturan kode *Shared Stock*).
3. **Modul Transaksi Operasional:** Fitur pembuatan, pengelolaan, pencetakan faktur penjualan, dan pencatatan pembelian bahan baku.
4. **Modul SDM:** Sistem manajemen rekap gaji karyawan dan pencatatan riwayat utang/kasbon karyawan.
5. **Modul Keuangan:** Pengelolaan tagihan utang/piutang perusahaan dan generator laporan keuangan periodik yang terfilter per divisi.

### Acceptance Criteria (Kriteria Keberhasilan)
Sistem dinyatakan berhasil apabila fungsionalitas inti dapat dijalankan tanpa error kritis, ditandai dengan: berhasilnya pembuatan faktur yang langsung mendeduksi stok barang terkait (serta *shared stock* terkait), sistem kasbon karyawan terintegrasi utuh dengan perhitungan gaji, laporan tersaji valid sesuai rentang waktu, dan aplikasi berhasil diluncurkan (*deployed*) serta terintegrasi dengan repository versi stabil di GitHub.

### Project Exclusions (Di Luar Ruang Lingkup)
Proyek ini **tidak mencakup**:
1. Implementasi mesin pemindai sidik jari otomatis (biometrik/hardware) atau pemindai *barcode* fisik pihak ketiga untuk absensi karyawan dan inventori.
2. Migrasi data transaksi dari sistem lama melebihi periode 6 bulan terakhir.
3. Ketersediaan aplikasi berbasis platform *mobile* native (Android/iOS) standalone. Desain akhir hanya direpresentasikan melalui tata letak web responsif di peramban.

### Constraints (Batasan Proyek)
Keterbatasan waktu pengembangan peluncuran versi stabil *(release deadline)* menyesuaikan jadwal operasional internal tanpa menunda tutup buku bulanan periode berjalan. Pengembangan harus dilakukan semata-mata dengan susunan arsitektur Laravel serta Vanilla CSS sesuai kesepakatan spesifikasi sistem.

### Assumptions (Asumsi Proyek)
Diasumsikan bahwa Abadi Sentosa akan menyediakan layanan *hosting/server* berkecepatan cukup berikut dengan kelengkapan *domain* yang disiapkan. Seluruh pihak yang berkepentingan akan meninjau *deliverables* secara tepat waktu untuk menghindari penundaan laju pengerjaan di fase lanjutan.

## 6. Work Breakdown Structure (WBS)

Untuk mengorganisasikan pelaksanaan, pekerjaan diturunkan melalui hierarki *Work Breakdown Structure* yang terbagi ke dalam empat fase utama:

1. **Fase Inisiasi & Konfigurasi**
   - *1.1 Environment Setup:* Persiapan sistem lokal, instalasi *framework* Laravel, Node.js, Vite, serta inisialisasi basis data MySQL awal.
   - *1.2 Repository Management:* Pembuatan struktur Git dan penyelarasan cabang (*branch*) ke penyimpanan sinkron (GitHub).

2. **Fase Pengembangan Basis Data & Master Data**
   - *2.1 Migrations & Models:* Perancangan dan pembuatan skema matriks relasi MySQL untuk seluruh modul bisnis, termasuk logika *shared stock*.
   - *2.2 Seeders Generation:* Injeksi akun pengguna *default* dan pengaturan relasi peranan hak akses (role & division).

3. **Fase Implementasi Modul Bisnis (Frontend & Backend)**
   - *3.1 Modul Transaksi (Invoices & Purchases):* Pengembangan antarmuka form pembuatan faktur manual hingga rekap otomatisasi sinkronisasi hitungan stok.
   - *3.2 Modul SDM (Payroll & Kasbon):* Pengembangan sistem pencatatan uang muka (kasbon) dan integrasi nominalnya ke fitur penerbitan slip penggajian.
   - *3.3 Modul Laporan Keuangan:* Pembuatan algoritma pengolahan data riwayat debet/kredit menjadi antarmuka grafik atau ringkasan numerik berfilter waktu dan divisi.

4. **Fase Pengujian & Deployment**
   - *4.1 Bug Fixing & QA:* Eksekusi skenario uji coba, validasi logika pengurangan persediaan barang dan sesi lintas kewenangan pengguna.
   - *4.2 Final Push:* Penyematan seluruh penyesuaian fungsional ke lingkungan produksi dan validasi serah terima keseluruhan kepada pelindung proyek.

## 7. Scope Verification

Proses verifikasi atas ruang lingkup pekerjaan dirancang secara formatif. Deliverables yang dihasilkan di tiap akhir fase WBS akan diverifikasi kelayakannya dalam pertemuan demonstrasi fungsional (demo fitur). 

Proses validasi ini dipimpin secara praktikal oleh Division Head serta representasi unit fungsional terkait (misalnya Kepala Produksi untuk modul transaksi, atau Tim Finansial untuk memeriksa validitas angka neraca). Mereka akan menjalankan skenario transaksi uji menggunakan akun simulasi seperti *Kasir Faktur*, untuk mengonfirmasi bahwa batasan antarmuka dan komputasi persediaan bekerja sebagaimana spesifikasi. Apabila fungsionalitas telah terpenuhi, Project Manager akan mencatatkan penyerahan berkas sebagai laporan tercapai, dan kelak direkap pada akhir durasi proyek demi mengamankan penerimaan operasional (Approval).

## 8. Scope Control

Pengendalian ruang lingkup krusial agar lintasan proyek tidak melebar tanpa kesepakatan tertulis. Pencegahan difokuskan pada manajemen komunikasi yang disiplin: pengembang (developer) dilarang mengimplementasikan fitur tambahan hanya dari diskusi lisan di lorong (hallway conversations) tanpa pencatatan ke dalam tiket proyek.

Jika muncul permintaan baru (*Change Request*), maka proses detail pelaksanaannya adalah:
1. Permintaan dicatatkan secara formal, kemudian dimitigasi terlebih dahulu—apakah ia benar-benar urgensi yang berdampak masif atau hanya penyempurnaan kosmetik (Nice to Have).
2. Apabila berdampak finansial atau mengubah fundamental arsitektur struktur database yang lama, maka permintaan dibekukan hingga *Project Sponsor* menyisihkan kompensasi rentang waktu atau anggaran ekstra bagi tim pengembang.
3. Terkait isu pelebaran ruang lingkup yang tak disadari (*Scope Creep*), Project Manager bertugas mengadakan rapat sinkronisasi harian atau mingguan untuk memastikan pengembang murni menuntaskan tugas dari daftar tunggu terotorisasi. Apabila item pekerjaan melenceng, tugas akan seketika diinterupsi dan dikembalikan ke rel spesifikasi utama WBS.

## 9. Sponsor Acceptance

Bagian ini merupakan elemen persetujuan final yang dieksekusi pasca proses pengembangan dan verifikasi lapangan usai direalisasikan secara teruji, pertanda dokumentasi teknis serta fungsional proyek beralih kendali kepada Abadi Sentosa secara penuh.

Dengan membubuhkan nama dan tanda tangan di bawah ini, Sponsor Proyek menyetujui detail keseluruhan *Scope Management Plan* dari implementasi Sistem Informasi Manajemen ERP Mini Abadi Sentosa, serta memahami bahwa segala penambahan fungsi di luar dokumen yang disepakati bersama akan memacu mekanisme adaptasi *Change Request* formal.

&nbsp;

**Disetujui Oleh:**

&nbsp;

______________________________  
**( Nama Lengkap Project Sponsor )**  
*Jabatan/Titel di Abadi Sentosa*  
**Tanggal:** _______________  

&nbsp;

______________________________  
**( Nama Lengkap Project Manager )**  
*Lead Developer ERP System*  
**Tanggal:** _______________  
