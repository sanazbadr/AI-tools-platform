<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Image Creator - Archeo AI Tools</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #212121;
            color: #fff;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .top-header {
            background-color: #171717;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 40px;
            height: auto;
        }

        .logo-title {
            font-size: 1.5em;
            margin: 0;
            color: #fff;
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
        }

        /* Restored button styles to match site */
        .nav-button {
            position: static;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease;
        }

        .back-to-chat {
            background-color: #404040;
        }

        .nav-button.back-to-chat:hover {
            background-color: #505050;
        }

        .logout-button {
            background-color: #dc3545;
        }

        .nav-button.logout-button:hover {
            background-color: #c82333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .input-container {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            background-color: #303030;
            padding: 20px;
            border-radius: 15px;
        }

        #promptInput {
            flex-grow: 1;
            padding: 15px;
            border: none;
            border-radius: 8px;
            background-color: #404040;
            color: #fff;
            font-size: 16px;
        }

        #generateButton {
            padding: 15px 30px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        #generateButton:hover {
            background-color: #c82333;
        }

        #generateButton:disabled {
            background-color: #666;
            cursor: not-allowed;
        }

        .loading {
            display: none;
            text-align: center;
            margin: 20px 0;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #dc3545;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .image-container {
            background-color: #303030;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
        }

        .image-container:hover {
            transform: translateY(-5px);
        }

        .generated-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            cursor: pointer;
        }

        .image-info {
            padding: 15px;
        }

        .prompt-text {
            margin: 0 0 10px 0;
            color: #ccc;
            font-size: 14px;
            line-height: 1.4;
        }

        .download-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .download-button:hover {
            background-color: #218838;
        }

        .error-message {
            background-color: #dc3545;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            display: none;
        }

        .hero-section {
            text-align: center;
            padding: 40px 20px;
            background: linear-gradient(135deg, #171717 0%, #212121 100%);
        }

        .hero-title {
            font-size: 3em;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #dc3545, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-description {
            font-size: 1.2em;
            color: #ccc;
            max-width: 600px;
            margin: 0 auto;
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            transition: left 0.3s ease;
            z-index: 1000;
        }

        .mobile-menu.active {
            left: 0;
        }

        .menu-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            gap: 20px;
        }

        .mobile-nav-button {
            background-color: #404040;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .mobile-nav-button:hover {
            background-color: #505050;
        }

        .hamburger-btn {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1001;
        }

        @media (max-width: 768px) {
            .hamburger-btn {
                display: block;
            }

            .nav-buttons {
                display: none;
            }

            .hero-title {
                font-size: 2em;
            }

            .input-container {
                flex-direction: column;
            }

            #generateButton {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="top-header">
        <div class="logo-container">
            <img src="{{ asset('image/LogoWeb.webp') }}" alt="Logo" class="logo">
            <h1 class="logo-title">AI Image Creator</h1>
        </div>
        <div class="nav-buttons">
            @if(app()->environment('local') || app()->environment('development'))
                <a href="/" class="nav-button back-to-chat">
                    <i class="fas fa-home"></i> Back to Home
                </a>
            @else
                <a href="/dashboard" class="nav-button back-to-chat">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-button logout-button">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            @endif
        </div>
    </div>

    <section class="hero-section">
        <h1 class="hero-title">AI Image Creator</h1>
        <p class="hero-description">Transform your ideas into stunning visuals with our advanced AI technology</p>
    </section>

    <div class="container">
        <div class="input-container">
            <input type="text" id="promptInput" placeholder="Describe the image you want to generate...">
            <button id="generateButton">Generate Image</button>
        </div>

        <div class="loading">
            <div class="loading-spinner"></div>
            <p>Generating your image...</p>
        </div>

        <div class="error-message"></div>

        <div class="gallery" id="imageGallery">
            <!-- Generated images will be displayed here -->
        </div>
    </div>

    <div class="mobile-menu" id="mobileMenu">
        <div class="menu-content">
            @if(app()->environment('local') || app()->environment('development'))
                <a href="/" class="mobile-nav-button">
                    <i class="fas fa-home"></i> Back to Home
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="mobile-nav-button">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mobile-nav-button">
                    @csrf
                    <button type="submit">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="hamburger-btn" id="hamburgerBtn">
        <i class="fas fa-bars"></i>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const promptInput = document.getElementById('promptInput');
            const generateButton = document.getElementById('generateButton');
            const loadingDiv = document.querySelector('.loading');
            const errorDiv = document.querySelector('.error-message');
            const gallery = document.getElementById('imageGallery');
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const body = document.body;

            // Determine the API endpoint based on the current environment
            const isDev = window.location.pathname.includes('/dev/');
            const apiEndpoint = isDev ? '/dev/dalle/generate-image' : '/api/dalle/generate-image';

            generateButton.addEventListener('click', generateImage);
            promptInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    generateImage();
                }
            });

            async function generateImage() {
                const prompt = promptInput.value.trim();
                if (!prompt) return;

                // Show loading state
                generateButton.disabled = true;
                loadingDiv.style.display = 'block';
                errorDiv.style.display = 'none';

                try {
                    const response = await fetch(apiEndpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ prompt })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Create new image container
                        const container = document.createElement('div');
                        container.className = 'image-container';

                        // Create image element
                        const img = document.createElement('img');
                        img.src = data.image;
                        img.alt = prompt;
                        img.className = 'generated-image';
                        img.onclick = () => window.open(data.image, '_blank');

                        // Create prompt text and download button container
                        const promptDiv = document.createElement('div');
                        promptDiv.className = 'image-info';
                        
                        // Add prompt text
                        const promptText = document.createElement('p');
                        promptText.className = 'prompt-text';
                        promptText.textContent = prompt;
                        
                        // Add download button
                        const downloadButton = document.createElement('button');
                        downloadButton.className = 'download-button';
                        downloadButton.innerHTML = '<i class="fas fa-download"></i> Save';
                        downloadButton.onclick = (e) => {
                            e.stopPropagation(); // Prevent image preview from opening
                            downloadImage(data.image, `dalle-image-${Date.now()}.png`);
                        };

                        // Assemble the elements
                        promptDiv.appendChild(promptText);
                        promptDiv.appendChild(downloadButton);
                        container.appendChild(img);
                        container.appendChild(promptDiv);
                        gallery.insertBefore(container, gallery.firstChild);

                        // Clear input
                        promptInput.value = '';
                    } else {
                        throw new Error(data.error || 'Failed to generate image');
                    }
                } catch (error) {
                    errorDiv.textContent = error.message;
                    errorDiv.style.display = 'block';
                } finally {
                    generateButton.disabled = false;
                    loadingDiv.style.display = 'none';
                }
            }

            // Function to handle image download
            async function downloadImage(url, filename) {
                try {
                    const response = await fetch(url);
                    const blob = await response.blob();
                    const downloadUrl = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = downloadUrl;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(downloadUrl);
                } catch (error) {
                    console.error('Error downloading image:', error);
                }
            }

            hamburgerBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('active');
                body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (mobileMenu.classList.contains('active') && 
                    !mobileMenu.contains(e.target) && 
                    !hamburgerBtn.contains(e.target)) {
                    mobileMenu.classList.remove('active');
                    body.style.overflow = '';
                }
            });
        });
    </script>
</body>
</html> 