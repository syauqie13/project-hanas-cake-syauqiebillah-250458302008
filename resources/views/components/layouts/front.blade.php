<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hana's Cake - Kue Homemade Terbaik</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css-front.css') }}">
    @livewireStyles
    <!-- /END GA -->
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <livewire:front.atom.navbar />

    <!-- Hero Section -->

    <livewire:front.konten />

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials">
        <h2 class="section-title">Apa Kata Pelanggan Kami</h2>
        <div class="testimonial-grid">
            <div class="testimonial-card">
                <div class="testimonial-header">
                    <div class="testimonial-avatar">S</div>
                    <div class="testimonial-info">
                        <h4>Sarah Amelia</h4>
                        <div class="stars">★★★★★</div>
                    </div>
                </div>
                <p class="testimonial-text">"Kue nya enak banget! Saya pesan untuk ulang tahun suami dan semua tamu puas. Presentasi nya juga cantik dan rasa nya premium. Pasti order lagi!"</p>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-header">
                    <div class="testimonial-avatar">D</div>
                    <div class="testimonial-info">
                        <h4>Diana Putri</h4>
                        <div class="stars">★★★★★</div>
                    </div>
                </div>
                <p class="testimonial-text">"Chocolate Dream Cake nya juara! Tekstur nya lembut, tidak terlalu manis, dan coklat nya premium. Anak-anak saya suka banget. Recommended!"</p>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-header">
                    <div class="testimonial-avatar">R</div>
                    <div class="testimonial-info">
                        <h4>Rizky Pratama</h4>
                        <div class="stars">★★★★★</div>
                    </div>
                </div>
                <p class="testimonial-text">"Pelayanan cepat dan ramah. Kue nya datang tepat waktu dan kondisi sempurna. Red Velvet nya enak sekali! Terima kasih Hana's Cake!"</p>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-header">
                    <div class="testimonial-avatar">M</div>
                    <div class="testimonial-info">
                        <h4>Maya Kusuma</h4>
                        <div class="stars">★★★★★</div>
                    </div>
                </div>
                <p class="testimonial-text">"Sudah langganan di sini sejak tahun lalu. Kualitas nya konsisten bagus dan harga nya worth it. Favorit saya Strawberry Delight!"</p>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-header">
                    <div class="testimonial-avatar">A</div>
                    <div class="testimonial-info">
                        <h4>Andi Wijaya</h4>
                        <div class="stars">★★★★★</div>
                    </div>
                </div>
                <p class="testimonial-text">"Kue ulang tahun untuk istri saya sukses besar! Design nya cantik sesuai request dan rasa nya memang premium. Highly recommended!"</p>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-header">
                    <div class="testimonial-avatar">L</div>
                    <div class="testimonial-info">
                        <h4>Linda Sari</h4>
                        <div class="stars">★★★★★</div>
                    </div>
                </div>
                <p class="testimonial-text">"Berry Cheesecake nya enak banget! Cream cheese nya pas tidak terlalu eneg dan buah berry nya segar. Pasti repeat order!"</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <h2 class="section-title">Tentang Kami</h2>
        <div class="about-content">
            <p>Hana's Cake adalah usaha kue premium yang didirikan dengan passion untuk menghadirkan kue-kue berkualitas tinggi dengan cita rasa istimewa dan desain modern yang memukau.</p>
            <p>Kami menggunakan bahan-bahan pilihan terbaik dan resep yang telah disempurnakan untuk memastikan setiap kue yang kami buat memberikan pengalaman yang tak terlupakan.</p>
            <p>Dengan pengalaman lebih dari 5 tahun melayani ratusan pelanggan puas, kami siap mewujudkan kue impian Anda untuk berbagai acara spesial seperti ulang tahun, pernikahan, dan moment berharga lainnya.</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <h2 class="section-title">Hubungi Kami</h2>
        <div class="contact-content">
            <div class="contact-item">
                <span class="contact-icon">📱</span>
                <div>
                    <strong>WhatsApp</strong>
                    +62 882-2585-3364
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">📧</span>
                <div>
                    <strong>Email</strong>
                    hanascake@gmail.com
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">📍</span>
                <div>
                    <strong>Alamat</strong>
                    Gang Masjid Rt 017 Rw 003<br>Desa Tegal Kunir Lor, Kec. Mauk<br>Kab. Tangerang
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">📷</span>
                <div>
                    <strong>Instagram</strong>
                    @hanascake.id
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">⏰</span>
                <div>
                    <strong>Jam Operasional</strong>
                    Senin - Sabtu: 08.00 - 20.00 WIB<br>
                    Minggu: 09.00 - 17.00 WIB
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Hana's Cake. All Rights Reserved. Crafted with passion ✨</p>
    </footer>

    <script>
        // Smooth scrolling
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
    </script>
</body>
</html>
