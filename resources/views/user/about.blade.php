@extends('user.layouts.app')

@section('title', 'Tentang Kami')

@section('content')
  <section class="page-title section light-background">
    <div class="container" data-aos="fade-up">
      <h1 class="mb-3">Tentang Masjid Abaabil</h1>
      <p class="mb-0">Pusat ibadah, pengetahuan, dan pelayanan zakat yang dirancang untuk melayani jamaah secara tertib, terbuka, dan mudah diakses.</p>
    </div>
  </section>

  <section class="about section">
    <div class="container" data-aos="fade-up">
      <div class="row gy-4 align-items-center">
        <div class="col-lg-6">
          <div class="section-title text-start mb-4">
            <h2>Profil</h2>
            <div><span>Masjid Yang Tumbuh</span> <span class="description-title">Bersama Jamaah</span></div>
          </div>
          <p>Masjid Abaabil hadir sebagai ruang ibadah dan pembinaan umat dengan fokus pada kemudahan layanan, termasuk informasi kegiatan, assessment zakat, dan pencatatan transaksi zakat yang lebih rapi.</p>
          <p>Kami mendorong keterlibatan jamaah melalui kegiatan rutin, edukasi zakat, dan penguatan nilai kebersamaan dalam aktivitas harian masjid.</p>
        </div>
        <div class="col-lg-6">
          <div class="p-4 rounded-4" style="background: linear-gradient(135deg, #0f4f24 0%, #1f8f3d 100%); color: #fff;">
            <h3 class="mb-3 text-white">Nilai Utama</h3>
            <div class="mb-3">
              <strong>Amanah</strong>
              <p class="mb-0">Setiap layanan dan pencatatan dikelola dengan tanggung jawab yang jelas.</p>
            </div>
            <div class="mb-3">
              <strong>Keterbukaan</strong>
              <p class="mb-0">Informasi kegiatan, layanan, dan kebutuhan jamaah disampaikan secara ringkas dan mudah dipahami.</p>
            </div>
            <div>
              <strong>Pelayanan</strong>
              <p class="mb-0">Fasilitas dan program dikembangkan untuk mendukung kenyamanan jamaah dalam beribadah.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="services section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Layanan</h2>
      <div><span>Fokus Utama</span> <span class="description-title">Masjid</span></div>
    </div>

    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="service-item position-relative h-100">
            <div class="icon"><i class="bi bi-moon-stars"></i></div>
            <h3>Ibadah Harian</h3>
            <p>Penyediaan informasi jadwal shalat, kesiapan ruang ibadah, dan dukungan kegiatan jamaah setiap hari.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
          <div class="service-item position-relative h-100">
            <div class="icon"><i class="bi bi-cash-stack"></i></div>
            <h3>Layanan Zakat</h3>
            <p>Assessment zakat untuk muzakki serta pencatatan transaksi yang membantu proses pengelolaan lebih sistematis.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
          <div class="service-item position-relative h-100">
            <div class="icon"><i class="bi bi-journal-richtext"></i></div>
            <h3>Edukasi dan Dakwah</h3>
            <p>Konten dan kegiatan pembinaan yang memperkuat literasi ibadah, zakat, dan kebersamaan jamaah.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection