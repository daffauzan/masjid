@extends('user.layouts.app')

@section('title', 'Jadwal Shalat')

@section('content')
  <section class="page-title section light-background">
    <div class="container" data-aos="fade-up">
      <h1 class="mb-3">Jadwal Shalat</h1>
      <p class="mb-0">Informasi waktu shalat harian untuk jamaah Masjid Abaabil beserta catatan kegiatan yang menyertainya.</p>
    </div>
  </section>

  <section class="services section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Waktu Shalat</h2>
      <div><span>Jadwal Ibadah</span> <span class="description-title">Hari Ini</span></div>
    </div>

    <div class="container">
      <div class="row gy-4">
        @foreach($prayerSchedule as $item)
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
            <div class="service-item position-relative h-100">
              <div class="icon">
                <i class="bi bi-clock-history"></i>
              </div>
              <h3>{{ $item['name'] }}</h3>
              <div class="mb-3" style="font-size: 2rem; font-weight: 700; color: var(--heading-color);">
                {{ $item['time'] }}
              </div>
              <p>{{ $item['note'] }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="about section">
    <div class="container" data-aos="fade-up">
      <div class="row gy-4 align-items-center">
        <div class="col-lg-6">
          <div class="section-title text-start mb-4">
            <h2>Catatan Jamaah</h2>
            <div><span>Datang Lebih Awal</span> <span class="description-title">Lebih Tenang</span></div>
          </div>
          <p>Jadwal ini dapat diperbarui mengikuti keputusan pengurus masjid dan penyesuaian waktu setempat. Jamaah disarankan hadir lebih awal untuk persiapan wudhu, membaca dzikir, dan menjaga ketertiban saf.</p>
          <ul class="list-unstyled mt-4">
            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Muadzin memulai adzan sesuai jadwal yang tercantum.</li>
            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Iqamah diumumkan beberapa menit setelah adzan sesuai kondisi jamaah.</li>
            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Kajian dan agenda tambahan diinformasikan melalui pengurus masjid.</li>
          </ul>
        </div>
        <div class="col-lg-6">
          <div class="p-4 rounded-4" style="background: linear-gradient(135deg, #eff8f1 0%, #dff0e5 100%); border: 1px solid rgba(31, 143, 61, 0.15);">
            <h3 class="mb-3" style="color: var(--heading-color);">Layanan Pendukung</h3>
            <p class="mb-3">Untuk kenyamanan jamaah, masjid juga menyiapkan pengumuman kegiatan rutin yang terkait dengan waktu shalat.</p>
            <div class="d-flex flex-column gap-3">
              <div class="d-flex align-items-start gap-3">
                <i class="bi bi-megaphone-fill text-success fs-4"></i>
                <div>
                  <strong>Pengumuman Harian</strong>
                  <div>Agenda kajian, kultum, dan layanan zakat diumumkan setelah shalat tertentu.</div>
                </div>
              </div>
              <div class="d-flex align-items-start gap-3">
                <i class="bi bi-people-fill text-success fs-4"></i>
                <div>
                  <strong>Koordinasi Jamaah</strong>
                  <div>Petugas membantu penataan saf dan alur masuk saat jumlah jamaah meningkat.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection