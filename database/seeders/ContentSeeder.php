<?php

namespace Database\Seeders;

use App\Models\Ambassador;
use App\Models\Article;
use App\Models\Category;
use App\Models\Gallery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $people = [
            ['Yuswa Andika Kusuma', 'Bogor', 'Videografi', 'Video Dokumentasi & Film', 'Mengembangkan video dokumentasi kegiatan dan film'],
            ['Muhammad Fahriansyah Nasution', 'Bogor', 'Fotografi', 'Fotografi CAP', 'Membuat karya foto untuk kebutuhan kegiatan CAP'],
            ['Ammar Abdul Malik', 'Bogor', 'IoT dan jaringan', 'Smart Infus Monitoring System', 'Membangun prototipe pemantauan infus berbasis IoT'],
            ['Muhammad Rasya Putra Islamy', 'Bogor', 'Digital marketing', 'Brand/Produk & Digital Marketing', 'Mengembangkan promosi produk, SEO, desain dan produksi parfum'],
            ['Khalilah Umati Zahra', 'Bogor', 'Arsitektur dan interior', 'Desain Arsitektur dan Interior 3D', 'Membuat model 3D bangunan dan desain interior'],
            ['Zaira Nur Azizah', 'Bogor', 'Data analyst', 'CoreMan Cost untuk UMKM', 'Membuat alat Excel untuk HPP, harga jual, margin, laba dan BEP'],
            ['Diva Mawardayanti', 'Bogor', 'Desain grafis', 'Desain Grafis Komunikatif', 'Mempraktikkan teori dan prinsip desain grafis'],
            ['Dafa Rizky Saputra', 'Bogor', 'Otomotif', 'Rencana Alat Press Mangkok Kopling', 'Mengusulkan pembuatan alat press mangkok kopling; belum memastikan alat selesai'],
            ['Muhamad Maulana', 'Bogor', 'Otomotif', 'Restorasi & Modifikasi Motor Honda', 'Melaporkan kegiatan restorasi dan modifikasi motor Honda'],
            ['Siti Nur Komalasari', 'Bogor', 'Videografi', 'Videografi & Storytelling', 'Mengembangkan dokumentasi acara, pengambilan gambar dan editing'],
            ['Kayilla Sifa Marwah', 'Bogor', 'Desain grafis', 'Desain Grafis & Kolaborasi', 'Membuat desain dan belajar mengembangkan ide menjadi proyek'],
            ['Putri Sabrina Agustina', 'Bogor', 'Arsitektur dan interior', 'Tata Letak Interior', 'Menerapkan perpaduan warna, bentuk dan tata letak benda'],
            ['Nur Ariyanti Pertiwi', 'Bogor', 'Desain grafis', 'Desain Grafis', 'Membuat karya desain grafis dan memperdalam teknik desain'],
            ['Neng Karmila Nadia M.', 'Bogor', 'Data analyst', 'Finhealth & Analisis Excel', 'Mengolah data memakai Excel dan mengembangkan tools Finhealth'],
            ['Justine', 'Bogor', 'IoT dan jaringan', 'Smart Hydroponic', 'Mengembangkan sistem hidroponik berbasis otomatisasi'],
            ['Vincentius Gabriel Tobias Brahmantyo', 'Bogor', 'Fotografi', 'Fotografi Kreatif', 'Menerapkan teknik dan komposisi kamera serta editing konten'],
            ['Muhamad Phazri Septian', 'Bogor', 'Programmer', 'Website Biodiversitas', 'Membangun website informasi hewan dan biodiversitas'],
            ['Muhammad Abdul Azis', 'Bogor', 'IoT dan jaringan', 'Server Berbasis AI', 'Melaporkan pembuatan server berbasis AI'],
            ['Jibril Al Qoyyum Adi Putra', 'Bogor', 'Fotografi', 'Foto Cerita Siklus Tanpa Sisa', 'Membuat foto cerita pengolahan kotoran ayam menjadi pupuk'],
            ['Aprilia Agustina', 'Bogor', 'Desain grafis', 'Portofolio Desain Profesional', 'Menghasilkan desain banner, poster acara dan portofolio'],
            ['Ayu Dia Isnani Arum Nisa', 'Bogor', 'Programmer', 'Website dan Keberanian Berpendapat', 'Membuat website sambil mengembangkan keterampilan komunikasi'],
            ['Inesya Radian Navishah', 'Bogor', 'Desain grafis', 'Desain, Figma & Digital Creativity', 'Membuat desain, mengedit video dan berlatih Figma serta coding'],
            ['M Danda Fermana', 'Bogor', 'Digital marketing', 'Brand Pakaian Mandiri', 'Mengembangkan brand pakaian, identitas merek, desain dan pemasaran'],
            ['Hanipah', 'Bogor', 'Programmer', 'Website Terstruktur', 'Membangun website dengan alur logika lebih rapi'],
            ['Muhamad Elgy Fahri Hidayat', 'Bogor', 'Programmer', 'Website Self-Love Remaja', 'Membuat website edukasi self-love dan pembelajaran emosi remaja'],
        ];

        $clusters = ['Desain grafis', 'Programmer', 'Fotografi', 'IoT dan jaringan', 'Videografi', 'Digital marketing', 'Arsitektur dan interior', 'Data analyst', 'Otomotif'];
        Category::whereNotIn('name', $clusters)->delete();
        Ambassador::query()->delete();
        foreach ($people as [$name, $city, $discipline, $project, $summary]) {
            $category = Category::firstOrCreate(['slug' => Str::slug($discipline)], ['name' => $discipline]);
            Ambassador::updateOrCreate(['slug' => Str::slug($name)], [
                'category_id' => $category->id, 'name' => $name, 'title' => $project, 'city' => $city, 'discipline' => $discipline, 'cluster' => $discipline, 'status' => 'Project Mandiri', 'bio' => $summary, 'skills' => [$discipline], 'project_name' => $project, 'project_description' => $summary, 'is_published' => true,
            ]);
        }

        $articles = [
            ['Dari Motor Lawas Menjadi Kendaraan Listrik: Perjalanan Dimas & Reza Mengembangkan Kit RetroVolt PNP', 'Catatan Rekayasa Otomotif'],
            ['AgriNode di Lahan Cabai Sleman: Bagaimana LoRaWAN Mengatasi Masalah Sinyal Lemah di Perkebunan Rakyat', 'Internet of Things (IoT)'],
            ['Mengapa Bambu Laminasi Adalah Masa Depan Hunian Modular Tahan Gempa Indonesia', 'Arsitektur Berkelanjutan'],
            ['Menjalankan Model AI 7B Langsung di Browser dengan WebGPU Tanpa Server Cloud Mahal', 'Web Dev & Cloud'],
            ['Dokumentasi Bootcamp Kolaborasi Lintas Disiplin: Sinergi Telemetri Otomotif dan Aplikasi UI/UX', 'Kegiatan & Warta'],
            ['Uji Aerodinamika CFD Bodykit Carbon Pre-Preg di Sirkuit Sentul: Hasil Pengurangan Drag 14%', 'Mobil Efisiensi & Rekayasa'],
            ['Mendesain Antarmuka Inklusif untuk Penyandang Disabilitas Netra: Pengujian Haptic & Screen Reader', 'Desain Human-Centered'],
            ['Rekayasa Smart BMS: Menjaga Suhu Baterai Motor Listrik di Cuaca Tropis Ekstrem', 'Dev Log Permotoran'],
            ['Catatan Uji Coba AgriNode: Menghubungkan 15 Hektar Lahan Bawang dengan Panel Surya Mini', 'Warta Lapangan'],
        ];

        foreach ($articles as [$title, $categoryName]) {
            $category = Category::firstOrCreate(['slug' => Str::slug($categoryName)], ['name' => $categoryName]);
            Article::updateOrCreate(['slug' => Str::slug($title)], ['category_id' => $category->id, 'title' => $title, 'excerpt' => 'Dokumentasi terbuka karya dan eksperimen Champion Ambassador Project Fase 2.', 'body' => 'Catatan lapangan, proses riset, dan pembelajaran champion dalam mengembangkan karya terapan untuk Indonesia.', 'is_published' => true, 'published_at' => now()->subDays(rand(1, 30))]);
        }

        $galleries = [
            ['Perakitan & Kalibrasi Modular EV Kit', 'Bootcamp Inovasi Batch 1', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCzdTRq0STXj2f7bKso83c2cX1gNIclCRJnAZamXW4mj_0lC_Pc9Ae8h8SzyT5cZM-5sFuQ5HSC7ltuevddTBpqa6FIeW8csWSv_S93vcBGqWTLUK3Gu8kJ5fkeI7cRrHiv-PoXX8Uec8ti7uSMW2cuu87vaYbPomL_xK2BYlx-eYQa4rcVgZPcSKSUPzU8sVS7yNK0H1_cyl4j5YbT-J_XYGwDS43kpVTezYx8xU8_uPEq8VvTOlc'],
            ['Validasi Sensor AgriNode 1.2', 'Pengujian Lapangan IoT di Sleman', 'https://lh3.googleusercontent.com/aida-public/AB6AXuD-CG9NE_e_k1alO1DRIROP2930C3TFk-1YVAIwQ7YwezCqNgxXkMsKU3zqPORwKyw3Dl9S1NQmRvmOUuupyzTa5wY5va09pAlnG3ousLPX42ii-UFq6cGkzy2qlN4bXlayd4gPJjEkGHTTN1kAFQXNg0RMzIMwL3HZ3MJuSs-I98uaiV3F3hNcxlcEJQtniCd_3O7SqgCkPjFEyymvefgPpBpJKZ4EdgcTynLfb5i1c2dY4Q2Hjsk'],
            ['Uji Gempa Struktur Resilien', 'Fasad Kinetik & Bambu', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDXveQftqJWQWeulOVxqifTMZPmsGxzoWm2SInbZGJ1w_xvH743dY8OVms-FLDV5rTyhn0QzX_OzXkCl4F7p8V1l69ZzuinSNIDotuPCot61hg-AHYhkpxmffjuOZinIVtRsvV8dbl3KaBVkJPdSnb9eZ5OKhCXZVDDdSr6xv7pQDAoetnYtMJz9F7wqCmBC4yVePVLw_cPzAjEzpY_UoNwp5uaWwk5KJ0SIl5q84gdPFgzzIe_v64'],
            ['Optimasi Model Kompresi Citra', 'Kolaborasi Edge AI', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBX972ZTV0EDao47hVKWpTq2HNXnaWoI_rt8TJ6E_L9yDynfLiFlcK9tr5tPswDekquigRBiG5ExHAJlq-SXKkW1jhKEccnP-u8MYSc0tR8kSTYiNwlwYyzQwwAWUFKeDsxZWpnrlMmkm3wUaYN86jxgTuLsc2s8oQaf_y57AF_SxVDni6tbVdbnob4D6HYA-Wvcq9cyb7MLGvrn0U_CqD8f4zzkTR2LcM6oK6JuiMd9d1fDVNZrvU'],
            ['Evaluasi Suspensi KinetikEV', 'Uji Dinamika Lintasan', 'https://lh3.googleusercontent.com/aida-public/AB6AXuD0qjTxku_gIc2ER_pC08V5wlrWVO49TAekDLLq7fmWs1I0ha2FOIlGRFWhnlR1CiKzrFFTZXld9p0lKh2q9feyapQTg6Kf-k2xvY0uOkJV_jEtxoFcD1-q5zvHsqinGzVPtOjUo1ojNeK_6eglJ1R6WfSciqJidr3CJcrUy4WU71Mg2oNeueVzd7tgz4yXHB-99P8Tz96d12xfePvhUaiEK1lpgb0Mp59pLylQfvFjlc1yMsnUhaQ'],
        ];

        foreach ($galleries as [$title, $description, $image]) {
            Gallery::updateOrCreate(['title' => $title], ['description' => $description, 'image' => $image, 'is_published' => true]);
        }
    }
}
