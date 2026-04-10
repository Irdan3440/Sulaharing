<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Disease;
use App\Models\Symptom;
use App\Models\Rule;
use App\Models\Article;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === USERS ===
        $admin = User::create([
            'name' => 'Admin SulaHaring',
            'email' => 'admin@sulaharing.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $pakar = User::create([
            'name' => 'Dr. Siti Rahayu, M.Psi',
            'email' => 'pakar@sulaharing.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pakar',
            'fakultas' => 'FKIP',
            'is_active' => true,
        ]);

        $mahasiswa = User::create([
            'name' => 'Irdan Mahasiswa',
            'email' => 'irdan@universitas.ac.id',
            'password' => Hash::make('password'),
            'nim' => '2024010001',
            'fakultas' => 'FKIP',
            'jurusan' => 'Bimbingan Konseling',
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi@universitas.ac.id',
            'password' => Hash::make('password'),
            'nim' => '2024010002',
            'fakultas' => 'FT',
            'jurusan' => 'Teknik Informatika',
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        // === DISEASES (4 klasifikasi depresi) ===
        $d1 = Disease::create([
            'kode' => 'D001',
            'nama' => 'Depresi Minimal',
            'deskripsi' => 'Tidak menunjukkan gejala depresi yang signifikan. Kondisi mental relatif sehat.',
            'rentang_skor' => '0-13',
            'saran_penanganan' => 'Tetap jaga pola hidup sehat. Lakukan aktivitas yang menyenangkan, olahraga teratur, dan pertahankan hubungan sosial yang positif.',
            'rujukan_helpdesk' => null,
            'created_by' => $pakar->id,
        ]);

        $d2 = Disease::create([
            'kode' => 'D002',
            'nama' => 'Depresi Ringan',
            'deskripsi' => 'Menunjukkan gejala depresi ringan. Biasanya muncul perasaan sedih, kehilangan minat, atau gangguan tidur ringan.',
            'rentang_skor' => '14-19',
            'saran_penanganan' => 'Terapkan pola tidur teratur, makan bergizi, dan teknik relaksasi. Pertimbangkan konseling kampus.',
            'rujukan_helpdesk' => 'Unit Pelayanan Konseling Kampus',
            'created_by' => $pakar->id,
        ]);

        $d3 = Disease::create([
            'kode' => 'D003',
            'nama' => 'Depresi Sedang',
            'deskripsi' => 'Gejala depresi sedang yang memerlukan perhatian serius. Aktivitas akademik dan sosial mulai terganggu.',
            'rentang_skor' => '20-28',
            'saran_penanganan' => 'Segera konseling dengan psikolog kampus. Bicarakan kondisi dengan orang terdekat. Pertimbangkan terapi profesional.',
            'rujukan_helpdesk' => 'Unit Pelayanan Konseling Kampus / Psikolog Profesional',
            'created_by' => $pakar->id,
        ]);

        $d4 = Disease::create([
            'kode' => 'D004',
            'nama' => 'Depresi Berat',
            'deskripsi' => 'Gejala depresi berat yang memerlukan penanganan profesional segera. Risiko tinggi terhadap keselamatan.',
            'rentang_skor' => '29-63',
            'saran_penanganan' => 'SEGERA hubungi layanan krisis. Konsultasi psikiater diperlukan. Pertimbangkan cuti akademik.',
            'rujukan_helpdesk' => 'Into The Light — 119 ext 8 / RSJ / Psikiater',
            'created_by' => $pakar->id,
        ]);

        // === SYMPTOMS (21 indikator BDI-II) ===
        $symptoms = [
            ['kode' => 'G01', 'nama' => 'Kesedihan', 'aspek' => 'afektif', 'deskripsi' => 'Mengukur tingkat kesedihan yang dialami mahasiswa terkait situasi akademik dan kehidupan kampus.', 'pilihan_jawaban' => ['Saya tidak merasa sedih', 'Saya merasa sedih sekali', 'Saya sering merasa sedih, terutama terkait tugas atau kegiatan kuliah', 'Saya sangat sedih sekali hampir sepanjang waktu sehingga sulit merasa sulit fokus sekali']],
            ['kode' => 'G02', 'nama' => 'Perasaan Bersalah', 'aspek' => 'kognitif', 'deskripsi' => 'Mengukur tingkat bersalah yang dialami mahasiswa terkait performa akademik.', 'pilihan_jawaban' => ['Saya jarang merasa bersalah atas hal-hal yang saya lakukan di kampus', 'Saya kadang merasa bersalah karena tidak maksimal belajar', 'Saya sering merasa bersalah kepada dosen, teman, atau keluarga', 'Saya selalu merasa bersalah atas kegagalan saya sebagai mahasiswa']],
            ['kode' => 'G03', 'nama' => 'Pikiran Bunuh Diri', 'aspek' => 'kognitif', 'deskripsi' => 'Mengukur adanya pikiran atau keinginan untuk bunuh diri akibat tekanan akademik.', 'pilihan_jawaban' => ['Saya tidak memiliki pikiran untuk bunuh diri', 'Saya pernah berpikir tentang bunuh diri, tapi tidak melakukannya', 'Saya ingin bunuh diri', 'Saya akan bunuh diri jika ada kesempatan']],
            ['kode' => 'G04', 'nama' => 'Menangis', 'aspek' => 'afektif', 'deskripsi' => 'Mengukur seberapa sering mahasiswa menangis terkait tekanan emosional.', 'pilihan_jawaban' => ['Saya tidak lebih sering menangis dari biasanya', 'Saya kadang menangis setelah mendapat hasil ujian buruk', 'Saya sering menangis karena tekanan kuliah', 'Saya selalu ingin menangis tapi kadang tidak bisa']],
            ['kode' => 'G05', 'nama' => 'Pesimisme', 'aspek' => 'kognitif', 'deskripsi' => 'Mengukur tingkat pesimisme terhadap masa depan akademik dan karier.', 'pilihan_jawaban' => ['Saya tidak merasa pesimis tentang masa depan', 'Saya kadang merasa ragu dengan prospek karier', 'Saya merasa tidak yakin akan berhasil setelah lulus', 'Saya merasa masa depan saya suram dan tidak ada harapan']],
            ['kode' => 'G06', 'nama' => 'Perasaan Gagal', 'aspek' => 'kognitif', 'deskripsi' => 'Mengukur perasaan gagal dalam konteks akademik.', 'pilihan_jawaban' => ['Saya tidak merasa gagal', 'Saya kadang merasa kurang berhasil dibanding teman', 'Saya sering merasa gagal dalam perkuliahan', 'Saya merasa benar-benar gagal sebagai mahasiswa']],
            ['kode' => 'G07', 'nama' => 'Kehilangan Kesenangan', 'aspek' => 'afektif', 'deskripsi' => 'Mengukur hilangnya kesenangan dalam aktivitas.', 'pilihan_jawaban' => ['Saya masih menikmati hal-hal yang biasa saya sukai', 'Saya agak kurang menikmati hal-hal tersebut', 'Saya hampir tidak menikmati hal-hal yang biasa saya sukai', 'Saya tidak menikmati apapun sama sekali']],
            ['kode' => 'G08', 'nama' => 'Agitasi', 'aspek' => 'afektif', 'deskripsi' => 'Mengukur tingkat gelisah atau agitasi.', 'pilihan_jawaban' => ['Saya tidak merasa lebih gelisah dari biasanya', 'Saya merasa sedikit lebih gelisah', 'Saya sangat gelisah sehingga sulit duduk tenang', 'Saya sangat gelisah sehingga harus terus bergerak']],
            ['kode' => 'G09', 'nama' => 'Kesulitan Mengambil Keputusan', 'aspek' => 'kognitif', 'deskripsi' => 'Mengukur kemampuan mengambil keputusan sehari-hari.', 'pilihan_jawaban' => ['Saya dapat mengambil keputusan seperti biasa', 'Saya kadang menunda mengambil keputusan', 'Saya sangat sulit mengambil keputusan', 'Saya tidak dapat mengambil keputusan apapun']],
            ['kode' => 'G10', 'nama' => 'Perasaan Tidak Berharga', 'aspek' => 'kognitif', 'deskripsi' => 'Mengukur perasaan tidak berharga.', 'pilihan_jawaban' => ['Saya tidak merasa tidak berharga', 'Saya kadang merasa kurang berharga', 'Saya merasa jauh lebih tidak berharga dibanding orang lain', 'Saya merasa benar-benar tidak berharga']],
            ['kode' => 'G11', 'nama' => 'Kehilangan Energi', 'aspek' => 'somatik', 'deskripsi' => 'Mengukur tingkat energi dan kelelahan.', 'pilihan_jawaban' => ['Saya mempunyai energi yang cukup seperti biasa', 'Saya kekurangan energi dibanding biasanya', 'Saya tidak punya energi yang cukup untuk banyak hal', 'Saya tidak punya energi sama sekali untuk melakukan apapun']],
            ['kode' => 'G12', 'nama' => 'Gangguan Tidur', 'aspek' => 'somatik', 'deskripsi' => 'Mengukur perubahan pola tidur.', 'pilihan_jawaban' => ['Pola tidur saya tidak berubah', 'Saya tidur sedikit lebih banyak/sedikit dari biasanya', 'Saya tidur jauh lebih banyak/sedikit dari biasanya', 'Saya hampir tidur sepanjang hari atau bangun 1-2 jam lebih awal']],
            ['kode' => 'G13', 'nama' => 'Kesulitan Konsentrasi', 'aspek' => 'kognitif', 'deskripsi' => 'Mengukur kemampuan konsentrasi dalam konteks akademik.', 'pilihan_jawaban' => ['Saya dapat berkonsentrasi seperti biasa', 'Saya agak sulit berkonsentrasi saat kuliah', 'Saya sangat sulit berkonsentrasi pada apapun', 'Saya tidak dapat berkonsentrasi sama sekali']],
            ['kode' => 'G14', 'nama' => 'Perubahan Nafsu Makan', 'aspek' => 'somatik', 'deskripsi' => 'Mengukur perubahan nafsu makan.', 'pilihan_jawaban' => ['Nafsu makan saya tidak berubah', 'Nafsu makan saya sedikit berkurang/bertambah', 'Nafsu makan saya sangat berkurang/bertambah', 'Saya tidak nafsu makan sama sekali']],
            ['kode' => 'G15', 'nama' => 'Iritabilitas', 'aspek' => 'afektif', 'deskripsi' => 'Mengukur tingkat iritabilitas atau mudah tersinggung.', 'pilihan_jawaban' => ['Saya tidak lebih mudah tersinggung dari biasanya', 'Saya sedikit lebih mudah tersinggung', 'Saya sangat mudah tersinggung', 'Saya tersinggung sepanjang waktu']],
            ['kode' => 'G16', 'nama' => 'Kehilangan Minat', 'aspek' => 'afektif', 'deskripsi' => 'Mengukur hilangnya minat terhadap aktivitas dan orang lain.', 'pilihan_jawaban' => ['Saya masih tertarik pada orang lain dan aktivitas', 'Saya agak kurang tertarik pada beberapa hal', 'Saya kehilangan sebagian besar minat pada hal-hal', 'Saya sama sekali tidak tertarik pada apapun']],
            ['kode' => 'G17', 'nama' => 'Perasaan Dihukum', 'aspek' => 'kognitif', 'deskripsi' => 'Mengukur perasaan dihukum atau layak menerima hukuman.', 'pilihan_jawaban' => ['Saya tidak merasa sedang dihukum', 'Saya merasa mungkin sedang dihukum', 'Saya berharap untuk dihukum', 'Saya merasa sedang dihukum']],
            ['kode' => 'G18', 'nama' => 'Mengkritik Diri Sendiri', 'aspek' => 'kognitif', 'deskripsi' => 'Mengukur tingkat self-criticism.', 'pilihan_jawaban' => ['Saya tidak mengkritik diri sendiri secara berlebihan', 'Saya kadang mengkritik diri sendiri', 'Saya sering mengkritik diri sendiri atas semua kesalahan', 'Saya menyalahkan diri sendiri atas segala hal buruk']],
            ['kode' => 'G19', 'nama' => 'Perubahan Berat Badan', 'aspek' => 'somatik', 'deskripsi' => 'Mengukur perubahan berat badan.', 'pilihan_jawaban' => ['Berat badan saya tidak berubah', 'Berat badan saya sedikit berubah', 'Berat badan saya berubah cukup signifikan', 'Berat badan saya berubah drastis']],
            ['kode' => 'G20', 'nama' => 'Kecemasan Kesehatan', 'aspek' => 'afektif', 'deskripsi' => 'Mengukur kecemasan tentang kesehatan fisik.', 'pilihan_jawaban' => ['Saya tidak khawatir tentang kesehatan', 'Saya agak khawatir tentang kesehatan', 'Saya sangat khawatir tentang masalah kesehatan', 'Saya terlalu khawatir sehingga sulit memikirkan hal lain']],
            ['kode' => 'G21', 'nama' => 'Kehilangan Minat Sosial', 'aspek' => 'afektif', 'deskripsi' => 'Mengukur hilangnya minat terhadap aktivitas sosial.', 'pilihan_jawaban' => ['Saya tidak merasakan perubahan pada minat saya', 'Saya agak kurang berminat dari biasanya', 'Saya jauh kurang berminat dari biasanya', 'Saya benar-benar kehilangan minat']],
        ];

        $symptomModels = [];
        foreach ($symptoms as $s) {
            $symptomModels[] = Symptom::create(array_merge($s, ['created_by' => $pakar->id]));
        }

        // === RULES (MB/MD for each symptom → each disease) ===
        // Rule: Higher answer → higher belief for more severe disease
        $diseases = [$d1, $d2, $d3, $d4];

        foreach ($symptomModels as $symptom) {
            // Minimal: high MB for low scores
            Rule::create(['symptom_id' => $symptom->id, 'disease_id' => $d1->id, 'mb' => 0.8, 'md' => 0.2, 'updated_by' => $pakar->id]);
            // Ringan
            Rule::create(['symptom_id' => $symptom->id, 'disease_id' => $d2->id, 'mb' => 0.6, 'md' => 0.2, 'updated_by' => $pakar->id]);
            // Sedang
            Rule::create(['symptom_id' => $symptom->id, 'disease_id' => $d3->id, 'mb' => 0.7, 'md' => 0.1, 'updated_by' => $pakar->id]);
            // Berat
            Rule::create(['symptom_id' => $symptom->id, 'disease_id' => $d4->id, 'mb' => 0.9, 'md' => 0.05, 'updated_by' => $pakar->id]);
        }

        // === ARTICLES ===
        Article::create([
            'title' => 'Mengenal Depresi: Lebih dari Sekadar Sedih',
            'slug' => 'mengenal-depresi',
            'category' => 'edukasi',
            'excerpt' => 'Depresi merupakan gangguan mood yang mempengaruhi cara seseorang berpikir, merasa, dan menjalani aktivitas sehari-hari.',
            'content' => '<p>Depresi adalah gangguan kesehatan mental yang ditandai dengan perasaan sedih yang berkepanjangan, kehilangan minat terhadap aktivitas yang biasanya dinikmati, serta berbagai gejala fisik dan psikologis lainnya.</p><p>Berbeda dengan rasa sedih biasa, depresi berlangsung lebih lama (minimal 2 minggu) dan mengganggu fungsi sehari-hari seseorang.</p>',
            'read_time' => 5,
            'author' => 'Tim SulaHaring',
            'is_published' => true,
            'created_by' => $admin->id,
        ]);

        Article::create([
            'title' => '5 Cara Menjaga Kesehatan Mental Saat Kuliah',
            'slug' => 'cara-menjaga-kesehatan-mental',
            'category' => 'tips',
            'excerpt' => 'Tekanan akademis dapat mempengaruhi kesehatan mental. Simak tips praktis untuk menjaga keseimbangan hidup di kampus.',
            'content' => '<p>Kehidupan perkuliahan membawa tantangan tersendiri yang dapat memengaruhi kesehatan mental mahasiswa.</p>',
            'read_time' => 4,
            'author' => 'Tim SulaHaring',
            'is_published' => true,
            'created_by' => $admin->id,
        ]);

        Article::create([
            'title' => 'Pentingnya Deteksi Dini Depresi pada Mahasiswa',
            'slug' => 'deteksi-dini-depresi',
            'category' => 'berita',
            'excerpt' => 'Penelitian menunjukkan bahwa deteksi dini dan intervensi tepat waktu dapat mencegah depresi berkembang lebih parah.',
            'content' => '<p>Data WHO menunjukkan bahwa depresi merupakan salah satu penyebab utama disabilitas di seluruh dunia.</p>',
            'read_time' => 6,
            'author' => 'Tim SulaHaring',
            'is_published' => true,
            'created_by' => $admin->id,
        ]);
    }
}
