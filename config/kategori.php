<?php

return [
    'default' => env('KATEGORI_DEFAULT', 'Umum'),
    'form_options' => [
        'Umum',
        'Administrasi Kependudukan',
        'Media dan Informasi Layanan',
        'SOP dan Alur Layanan',
        'Waktu Pelayanan',
        'Biaya Layanan',
        'Produk Layanan',
        'Petugas Layanan',
        'Sarana dan Prasarana Layanan',
        'Gratifikasi',
        'Layanan Konsultasi',
        'Lainnya',
    ],
    'descriptions' => [
        'Media dan Informasi Layanan' => 'Informasi layanan, publikasi, dan media.',
        'SOP dan Alur Layanan' => 'Prosedur/SOP dan alur pelayanan.',
        'Waktu Pelayanan' => 'Jam layanan, ketepatan waktu, dan durasi.',
        'Biaya Layanan' => 'Biaya, tarif, dan transparansi biaya.',
        'Produk Layanan' => 'Hasil/produk layanan dan kualitasnya.',
        'Petugas Layanan' => 'Sikap, kompetensi, dan respons petugas.',
        'Sarana dan Prasarana Layanan' => 'Fasilitas, sarana, dan prasarana layanan.',
        'Gratifikasi' => 'Permintaan/pemberian gratifikasi atau pungli.',
        'Layanan Konsultasi' => 'Informasi dan konsultasi layanan.',
        'Lainnya' => 'Keluhan di luar kategori lain.',
        'Administrasi Kependudukan' => 'KTP, KK, akta, dan layanan kependudukan.',
        'Umum' => 'Kategori umum',
    ],
    'rules' => [
        // 10. GRATIFIKASI - Prioritas tertinggi
        'Gratifikasi' => [
            'pungli', 'sogok', 'suap', 'amplop', 'uang pelicin', 'minta uang', 'kasih uang',
            'pemerasan', 'tips', 'gratifikasi', 'korupsi', 'pelicin', 'bayar diluar',
            'oknum minta', 'pungutan liar', 'uang rokok', 'uang bensin', 'setoran',
        ],
        
        // 6. BIAYA LAYANAN
        'Biaya Layanan' => [
            'biaya', 'tarif', 'bayar berapa', 'harganya', 'uang admin', 'biaya admin',
            'mahal', 'gratis tidak', 'retribusi', 'ongkos', 'kwitansi', 'pembayaran',
            'biaya cetak', 'rincian biaya', 'transparansi', 'tarif resmi', 'berapa harga',
        ],
        
        // 9. SARANA DAN PRASARANA LAYANAN
        'Sarana dan Prasarana Layanan' => [
            'fasilitas', 'tinta', 'blanko', 'blangko', 'habis', 'kosong', 'stok', 'rusak',
            'komputer', 'alat', 'jaringan', 'listrik', 'mati lampu', 'server', 'down',
            'offline', 'eror', 'panas', 'kursi', 'toilet', 'wc', 'kamar mandi', 'parkir',
            'gedung', 'kantor', 'ruang tunggu', 'sempit', 'lantai', 'kotor', 'bersih',
            'bau', 'sampah', 'berantakan', 'atap', 'bocor', 'ac', 'kipas', 'antrean online',
            'internet', 'wifi', 'sinyal', 'layar', 'mesin cetak', 'antre digital', 'loket tutup',
        ],
        
        // 5. WAKTU PELAYANAN
        'Waktu Pelayanan' => [
            'jam buka', 'jam tutup', 'buka jam', 'tutup jam', 'istirahat', 'jadwal',
            'hari kerja', 'sabtu', 'minggu', 'libur', 'tanggal merah', 'telat buka',
            'tutup cepat', 'siang', 'pagi', 'sore', 'lama menunggu', 'menunggu lama',
            'durasi', 'ngaret', 'antri berjam-jam', 'lelet', 'cepat dong', 'segera',
            'kapan selesai', 'berapa lama', 'estimasi', 'deadline', 'waktu pelayanan',
            'jam pelayanan', 'jam layanan', 'jam kerja', 'waktu operasional',
            'jadwal layanan', 'jadwal pelayanan', 'waktu tunggu', 'antrian lama',
            'keterlambatan', 'terlambat', 'durasi pelayanan', 'lama proses',
        ],
        
        // 8. PETUGAS LAYANAN
        'Petugas Layanan' => [
            'petugas', 'pegawai', 'jaga loket', 'orang capil', 'staff', 'pelayanan',
            'lambat', 'lama', 'jutek', 'marah', 'tidak ramah', 'sopan', 'lelet', 'antri',
            'antrean', 'panggil', 'respon', 'tanggapan', 'cuek', 'ngobrol', 'sibuk hp',
            'main hp', 'sombong', 'kasar', 'bentak', 'pilih kasih', 'diskriminatif',
            'tidak bantu', 'acuh', 'galak', 'muka masam', 'tidak profesional',
        ],
        
        // 4. SOP DAN ALUR LAYANAN
        'SOP dan Alur Layanan' => [
            'alur', 'prosedur', 'sop', 'aturan', 'kebijakan', 'berbelit', 'ribet', 'susah',
            'bolak-balik', 'birokrasi', 'pindah loket', 'tahapan', 'langkah', 'persyaratan',
            'mekanisme', 'syarat susah', 'dipimpong', 'oper sana', 'oper sini', 'bingung alur',
            'salah loket', 'prosedur baru', 'persyaratan tidak jelas',
        ],
        
        // 7. PRODUK LAYANAN
        'Produk Layanan' => [
            'kualitas ktp', 'kualitas kk', 'buram', 'jelek', 'pudar', 'tidak jelas',
            'salah cetak', 'salah ketik', 'typo', 'salah nama', 'salah data', 'foto gelap',
            'hasil cetak', 'rusak fisik', 'terkelupas', 'plastik copot', 'chip rusak',
            'ttd salah', 'salah alamat', 'data tidak sinkron',
        ],
        
        // 3. MEDIA DAN INFORMASI LAYANAN
        'Media dan Informasi Layanan' => [
            'website', 'aplikasi', 'sosmed', 'instagram', 'facebook', 'wa', 'whatsapp',
            'nomor telepon', 'call center', 'email', 'balas', 'internet', 'berita',
            'pengumuman', 'brosur', 'spanduk', 'sosialisasi', 'online', 'web error',
            'tidak bisa login', 'link rusak', 'info salah', 'update data',
        ],
        
        // 11. LAYANAN KONSULTASI
        'Layanan Konsultasi' => [
            'konsultasi', 'tanya', 'bertanya', 'informasi', 'info', 'bingung', 'cara',
            'bagaimana', 'syarat', 'panduan', 'bantuan', 'jelaskan', 'pencerahan',
            'solusi', 'menanyakan', 'curhat', 'mohon petunjuk', 'arahan', 'minta penjelasan',
        ],
        
        // 2. ADMINISTRASI KEPENDUDUKAN
        'Administrasi Kependudukan' => [
            'ktp', 'ektp', 'e-ktp', 'kk', 'kartu keluarga', 'akta', 'akte', 'kelahiran',
            'kematian', 'kawin', 'nikah', 'cerai', 'pindah', 'datang', 'surat', 'domisili',
            'nik', 'no kk', 'data', 'dokumen', 'perekaman', 'kia', 'kartu anak', 'skpwni',
            'legalisir', 'pindah datang', 'ubah data', 'kependudukan', 'sipil',
            'dukcapil', 'pindah alamat', 'surat keterangan',
        ],
        
        // 1. UMUM - Fallback untuk saran/apresiasi
        'Umum' => [
            'saran', 'masukan', 'terima kasih', 'halo', 'selamat', 'apresiasi',
            'komplain', 'mohon bantuan', 'kepada yth',
        ],
        
        // 12. LAINNYA - Fallback terakhir
        'Lainnya' => [
            'lain-lain', 'diluar tema', 'test', 'uji coba', 'nyoba', 'random', 'macam-macam',
        ],
    ],
    'python' => env('KATEGORI_PYTHON', env('ML_PYTHON', 'python')),
    'script' => base_path('tools/predict_kategori.py'),
    'model_dir' => env('KATEGORI_MODEL_DIR', storage_path('app/ml_kategori')),
    'api_url' => env('KATEGORI_API_URL', env('ML_API_URL', '')),
];
