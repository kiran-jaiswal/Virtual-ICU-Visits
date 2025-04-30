<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Virtual ICU Visits</title>
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

        /* Dynamic Gradient Background */
        .bg-gradient {
            background: linear-gradient(to bottom right, #1e40af, #60a5fa);
            position: relative;
            overflow: hidden;
        }

        /* Particle Animation */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            animation: float 15s infinite ease-in-out;
        }

        @keyframes float {
            0% { transform: translateY(0) scale(1); opacity: 0.8; }
            50% { transform: translateY(-100px) scale(1.2); opacity: 0.4; }
            100% { transform: translateY(0) scale(1); opacity: 0.8; }
        }

        /* Input Glow Effect */
        .input-glow {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
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

        /* Ensure particles stay within viewport */
        @media (max-width: 640px) {
            .particle {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gradient min-h-screen flex items-center justify-center relative py-6 px-4" style="font-family: Alkatra, system-ui;">
    <!-- Particle Background Elements -->
    <div class="particle w-4 h-4 top-10 left-10"></div>
    <div class="particle w-6 h-6 bottom-40 right-10" style="animation-delay: 2s;"></div>
    <div class="particle w-3 h-3 top-40 left-20" style="animation-delay: 4s;"></div>
    <div class="particle w-5 h-5 bottom-20 left-20" style="animation-delay: 6s;"></div>

    <!-- Register Form -->
    <div class="w-full max-w-sm sm:max-w-md mx-auto z-10 fade-in-section">
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-2xl glass-effect">
            <div class="text-center mb-8 animate__animated animate__fadeInDown">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-800">Create Your Account</h2>
                <p class="text-gray-600 mt-2">Join us to connect with ICU patients</p>
            </div>

            <form action="auth/process_register.php" method="POST">
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="full_name">Full Name</label>
                    <div class="relative">
                        <input class="input-glow w-full border rounded-lg px-4 py-3 text-gray-700 focus:outline-none"
                            id="full_name" type="text" name="full_name" required>
                        <i class="fas fa-user absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email Address</label>
                    <div class="relative">
                        <input class="input-glow w-full border rounded-lg px-4 py-3 text-gray-700 focus:outline-none"
                            id="email" type="email" name="email" required>
                        <i class="fas fa-envelope absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="user_type">I am a:</label>
                    <div class="relative">
                        <select class="input-glow w-full border rounded-lg px-4 py-3 text-gray-700 focus:outline-none appearance-none"
                            id="user_type" name="user_type" required>
                            <option value="family">Family Member</option>
                            <option value="staff">Medical Staff</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                    <div class="relative">
                        <input class="input-glow w-full border rounded-lg px-4 py-3 text-gray-700 focus:outline-none"
                            id="password" type="password" name="password" required>
                        <i class="fas fa-lock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer toggle-password"></i>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">Confirm Password</label>
                    <div class="relative">
                        <input class="input-glow w-full border rounded-lg px-4 py-3 text-gray-700 focus:outline-none"
                            id="confirm_password" type="password" name="confirm_password" required>
                        <i class="fas fa-lock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer toggle-password"></i>
                    </div>
                </div>

                <button class="btn bg-blue-600 text-white w-full py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 animate__animated animate__pulse animate__infinite animate__slower"
                    type="submit">
                    Create Account
                </button>
            </form>

            <p class="text-center mt-6 text-sm text-gray-600">
                Already have an account? 
                <a href="login.php" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors">Login here</a>
            </p>
        </div>
    </div>

    <!-- Decorative Floating Elements -->
    <div class="absolute top-10 left-10 animate__animated animate__fadeIn animate__delay-1s hidden sm:block">
        <i class="fas fa-heartbeat text-blue-200 text-4xl opacity-50 animate__animated animate__pulse animate__infinite"></i>
    </div>
    <div class="absolute bottom-20 right-10 animate__animated animate__fadeIn animate__delay-2s hidden sm:block">
        <i class="fas fa-stethoscope text-blue-300 text-5xl opacity-50 animate__animated animate__pulse animate__infinite"></i>
    </div>

    <script>
        // Fade-in on load
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('.fade-in-section');
            sections.forEach(section => {
                section.classList.add('visible');
            });

            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(icon => {
                icon.addEventListener('click', () => {
                    const input = icon.previousElementSibling;
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-lock');
                        icon.classList.add('fa-unlock');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-unlock');
                        icon.classList.add('fa-lock');
                    }
                });
            });
        });
    </script>
</body>
</html>