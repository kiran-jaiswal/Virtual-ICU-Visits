<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtual ICU Visits - Connect with Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400..700&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <style>
       
        /* Glassmorphism Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Parallax Background */
        .hero-section {
            background: linear-gradient(to right, rgba(29, 78, 216, 0.9), rgba(59, 130, 246, 0.9)), 
                        url('https://images.unsplash.com/photo-1585435557343-3b092031a831?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* Card Hover Effect */
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* Button Hover Effect */
        .cta-button {
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Floating Animation */
        .animate-float {
            animation: float 5s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 5px;
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

        .bg__video {
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.6);
  }
    </style>
</head>
<body class="bg-gray-50 font-sans" style="font-family: Alkatra, system-ui;">
    <!-- Hero Section -->
    <section class="hero-section h-screen flex items-center text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-700/80 to-blue-800/80 z-0">
            <video autoplay loop muted playsinline class="bg__video">
      <source src="bg-video.mp4" type="video/mp4" />
    </video>
        </div>
        <div class="container mx-auto px-6 z-10">
            <div class="flex flex-col lg:flex-row items-center">
            <div class="h-screen flex items-center justify-center"><div class="animate__animated animate__fadeInLeft items-center justify-center">
                    <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-6 leading-tight" style="font-family: Sedgwick Ave Display">
                        Connect with Loved Ones in the ICU
                    </h1>
                    <p class="text-blue-100 text-lg md:text-xl mb-8 max-w-2xl">
                        Virtual ICU Visits bring families together with secure, high-quality video calls, ensuring you’re there when it matters most.
                    </p>
                    <div class="space-x-4">
                        <a href="login.php" class="cta-button inline-block bg-white text-blue-700 px-8 py-4 rounded-full font-semibold text-lg shadow-lg hover:bg-blue-50">
                            Login
                        </a>
                        <a href="register.php" class="cta-button inline-block bg-transparent border-2 border-white text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white hover:text-blue-700">
                            Register
                        </a>
                    </div>
                </div></div>
                
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 fade-in-section">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Why Virtual ICU Visits?</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Experience seamless, secure, and heartfelt connections with our state-of-the-art virtual visit platform.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card p-8 bg-white rounded-2xl shadow-xl glass-effect fade-in-section">
                    <i class="fas fa-video text-5xl text-blue-600 mb-6"></i>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3">Crystal-Clear HD Video</h3>
                    <p class="text-gray-600 leading-relaxed">Enjoy high-definition video calls for meaningful, face-to-face interactions.</p>
                </div>
                <div class="feature-card p-8 bg-white rounded-2xl shadow-xl glass-effect fade-in-section" style="transition-delay: 0.2s">
                    <i class="fas fa-shield-alt text-5xl text-blue-600 mb-6"></i>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3">Uncompromising Security</h3>
                    <p class="text-gray-600 leading-relaxed">End-to-end encryption ensures your conversations remain private and secure.</p>
                </div>
                <div class="feature-card p-8 bg-white rounded-2xl shadow-xl glass-effect fade-in-section" style="transition-delay: 0.4s">
                    <i class="fas fa-clock text-5xl text-blue-600 mb-6"></i>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3">Anytime Access</h3>
                    <p class="text-gray-600 leading-relaxed">Schedule visits 24/7 to stay connected on your terms.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div class="container mx-auto px-6 text-center fade-in-section">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Start Connecting Today</h2>
            <p class="text-lg text-blue-100 max-w-2xl mx-auto mb-8">
                Join thousands of families staying close with Virtual ICU Visits. Sign up now to experience the difference.
            </p>
            <a href="register.php" class="cta-button inline-block bg-white text-blue-700 px-8 py-4 rounded-full font-semibold text-lg shadow-lg hover:bg-blue-50">
                Get Started
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="fade-in-section">
                    <h3 class="text-2xl font-bold mb-4">Virtual ICU Visits</h3>
                    <p class="text-gray-400 leading-relaxed">Connecting families with care, compassion, and cutting-edge technology.</p>
                </div>
                <div class="fade-in-section">
                    <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="login.php" class="text-gray-400 hover:text-blue-400 transition-colors">Login</a></li>
                        <li><a href="register.php" class="text-gray-400 hover:text-blue-400 transition-colors">Register</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Support</a></li>
                    </ul>
                </div>
                <div class="fade-in-section">
                    <h3 class="text-xl font-semibold mb-4">Connect With Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors text-2xl"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors text-2xl"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors text-2xl"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="mt-8 text-center text-gray-400 fade-in-section">
                <p>© 2025 Virtual ICU Visits. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Fade-in on scroll
        const sections = document.querySelectorAll('.fade-in-section');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        sections.forEach(section => observer.observe(section));
    </script>
</body>
</html>