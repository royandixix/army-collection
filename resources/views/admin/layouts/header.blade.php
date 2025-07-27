<header class="cx-header">
  <div class="cx-header-items">

    {{-- Left Header: Sidebar Toggle --}}
    <div class="left-header">
      <a href="javascript:void(0)" class="cx-toggle-sidebar">
        <span class="outer-ring">
          <span class="inner-ring"></span>
        </span>
      </a>
    </div>

    {{-- Right Header --}}
    <div class="right-header">

      {{-- Logo --}}
      <div class="cx-header-logo">
        <img src="{{ asset('assets/img/logo/full-logo-dark.png') }}" alt="Logo Dark" class="dark-logo">
        <img src="{{ asset('assets/img/logo/full-logo.png') }}" alt="Logo Light" class="white-logo">
      </div>

      {{-- Inner Right --}}
      <div class="inner-right-header">

        {{-- Notification Icon --}}
        <div class="cx-right-tool">
          <a href="#" class="cx-notify">
            <i class="ri-notification-2-line"></i>
            <span class="label"></span>
          </a>
        </div>

        {{-- User Dropdown --}}
        <div class="cx-right-tool cx-user-drop">
          <div class="cx-hover-drop">
            <div class="cx-hover-tool">
              <img 
                src="{{ Auth::user()->profile_photo_url ?? asset('img/default-user.png') }}" 
                alt="User" 
                class="user"
              >
            </div>

            {{-- Dropdown Panel --}}
            <div class="cx-hover-drop-panel right">
              <div class="details">
                <h6>{{ Auth::user()->pelanggan->nama ?? Auth::user()->username ?? 'Pengguna' }}</h6>
                <p>{{ Auth::user()->email }}</p>
              </div>

              {{-- Optional Menu --}}
              {{-- 
              <ul class="border-top">
                <li><a href="{{ route('profile') }}">Profile</a></li>
                <li><a href="{{ route('help') }}">Help</a></li>
                <li><a href="{{ route('messages') }}">Messages</a></li>
              </ul>
              --}}

              {{-- Logout --}}
              <ul class="border-top">
                <li>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="button" class="dropdown-item" onclick="confirmLogout()">
                      <i class="ri-logout-circle-r-line"></i> Logout
                    </button>
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>

      </div> {{-- /.inner-right-header --}}
    </div> {{-- /.right-header --}}
  </div> {{-- /.cx-header-items --}}
</header>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmLogout() {
    Swal.fire({
      title: 'Yakin ingin keluar?',
      text: 'Sesi kamu akan diakhiri.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3b82f6', // Tailwind blue-500
      cancelButtonColor: '#6b7280',  // Tailwind gray-500
      confirmButtonText: '<i class="ri-logout-box-line mr-1"></i> Ya, Logout',
      cancelButtonText: 'Batal',
      customClass: {
        popup: 'rounded-xl shadow-md',
        title: 'text-lg font-bold text-gray-800',
        confirmButton: 'px-4 py-2 rounded-md text-white bg-blue-500',
        cancelButton: 'px-4 py-2 rounded-md bg-gray-200 text-gray-700',
        // ini dat di ccansek 
      },
      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('logout-form').submit();
      }
    });
  }
</script>
