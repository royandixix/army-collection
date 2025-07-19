<nav class="navbar navbar-expand-lg shadow-sm fixed-top" style="background: linear-gradient(90deg, #1e3c72, #2a5298); z-index:1040;">
    <div class="container-fluid px-4 d-flex align-items-center justify-content-between">

        {{-- Brand --}}
        <a class="navbar-brand text-white fw-bold glow-text d-flex align-items-center" href="{{ url('/') }}">
            <i class="bi bi-box-seam me-2 fs-4"></i>
            <span class="fs-5">Army Collection</span>
        </a>

        {{-- Animated Inspirational Text --}}
        <span class="text-light small d-none d-md-inline inspirational-text">
            <span id="typing-text"></span>
        </span>

    </div>
</nav>

@push('styles')
<style>
    /* Glow text - warna soft biru sesuai tema */
    .glow-text {
        text-shadow: 0 0 8px rgba(66, 135, 245, 0.7);
        transition: text-shadow 0.3s ease;
    }
    .glow-text:hover {
        text-shadow: 0 0 16px rgba(66, 135, 245, 0.9);
    }

    /* Tombol glow untuk aksi (jika dipakai) dengan warna gold/orange */
    .btn-glow {
        background: linear-gradient(90deg, #f6d365, #fda085);
        color: #fff;
        font-weight: 600;
        border: none;
        padding: 6px 16px;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(253, 160, 133, 0.5);
        transition: all 0.3s ease-in-out;
    }
    .btn-glow:hover {
        background: linear-gradient(90deg, #fda085, #f6d365);
        box-shadow: 0 0 14px rgba(253, 160, 133, 0.8);
        color: #000;
    }

    /* Style teks inspirasi */
    .inspirational-text {
        font-style: italic;
        font-size: 0.875rem;
        opacity: 0.85;
        letter-spacing: 0.3px;
        min-height: 1.2rem;
        user-select: none;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const textElement = document.getElementById("typing-text");
        const phrases = [
            "Temukan gaya terbaikmu.",
            "Mulai harimu dengan percaya diri.",
            "Tampil keren tanpa usaha lebih.",
            "Belanja mudah, tampil maksimal."
        ];
        let currentPhrase = 0;
        let currentChar = 0;
        let isDeleting = false;

        function type() {
            const current = phrases[currentPhrase];
            if (isDeleting) {
                currentChar--;
            } else {
                currentChar++;
            }

            textElement.textContent = current.substring(0, currentChar);

            if (!isDeleting && currentChar === current.length) {
                isDeleting = true;
                setTimeout(type, 2000); // stay full text 2s
            } else if (isDeleting && currentChar === 0) {
                isDeleting = false;
                currentPhrase = (currentPhrase + 1) % phrases.length;
                setTimeout(type, 300); // pause before typing next
            } else {
                setTimeout(type, isDeleting ? 40 : 70);
            }
        }

        type();
    });
</script>
@endpush
