<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Café Elegant</title>
    <style>
        /* Dark Mode Elegant CSS untuk Menu Café */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Dark Color Palette */
            --primary-dark: #0a0a0a;
            --secondary-dark: #1a1a1a;
            --tertiary-dark: #2a2a2a;
            --accent-gold: #d4af37;
            --accent-copper: #b87333;
            --text-primary: #ffffff;
            --text-secondary: #cccccc;
            --text-muted: #888888;
            --border-color: #333333;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --shadow-dark: rgba(0, 0, 0, 0.5);
            --gradient-primary: linear-gradient(135deg, var(--accent-gold), var(--accent-copper));
            --gradient-bg: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--gradient-bg);
            min-height: 100vh;
            overflow-x: hidden;
            padding: 2rem 0;
        }

        /* Container */
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
        }

        /* Header */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(ellipse at center, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
            z-index: -1;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 20px rgba(212, 175, 55, 0.3);
            animation: fadeInUp 1s ease-out;
        }

        .page-subtitle {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        /* Form Styling */
        .order-form {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 40px var(--shadow-dark);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        .order-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
            animation: shimmer 2s ease-in-out infinite;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text-primary);
            margin-bottom: 0.8rem;
            position: relative;
        }

        .form-label::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 30px;
            height: 2px;
            background: var(--gradient-primary);
            border-radius: 1px;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--accent-gold);
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.2);
            background: rgba(255, 255, 255, 0.08);
        }

        .form-select option {
            background: var(--secondary-dark);
            color: var(--text-primary);
            padding: 0.5rem;
        }

        /* Menu Preview Container */
        .menu-preview-container {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        .menu-selection {
            flex: 1;
        }

        .menu-preview {
            flex: 0 0 300px;
            min-height: 200px;
            display: none;
            animation: fadeInRight 0.5s ease-out;
        }

        .menu-preview.active {
            display: block;
        }

        .menu-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 15px;
            border: 2px solid var(--glass-border);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .menu-image:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.2);
        }

        .menu-info {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
            animation: fadeInUp 0.5s ease-out 0.2s both;
        }

        .menu-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-gold);
            margin-bottom: 0.5rem;
        }

        .menu-price {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .menu-description {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1.2rem 2.5rem;
            text-decoration: none;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            position: relative;
            overflow: hidden;
            text-align: center;
            min-width: 200px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: var(--primary-dark);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
            width: 100%;
            margin-bottom: 2rem;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(212, 175, 55, 0.4);
        }

        .btn-secondary {
            background: var(--glass-bg);
            color: var(--text-primary);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
        }

        /* Footer Navigation */
        .footer-nav {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        /* Menu Icon */
        .menu-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            filter: drop-shadow(0 0 10px var(--accent-gold));
            animation: float 3s ease-in-out infinite;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .menu-preview-container {
                flex-direction: column;
            }
            
            .menu-preview {
                flex: none;
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .page-title {
                font-size: 2.5rem;
            }
            
            .order-form {
                padding: 2rem;
            }
            
            .footer-nav {
                flex-direction: column;
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 2rem;
            }
            
            .order-form {
                padding: 1.5rem;
            }
            
            .btn {
                min-width: auto;
                width: 100%;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--secondary-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-primary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-copper);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <div class="menu-icon">☕</div>
            <h1 class="page-title">Menu Café</h1>
            <p class="page-subtitle">Pilih menu favorit Anda dan nikmati pengalaman kuliner terbaik</p>
        </div>

        <form class="order-form" action="proses_pesan.php" method="post">
            <div class="form-group">
                <label class="form-label">Nama Anda</label>
                <input type="text" name="nama_pelanggan" class="form-input" placeholder="Masukkan nama lengkap Anda" required>
            </div>

            <div class="menu-preview-container">
                <div class="menu-selection">
                    <div class="form-group">
                        <label class="form-label">Pilih Menu</label>
                        <select name="menu_id" id="menuSelect" class="form-select" required>
                            <option value="">-- Pilih Menu Favorit Anda --</option>
                            <option value="1" data-name="Espresso" data-price="Rp20,000" data-desc="Kopi espresso premium dengan rasa yang kuat dan aroma yang nikmat.">Espresso - Rp20,000</option>
                            <option value="2" data-name="Cappuccino" data-price="Rp25,000" data-desc="Minuman kopi dengan campuran susu steamed yang creamy.">Cappuccino - Rp25,000</option>
                            <option value="3" data-name="Croissant" data-price="Rp18,000" data-desc="Roti croissant yang renyah dan buttery, cocok untuk sarapan.">Croissant - Rp18,000</option>
                            <option value="4" data-name="Sandwich" data-price="Rp30,000" data-desc="Sandwich dengan isian lengkap, daging, sayuran segar dan saus special.">Sandwich - Rp30,000</option>
                            <option value="5" data-name="Lemon Tea" data-price="Rp15,000" data-desc="Teh lemon segar dengan perasan jeruk nipis alami.">Lemon Tea - Rp15,000</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" class="form-input" min="1" value="1" placeholder="Berapa porsi?" required>
                    </div>
                </div>

                <div class="menu-preview" id="menuPreview">
                    <img id="menuImage" class="menu-image" src="" alt="Menu Preview">
                    <div class="menu-info">
                        <div class="menu-name" id="menuName"></div>
                        <div class="menu-price" id="menuPrice"></div>
                        <div class="menu-description" id="menuDescription"></div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                ✨ Pesan Sekarang
            </button>

            <div class="footer-nav">
                <a href="home.php" class="btn btn-secondary">🏠 Kembali ke Home</a>
                <a href="login.php" class="btn btn-secondary">👤 Login Admin</a>
            </div>
        </form>
    </div>

    <script>
        // Data menu dengan gambar (menggunakan placeholder images)
        const menuData = {
            '1': {
                image: 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?w=400&h=300&fit=crop&crop=center',
                name: 'Espresso',
                price: 'Rp20,000',
                description: 'Kopi hitam pekat dengan rasa yang kuat dan aroma yang menggugah selera. Dibuat dari biji kopi pilihan terbaik.'
            },
            '2': {
                image: 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=400&h=300&fit=crop&crop=center',
                name: 'Cappuccino',
                price: 'Rp25,000',
                description: 'Minuman kopi dengan campuran susu steamed yang creamy.'
            },
            '3': {
                image: 'https://plus.unsplash.com/premium_photo-1692805433455-ff41be96b357?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                name: 'Croissant',
                price: 'Rp18,000',
                description: 'Roti croissant yang renyah dan buttery, cocok untuk sarapan.'
            },
            '4': {
                image: 'https://images.unsplash.com/photo-1509722747041-616f39b57569?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                name: 'Sandwich',
                price: 'Rp30,000',
                description: 'Sandwich dengan isian lengkap, daging, sayuran segar dan saus special.'
            },
            '5': {
                image: 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=764&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                name: 'Lemon tea',
                price: 'Rp15,000',
                description: 'Teh lemon segar dengan perasan jeruk nipis alami.'
            }
        };

        // Event listener untuk dropdown menu
        document.getElementById('menuSelect').addEventListener('change', function() {
            const selectedValue = this.value;
            const preview = document.getElementById('menuPreview');
            
            if (selectedValue && menuData[selectedValue]) {
                const menu = menuData[selectedValue];
                
                // Update gambar dan informasi
                document.getElementById('menuImage').src = menu.image;
                document.getElementById('menuName').textContent = menu.name;
                document.getElementById('menuPrice').textContent = menu.price;
                document.getElementById('menuDescription').textContent = menu.description;
                
                // Tampilkan preview
                preview.classList.add('active');
            } else {
                // Sembunyikan preview jika tidak ada yang dipilih
                preview.classList.remove('active');
            }
        });

        // Animasi loading gambar
        document.getElementById('menuImage').addEventListener('load', function() {
            this.style.opacity = '0';
            this.style.transform = 'scale(0.9)';
            
            setTimeout(() => {
                this.style.transition = 'all 0.3s ease';
                this.style.opacity = '1';
                this.style.transform = 'scale(1)';
            }, 50);
        });
    </script>
</body>
</html>