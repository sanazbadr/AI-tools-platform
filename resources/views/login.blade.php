<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - AI Tools Archeoam</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #fff;
        }

        .login-container {
            background-color: #212121;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo {
            width: 80px;
            height: auto;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .login-logo:hover {
            transform: scale(1.05);
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #888;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .auth-tabs {
            display: flex;
            margin-bottom: 25px;
            background-color: #303030;
            border-radius: 8px;
            padding: 4px;
        }

        .auth-tab {
            flex: 1;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .auth-tab.active {
            background-color: #dc3545;
            color: #fff;
        }

        .auth-tab:not(.active) {
            color: #888;
        }

        .auth-tab:not(.active):hover {
            color: #fff;
        }

        .auth-content {
            display: none;
        }

        .auth-content.active {
            display: block;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            background-color: #303030;
            border: 2px solid #404040;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #dc3545;
            background-color: #383838;
        }

        .form-group label {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: #dc3545;
            background-color: #212121;
            padding: 0 5px;
        }

        .login-button {
            width: 100%;
            padding: 15px;
            background-color: #dc3545;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-button:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .google-login-button {
            width: 100%;
            padding: 15px;
            background-color: #4285f4;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .google-login-button:hover {
            background-color: #3367d6;
            transform: translateY(-2px);
        }

        .google-login-button:active {
            transform: translateY(0);
        }

        .google-icon {
            width: 20px;
            height: 20px;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
            color: #888;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #404040;
        }

        .divider span {
            background-color: #212121;
            padding: 0 15px;
        }

        .error-message {
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
            display: none;
            padding: 10px;
            border-radius: 5px;
            background-color: rgba(220, 53, 69, 0.2);
        }

        .success-message {
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
            display: none;
            padding: 10px;
            border-radius: 5px;
            background-color: rgba(40, 167, 69, 0.2);
        }

        /* Decorative animated blobs */
        .decorative {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            width: 46vmax;
            height: 46vmax;
            border-radius: 50%;
            opacity: 0.32;
            filter: blur(70px);
            transform: translate3d(0, 0, 0);
            will-change: transform, opacity;
        }

        .blob1 {
            left: -12vmax;
            bottom: -10vmax;
            background:
                radial-gradient(35% 35% at 30% 30%, #ff6b6b 0%, rgba(255,107,107,0.0) 100%),
                radial-gradient(40% 40% at 70% 70%, #dc3545 0%, rgba(220,53,69,0.0) 100%);
            animation: float1 18s ease-in-out infinite;
        }

        .blob2 {
            right: -14vmax;
            top: -12vmax;
            background:
                radial-gradient(35% 35% at 30% 30%, #6a5acd 0%, rgba(106,90,205,0.0) 100%),
                radial-gradient(40% 40% at 70% 70%, #00c2ff 0%, rgba(0,194,255,0.0) 100%);
            animation: float2 22s ease-in-out infinite;
        }

        .blob3 {
            left: 35vmax;
            bottom: 5vmax;
            background:
                radial-gradient(35% 35% at 30% 30%, #ff8fab 0%, rgba(255,143,171,0.0) 100%),
                radial-gradient(40% 40% at 70% 70%, #ffa94d 0%, rgba(255,169,77,0.0) 100%);
            animation: float3 20s ease-in-out infinite;
        }

        /* Animated AI icon */
        .ai-icon {
            position: fixed;
            object-fit: contain;
            pointer-events: none;
            z-index: 0; /* keep behind container content */
            opacity: 0.28; /* clearer */
            filter: blur(0.4px) drop-shadow(0 1px 4px rgba(0, 0, 0, 0.16)) drop-shadow(0 0 14px rgba(220, 53, 69, 0.14));
            animation: aiFloat 7s ease-in-out infinite, aiTilt 13s ease-in-out infinite;
            will-change: transform, filter, opacity;
        }

        .ai-icon--tl { top: 12vh; left: 8vw; width: 36px; height: 36px; animation-delay: -1.2s, -2.4s; }
        .ai-icon--br { bottom: 12vh; right: 8vw; width: 40px; height: 40px; animation-delay: -0.6s, -3.6s; }
        .ai-icon--lc { top: 55vh; left: 14vw; width: 40px; height: 40px; animation-delay: -2.2s, -3.1s; }
        .ai-icon--rc { top: 42vh; right: 16vw; width: 40px; height: 40px; animation-delay: -4.1s, -1.4s; }
        .ai-icon--tc { top: 11vh; left: 38%; width: 32px; height: 32px; transform: translateX(-50%); animation-delay: -1.5s, -2.5s; }
        .ai-icon--bc { bottom: 9vh; left: 35%; width: 34px; height: 34px; transform: translateX(-50%); animation-delay: -3.0s, -4.0s; }
        .ai-icon--rt { top: 22vh; right: 22vw; width: 34px; height: 34px; animation-delay: -2.7s, -3.7s; }
        .ai-icon--lb { bottom: 18vh; left: 22vw; width: 34px; height: 34px; animation-delay: -1.9s, -2.9s; }

        /* Near-container placements (around centered card) */
        .ai-icon--nlt { top: calc(50% - 140px); left: calc(50% - 290px); width: 26px; height: 26px; animation-delay: -1.4s, -2.6s; }
        .ai-icon--nrt { top: calc(50% - 140px); left: calc(50% + 290px); width: 26px; height: 26px; animation-delay: -2.2s, -3.2s; }
        .ai-icon--nlb { top: calc(50% + 150px); left: calc(50% - 290px); width: 26px; height: 26px; animation-delay: -2.8s, -1.6s; }
        .ai-icon--nrb { top: calc(50% + 150px); left: calc(50% + 290px); width: 26px; height: 26px; animation-delay: -3.2s, -1.8s; }

        /* Magic icon variants */
        .magic-icon {
            position: fixed;
            object-fit: contain;
            pointer-events: none;
            z-index: 0;
            opacity: 0.22; /* clearer */
            filter: blur(0.6px) drop-shadow(0 1px 4px rgba(0,0,0,0.14)) drop-shadow(0 0 12px rgba(0,194,255,0.14)) drop-shadow(0 0 10px rgba(255,169,77,0.10));
            animation: aiFloat 8s ease-in-out infinite, aiTilt 14s ease-in-out infinite;
            will-change: transform, filter, opacity;
        }
        .magic-icon--tc { top: 9vh; left: 62%; width: 40px; height: 40px; transform: translateX(-50%); animation-delay: -1.1s, -2.2s; }
        .magic-icon--rc { top: 58vh; right: 14vw; width: 36px; height: 36px; animation-delay: -3.3s, -1.9s; }
        .magic-icon--tl { top: 16vh; left: 14vw; width: 34px; height: 34px; animation-delay: -2.0s, -3.2s; }
        .magic-icon--bl { bottom: 12vh; left: 18vw; width: 36px; height: 36px; animation-delay: -1.7s, -2.1s; }
        .magic-icon--bc { bottom: 9vh; left: 65%; width: 38px; height: 38px; transform: translateX(-50%); animation-delay: -2.6s, -3.6s; }
        .magic-icon--lc { top: 50%; left: 10vw; width: 34px; height: 34px; transform: translateY(-50%); animation-delay: -1.4s, -2.4s; }
        .magic-icon--tr { top: 14vh; right: 12vw; width: 32px; height: 32px; animation-delay: -2.4s, -3.4s; }
        .magic-icon--rb { bottom: 14vh; right: 18vw; width: 34px; height: 34px; animation-delay: -3.1s, -2.6s; }
        .magic-icon--nrt { top: calc(50% - 150px); left: calc(50% + 270px); width: 24px; height: 24px; animation-delay: -2.1s, -3.1s; }
        .magic-icon--nlb { top: calc(50% + 160px); left: calc(50% - 280px); width: 24px; height: 24px; animation-delay: -1.7s, -2.7s; }

        @keyframes aiFloat {
            0%, 100% { transform: translate3d(0, 0, 0); }
            50% { transform: translate3d(0, -10px, 0); }
        }

        @keyframes aiTilt {
            0%, 100% { rotate: 0deg; }
            25% { rotate: 4deg; }
            50% { rotate: 0deg; }
            75% { rotate: -4deg; }
        }

        @keyframes float1 {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(2vmax, -1.5vmax, 0) scale(1.05); }
        }

        @keyframes float2 {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(-2vmax, 2vmax, 0) scale(0.98); }
        }

        @keyframes float3 {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(-3vmax, -1vmax, 0) scale(1.03); }
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 30px;
            }
            .blob { width: 60vmax; height: 60vmax; filter: blur(60px); opacity: 0.28; }
            .blob1 { left: -25vmax; bottom: -22vmax; }
            .blob2 { right: -28vmax; top: -26vmax; }
            .blob3 { left: 10vmax; bottom: -10vmax; }
            .ai-icon { opacity: 0.24; filter: blur(0.7px) drop-shadow(0 1px 3px rgba(0, 0, 0, 0.12)) drop-shadow(0 0 8px rgba(220, 53, 69, 0.12)); }
            /* Focus on near-container accents on mobile */
            .ai-icon:not(.ai-icon--nlt):not(.ai-icon--nrt):not(.ai-icon--nlb):not(.ai-icon--nrb) { display: none; }
            .ai-icon--nlt { top: calc(50% - 120px); left: calc(50% - 150px); width: 22px; height: 22px; display: block; }
            .ai-icon--nrt { top: calc(50% - 120px); left: calc(50% + 150px); width: 22px; height: 22px; display: block; }
            .ai-icon--nlb { top: calc(50% + 120px); left: calc(50% - 150px); width: 22px; height: 22px; display: block; }
            .ai-icon--nrb { top: calc(50% + 120px); left: calc(50% + 150px); width: 22px; height: 22px; display: block; }
            /* Magic icons: show one near top center for balance */
            .magic-icon { opacity: 0.16; filter: blur(0.9px) drop-shadow(0 1px 2px rgba(0,0,0,0.12)); }
            .magic-icon:not(.magic-icon--tc) { display: none; }
            .magic-icon--tc { width: 24px; height: 24px; top: 9vh; left: 60%; }
            /* Hide magic near variants on mobile to avoid clutter */
            .magic-icon--nrt, .magic-icon--nlb { display: none; }
        }

        /* Animation for focus effect */
        @keyframes focusAnimation {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        .form-group input:focus {
            animation: focusAnimation 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="decorative" aria-hidden="true">
        <div class="blob blob1"></div>
        <div class="blob blob2"></div>
        <div class="blob blob3"></div>
    </div>
    <img src="/image/ai-magicx-icon-filled-256.png" alt="" class="ai-icon ai-icon--tl" aria-hidden="true">
    <img src="/image/ai-magicx-icon-filled-256.png" alt="" class="ai-icon ai-icon--br" aria-hidden="true">
    <img src="/image/ai-magicx-icon-filled-256.png" alt="" class="ai-icon ai-icon--lc" aria-hidden="true">
    <img src="/image/ai-magicx-icon-filled-256.png" alt="" class="ai-icon ai-icon--rc" aria-hidden="true">
    <img src="/image/ai-magicx-icon-filled-256.png" alt="" class="ai-icon ai-icon--tc" aria-hidden="true">
    <img src="/image/ai-magicx-icon-filled-256.png" alt="" class="ai-icon ai-icon--bc" aria-hidden="true">
    <img src="/image/ai-magicx-icon-filled-256.png" alt="" class="ai-icon ai-icon--rt" aria-hidden="true">
    <img src="/image/ai-magicx-icon-filled-256.png" alt="" class="ai-icon ai-icon--lb" aria-hidden="true">
    <img src="/image/ai-magicx-icon-filled-256.png" alt="" class="ai-icon ai-icon--nlt" aria-hidden="true">
    <img src="/image/ai-magicx-icon-filled-256.png" alt="" class="ai-icon ai-icon--nrt" aria-hidden="true">
    <img src="/image/ai-magicx-icon-filled-256.png" alt="" class="ai-icon ai-icon--nlb" aria-hidden="true">
    <img src="/image/ai-magicx-icon-filled-256.png" alt="" class="ai-icon ai-icon--nrb" aria-hidden="true">
    <img src="/image/magic.png" alt="" class="magic-icon magic-icon--tc" aria-hidden="true">
    <img src="/image/magic.png" alt="" class="magic-icon magic-icon--rc" aria-hidden="true">
    <img src="/image/magic.png" alt="" class="magic-icon magic-icon--tl" aria-hidden="true">
    <img src="/image/magic.png" alt="" class="magic-icon magic-icon--bl" aria-hidden="true">
    <img src="/image/magic.png" alt="" class="magic-icon magic-icon--bc" aria-hidden="true">
    <img src="/image/magic.png" alt="" class="magic-icon magic-icon--lc" aria-hidden="true">
    <img src="/image/magic.png" alt="" class="magic-icon magic-icon--tr" aria-hidden="true">
    <img src="/image/magic.png" alt="" class="magic-icon magic-icon--rb" aria-hidden="true">
    <img src="/image/magic.png" alt="" class="magic-icon magic-icon--nrt" aria-hidden="true">
    <img src="/image/magic.png" alt="" class="magic-icon magic-icon--nlb" aria-hidden="true">
    <div class="login-container">
        <div class="login-header">
            <img src="/image/LogoWeb.webp" alt="Archeo AI Logo" class="login-logo">
            <h1>Welcome Back</h1>
            <p>Choose your preferred login method</p>
        </div>

        <!-- Authentication Tabs -->
        <div class="auth-tabs">
            <div class="auth-tab active" onclick="switchTab('google')">Google Login</div>
            <div class="auth-tab" onclick="switchTab('archeoam')">Archeoam Login</div>
        </div>

        <!-- Google Authentication -->
        <div id="google-auth" class="auth-content active">
            <button onclick="handleGoogleLogin()" class="google-login-button">
                <svg class="google-icon" viewBox="0 0 24 24">
                    <path fill="#fff" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#fff" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#fff" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#fff" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continue with Google
            </button>
            <div class="divider">
                <span>or</span>
            </div>
            <p style="text-align: center; color: #888; font-size: 14px; margin-bottom: 20px;">
                New users will get a Basic plan automatically
            </p>
        </div>

        <!-- Archeoam Authentication -->
        <div id="archeoam-auth" class="auth-content">
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="login-button">Login with Archeoam</button>
            </form>
        </div>

        <div class="error-message" id="loginError"></div>
        <div class="success-message" id="loginSuccess"></div>
    </div>

    <script>
        function switchTab(tab) {
            // Update tab styling
            document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
            event.target.classList.add('active');
            
            // Update content visibility
            document.querySelectorAll('.auth-content').forEach(c => c.classList.remove('active'));
            if (tab === 'google') {
                document.getElementById('google-auth').classList.add('active');
            } else {
                document.getElementById('archeoam-auth').classList.add('active');
            }
            
            // Clear messages
            document.getElementById('loginError').style.display = 'none';
            document.getElementById('loginSuccess').style.display = 'none';
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const errorElement = document.getElementById('loginError');
            const successElement = document.getElementById('loginSuccess');
            
            // Hide any existing messages
            errorElement.style.display = 'none';
            successElement.style.display = 'none';
            
            // Include redirect param if present in URL
            const urlParams = new URLSearchParams(window.location.search);
            const redirectParam = urlParams.get('redirect');
            // If we're on the marketing domain, post to the app domain to preserve session continuity
            const appBase = (window.location.hostname === 'archeo.ai') ? 'https://ai.archeoam.com' : '';
            const loginEndpoint = `${appBase}/login${redirectParam ? `?redirect=${encodeURIComponent(redirectParam)}` : ''}`;

            fetch(loginEndpoint, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: formData.get('email'),
                    password: formData.get('password'),
                    _token: formData.get('_token')
                })
            })
            .then(async response => {
                const data = await response.json();
                
                // Handle different response status codes
                if (response.status === 401) {
                    // Authentication failed
                    errorElement.textContent = data.message || 'Authentication failed. Please try again.';
                    errorElement.style.display = 'block';
                    return;
                }
                
                if (response.status === 403) {
                    // Plan access error
                    errorElement.textContent = 'Your plan does not grant access to this service.';
                    errorElement.style.display = 'block';
                    return;
                }
                
                if (data.success) {
                    // Check if there's a pending message from the home page (check both sessionStorage and localStorage)
                    const pendingMessage = sessionStorage.getItem('pendingMessage') || localStorage.getItem('pendingMessage');
                    console.log('Login page - pendingMessage from sessionStorage:', sessionStorage.getItem('pendingMessage'));
                    console.log('Login page - pendingMessage from localStorage:', localStorage.getItem('pendingMessage'));
                    console.log('Login page - final pendingMessage:', pendingMessage);
                    
                    if (pendingMessage) {
                        // Clear the pending message from both storages
                        sessionStorage.removeItem('pendingMessage');
                        localStorage.removeItem('pendingMessage');
                        // Store the message for the chatbot
                        sessionStorage.setItem('chatMessage', pendingMessage);
                        // Store flag to create new conversation
                        sessionStorage.setItem('createNewConversation', 'true');
                        console.log('Login page - stored chatMessage:', sessionStorage.getItem('chatMessage'));
                        console.log('Login page - stored createNewConversation:', sessionStorage.getItem('createNewConversation'));
                        successElement.textContent = 'Login successful! Creating new conversation...';
                        
                        // Also pass as URL parameter as backup
                        const encodedMessage = encodeURIComponent(pendingMessage);
                        setTimeout(() => {
                            // Prefer server-provided redirect when available
                            const target = (data.redirect && data.redirect !== '/chatbot') ? data.redirect : `/chatbot?message=${encodedMessage}&create_conversation=true`;
                            const fullTarget = (window.location.hostname === 'archeo.ai') ? `https://ai.archeoam.com${target}` : target;
                            window.location.href = fullTarget;
                        }, 1000);
                    } else {
                        // Use server-provided redirect if present; fallback to /chatbot
                        successElement.textContent = 'Login successful! Redirecting...';
                        setTimeout(() => {
                            const target = data.redirect || '/chatbot';
                            const fullTarget = (window.location.hostname === 'archeo.ai') ? `https://ai.archeoam.com${target}` : target;
                            window.location.href = fullTarget;
                        }, 1000);
                    }
                    
                    successElement.style.display = 'block';
                } else {
                    // Other errors from the API
                    errorElement.textContent = data.message || 'An error occurred. Please try again.';
                    errorElement.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorElement.textContent = 'An error occurred. Please try again.';
                errorElement.style.display = 'block';
            });
        });

        // Clear error message when input changes
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                document.getElementById('loginError').style.display = 'none';
                document.getElementById('loginSuccess').style.display = 'none';
            });
        });

        // Handle Google login
        function handleGoogleLogin() {
            // Prefer message from URL (carried from homepage), fallback to storages
            const urlMessage = urlParams.get('message');
            const redirectParam = urlParams.get('redirect');
            const pendingMessage = urlMessage || sessionStorage.getItem('pendingMessage') || localStorage.getItem('pendingMessage');
            console.log('Google login - url message:', urlMessage);
            console.log('Google login - pendingMessage from sessionStorage:', sessionStorage.getItem('pendingMessage'));
            console.log('Google login - pendingMessage from localStorage:', localStorage.getItem('pendingMessage'));
            console.log('Google login - final pendingMessage:', pendingMessage);

            if (pendingMessage) {
                // Seed storages for chatbot page
                sessionStorage.setItem('chatMessage', pendingMessage);
                sessionStorage.setItem('createNewConversation', 'true');
                localStorage.setItem('pendingChatMessage', pendingMessage);
                localStorage.setItem('pendingCreateConversation', 'true');
                sessionStorage.removeItem('pendingMessage');
                localStorage.removeItem('pendingMessage');
            }

            // Redirect to Google and carry the message via query to our /auth/google route
            const encoded = pendingMessage ? encodeURIComponent(pendingMessage) : '';
            const encodedRedirect = redirectParam ? encodeURIComponent(redirectParam) : '';
            // Always initiate Google OAuth on app domain to preserve session and redirect
            const appBase = (window.location.hostname === 'archeo.ai') ? 'https://ai.archeoam.com' : '';
            let target = `${appBase}/auth/google`;
            const join = target.includes('?') ? '&' : '?';
            if (encoded || encodedRedirect) {
                const parts = [];
                if (encoded) parts.push(`message=${encoded}`);
                if (encodedRedirect) parts.push(`redirect=${encodedRedirect}`);
                target = `${target}${join}${parts.join('&')}`;
            }
            console.log('Redirecting to Google login with:', target);
            window.location.href = target;
        }

        // On load: hydrate storages from URL if present (supports deep-link from homepage)
        const urlParams = new URLSearchParams(window.location.search);
        const incomingMessage = urlParams.get('message');
        const incomingCreate = urlParams.get('create_conversation') === 'true';
        if (incomingMessage) {
            sessionStorage.setItem('pendingMessage', incomingMessage);
            localStorage.setItem('pendingMessage', incomingMessage);
            // Also pre-seed for chatbot
            sessionStorage.setItem('chatMessage', incomingMessage);
            sessionStorage.setItem('createNewConversation', incomingCreate ? 'true' : 'true');
            localStorage.setItem('pendingChatMessage', incomingMessage);
            localStorage.setItem('pendingCreateConversation', 'true');
            console.log('Login page hydrated from URL message:', incomingMessage);
        }

        // Check for URL parameters to show success/error messages
        if (urlParams.get('success')) {
            document.getElementById('loginSuccess').textContent = urlParams.get('success');
            document.getElementById('loginSuccess').style.display = 'block';
        }
        if (urlParams.get('error')) {
            document.getElementById('loginError').textContent = urlParams.get('error');
            document.getElementById('loginError').style.display = 'block';
        }
    </script>
</body>
</html> 