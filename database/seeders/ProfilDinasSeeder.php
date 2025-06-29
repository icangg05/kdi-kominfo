<?php

namespace Database\Seeders;

use App\Models\ProfilDinas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilDinasSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $data = [
      [
        'jenis'  => 'sambutan-kadis',
        'konten' => '<p>Sebagai institusi yang bertanggung jawab dalam bidang teknologi informasi dan komunikasi, kami berkomitmen untuk memberikan layanan terbaik guna mendukung transformasi digital dan mewujudkan Kota Kendari sebagai Smart City yang maju, inovatif, dan berdaya saing.</p><p>Website ini hadir sebagai sarana informasi dan komunikasi antara pemerintah dan masyarakat. Kami mengajak seluruh masyarakat Kota Kendari untuk bersama-sama memanfaatkan teknologi informasi guna menciptakan kota yang lebih cerdas, terhubung, dan sejahtera.</p><p>Dengan semangat kolaborasi dan inovasi, mari kita wujudkan visi Kota Kendari sebagai pusat pertumbuhan ekonomi digital dan teknologi di wilayah Indonesia Timur.</p>'
      ],

      [
        'jenis'  => 'tagline-sambutan',
        'konten' =>  json_encode([
          [
            'id'    => 1,
            'value' => "Smart City Terdepan",
          ],
          [
            'id'    => 2,
            'value' => "Transformasi Digital",
          ],
          [
            'id'    => 3,
            'value' => "Inovasi Teknologi",
          ],
          [
            'id'    => 4,
            'value' =>  "Layanan Publik Prima",
          ],
          [
            'id'    => 5,
            'value' => "Keamanan Siber",
          ],
          [
            'id'    => 6,
            'value' => "Literasi Digital",
          ],
        ]),
      ],

      [
        'jenis'  => 'sejarah',
        'konten' => '<p>Dinas Komunikasi dan Informatika (Diskominfo) Kota Kendari merupakan perangkat daerah yang memiliki peran strategis dalam mendukung transformasi digital pemerintahan serta penyelenggaraan layanan publik berbasis teknologi informasi. Sejak awal pembentukannya, Diskominfo Kota Kendari telah menunjukkan komitmen untuk menjadi penggerak utama dalam memodernisasi sistem informasi pemerintahan dan membangun keterhubungan antara pemerintah dan masyarakat.</p>
          <p>Salah satu tonggak penting dalam sejarah Diskominfo adalah peluncuran <strong>Layanan Call Center 112 “Kendari Siaga”</strong>, yang merupakan sistem layanan darurat terpadu berbasis nasional. Layanan ini diluncurkan sebagai bagian dari upaya percepatan transformasi layanan publik, dan ditetapkan secara resmi melalui Surat Keputusan Wali Kota Kendari pada April 2022. Call Center 112 memungkinkan masyarakat mengakses bantuan darurat secara gratis dan cepat, hanya dengan satu nomor, tanpa harus mengingat banyak kontak instansi terkait.</p>
          <p>Pada masa pandemi COVID-19, Diskominfo Kendari menunjukkan peran yang sangat aktif dalam penyebaran informasi, edukasi publik, serta penyelenggaraan berbagai aplikasi penunjang layanan pemerintahan. Aplikasi seperti <strong>Cov-Heroes</strong>, <strong>e-SPPD</strong>, <strong>LAIKA</strong>, dan <strong>JARI</strong> hadir sebagai solusi untuk mempercepat koordinasi dan pengambilan keputusan di tengah keterbatasan pertemuan fisik. Diskominfo juga secara intensif menyelenggarakan webinar literasi digital dan pelatihan keamanan siber, guna meningkatkan kesiapan masyarakat menghadapi era digital dan melawan hoaks.</p>
          <p>Seiring dengan perkembangan teknologi, Diskominfo terus melahirkan berbagai inovasi baru. Beberapa di antaranya adalah <strong>Aplikasi Srikandi</strong> sebagai sistem kearsipan digital, <strong>E-Laika</strong> untuk manajemen surat menyurat tingkat RT/RW, dan <strong>E-Office</strong> sebagai platform tata kelola administrasi pemerintahan yang efisien dan terintegrasi. Aplikasi-aplikasi ini dirancang tidak hanya untuk meningkatkan kinerja aparatur, tetapi juga memberikan kemudahan layanan bagi masyarakat.</p>
          <p>Melalui semangat kolaborasi dan inovasi, Diskominfo Kota Kendari terus meneguhkan eksistensinya sebagai motor penggerak digitalisasi pemerintahan. Dengan mengintegrasikan berbagai sistem informasi, memperkuat literasi digital, dan membangun Command Center sebagai pusat kendali kota, Diskominfo menjadi salah satu pilar utama dalam mewujudkan visi Kota Kendari sebagai Smart City yang terhubung, cerdas, dan berdaya saing di wilayah Indonesia Timur.</p>'
      ],

      [
        'jenis'  => 'visi',
        'konten' => '"Terwujudnya Kota Kendari Tahun 2029 sebagai Kota Layak Huni yang Semakin Maju, Berdaya Saing, Adil, Sejahtera, dan Berkelanjutan."',
      ],

      [
        'jenis'  => 'misi',
        'konten' => json_encode([
          [
            'id'   => 1,
            'value' => "Menyediakan, memelihara dan mengembangkan berbagai fasilitas yang layak dan mencukupi untuk kebutuhan warga dan pengguna kota lainnya (yakni fasilitas umum, sosial, ruang publik, dan lainnya).",
          ],
          [
            'id'   => 2,
            'value' => "Mewujudkan Tata Penyelenggaraan Kota yang baik (good city governance), meliputi tatakelola pemerintahan Kota yang baik, partisipasi warga kota yang baik dalam pengelolaan kota, dan juga kenyamanan bagi pengguna kota yang baik."
          ],
          [
            'id'   => 3,
            'value' => "Pembangunan Infrastruktur Kota yang terintegrasi, efisien, dan ramah lingkungan, dalam rangka memenuhi pelayanan dasar yang berkualitas."
          ],
          [
            'id'   => 4,
            'value' => "Peningkatan Pelayanan Sosial dan Kesejahteraan Warga Kota (Pendidikan, Kesehatan, dan lainnya).",
          ],
        ]),
      ],

      [
        'jenis'  => 'foto-diskominfo',
        'konten' => json_encode([
          [
            'id'    => 1,
            'value' => "foto-diskominfo/diskominfo-1.webp",
          ],
          [
            'id'    => 2,
            'value' => "foto-diskominfo/diskominfo-2.webp",
          ],
          [
            'id'    => 3,
            'value' => "foto-diskominfo/diskominfo-3.webp",
          ],
          [
            'id'    => 4,
            'value' => "foto-diskominfo/diskominfo-4.webp",
          ],
        ]),
      ],

      [
        'jenis'  => 'tugas',
        'konten' => '<p>Berdasarkan <strong>Peraturan Wali Kota Kendari Nomor 20 Tahun 2022</strong>, Dinas Komunikasi dan Informatika mempunyai tugas:</p>
        <p><em>“<strong>Melaksanakan urusan pemerintahan</strong> bidang <strong>komunikasi dan informatika</strong>, bidang <strong>persandian</strong>, dan bidang <strong>statistik</strong> sesuai dengan ketentuan peraturan perundang-undangan.”</em></p>',
      ],

      [
        'jenis'  => 'fungsi',
        'konten' => json_encode([
          [
            'id'    => 1,
            'value' => 'Perumusan kebijakan teknis di bidang komunikasi dan informatika, persandian, dan statistik.',
            'icon'  => 'fa-solid fa-lightbulb',
          ],
          [
            'id'    => 2,
            'value' => 'Pelaksanaan kebijakan di bidang komunikasi dan informatika, persandian, dan statistik.',
            'icon'  => 'fa-solid fa-gears',
          ],
          [
            'id'    => 3,
            'value' => 'Pelaksanaan evaluasi dan pelaporan di bidang komunikasi dan informatika, persandian, dan statistik.',
            'icon'  => 'fa-solid fa-chart-line',
          ],
          [
            'id'    => 4,
            'value' => 'Pelaksanaan administrasi dinas sesuai dengan lingkup tugasnya.',
            'icon'  => 'fa-solid fa-file-lines',
          ],
          [
            'id'    => 5,
            'value' => 'Pelaksanaan fungsi lain yang diberikan oleh Wali Kota sesuai dengan tugas dan fungsinya.',
            'icon'  => 'fa-solid fa-briefcase',
          ]
        ]),
      ],

      [
        'jenis'  => 'struktur-organisasi',
        'konten' => 'struktur-organisasi/struktur-organisasi.webp'
      ],

      [
        'jenis'  => 'pengaturan',
        'konten' => json_encode([
          [
            'id'    => 'telp',
            'value' => '0822-4618-8268',
          ],
          [
            'id'    => 'email',
            'value' => 'diskominfokendari@gmail.com',
          ],
          [
            'id'    => 'fb',
            'value' => null,
          ],
          [
            'id'    => 'ig',
            'value' => null,
          ],
          [
            'id'    => 'tt',
            'value' => null,
          ],
          [
            'id'    => 'yt',
            'value' => null,
          ],
        ]),
      ],
    ];

    foreach ($data as $item)
      ProfilDinas::create($item);
  }
}
