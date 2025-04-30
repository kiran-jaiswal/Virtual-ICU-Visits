<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Virtual ICU Visits</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">


    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <style>
        /* Glassmorphism Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Dynamic Background with Wave Effect */
        .bg-wave {
            background: linear-gradient(to bottom, #1e40af, #3b82f6);
            position: relative;
            overflow: hidden;
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113.64,28.07,1200,56.86V0Z" fill="rgba(255,255,255,0.2)"/></svg>');
            background-size: cover;
            animation: wave 10s linear infinite;
        }

        @keyframes wave {
            0% { transform: translateX(0); }
            50% { transform: translateX(-25%); }
            100% { transform: translateX(0); }
        }

        /* Input Glow Effect */
        .input-glow {
            transition: all 0.3s ease;
        }

        .input-glow:focus {
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
            border-color: #3b82f6;
        }

        /* Button Hover Effect */
        .btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.4s ease, height 0.4s ease;
        }

        .btn:hover::after {
            width: 200px;
            height: 200px;
        }

        /* Fade-In Animation */
        .fade-in-section {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .fade-in-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-wave min-h-screen flex items-center justify-center relative" style="font-family: Alkatra, system-ui;">
    <!-- Wave Background -->
    <div class="wave"></div>

    <!-- Login Form -->
    <div class="max-w-md w-full mx-4 z-10 fade-in-section">
        <div class="bg-white p-8 rounded-2xl shadow-2xl glass-effect">
            <div class="text-center mb-8 animate__animated animate__fadeInDown">
                <h2 class="text-4xl font-bold text-gray-800">Welcome Back</h2>
                <p class="text-gray-600 mt-2">Login to connect with your loved ones in the ICU</p>
            </div>

            <form action="auth/process_login.php" method="POST">
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email Address</label>
                    <div class="relative">
                        <input class="input-glow w-full border rounded-lg px-4 py-3 text-gray-700 focus:outline-none bg-white/80"
                            id="email" type="email" name="email" required>
                        <i class="fas fa-envelope absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                    <div class="relative">
                        <input class="input-glow w-full border rounded-lg px-4 py-3 text-gray-700 focus:outline-none bg-white/80"
                            id="password" type="password" name="password" required>
                        <i class="fas fa-lock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" class="h-4 w-4 text-blue-600 rounded focus:ring-blue-500">
                        <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="forgot-password.php" class="text-blue-600 hover:underline">Forgot Password?</a>
                    </div>
                </div>

                <button class="btn bg-blue-600 text-white w-full py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 animate__animated animate__pulse animate__infinite animate__slower"
                    type="submit">
                    Sign In
                </button>
            </form>

            <p class="text-center mt-6 text-sm text-gray-600">
                Don't have an account? 
                <a href="register.php" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors">Register here</a>
            </p>
        </div>
    </div>

    <!-- Decorative Floating Elements -->
    <div class="absolute top-10 left-10 animate__animated animate__fadeIn animate__delay-1s">
        <i class="fas fa-heartbeat text-blue-200 text-4xl opacity-50 animate__animated animate__pulse animate__infinite"></i>
    </div>
    <div class="absolute bottom-20 right-20 animate__animated animate__fadeIn animate__delay-2s">
        <i class="fas fa-stethoscope text-blue-300 text-5xl opacity-50 animate__animated animate__pulse animate__infinite"></i>
    </div>

    <script>
        // Fade-in on load
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('.fade-in-section');
            sections.forEach(section => {
                section.classList.add('visible');
            });
        });

        // Toggle password visibility
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.querySelector('.fa-lock');
        passwordIcon.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-lock');
                passwordIcon.classList.add('fa-unlock');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-unlock');
                passwordIcon.classList.add('fa-lock');
            }
        });
    </script>
</body>
</html>