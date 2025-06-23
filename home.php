<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Kami- Home</title>
    <link rel="stylesheet" href="assets/home.css">

</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <div class="logo">
                <div class="logo-icon steam">☕</div>
                <h1>Café Kami</h1>
            </div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#menu">Menu</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a href="login.php">Admin</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <h2>Selamat Datang di Café Kami</h2>
        <p>Nikmati minuman dan makanan dengan menu pilihan yang lezat. Pesan sekarang dan rasakan kelezatannya sekarang!</p>
        <div class="cta-buttons">
            <a href="index.php" class="btn btn-primary">
                Lihat Menu & Pesan
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="menu">
        <h3>Layanan Kami</h3>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h4>Pemesanan Cepat</h4>
                <p>Sistem pemesanan online yang mudah dan cepat. Cukup pilih menu, masukkan jumlah, dan pesanan Anda akan segera diproses.</p>
                <a href="index.php" class="btn btn-primary">Mulai Pesan</a>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">💳</div>
                <h4>Pembayaran Digital</h4>
                <p>Mendukung berbagai metode pembayaran digital dengan QR Code untuk kemudahan dan keamanan transaksi Anda.</p>
                <a href="https://link.dana.id/transfer?phone=082376329797" class="btn btn-secondary">Info Pembayaran</a>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🏪</div>
                <h4>Kelola Menu</h4>
                <p>Sistem manajemen menu yang lengkap untuk admin dalam mengelola daftar menu, harga, dan ketersediaan stok.</p>
                <a href="login.php" class="btn btn-secondary">Login Admin</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number">5</span>
                <span class="stat-label">Menu Pilihan</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">10</span>
                <span class="stat-label">Pelanggan Puas</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">12/5</span>
                <span class="stat-label">Layanan Online</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">5★</span>
                <span class="stat-label">Rating Pelanggan</span>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="about">
        <p>&copy; 2025 Café Kami. </p>
        <p>☎️ (+62)0812xxxxxxx </p>
    </footer>

    <script>
        // Smooth scrolling untuk navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Counter animation untuk stats
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current) + (target > 100 ? '+' : target === 5 ? '★' : target === 24 ? '/7' : '');
            }, 20);
        }

        // Intersection Observer untuk animasi stats
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statNumbers = entry.target.querySelectorAll('.stat-number');
                    statNumbers.forEach(stat => {
                        const text = stat.textContent;
                        let target = parseInt(text.replace(/\D/g, ''));
                        if (text.includes('★')) target = 5;
                        else if (text.includes('/')) target = 24;
                        animateCounter(stat, target);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe stats section
        const statsSection = document.querySelector('.stats');
        if (statsSection) {
            observer.observe(statsSection);
        }

        // Dynamic greeting berdasarkan waktu
        function updateGreeting() {
            const hour = new Date().getHours();
            const heroTitle = document.querySelector('.hero h2');
            
            if (hour < 12) {
                heroTitle.textContent = 'Selamat Pagi! Selamat Datang di Café Kami';
            } else if (hour < 15) {
                heroTitle.textContent = 'Selamat Siang! Selamat Datang di Café Kami';
            } else if (hour < 18) {
                heroTitle.textContent = 'Selamat Sore! Selamat Datang di Café Kami';
            } else {
                heroTitle.textContent = 'Selamat Malam! Selamat Datang di Café Kami';
            }
        }

        // Call greeting function
        updateGreeting();

        // Parallax effect untuk hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });
    </script>
</body>
</html>