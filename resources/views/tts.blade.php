<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Text to Speech</title>
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
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background-color: #212121;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            max-width: 180px;
            height: auto;
            transition: all 0.3s ease;
        }

        @media (max-width: 480px) {
            .logo {
                max-width: 140px;
            }
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #fff;
            font-weight: 500;
        }

        textarea {
            width: 100%;
            padding: 15px;
            background-color: #303030;
            border: 2px solid #404040;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            resize: vertical;
            min-height: 150px;
            transition: all 0.3s ease;
        }

        select {
            width: 100%;
            padding: 15px;
            background-color: #303030;
            border: 2px solid #404040;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            appearance: none;
        }

        .voice-dropdown {
            position: relative;
            width: 100%;
        }

        .voice-select-btn {
            width: 100%;
            padding: 15px;
            background-color: #303030;
            border: 2px solid #404040;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            text-align: left;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .voice-select-btn:hover {
            background-color: #383838;
            border-color: #dc3545;
        }

        .voice-select-btn i {
            transition: transform 0.3s ease;
        }

        .voice-select-btn.active i {
            transform: rotate(180deg);
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: #303030;
            border: 2px solid #404040;
            border-radius: 8px;
            margin-top: 5px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
        }

        .dropdown-content.show {
            display: block;
        }

        .voice-option {
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 1px solid #404040;
        }

        .voice-option:last-child {
            border-bottom: none;
        }

        .voice-option:hover {
            background-color: #383838;
        }

        .voice-option.selected {
            background-color: #383838;
            border-color: #dc3545;
        }

        .voice-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .voice-description {
            color: #888;
            font-size: 14px;
        }

        .selected-voice {
            display: flex;
            flex-direction: column;
        }

        /* Scrollbar styling */
        .dropdown-content::-webkit-scrollbar {
            width: 8px;
        }

        .dropdown-content::-webkit-scrollbar-track {
            background: #303030;
            border-radius: 4px;
        }

        .dropdown-content::-webkit-scrollbar-thumb {
            background: #505050;
            border-radius: 4px;
        }

        .dropdown-content::-webkit-scrollbar-thumb:hover {
            background: #606060;
        }

        .controls {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        button {
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .convert-btn {
            background-color: #dc3545;
            color: #fff;
            flex: 1;
        }

        .convert-btn:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .convert-btn:disabled {
            background-color: #666;
            cursor: not-allowed;
            transform: none;
        }

        .clear-btn {
            background-color: #424242;
            color: #fff;
        }

        .clear-btn:hover {
            background-color: #505050;
        }

        .audio-container {
            margin-top: 20px;
            display: none;
        }

        audio {
            width: 100%;
            margin-top: 10px;
            border-radius: 8px;
        }

        .loading {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #fff;
            margin-top: 10px;
        }

        .loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error-message {
            color: #dc3545;
            margin-top: 10px;
            text-align: center;
            display: none;
            padding: 10px;
            border-radius: 5px;
            background-color: rgba(220, 53, 69, 0.1);
        }

        .character-count {
            color: #888;
            font-size: 14px;
            text-align: right;
            margin-top: 5px;
        }

        @media (max-width: 480px) {
            .container {
                padding: 25px;
            }
        }

        /* Add these styles for the heading section */
        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 10px;
        }

        .page-description {
            color: #888;
            font-size: 16px;
            line-height: 1.5;
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 28px;
            }
            .page-description {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 20px;">
            <a href="/dashboard" style="text-decoration:none; background:#404040; color:#fff; padding:10px 16px; border-radius:8px; display:inline-flex; align-items:center; gap:8px;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" style="background:#dc3545; color:#fff; border:none; padding:10px 16px; border-radius:8px; cursor:pointer; display:inline-flex; align-items:center; gap:8px;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
        <div class="logo-container">
            <img src="/image/LogoWeb.webp" alt="Logo" class="logo">
        </div>

        <div class="page-header">
            <h1 class="page-title">Text to Speech Converter</h1>
            <p class="page-description">Convert your text into natural-sounding speech using advanced AI voices</p>
        </div>

        <div class="form-group">
            <label for="text">Enter Text (max 4096 characters)</label>
            <textarea id="text" placeholder="Type or paste your text here..."></textarea>
            <div class="character-count">0 / 4096</div>
        </div>

        <div class="form-group">
            <label>Select Voice</label>
            <div class="voice-dropdown">
                <button type="button" class="voice-select-btn" onclick="toggleDropdown()">
                    <div class="selected-voice">
                        <div class="voice-name">Alloy</div>
                        <div class="voice-description">Neutral and balanced</div>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="dropdown-content">
                    @foreach($voices as $key => $description)
                    <div class="voice-option {{ $loop->first ? 'selected' : '' }}" 
                         onclick="selectVoice(this, '{{ $key }}', '{{ ucfirst($key) }}', '{{ $description }}')">
                        <div class="voice-name">{{ ucfirst($key) }}</div>
                        <div class="voice-description">{{ $description }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="controls">
            <button class="convert-btn" onclick="convertToSpeech()" id="convertBtn">
                <i class="fas fa-play"></i>
                Convert to Speech
            </button>
            <button class="clear-btn" onclick="clearText()">
                <i class="fas fa-trash"></i>
                Clear
            </button>
        </div>

        <div class="loading">
            <i class="fas fa-spinner"></i>
            Generating audio...
        </div>

        <div class="error-message"></div>

        <div class="audio-container">
            <audio controls>
                Your browser does not support the audio element.
            </audio>
        </div>
    </div>

    <script>
        const textarea = document.getElementById('text');
        const charCount = document.querySelector('.character-count');
        const convertBtn = document.getElementById('convertBtn');
        let selectedVoice = 'alloy'; // Default voice

        function updateCharCount() {
            const count = textarea.value.length;
            charCount.textContent = `${count} / 4096`;
            convertBtn.disabled = count === 0 || count > 4096;
        }

        function toggleDropdown() {
            const dropdown = document.querySelector('.dropdown-content');
            const btn = document.querySelector('.voice-select-btn');
            dropdown.classList.toggle('show');
            btn.classList.toggle('active');
        }

        function selectVoice(element, voice, name, description) {
            selectedVoice = voice;
            
            // Update selected state in dropdown
            document.querySelectorAll('.voice-option').forEach(opt => opt.classList.remove('selected'));
            element.classList.add('selected');
            
            // Update button content
            const selectedVoiceDiv = document.querySelector('.selected-voice');
            selectedVoiceDiv.innerHTML = `
                <div class="voice-name">${name}</div>
                <div class="voice-description">${description}</div>
            `;
            
            // Close dropdown
            toggleDropdown();
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.voice-dropdown')) {
                document.querySelector('.dropdown-content').classList.remove('show');
                document.querySelector('.voice-select-btn').classList.remove('active');
            }
        });

        async function convertToSpeech() {
            const text = textarea.value.trim();
            if (!text) return;

            showLoading(true);
            hideError();
            
            try {
                const response = await fetch('/api/tts/convert', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ text, voice: selectedVoice })
                });

                const data = await response.json();

                if (data.success) {
                    const audioContainer = document.querySelector('.audio-container');
                    const audio = audioContainer.querySelector('audio');
                    audio.src = data.audio_url;
                    audioContainer.style.display = 'block';
                    audio.play();
                } else {
                    throw new Error(data.error || 'Failed to generate audio');
                }
            } catch (error) {
                console.error('Error:', error);
                showError(error.message || 'Failed to generate audio');
            } finally {
                showLoading(false);
            }
        }

        function clearText() {
            textarea.value = '';
            updateCharCount();
            document.querySelector('.audio-container').style.display = 'none';
            hideError();
        }

        function showLoading(show) {
            document.querySelector('.loading').style.display = show ? 'flex' : 'none';
            convertBtn.disabled = show;
        }

        function showError(message) {
            const errorElement = document.querySelector('.error-message');
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }

        function hideError() {
            document.querySelector('.error-message').style.display = 'none';
        }

        textarea.addEventListener('input', updateCharCount);
        updateCharCount();
    </script>
</body>
</html>
