<footer id="footer" class="footer dark-background">

  <div class="container footer-top">
    <div class="row gy-4">

      <div class="col-lg-4 col-md-6 footer-about">
        <a href="{{ url('/') }}" class="logo d-flex align-items-center">
          <span class="sitename">Masjid Abaabil</span>
        </a>
        <p>Jalan <br>Tasikmalaya, NY 535022</p>
      </div>

      <div class="col-lg-2 col-md-3 footer-links">
        <h4>Useful Links</h4>
        <ul>
          <li><a href="{{ url('/') }}">Home</a></li>
          <li><a href="{{ route('pages.about') }}">About</a></li>
          <li><a href="{{ route('pages.jadwal-shalat') }}">Jadwal Shalat</a></li>
        </ul>
      </div>

      <div class="col-lg-4 col-md-12 footer-newsletter">
        <h4>Newsletter</h4>
      </div>

    </div>
  </div>
    <div class="container copyright text-center mt-4">
      <p>&copy; <span>{{ date('Y') }}</span> <strong class="px-1 sitename">Masjid Abaabil</strong> <span>All Rights Reserved</span></p>
        <div class="credits">
        </div>
    </div>
</footer>
