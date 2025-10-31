<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Access :: Clikzop Software Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #3762b8;
            --primary-dark: #2a4c8f;
            --secondary: #64748b;
            --success: #10b981;
            --info: #06b6d4;
            --warning: #f59e0b;
            --danger: #ef4444;
            --light: #f8fafc;
            --dark: #1e293b;
            --background: #f1f5f9;
            --card-bg: #ffffff;
            --border: #e5e7eb;
            --shadow: rgba(0, 0, 0, 0.1);
            --text: #1f2a44;
            --text-light: #6b7280;
            --terminal-bg: rgba(255, 255, 255, 0.95);
            --terminal-border: #e5e7eb;
            --terminal-text: #1e293b;
            --terminal-accent: #3b82f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
            background-image: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            padding: 2rem;
        }
        .brand-section {
            padding: 2rem;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .brand-logo i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-right: 1rem;
        }

        .brand-logo h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .brand-tagline {
            font-size: 1.25rem;
            color: var(--secondary);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .feature-icon {
            background-color: var(--primary);
            color: white;
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-text h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--dark);
        }

        .feature-text p {
            font-size: 0.875rem;
            color: var(--secondary);
            line-height: 1.5;
        }

        .login-card {
            background-color: var(--card-bg);
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid var(--border);
            max-width:750px;
            margin:auto;
        }

        .card-header {
            background-color: var(--primary);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .card-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .card-body {
            padding: 2rem;
        }

        .status-terminal {
            background-color: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            color: var(--dark);
            height: 120px;
            overflow-y: auto;
        }

        .terminal-line {
            margin-bottom: 0.25rem;
            display: flex;
            align-items: flex-start;
        }

        .terminal-prompt {
            color: var(--primary);
            font-weight: 600;
            margin-right: 0.5rem;
        }

        .cursor {
            display: inline-block;
            background-color: var(--primary);
            width: 8px;
            height: 1rem;
            margin-left: 3px;
            vertical-align: middle;
            animation: blink 1s step-end infinite;
        }

        @keyframes blink {
            from, to { background-color: transparent; }
            50% { background-color: var(--primary); }
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s ease;
            background-color: white;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary);
        }

        .input-with-icon .form-control {
            padding-left: 3rem;
        }

        .btn-primary {
            background-color: var(--primary);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 0.5rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .footer-links {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            font-size: 0.875rem;
        }

        .footer-links a {
            color: var(--secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .system-status {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
            font-size: 0.875rem;
            color: var(--success);
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--success);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        @media (max-width: 992px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }
            
            .brand-section {
                text-align: center;
                padding: 1rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) 
        {
            .container 
            
            {
                padding: 1rem;
            }
            
            .card-body 
            {
                padding: 1.5rem;
            }
            
            .footer-links 
            {
                flex-direction: column;
                gap: 0.5rem;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-wrapper">
            <div class="login-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-lock"></i>
                        Secure Access Portal
                    </h2>
                </div>
                <div class="card-body">
                    <div class="status-terminal" id="statusTerminal">
                        <div class="terminal-line">
                            <span class="terminal-prompt">></span>
                            <span id="terminalText">Initializing Clikzop Management System...</span>
                            <span class="cursor"></span>
                        </div>
                    </div>
                    
                    <form id="loginForm" method="POST" action="{{ route('do.login') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="loginUser">Username or Email</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" id="loginUser" name="loginUser" class="form-control" required 
                                       placeholder="Enter your username or email" value="{{ old('loginUser') }}">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="loginPass">Password</label>
                            <div class="input-with-icon">
                                <i class="fas fa-key input-icon"></i>
                                <input type="password" id="loginPass" name="loginPass" class="form-control" required 
                                       placeholder="Enter your password">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Authenticate & Access Dashboard
                        </button>
                        
                        @if(session()->has('flasher'))
                            <div class="alert alert-{{ session('flasher')['type'] === 'success' ? 'success' : 'danger' }} mt-3">
                                {{ session('flasher')['message'] }}
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3000
            };
            const terminalText = document.getElementById('terminalText');
            const statusTerminal = document.getElementById('statusTerminal');
            const messages = [
                "Initializing Clikzop Management System...",
                "Loading security protocols...",
                "Establishing secure connection...",
                "Verifying system integrity...",
                "Authentication module ready...",
                "System Status: Secure",
                "Welcome to Clikzop Software Management"
            ];
            
            let messageIndex = 0;
            let charIndex = 0;
            let isDeleting = false;
            let isPaused = false;
            
            function typeText() 
            {
                const currentMessage = messages[messageIndex];
                if (!isDeleting && !isPaused) 
                {
                    if (charIndex < currentMessage.length) 
                    {
                        terminalText.textContent = currentMessage.substring(0, charIndex + 1);
                        charIndex++;
                        setTimeout(typeText, 50);
                    } 
                    else 
                    {
                        isPaused = true;
                        setTimeout(() => {
                            isPaused = false;
                            isDeleting = true;
                            setTimeout(typeText, 500);
                        }, 1500);
                    }
                } 
                else if (isDeleting && !isPaused) 
                {
                    if (charIndex > 0) 
                    {
                        terminalText.textContent = currentMessage.substring(0, charIndex - 1);
                        charIndex--;
                        setTimeout(typeText, 30);
                    } 
                    else 
                    {
                        isDeleting = false;
                        messageIndex = (messageIndex + 1) % messages.length;
                        setTimeout(typeText, 500);
                    }
                }
                statusTerminal.scrollTop = statusTerminal.scrollHeight;
            }
            typeText();
            const loginForm = document.getElementById('loginForm');
            if (loginForm) 
            {
                loginForm.addEventListener('submit', function(e) 
                {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Authenticating...';
                    submitBtn.disabled = true;
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 2000);
                });
            }
        });
    </script>
</body>
</html>