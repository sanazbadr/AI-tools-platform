<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Archeo AI Dashboard</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
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

        .header {
            background-color: #171717;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .title {
            font-size: 1.5em;
            margin: 0;
            color: #fff;
        }

        .user-menu {
            position: relative;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 15px;
            background-color: #303030;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .user-profile:hover {
            background-color: #404040;
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #303030;
            border-radius: 8px;
            padding: 10px 0;
            margin-top: 5px;
            min-width: 150px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .user-dropdown.active {
            display: block;
        }

        .user-dropdown a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .user-dropdown a:hover {
            background-color: #404040;
        }

        .user-dropdown form {
            margin: 0;
            padding: 0;
        }

        .user-dropdown button {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            color: #fff;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .user-dropdown button:hover {
            background-color: #404040;
        }

        .main-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .tool-card {
            background-color: #303030;
            border-radius: 15px;
            padding: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .tool-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .tool-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
            color: #dc3545;
        }

        .tool-title {
            font-size: 1.2em;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .tool-description {
            font-size: 0.9em;
            color: #aaa;
            line-height: 1.5;
        }

        .stats {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            font-size: 0.8em;
            color: #888;
        }

        .logout-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .logout-button:hover {
            background-color: #c82333;
        }

        @media (max-width: 768px) {
            .tools-grid {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .user-menu {
                flex-direction: column;
            }
        }

        .nav-link {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 8px;
            background-color: #303030;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease;
        }

        .nav-link:hover {
            background-color: #404040;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="{{ asset('image/LogoWeb.webp') }}" alt="Logo" class="logo">
            <h1 class="title">Archeo AI Dashboard</h1>
        </div>
        <div class="user-menu">
            <div class="user-profile" onclick="toggleDropdown()">
                @php
                    $user = Session::get('user');
                    $avatar = $user['avatar'] ?? null;
                    $authType = $user['auth_type'] ?? 'archeoam';
                    $userName = $user['name'] ?? Session::get('name', 'User');
                @endphp
                
                @if($avatar && $authType === 'google')
                    <img src="{{ $avatar }}" alt="Profile" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                @else
                    <i class="fas fa-user"></i>
                @endif
                <span>{{ $userName }}</span>
                <i class="fas fa-chevron-down" style="margin-left: 5px; font-size: 0.8em;"></i>
            </div>
            <div class="user-dropdown" id="userDropdown">
                <a href="/">
                    <i class="fas fa-home"></i>
                    Home
                </a>
                @if($authType === 'google')
                    <form method="POST" action="{{ route('google.logout') }}">
                        @csrf
                        <button type="submit">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="tools-grid">
            <a href="/chatbot" class="tool-card">
                <div class="tool-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="tool-title">AI Chatbot</div>
                <div class="tool-description">Interact with our advanced AI chatbot for intelligent conversations and assistance.</div>
                <div class="stats">
                    <span><i class="fas fa-clock"></i> Always Available</span>
                </div>
            </a>

            <a href="/dalle" class="tool-card">
                <div class="tool-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="tool-title">Archeo Image Generator</div>
                <div class="tool-description">Generate unique and creative images from text descriptions using AI.</div>
                <div class="stats">
                    <span><i class="fas fa-image"></i> High Quality Images</span>
                </div>
            </a>

            <a href="/tts" class="tool-card">
                <div class="tool-icon">
                    <i class="fas fa-volume-up"></i>
                </div>
                <div class="tool-title">Text to Speech</div>
                <div class="tool-description">Convert text to natural-sounding speech in multiple languages.</div>
                <div class="stats">
                    <span><i class="fas fa-language"></i> Multiple Languages</span>
                </div>
            </a>

            <a href="/stt" class="tool-card">
                <div class="tool-icon">
                    <i class="fas fa-microphone"></i>
                </div>
                <div class="tool-title">Speech to Text</div>
                <div class="tool-description">Convert spoken words to text with high accuracy.</div>
                <div class="stats">
                    <span><i class="fas fa-check"></i> High Accuracy</span>
                </div>
            </a>
        </div>
    </div>

    <script>
    function toggleDropdown() {
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('active');

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const userMenu = document.querySelector('.user-menu');
            if (!userMenu.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });
    }

    </script>
</body>
</html> 