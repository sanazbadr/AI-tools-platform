<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Speech to Text</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
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
            max-width: 120px;
            height: auto;
        }

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

        .upload-container {
            margin-bottom: 30px;
        }

        .file-input-container {
            position: relative;
            margin-bottom: 20px;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px;
            background-color: #dc3545;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            background-color: #c82333;
        }

        .file-input {
            display: none;
        }

        .file-info {
            margin-top: 10px;
            color: #888;
            font-size: 14px;
            text-align: center;
        }

        .editor-container {
            margin-bottom: 30px;
            background-color: #303030;
            border-radius: 8px;
            overflow: hidden;
        }

        .ql-toolbar.ql-snow {
            border: none !important;
            background-color: #383838;
            border-bottom: 1px solid #404040 !important;
        }

        .ql-toolbar button {
            color: #fff !important;
        }

        .ql-toolbar button:hover {
            color: #dc3545 !important;
        }

        .ql-toolbar .ql-stroke {
            stroke: #fff !important;
        }

        .ql-toolbar .ql-fill {
            fill: #fff !important;
        }

        .ql-toolbar button:hover .ql-stroke {
            stroke: #dc3545 !important;
        }

        .ql-toolbar button:hover .ql-fill {
            fill: #dc3545 !important;
        }

        .ql-toolbar .ql-picker {
            color: #fff !important;
        }

        .ql-toolbar .ql-picker-options {
            background-color: #383838 !important;
            border-color: #404040 !important;
        }

        .ql-container.ql-snow {
            border: none !important;
            height: 200px;
        }

        .ql-editor {
            color: #fff !important;
            font-size: 16px;
            direction: inherit;
        }

        .ql-editor.ql-blank::before {
            color: #666 !important;
            font-style: normal !important;
            font-size: 16px;
        }

        /* Hide placeholder when editor has content */
        .ql-editor:not(.ql-blank)::before {
            display: none !important;
        }

        .controls {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .control-button {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }

        .convert-btn {
            background-color: #dc3545;
            color: #fff;
        }

        .convert-btn:hover {
            background-color: #c82333;
        }

        .convert-btn:disabled {
            background-color: #666;
            cursor: not-allowed;
        }

        .clear-btn {
            background-color: #424242;
            color: #fff;
        }

        .clear-btn:hover {
            background-color: #505050;
        }

        .loading {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
            color: #888;
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
            background-color: rgba(220, 53, 69, 0.1);
            border-radius: 5px;
        }

        .text-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .direction-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            background-color: #383838;
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .direction-btn:hover {
            background-color: #404040;
        }

        .direction-btn.active {
            background-color: #dc3545;
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            .page-title {
                font-size: 24px;
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
            <h1 class="page-title">Speech to Text Converter</h1>
            <p class="page-description">Convert your audio files into text with high accuracy using advanced AI technology</p>
        </div>

        <div class="upload-container">
            <div class="file-input-container">
                <label class="file-input-label">
                    <i class="fas fa-upload"></i>
                    Add Audio File
                    <input type="file" class="file-input" id="audioFile" 
                           accept=".wav,.mp3,.m4a,.ogg,.flac"
                           onchange="handleFileSelect(this)">
                </label>
                <p class="file-info" id="fileInfo">Supported formats: .wav, .mp3, .m4a, .ogg, .flac</p>
            </div>
        </div>

        <div class="editor-container">
            <div class="text-controls">
                <button class="direction-btn" onclick="setDirection('ltr')" id="ltrBtn">
                    <i class="fas fa-paragraph"></i> LTR
                </button>
                <button class="direction-btn" onclick="setDirection('rtl')" id="rtlBtn">
                    <i class="fas fa-paragraph fa-flip-horizontal"></i> RTL
                </button>
            </div>
            <div id="editor"></div>
        </div>

        <div class="controls">
            <button class="control-button convert-btn" onclick="convertAudio()" id="convertBtn" disabled>
                <i class="fas fa-sync-alt"></i>
                Convert to Text
            </button>
            <button class="control-button clear-btn" onclick="clearAll()">
                <i class="fas fa-trash"></i>
                Clear
            </button>
            <button class="control-button clear-btn" onclick="copyText()">
                <i class="fas fa-copy"></i>
                Copy
            </button>
        </div>

        <div class="loading" id="loading">
            <i class="fas fa-spinner"></i>
            Converting audio to text...
        </div>

        <div class="error-message" id="errorMessage"></div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // Create a custom placeholder div
        const editorContainer = document.querySelector('.editor-container');
        const placeholderDiv = document.createElement('div');
        placeholderDiv.className = 'editor-placeholder';
        placeholderDiv.style.cssText = `
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #666;
            pointer-events: none;
            font-size: 16px;
            opacity: 1;
            transition: opacity 0.3s ease;
        `;
        placeholderDiv.textContent = 'Converted text will appear here...';
        editorContainer.style.position = 'relative';
        editorContainer.appendChild(placeholderDiv);

        // Initialize Quill with basic formatting options
        let quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'align': [] }],
                    [{ 'size': ['small', false, 'large'] }],
                ]
            }
        });

        // Text direction control
        function setDirection(direction) {
            const ltrBtn = document.getElementById('ltrBtn');
            const rtlBtn = document.getElementById('rtlBtn');
            
            quill.format('direction', direction);
            quill.format('align', direction === 'rtl' ? 'right' : 'left');

            // Update button states
            ltrBtn.classList.toggle('active', direction === 'ltr');
            rtlBtn.classList.toggle('active', direction === 'rtl');
        }

        // Copy text function
        function copyText() {
            const text = quill.root.innerHTML;
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = text;
            
            // Preserve formatting when copying
            const formattedText = tempDiv.innerText;
            
            navigator.clipboard.writeText(formattedText).then(() => {
                // Show temporary success message
                const copyBtn = document.querySelector('.controls button:last-child');
                const originalText = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy text:', err);
            });
        }

        // Show/hide placeholder based on content
        function updatePlaceholder() {
            const content = quill.getText().trim();
            if (content) {
                placeholderDiv.style.opacity = '0';
                placeholderDiv.style.visibility = 'hidden';
            } else {
                placeholderDiv.style.opacity = '1';
                placeholderDiv.style.visibility = 'visible';
            }
        }

        // Initial placeholder state
        updatePlaceholder();

        // Update placeholder when content changes
        quill.on('text-change', updatePlaceholder);

        // Also update placeholder when setting content directly
        const originalSetContents = quill.setContents;
        quill.setContents = function() {
            originalSetContents.apply(this, arguments);
            updatePlaceholder();
        };

        let selectedFile = null;

        function handleFileSelect(input) {
            const file = input.files[0];
            const convertBtn = document.getElementById('convertBtn');
            const fileInfo = document.getElementById('fileInfo');
            
            if (file) {
                selectedFile = file;
                fileInfo.textContent = `Selected file: ${file.name}`;
                convertBtn.disabled = false;
            } else {
                selectedFile = null;
                fileInfo.textContent = 'Supported formats: .wav, .mp3, .m4a, .ogg, .flac';
                convertBtn.disabled = true;
            }
            hideError();
        }

        async function convertAudio() {
            if (!selectedFile) {
                showError('Please select an audio file first.');
                return;
            }

            const loading = document.getElementById('loading');
            const convertBtn = document.getElementById('convertBtn');
            
            loading.style.display = 'flex';
            convertBtn.disabled = true;
            hideError();

            const formData = new FormData();
            formData.append('audio', selectedFile);

            try {
                const response = await fetch('/api/stt/convert', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (!response.ok) {
                    if (response.headers.get('content-type')?.includes('application/json')) {
                        const errorData = await response.json();
                        throw new Error(errorData.error || 'Failed to convert audio');
                    } else {
                        throw new Error('Server error: ' + response.status);
                    }
                }

                const data = await response.json();
                
                if (data.success) {
                    quill.root.innerHTML = data.text;
                    updatePlaceholder();
                    // Set initial direction to LTR after conversion
                    setDirection('ltr');
                } else {
                    throw new Error(data.error || 'Failed to convert audio to text');
                }
            } catch (error) {
                console.error('Error:', error);
                showError(error.message || 'Failed to convert audio to text');
            } finally {
                loading.style.display = 'none';
                convertBtn.disabled = false;
            }
        }

        function clearAll() {
            quill.setContents([]);
            selectedFile = null;
            document.getElementById('audioFile').value = '';
            document.getElementById('fileInfo').textContent = 'Supported formats: .wav, .mp3, .m4a, .ogg, .flac';
            document.getElementById('convertBtn').disabled = true;
            hideError();
            updatePlaceholder();
            setDirection('ltr'); // Reset direction to LTR
        }

        function showError(message) {
            const errorElement = document.getElementById('errorMessage');
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }

        function hideError() {
            document.getElementById('errorMessage').style.display = 'none';
        }
    </script>
</body>
</html>

