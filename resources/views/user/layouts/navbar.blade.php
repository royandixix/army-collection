<nav class="navbar navbar-expand-lg shadow-sm fixed-top" style="background: linear-gradient(90deg, #1e3c72, #2a5298); z-index:1040;">
    <div class="container-fluid px-4 d-flex align-items-center justify-content-between">
        {{-- Logo Brand --}}
        <a class="navbar-brand text-white fw-bold d-flex align-items-center glow-text" href="{{ url('/') }}">
            <i class="bi bi-box-seam me-2 fs-4"></i>
            <span class="fs-5">Army Collection</span>
        </a>

        {{-- Typing Animation --}}
        <div class="text-light inspirational-text d-none d-md-block">
            <span id="typing-text"></span>
        </div>

        {{-- Akun Dropdown --}}
        <div class="dropdown ms-3">
            @auth
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->img ? asset('storage/' . Auth::user()->img) : asset('img/default-user.png') }}" alt="Foto Profil" width="40" height="40" class="elegant-avatar">
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow profile-dropdown mt-3" aria-labelledby="userDropdown">
                    <li class="px-3 pt-2 pb-1 text-muted small">Akun Saya</li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('user.profil.profil') }}">
                            <i class="bi bi-person-fill-gear text-primary fs-5"></i>
                            <span>Profil & Pengaturan</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger fw-semibold">
                                <i class="bi bi-box-arrow-right fs-5"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </li>
                </ul>
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-sm fw-semibold me-2">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm fw-semibold">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

{{-- Search Sticky --}}
<div id="stickySearch" class="position-sticky end-0 py-2 animate__animated" style="top: 56px; z-index: 1030;">
    <div class="container d-flex justify-content-end">
        <form action="{{ route('user.produk.index') }}" method="GET" class="d-flex align-items-center px-3 py-2 shadow-sm rounded-2 search-box" style="gap: 0.5rem; max-width: 100%; background: #ffffffee;">
            <input type="text" name="search" class="form-control form-control-sm border-0 shadow-none px-2 search-input" placeholder="Cari produk..." value="{{ request('search') }}">
            <button class="btn btn-sm btn-primary rounded-1 px-3 py-2" type="submit">Cari</button>
        </form>
    </div>
</div>





@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<style>
  

  #stickySearch {
       
    }

    .search-box {
        backdrop-filter: blur(6px);
        border: 1px solid #ddd;
        
    }

    .search-input::placeholder {
        font-size: 0.9rem;
        
        color: #999;
    }

    .search-input:focus {
        background-color: transparent;
        outline: none;
        box-shadow: none;
        
    }

    .btn-primary {
        background-color: #1e3c72;
        border: none;
    }

    .btn-primary:hover {
        background-color: #2a5298;
        box-shadow: 0 3px 8px rgba(30, 60, 114, 0.2);
    }

    @media (max-width: 576px) {
        #stickySearch .container {
            justify-content: center !important;
        }

        #stickySearch form {
            flex-direction: column;
            width: 100%;
        }

        .search-input,
        .btn-primary {
            width: 100%;
        }

        .btn-primary {
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        #stickySearch .container {
            justify-content: center !important;
        }

        #stickySearch form {
            flex-direction: column;
            width: 100%;
            max-width: 100%;
        }

        #stickySearch input,
        #stickySearch button {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        #stickySearch form {
            flex-direction: column;
            gap: 0.5rem;
        }

        #stickySearch input {
            max-width: 100%;
            width: 100%;
        }

        #stickySearch button {
            width: 100%;
        }
    }


