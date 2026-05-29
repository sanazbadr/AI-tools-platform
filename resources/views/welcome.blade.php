<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Tools - Advanced AI Solutions</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #171717;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(33, 33, 33, 0.95);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .logo {
            max-width: 100px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            background-color: #383838;
        }

        .login-btn {
            background-color: #dc3545;
            color: #fff;
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .login-btn:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 160px 20px 60px;
        }

        .hero-section {
            text-align: center;
            max-width: 1200px;
            margin: 0 auto 80px;
            padding-top: 100px;
        }

        .hero-title {
            font-size: 56px;
            font-weight: bold;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #dc3545, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }

        .hero-description {
            font-size: 20px;
            color: #888;
            max-width: 600px;
            margin: 0 auto 40px;
            line-height: 1.6;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 100px;
        }

        /* Text to Image Feature Section */
        .text-to-image-section {
            max-width: 1200px;
            margin: 0 auto 80px;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 60px;
            min-height: 500px;
        }

        .image-slider {
            flex: 1;
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .slider-container {
            position: relative;
            width: 100%;
            height: 400px;
        }

        .slider-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }

        .slider-image.active {
            opacity: 1;
        }

        .slider-caption {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            font-size: 16px;
            backdrop-filter: blur(10px);
        }

        .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .slider-nav:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-50%) scale(1.1);
        }

        .slider-nav.prev {
            left: 20px;
        }

        .slider-nav.next {
            right: 20px;
        }

        .feature-content {
            flex: 1;
            padding: 40px 0;
        }

        .feature-title {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #fff;
            line-height: 1.2;
        }

        .feature-description {
            font-size: 18px;
            color: #888;
            line-height: 1.7;
            margin-bottom: 40px;
            max-width: 500px;
        }

        .feature-button {
            background: linear-gradient(45deg, #dc3545, #ff6b6b);
            color: white;
            padding: 15px 35px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .feature-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(220, 53, 69, 0.4);
        }

        .feature-button i {
            font-size: 14px;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 0 20px;
        }

        .feature-card {
            background-color: #212121;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #dc3545, #ff6b6b);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            font-size: 40px;
            color: #dc3545;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }

        .feature-title {
            font-size: 24px;
            margin-bottom: 15px;
            color: #fff;
        }

        .feature-description {
            color: #888;
            line-height: 1.6;
        }

        .footer {
            background-color: #212121;
            padding: 40px 20px;
            margin-top: 80px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-links {
            display: flex;
            gap: 30px;
        }

        .footer-links a {
            color: #888;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #dc3545;
        }

        .copyright {
            color: #888;
            font-size: 14px;
        }

        /* Hamburger Menu Styles */
        .hamburger {
            display: none;
            cursor: pointer;
            padding: 10px;
            z-index: 1001;
        }

        .hamburger-line {
            width: 25px;
            height: 3px;
            background-color: #fff;
            margin: 5px 0;
            transition: all 0.3s ease;
        }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            right: -100%;
            width: 250px;
            height: 100vh;
            background-color: #212121;
            padding: 80px 20px 20px;
            transition: right 0.3s ease;
            z-index: 1000;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
        }

        .mobile-menu.active {
            right: 0;
        }

        .mobile-menu a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .mobile-menu a:hover {
            background-color: #383838;
        }

        .hamburger.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 40px;
            }

            .hero-description {
                font-size: 18px;
            }

            .nav-links {
                display: none;
            }

            .hamburger {
                display: block;
            }

            .mobile-menu {
                display: block;
            }

            .text-to-image-section {
                flex-direction: column;
                gap: 40px;
                text-align: center;
            }

            .feature-title {
                font-size: 36px;
            }

            .slider-container {
                height: 300px;
            }

            .footer-content {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .footer-links {
                flex-direction: column;
                gap: 15px;
            }
        }

        @media (max-width: 1024px) {
            .features {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 32px;
            }

            .cta-buttons {
                flex-direction: column;
                padding: 0 20px;
            }

            .feature-card {
                padding: 30px 20px;
            }

            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <img src="/image/LogoWeb.webp" alt="Logo" class="logo">
        <nav class="nav-links">
            <a href="{{ route('chatbot') }}">AI Chatbot</a>
            <a href="{{ route('dalle') }}">Image Generation</a>
            <a href="{{ route('tts') }}">Text to Speech</a>
            <a href="{{ route('stt') }}">Speech to Text</a>
            @if(Session::has('user_id'))
                <a href="{{ route('dashboard') }}" class="login-btn">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="login-btn">Login</a>
            @endif
        </nav>
        
        <!-- Hamburger Menu Button -->
        <div class="hamburger">
            <div class="hamburger-line"></div>
            <div class="hamburger-line"></div>
            <div class="hamburger-line"></div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu">
            <a href="{{ route('chatbot') }}">AI Chatbot</a>
            <a href="{{ route('dalle') }}">Image Generation</a>
            <a href="{{ route('tts') }}">Text to Speech</a>
            <a href="{{ route('stt') }}">Speech to Text</a>
            @if(Session::has('user_id'))
                <a href="{{ route('dashboard') }}" class="login-btn">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="login-btn">Login</a>
            @endif
        </div>
    </header>

    <!-- Add JavaScript for hamburger menu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.querySelector('.hamburger');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            hamburger.addEventListener('click', function() {
                hamburger.classList.toggle('active');
                mobileMenu.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
                    hamburger.classList.remove('active');
                    mobileMenu.classList.remove('active');
                }
            });

            // Close menu when clicking a link
            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    hamburger.classList.remove('active');
                    mobileMenu.classList.remove('active');
                });
            });

            // Image Slider Functionality
            const sliderImages = document.querySelectorAll('.slider-image');
            const prevBtn = document.querySelector('.slider-nav.prev');
            const nextBtn = document.querySelector('.slider-nav.next');
            let currentImageIndex = 0;

            function showImage(index) {
                sliderImages.forEach((img, i) => {
                    img.classList.toggle('active', i === index);
                });
                
                // Update caption
                const caption = document.querySelector('.slider-caption p');
                if (caption && sliderImages[index]) {
                    caption.textContent = sliderImages[index].getAttribute('data-caption');
                }
            }

            function nextImage() {
                currentImageIndex = (currentImageIndex + 1) % sliderImages.length;
                showImage(currentImageIndex);
            }

            function prevImage() {
                currentImageIndex = (currentImageIndex - 1 + sliderImages.length) % sliderImages.length;
                showImage(currentImageIndex);
            }

            if (prevBtn && nextBtn) {
                prevBtn.addEventListener('click', prevImage);
                nextBtn.addEventListener('click', nextImage);
            }

            // Auto-advance slider every 5 seconds
            setInterval(nextImage, 5000);
        });
    </script>

    <main class="main-content">
        <section class="hero-section">
            <h1 class="hero-title">
                AI-Powered Communication Suite: Chat, Voice & Text Conversion
            </h1>
            <p class="hero-description">
                Experience the power of artificial intelligence with our suite of advanced tools designed to enhance your productivity and creativity.
            </p>
            <div class="cta-buttons">
                <a href="{{ route('login') }}" class="login-btn">Get Started Free</a>
                <a href="#features" class="login-btn" style="background-color: transparent; border: 2px solid #dc3545;">
                    Explore Features
                </a>
            </div>
        </section>

        <section class="text-to-image-section">
            <div class="image-slider">
                <div class="slider-container">
                    <img src="https://via.placeholder.com/600x400/4a90e2/ffffff?text=Astronaut+in+Grass+Field" alt="AI Generated Astronaut in Grass Field" class="slider-image active" data-caption="Astronaut in a grass field - AI Generated Art">
                    <img src="https://via.placeholder.com/600x400/e24a90/ffffff?text=Futuristic+City+Landscape" alt="AI Generated Futuristic City" class="slider-image" data-caption="Futuristic city landscape - AI Generated Art">
                </div>
                <div class="slider-nav prev">&#10094;</div>
                <div class="slider-nav next">&#10095;</div>
                <div class="slider-caption">
                    <p>Astronaut in a grass field - AI Generated Art</p>
                </div>
            </div>
            <div class="feature-content">
                <h2 class="feature-title">Text to Image</h2>
                <p class="feature-description">Ignite your creative spark with our AI Image Generator. Describe your vision with words, and watch the powerful tool translate them into captivating artwork. Catalyze a flurry of ideas and conquer creative roadblocks.</p>
                <a href="{{ route('dalle') }}" class="feature-button">
                    <i class="fas fa-arrow-up-right"></i>
                    Generate Image
                </a>
            </div>
        </section>

        <section class="features" id="features">
            <div class="feature-card">
                <i class="fas fa-robot feature-icon"></i>
                <h2 class="feature-title">AI Chatbot</h2>
                <p class="feature-description">Engage with our intelligent chatbot for instant assistance and natural conversations. Experience human-like interactions powered by advanced AI.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-palette feature-icon"></i>
                <h2 class="feature-title">Image Generation</h2>
                <p class="feature-description">Transform your ideas into stunning visuals with our advanced AI image generation tool. Create unique artworks, realistic photos, and creative designs in seconds.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-microphone feature-icon"></i>
                <h2 class="feature-title">Text to Speech</h2>
                <p class="feature-description">Convert your text into natural-sounding speech with multiple voice options. Perfect for content creation, accessibility, and learning.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-headphones feature-icon"></i>
                <h2 class="feature-title">Speech to Text</h2>
                <p class="feature-description">Transform your voice into written text with high accuracy and multiple language support. Ideal for transcription and note-taking.</p>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">About Us</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Contact</a>
            </div>
            <div class="copyright">
                © {{ date('Y') }} Archeo AI Tools. All rights reserved.
            </div>
        </div>
    </footer>
Success!
</body>
</html>
