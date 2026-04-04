{{-- resources/views/landing.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Threads of Hope | Donate Clothes, Transform Lives</title>

    <!-- Google Fonts & Tailwind CSS + Font Awesome -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Custom Tailwind Overrides -->
    <style>
        * {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #0f2b2d 0%, #1e4a4b 100%);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.2, 0, 0, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 35px -12px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: linear-gradient(95deg, #0f766e 0%, #0d9488 100%);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(95deg, #0d5c56 0%, #0b6b61 100%);
            transform: scale(1.02);
            box-shadow: 0 10px 20px -5px rgba(13, 118, 110, 0.4);
        }

        .stat-card {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .section-fade {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .section-fade.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .testimonial-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(4px);
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .scroll-indicator {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(10px);
            }
        }
    </style>
</head>

<body class="bg-white text-gray-800">

    <!-- ========== NAVBAR (transparent to solid on scroll) ========== -->
    <nav id="navbar"
        class="fixed w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md shadow-sm py-3 md:py-4">
        <div class="container mx-auto px-6 lg:px-12 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-tshirt text-teal-700 text-2xl"></i>
                <span class="font-extrabold text-2xl tracking-tight text-teal-800">Threads<span
                        class="text-teal-600">OfHope</span></span>
            </div>
            <div class="hidden md:flex space-x-8 font-medium text-gray-700">
                <a href="#home" class="hover:text-teal-700 transition">Home</a>
                <a href="#impact" class="hover:text-teal-700 transition">Impact</a>
                <a href="#how-it-works" class="hover:text-teal-700 transition">How It Works</a>
                <a href="#testimonials" class="hover:text-teal-700 transition">Stories</a>
                <a href="#contact" class="hover:text-teal-700 transition">Contact</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}"
                    class="hidden md:inline-block text-teal-700 font-semibold hover:underline">Sign in</a>
                <a href="{{ route('register') }}"
                    class="btn-primary text-white px-5 py-2 rounded-full font-semibold shadow-md">Register <i
                        class="fas fa-heart ml-1"></i></a>
                <button id="mobileMenuBtn" class="md:hidden text-2xl text-teal-800 focus:outline-none">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white/95 backdrop-blur-lg px-6 pt-4 pb-6 shadow-lg">
            <a href="#home" class="block py-2 font-medium">Home</a>
            <a href="#impact" class="block py-2 font-medium">Impact</a>
            <a href="#how-it-works" class="block py-2 font-medium">How It Works</a>
            <a href="#testimonials" class="block py-2 font-medium">Stories</a>
            <a href="#contact" class="block py-2 font-medium">Contact</a>
            <div class="pt-4 flex gap-3">
                <a href="{{ route('login') }}"
                    class="border border-teal-600 text-teal-700 px-4 py-2 rounded-full w-full text-center">Sign In</a>
            </div>
        </div>
    </nav>

    <!-- ========== HERO SECTION ========== -->
    <section id="home" class="relative pt-32 pb-20 md:pt-44 md:pb-28 hero-gradient overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-20 left-10 w-64 h-64 bg-teal-300 rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-80 h-80 bg-emerald-400 rounded-full filter blur-3xl"></div>
        </div>
        <div class="container mx-auto px-6 lg:px-12 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <div
                        class="inline-block bg-white/20 backdrop-blur-sm rounded-full px-4 py-1 text-sm font-semibold mb-6">
                        🌍 MAKE A DIFFERENCE TODAY
                    </div>
                    <h1 class="text-4xl md:text-6xl font-extrabold leading-tight tracking-tight">
                        Give Your <span class="text-amber-200">Clothes</span> a Second Life
                    </h1>
                    <p class="text-lg md:text-xl text-gray-100 mt-6 opacity-90 leading-relaxed">
                        Every unused garment can become a lifeline. Join thousands of changemakers transforming
                        wardrobes into warmth, dignity, and opportunity.
                    </p>
                    <div class="flex flex-wrap gap-4 mt-10">
                        <a href="{{ route('register') }}"
                            class="btn-primary px-8 py-4 rounded-full text-lg font-bold shadow-xl flex items-center gap-2">
                            Start Donating <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="#impact"
                            class="bg-transparent border-2 border-white/70 hover:bg-white/20 rounded-full px-7 py-4 font-semibold transition">
                            See Our Impact
                        </a>
                    </div>
                    <div class="flex gap-6 mt-12 text-white/80 text-sm">
                        <div><i class="fas fa-check-circle text-amber-300 mr-2"></i> Free pickup</div>
                        <div><i class="fas fa-check-circle text-amber-300 mr-2"></i> Tax receipt</div>
                        <div><i class="fas fa-check-circle text-amber-300 mr-2"></i> 100% transparent</div>
                    </div>
                </div>
                <div class="relative hidden md:block">
                    <div class="floating">
                        <img src="https://images.unsplash.com/photo-1584515933487-779824d29309?w=600&auto=format"
                            alt="Donation clothes" class="rounded-3xl shadow-2xl object-cover w-full h-[450px]">
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl p-4 shadow-xl flex items-center gap-3">
                        <i class="fas fa-hands-helping text-teal-600 text-2xl"></i>
                        <div><span class="font-black text-xl">2,480+</span><br><span class="text-xs">donations this
                                month</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-white to-transparent"></div>
    </section>

    <!-- ========== STATS / IMPACT BANNER ========== -->
    <section id="impact" class="py-16 bg-white">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-teal-600 font-semibold tracking-wide uppercase">Real change</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2">Our measurable impact</h2>
                <div class="w-20 h-1 bg-teal-500 mx-auto mt-4 rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="stat-card rounded-2xl p-8 text-center shadow-md transition card-hover">
                    <i class="fas fa-tshirt text-teal-600 text-5xl mb-4"></i>
                    <div class="text-4xl font-black text-teal-800">12,340+</div>
                    <p class="text-gray-600 mt-2">Clothes items collected</p>
                </div>
                <div class="stat-card rounded-2xl p-8 text-center shadow-md transition card-hover">
                    <i class="fas fa-smile text-teal-600 text-5xl mb-4"></i>
                    <div class="text-4xl font-black text-teal-800">8,920+</div>
                    <p class="text-gray-600 mt-2">People impacted</p>
                </div>
                <div class="stat-card rounded-2xl p-8 text-center shadow-md transition card-hover">
                    <i class="fas fa-leaf text-teal-600 text-5xl mb-4"></i>
                    <div class="text-4xl font-black text-teal-800">2,500 kg</div>
                    <p class="text-gray-600 mt-2">Textile waste reduced</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== HOW IT WORKS ========== -->
    <section id="how-it-works" class="py-20 bg-gray-50">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-teal-600 font-semibold">Simple process</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2">Donate in three easy steps</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-10">
                <div class="bg-white rounded-2xl p-8 text-center shadow-sm card-hover relative">
                    <div
                        class="absolute -top-5 left-1/2 -translate-x-1/2 bg-teal-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold">
                        1</div>
                    <i class="fas fa-box-open text-teal-600 text-4xl mt-4 mb-4"></i>
                    <h3 class="text-xl font-bold">Pack your clothes</h3>
                    <p class="text-gray-500 mt-3">Gently used or new clothes, shoes, accessories — we accept all
                        seasons.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 text-center shadow-sm card-hover relative">
                    <div
                        class="absolute -top-5 left-1/2 -translate-x-1/2 bg-teal-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold">
                        2</div>
                    <i class="fas fa-calendar-check text-teal-600 text-4xl mt-4 mb-4"></i>
                    <h3 class="text-xl font-bold">Schedule pickup</h3>
                    <p class="text-gray-500 mt-3">Choose a date, we’ll collect from your doorstep — free of charge.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 text-center shadow-sm card-hover relative">
                    <div
                        class="absolute -top-5 left-1/2 -translate-x-1/2 bg-teal-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold">
                        3</div>
                    <i class="fas fa-chart-line text-teal-600 text-4xl mt-4 mb-4"></i>
                    <h3 class="text-xl font-bold">Track impact</h3>
                    <p class="text-gray-500 mt-3">Get updates & certificates showing exactly who your donation helped.
                    </p>
                </div>
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center gap-2 text-teal-700 font-semibold border-b-2 border-teal-300 hover:border-teal-700 pb-1 transition">Become
                    a donor →</a>
            </div>
        </div>
    </section>

    <!-- ========== FEATURED CATEGORIES / ACCEPTED ITEMS ========== -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center mb-12">
                <h3 class="text-2xl font-bold">What we accept</h3>
                <p class="text-gray-500 mt-2">Every piece matters — from winter coats to office wear</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-5 text-center">
                <div class="p-4 rounded-xl hover:bg-teal-50 transition"><i
                        class="fas fa-vest text-3xl text-teal-700"></i>
                    <p class="mt-2 font-medium">Men's wear</p>
                </div>
                <div class="p-4 rounded-xl hover:bg-teal-50 transition"><i
                        class="fas fa-female text-3xl text-teal-700"></i>
                    <p class="mt-2 font-medium">Women's wear</p>
                </div>
                <div class="p-4 rounded-xl hover:bg-teal-50 transition"><i
                        class="fas fa-child text-3xl text-teal-700"></i>
                    <p class="mt-2 font-medium">Kids' clothes</p>
                </div>
                <div class="p-4 rounded-xl hover:bg-teal-50 transition"><i
                        class="fas fa-shoe-prints text-3xl text-teal-700"></i>
                    <p class="mt-2 font-medium">Footwear</p>
                </div>
                <div class="p-4 rounded-xl hover:bg-teal-50 transition"><i
                        class="fas fa-hand-peace text-3xl text-teal-700"></i>
                    <p class="mt-2 font-medium">Accessories</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== TESTIMONIALS ========== -->
    <section id="testimonials" class="py-20 bg-cover bg-center bg-fixed"
        style="background-image: linear-gradient(rgba(15,43,45,0.85), rgba(30,74,75,0.85)), url('https://images.unsplash.com/photo-1559027616-2d6ac0ab5c5a?w=1600');">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center text-white mb-12">
                <i class="fas fa-quote-left text-3xl opacity-60 mb-3"></i>
                <h2 class="text-3xl md:text-4xl font-bold">Voices of Hope</h2>
                <p class="text-teal-100 mt-2">Real stories from donors & beneficiaries</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="testimonial-card rounded-2xl p-6 shadow-xl">
                    <div class="flex gap-1 text-amber-400 mb-3"><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i></div>
                    <p class="italic text-gray-700">"Donating was so easy, and knowing my old jackets warmed a family
                        this winter is priceless."</p>
                    <div class="flex items-center mt-5 gap-3">
                        <div class="w-10 h-10 rounded-full bg-teal-200 flex items-center justify-center"><i
                                class="fas fa-user text-teal-700"></i></div>
                        <div><strong>Emily R.</strong><br><span class="text-xs text-gray-500">Donor since 2024</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card rounded-2xl p-6 shadow-xl">
                    <div class="flex gap-1 text-amber-400 mb-3"><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i></div>
                    <p class="italic text-gray-700">"ThreadsOfHope gave me confidence for job interviews. I'm forever
                        grateful."</p>
                    <div class="flex items-center mt-5 gap-3">
                        <div class="w-10 h-10 rounded-full bg-teal-200 flex items-center justify-center"><i
                                class="fas fa-user-check text-teal-700"></i></div>
                        <div><strong>Michael O.</strong><br><span class="text-xs text-gray-500">Beneficiary</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card rounded-2xl p-6 shadow-xl">
                    <div class="flex gap-1 text-amber-400 mb-3"><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i></div>
                    <p class="italic text-gray-700">"I've donated 3 times, each pickup seamless. The transparency
                        dashboard is amazing."</p>
                    <div class="flex items-center mt-5 gap-3">
                        <div class="w-10 h-10 rounded-full bg-teal-200 flex items-center justify-center"><i
                                class="fas fa-store text-teal-700"></i></div>
                        <div><strong>Sarah K.</strong><br><span class="text-xs text-gray-500">Corporate partner</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== CALL TO ACTION + NEWSLETTER ========== -->
    <section class="py-20 bg-gradient-to-r from-teal-800 to-teal-700">
        <div class="container mx-auto px-6 lg:px-12 text-center text-white">
            <h2 class="text-3xl md:text-5xl font-bold mb-4">Ready to spark change?</h2>
            <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto">One bag of clothes can change someone’s world.
                Join the movement today.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-5">
                <a href="{{ route('register') }}"
                    class="bg-amber-400 hover:bg-amber-500 text-gray-900 px-8 py-4 rounded-full font-bold text-lg shadow-xl transition flex gap-2 items-center"><i
                        class="fas fa-gift"></i> Donate Now</a>
                <a href="#contact"
                    class="bg-transparent border-2 border-white hover:bg-white/20 px-8 py-4 rounded-full font-semibold transition">Become
                    a volunteer</a>
            </div>
        </div>
    </section>

    <!-- ========== CONTACT & FOOTER ========== -->
    <footer id="contact" class="bg-gray-900 text-gray-300 pt-16 pb-8">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2 text-white"><i
                            class="fas fa-tshirt text-teal-400 text-2xl"></i><span
                            class="font-bold text-xl">ThreadsOfHope</span></div>
                    <p class="mt-4 text-sm">Empowering communities through sustainable clothing donation.</p>
                    <div class="flex gap-4 mt-5">
                        <a href="#" class="hover:text-teal-400"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="hover:text-teal-400"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="hover:text-teal-400"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="hover:text-teal-400"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div>
                    <h5 class="font-bold text-white text-lg">Quick links</h5>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li><a href="#home" class="hover:text-teal-400">Home</a></li>
                        <li><a href="#impact" class="hover:text-teal-400">Our impact</a></li>
                        <li><a href="#how-it-works" class="hover:text-teal-400">How to donate</a></li>
                        <li><a href="#testimonials" class="hover:text-teal-400">Stories</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-white text-lg">Support</h5>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li><a href="#" class="hover:text-teal-400">FAQ</a></li>
                        <li><a href="#" class="hover:text-teal-400">Privacy policy</a></li>
                        <li><a href="#" class="hover:text-teal-400">Terms of service</a></li>
                        <li><a href="#" class="hover:text-teal-400">Partner with us</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-white text-lg">Get in touch</h5>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li><i class="fas fa-envelope mr-2"></i> hello@threadsofhope.org</li>
                        <li><i class="fas fa-phone-alt mr-2"></i> +1 (800) 234-5678</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> 123 Charity Ave, Suite 200</li>
                    </ul>
                    <div class="mt-6">
                        <div class="flex"><input type="email" placeholder="Your email"
                                class="bg-gray-800 border border-gray-700 rounded-l-lg px-4 py-2 w-full focus:outline-none"><button
                                class="bg-teal-600 px-4 rounded-r-lg hover:bg-teal-700"><i
                                    class="fas fa-paper-plane"></i></button></div>
                        <p class="text-xs mt-2">Subscribe for updates</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-sm text-gray-500">
                © 2025 ThreadsOfHope — Clothing donation platform. Crafted with <i
                    class="fas fa-heart text-teal-400"></i> for a better world.
            </div>
        </div>
    </footer>

    <!-- Scroll Animation & Mobile menu toggler -->
    <script>
        // navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 30) navbar.classList.add('bg-white/95', 'shadow-md');
            else navbar.classList.remove('bg-white/95', 'shadow-md');
        });
        // mobile menu toggle
        const btn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        btn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

        // Intersection Observer for fade-in sections
        const fadeElements = document.querySelectorAll('.section-fade');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('visible');
            });
        }, {
            threshold: 0.2
        });
        document.querySelectorAll('.stat-card, .bg-white.rounded-2xl, .testimonial-card').forEach(el => {
            el.classList.add('section-fade');
            observer.observe(el);
        });
        // manually add visible to hero stats later
        document.querySelectorAll('.stat-card').forEach(el => observer.observe(el));
    </script>
    <script>
        // add visible on load for some cards after small delay
        window.addEventListener('load', () => {
            document.querySelectorAll('.section-fade').forEach(el => {
                if (el.getBoundingClientRect().top < window.innerHeight) el.classList.add('visible');
            });
        });
    </script>
</body>

</html>