@media (max-width: 767.98px) {
  .sticky-search {
    top: 60px;
  }
}

    .glow-text {
        text-shadow: 0 0 6px rgba(255, 255, 255, 0.5);
    }

    .inspirational-text {
        font-style: italic;
        font-size: 0.95rem;
        font-weight: 500;
        opacity: 0.95;
        letter-spacing: 0.5px;
        white-space: nowrap;
        overflow: hidden;
        max-width: 100%;
        background: linear-gradient(90deg, #ffffff, #cce0ff, #ffffff);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shimmer 4s linear infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: 200% center;
        }
        100% {
            background-position: -200% center;
        }
    }

    .elegant-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .elegant-avatar:hover {
        transform: scale(1.05);
    }

    .profile-dropdown {
        border-radius: 14px;
        min-width: 240px;
        background-color: #ffffff;
        padding: 8px 0;
        border: 1px solid #e4e6ef;
        animation: fadeInDown 0.2s ease-out;
    }

    .profile-dropdown .dropdown-item {
        padding: 10px 16px;
        font-weight: 500;
        color: #333;
        transition: all 0.2s ease-in-out;
        border-radius: 8px;
    }

    .profile-dropdown .dropdown-item:hover {
        background: #f1f3f9;
        color: #1e3c72;
        box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.03);
    }

    .profile-dropdown i {
        min-width: 24px;
        text-align: center;
    }

    .profile-dropdown hr {
        margin: 0.5rem 1rem;
    }

    .profile-dropdown small {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

</style>
@endpush
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const textElement = document.getElementById("typing-text");
        const phrases = [
            "Temukan gaya terbaikmu."
            , "Tampil keren tanpa usaha lebih."
            , "Belanja mudah, tampil maksimal."
            , "Mulai harimu dengan percaya diri."
        ];
        let currentPhrase = 0;
        let currentChar = 0;
        let isDeleting = false;

        function type() {
            const current = phrases[currentPhrase];
            textElement.textContent = current.substring(0, currentChar);

            if (!isDeleting && currentChar < current.length) {
                currentChar++;
            } else if (isDeleting && currentChar > 0) {
                currentChar--;
            }

            if (!isDeleting && currentChar === current.length) {
                isDeleting = true;
                setTimeout(type, 1800);
                return;
            } else if (isDeleting && currentChar === 0) {
                isDeleting = false;
                currentPhrase = (currentPhrase + 1) % phrases.length;
            }

            setTimeout(type, isDeleting ? 40 : 70);
        }

        type();
    });


    document.addEventListener("DOMContentLoaded", function () {
        const textElement = document.getElementById("typing-text");
        const phrases = [
            "Temukan gaya terbaikmu.",
            "Tampil keren tanpa usaha lebih.",
            "Belanja mudah, tampil maksimal.",
            "Mulai harimu dengan percaya diri."
        ];

        let currentPhrase = 0;
        let currentChar = 0;
        let isDeleting = false;

        function type() {
            const current = phrases[currentPhrase];
            const fullText = current;
            const partial = current.substring(0, currentChar);

            textElement.textContent = partial;

            if (!isDeleting && currentChar < fullText.length) {
                currentChar++;
            } else if (isDeleting && currentChar > 0) {
                currentChar--;
            }

            if (!isDeleting && currentChar === fullText.length) {
                textElement.classList.add("glow-text"); // Add glow when fully typed
                isDeleting = true;
                setTimeout(type, 1800); // pause
                return;
            } else if (isDeleting && currentChar === 0) {
                textElement.classList.remove("glow-text"); // Remove glow when deleting
                isDeleting = false;
                currentPhrase = (currentPhrase + 1) % phrases.length;
            }

            setTimeout(type, isDeleting ? 40 : 70);
        }

        type();
    });



    let lastScrollTop = 0;
    const searchBar = document.getElementById("stickySearch");

    window.addEventListener("scroll", function () {
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
            // Scroll Down - Hide
            searchBar.classList.remove("animate__fadeInDown");
            searchBar.classList.add("animate__fadeOutUp");
        } else {
            // Scroll Up - Show
            searchBar.classList.remove("animate__fadeOutUp");
            searchBar.classList.add("animate__fadeInDown");
        }

        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });
</script>
@endpush
