<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade Plan - Coming Soon</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow-x: hidden;
        }

        .container {
            max-width: 800px;
            padding: 2rem;
            margin: 0 auto;
        }

        .crown-icon {
            font-size: 4rem;
            color: #ffd700;
            margin-bottom: 1.5rem;
            animation: float 3s ease-in-out infinite;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #ffd700, #ffa500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .subtitle {
            font-size: 1.5rem;
            color: #cccccc;
            margin-bottom: 2rem;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 2rem;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2rem;
            color: #ffd700;
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #ffffff;
        }

        .feature-description {
            color: #cccccc;
            line-height: 1.6;
        }

        .coming-soon {
            font-size: 1.2rem;
            color: #ffd700;
            margin-top: 2rem;
            padding: 1rem 2rem;
            border: 2px solid #ffd700;
            border-radius: 30px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .coming-soon:hover {
            background: #ffd700;
            color: #1a1a1a;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            h1 {
                font-size: 2rem;
            }

            .subtitle {
                font-size: 1.2rem;
            }

            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <i class="fas fa-crown crown-icon"></i>
        <h1>Premium Features Coming Soon</h1>
        <p class="subtitle">Get ready for an enhanced experience with our upcoming premium features</p>

        <div class="features">
            <div class="feature-card">
                <i class="fas fa-robot feature-icon"></i>
                <h3 class="feature-title">Advanced AI Capabilities</h3>
                <p class="feature-description">Access to more sophisticated AI models and enhanced response quality</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-image feature-icon"></i>
                <h3 class="feature-title">High-Quality Image Generation</h3>
                <p class="feature-description">Generate stunning, high-resolution images with advanced AI technology</p>
            </div>

        
        </div>

        <div class="coming-soon">
            Coming Soon - Stay Tuned!
        </div>
    </div>
</body>
</html> 