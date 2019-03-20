<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <div class="nav-link">
        <div class="user-wrapper">
          <div class="profile-image">
            <img src="{{ URL::asset('public/images/account.png')}}">
          </div>
          <div class="text-wrapper">
            <p class="profile-name">{{ Auth::user()->name }}</p>
            <div>
              <small class="designation text-muted">Manager</small>
              <span class="status-indicator online"></span>
            </div>
          </div>
        </div>
        <button class="btn btn-success btn-block"><div id="jam"></div>
        </button>
      </div>
    </li>
    @if(access_level_user('view','dashboard')=='allow')
    <li class="nav-item">
      <a class="nav-link" href="{{URL::to('/')}}">
        <i class="menu-icon mdi mdi-television"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    @endif
    @if(access_level_user('view','formulir')=='allow')
    <li class="nav-item">
           <a class="nav-link" href="{{URL::to('formulir')}}">
             <i class="menu-icon mdi mdi-file-document-box"></i>
             <span class="menu-title">Formulir</span>
           </a>
         </li>
    @endif
    @if(access_level_user('view','pemesanan')=='allow')
    <li class="nav-item">
           <a class="nav-link" href="{{URL::to('pemesanan')}}">
             <i class="menu-icon mdi mdi-shopping"></i>
             <span class="menu-title">Pemesanan</span>
           </a>
         </li>
    @endif
    @if(access_level_user('view','pembayaran')=='allow')
    <li class="nav-item">
           <a class="nav-link" href="{{URL::to('pembayaran')}}">
             <i class="menu-icon mdi mdi-file-document"></i>
             <span class="menu-title">Pembayaran</span>
           </a>
         </li>
    @endif

    @if(access_level_user('view','master_data')=='allow')
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#master_data" aria-expanded="false" aria-controls="ui-basic">
        <i class="menu-icon mdi mdi-folder"></i>
        <span class="menu-title">Data Master</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="master_data">
        <ul class="nav flex-column sub-menu">
          @if(access_level_user('view','pendidikan')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('pendidikan')}}">Pendidikan</a>
          </li>
          @endif
          @if(access_level_user('view','pekerjaan')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('pekerjaan')}}">Pekerjaan</a>
          </li>
          @endif
          @if(access_level_user('view','status')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('status')}}">Status Pernikahan</a>
          </li>
          @endif
          @if(access_level_user('view','hubungan')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('hubungan')}}">Hubungan</a>
          </li>
          @endif
          @if(access_level_user('view','negara')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('negara')}}">Negara</a>
          </li>
          @endif
          @if(access_level_user('view','kota')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('kota')}}">Kota</a>
          </li>
          @endif
          @if(access_level_user('view','service')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('service')}}">Service</a>
          </li>
          @endif
          @if(access_level_user('view','hotel')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('hotel')}}">Hotel</a>
          </li>
          @endif
          @if(access_level_user('view','room')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('room')}}">Room</a>
          </li>
          @endif
          @if(access_level_user('view','airlines')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('airlines')}}">Airlines</a>
          </li>
          @endif
          @if(access_level_user('view','pesawat')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('pesawat')}}">Pesawat</a>
          </li>
          @endif
          @if(access_level_user('view','kategori_perjalanan')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('kategori_perjalanan')}}">Kategori Perjalanan</a>
          </li>
          @endif
          @if(access_level_user('view','paket_perjalanan')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('paket_perjalanan')}}">Paket Perjalanan</a>
          </li>
          @endif
        </ul>
      </div>
    </li>
    @endif
    @if(access_level_user('view','role')=='allow')
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
        <i class="menu-icon mdi mdi-security"></i>
        <span class="menu-title">Pengaturan Akses</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="auth">
        <ul class="nav flex-column sub-menu">
          @if(access_level_user('view','user')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('users')}}"> Pengguna </a>
          </li>
          @endif
          @if(access_level_user('view','priveleges')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('priveleges')}}"> Group Akses </a>
          </li>
          @endif
          @if(access_level_user('view','module')=='allow')
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('module')}}"> Module </a>
          </li>
          @endif
        </ul>
      </div>
    </li>
    @endif
    @if(access_level_user('view','log')=='allow')
    <li class="nav-item">
           <a class="nav-link" href="{{URL::to('log')}}">
             <i class="menu-icon mdi mdi-settings"></i>
             <span class="menu-title">Log Aktivitas</span>
           </a>
         </li>
    @endif
  </ul>
</nav>
