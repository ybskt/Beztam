<template>
  <PublicLayout>
      <!-- Set Dynamic Title -->
      <Head>
          <title>Home - BEZTAM</title>
      </Head>
  
      <!-- Hero Section -->
      <section id="hero" class="my-10 md:my-20">
          <div class="container mx-auto px-6 max-w-6xl flex flex-col-reverse md:flex-row items-center justify-between gap-8">
              <div class="text-center md:text-left md:w-1/2">
                  <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-[#5AE4A8]">BEZTAM<span class="text-black">:</span></h1>
                  <h2 class="text-xl sm:text-2xl md:text-3xl font-light mt-4 mb-6">L'application Idéale pour Prenez le contrôle de vos finances !</h2>
                  <p class="text-base sm:text-lg mb-8">
                      Gérez vos revenus, vos dépenses et vos objectifs financiers, et recevez des rappels pour garder le cap sur vos règles, tout en un seul endroit.
                  </p>
                  <Link :href="route('register')" class="inline-block rounded-xl py-2 px-8 bg-green-300 hover:bg-green-400 border-green-300 border-2 hover:border-black text-black font-semibold text-lg transition-all">Commencez gratuitement !</Link>
              </div>
              <div class="md:w-1/2">
                  <img src="@/Assets/IMG/HomeHero.jpg" alt="Money Management" class="w-full h-auto rounded-lg">
              </div>
          </div>
      </section>
  
      <!-- Features Section -->
      <section class="my-16 md:my-32">
          <div class="container mx-auto px-6 max-w-6xl">
              <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-12">Pourquoi choisir <span class="text-[#5AE4A8]">BEZTAM</span></h1>
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                  <!-- Feature Card 1 -->
                  <div class="bg-[#F8F8F9] p-6 rounded-lg shadow-md flex flex-col items-center text-center">
                      <img src="@/Assets/IMG/process.png" alt="Suivi complet" class="w-16 h-16 sm:w-20 sm:h-20 mb-6">
                      <h2 class="text-xl font-medium mb-4">Suivi complet</h2>
                      <p class="text-base mb-6">Analysez vos finances en temps réel, suivez vos revenus et dépenses avec précision et gardez le contrôle total sur votre budget facilement.</p>
                      <Link :href="route('features')" class="text-base font-medium text-[#5AE4A8] hover:underline">Lire la suite</Link>
                  </div>
  
                  <!-- Feature Card 2 -->
                  <div class="bg-[#F8F8F9] p-6 rounded-lg shadow-md flex flex-col items-center text-center">
                      <img src="@/Assets/IMG/notification.png" alt="Notifications et alertes" class="w-16 h-16 sm:w-20 sm:h-20 mb-6">
                      <h2 class="text-xl font-medium mb-4">Notifications & alertes</h2>
                      <p class="text-base mb-6">Recevez des alertes pour éviter les oublis de paiements, contrôler votre budget et suivre vos objectifs financiers sans stress, en toute simplicité.</p>
                      <Link :href="route('features')" class="text-base font-medium text-[#5AE4A8] hover:underline">Lire la suite</Link>
                  </div>
  
                  <!-- Feature Card 3 -->
                  <div class="bg-[#F8F8F9] p-6 rounded-lg shadow-md flex flex-col items-center text-center">
                      <img src="@/Assets/IMG/cyber-security.png" alt="Sécurité maximale" class="w-16 h-16 sm:w-20 sm:h-20 mb-6">
                      <h2 class="text-xl font-medium mb-4">Sécurité maximale</h2>
                      <p class="text-base mb-6">Vos données sont protégées par un chiffrement avancé, garantissant confidentialité et sécurité pour gérer votre argent en toute tranquillité.</p>
                      <Link :href="route('features')" class="text-base font-medium text-[#5AE4A8] hover:underline">Lire la suite</Link>
                  </div>
              </div>
          </div>
      </section>
  
      <!-- Tutorial Section -->
      <section class="bg-[#EBFFF5] py-8 sm:py-10 my-16 md:my-32">
          <div class="container mx-auto px-6 max-w-6xl text-center">
              <h2 class="text-2xl sm:text-3xl font-bold mb-8">Regarder comment utiliser !</h2>
              <a href="https://www.youtube.com/watch?v=qHQaLs1nypg&ab_channel=LIMMA" target="_blank">
                  <img src="@/Assets/IMG/play-button.png" alt="Tutorial" class="w-16 h-16 sm:w-20 sm:h-20 mx-auto cursor-pointer">
              </a>
          </div>
      </section>
  
      <!-- Testimonials Section -->
      <section class="my-16 md:my-32">
          <div class="container mx-auto px-6 max-w-6xl">
              <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-12">Ce que disent nos <span class="text-[#5AE4A8]">Clients</span></h1>
              
              <!-- Testimonial Slides Container -->
              <div class="relative">
                  <!-- Testimonial Slide 1 (Active by default) -->
                  <div class="testimonial-slide active">
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                          <!-- Testimonial 1 -->
                          <div class="bg-[#F8F8F9] p-6 rounded-lg shadow-md text-center mx-auto w-full max-w-md">
                              <img src="@/Assets/IMG/p1.png" alt="Lionel m3izo profile" class="w-24 h-24 rounded-full mx-auto mb-6">
                              <h3 class="text-xl font-medium mb-2">Lionel m3izo</h3>
                              <p class="text-base text-gray-600 mb-4">lionelmessi10@ballon.dor</p>
                              <p class="text-base">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                          </div>
  
                          <!-- Testimonial 2 (Hidden on mobile) -->
                          <div class="bg-[#F8F8F9] p-6 rounded-lg shadow-md text-center mx-auto w-full max-w-md hidden md:block">
                              <img src="@/Assets/IMG/p2.png" alt="Saiid67 profile" class="w-24 h-24 rounded-full mx-auto mb-6">
                              <h3 class="text-xl font-medium mb-2">Saiid67</h3>
                              <p class="text-base text-gray-600 mb-4">saiiid67@gmail.com</p>
                              <p class="text-base">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore veniam, quis.</p>
                          </div>
                      </div>
                  </div>
  
                  <!-- Testimonial Slide 2 (Hidden by default) -->
                  <div class="testimonial-slide hidden">
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                          <!-- Testimonial 3 -->
                          <div class="bg-[#F8F8F9] p-6 rounded-lg shadow-md text-center mx-auto w-full max-w-md">
                              <img src="@/Assets/IMG/p3.png" alt="Ahmed profile" class="w-24 h-24 rounded-full mx-auto mb-6">
                              <h3 class="text-xl font-medium mb-2">Ahmed L.</h3>
                              <p class="text-base text-gray-600 mb-4">ahmed.l@finance.com</p>
                              <p class="text-base">Depuis que j'utilise BEZTAM, j'ai pu économiser 20% de mon revenu mensuel. L'application est intuitive et facile à utiliser.</p>
                          </div>
  
                          <!-- Testimonial 4 (Hidden on mobile) -->
                          <div class="bg-[#F8F8F9] p-6 rounded-lg shadow-md text-center mx-auto w-full max-w-md hidden md:block">
                              <img src="@/Assets/IMG/p4.png" alt="Maria profile" class="w-24 h-24 rounded-full mx-auto mb-6">
                              <h3 class="text-xl font-medium mb-2">Maria K.</h3>
                              <p class="text-base text-gray-600 mb-4">maria.k@example.com</p>
                              <p class="text-base">Les notifications m'aident à ne jamais manquer un paiement. Je recommande BEZTAM à tous mes amis qui veulent mieux gérer leurs finances.</p>
                          </div>
                      </div>
                  </div>
  
                  <!-- Testimonial Slide 3 (Hidden by default) -->
                  <div class="testimonial-slide hidden">
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                          <!-- Testimonial 5 -->
                          <div class="bg-[#F8F8F9] p-6 rounded-lg shadow-md text-center mx-auto w-full max-w-md">
                              <img src="@/Assets/IMG/p5.png" alt="Sophie profile" class="w-24 h-24 rounded-full mx-auto mb-6">
                              <h3 class="text-xl font-medium mb-2">Sophie T.</h3>
                              <p class="text-base text-gray-600 mb-4">sophie.t@outlook.com</p>
                              <p class="text-base">La visualisation des dépenses m'a permis de prendre conscience de mes habitudes et d'ajuster mon budget plus efficacement.</p>
                          </div>
  
                          <!-- Testimonial 6 (Hidden on mobile) -->
                          <div class="bg-[#F8F8F9] p-6 rounded-lg shadow-md text-center mx-auto w-full max-w-md hidden md:block">
                              <img src="@/Assets/IMG/p6.png" alt="Karim profile" class="w-24 h-24 rounded-full mx-auto mb-6">
                              <h3 class="text-xl font-medium mb-2">Karim B.</h3>
                              <p class="text-base text-gray-600 mb-4">karim.b@gmail.com</p>
                              <p class="text-base">Application sécurisée et fiable. Je l'utilise quotidiennement pour suivre mes dépenses et planifier mes objectifs financiers.</p>
                          </div>
                      </div>
                  </div>
              </div>
  
              <!-- Navigation Buttons -->
              <div class="flex justify-center space-x-4 mt-12">
                  <button id="prev-testimonial" class="bg-white border-2 border-[#5AE4A8] text-[#5AE4A8] font-extrabold py-2 px-6 rounded-full hover:bg-[#5AE4A8] hover:text-black hover:border-black transition-all">&lt;</button>
                  <button id="next-testimonial" class="bg-white border-2 border-[#5AE4A8] text-[#5AE4A8] font-extrabold py-2 px-6 rounded-full hover:bg-[#5AE4A8] hover:text-black hover:border-black transition-all">&gt;</button>
              </div>
          </div>
      </section>
  
      <!-- Team Section -->
      <section id="team" class="my-16 md:my-32">
          <div class="container mx-auto px-6 max-w-6xl">
              <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-12">Notre <span class="text-[#5AE4A8]">Equipe</span></h1>
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                  <!-- Team Member 1 -->
                  <div class="p-6 rounded-2xl text-center transition-all hover:bg-[#F8F8F9] hover:shadow-md">
                      <img src="@/Assets/IMG/p7.png" alt="Mark Hillton" class="w-24 h-24 rounded-full mx-auto mb-6">
                      <h2 class="text-xl font-semibold text-[#5AE4A8] mb-2">Mark Hillton</h2>
                      <p class="text-base mb-6">CEO & Founder</p>
                      <div class="flex justify-center space-x-6">
                          <a href="#"><img src="@/Assets/IMG/X.png" alt="X" class="w-6 h-6"></a>
                          <a href="#"><img src="@/Assets/IMG/linkedin.png" alt="LinkedIn" class="w-6 h-6"></a>
                          <a href="#"><img src="@/Assets/IMG/mail.png" alt="Mail" class="w-6 h-6"></a>
                      </div>
                  </div>
  
                  <!-- Team Member 2 -->
                  <div class="p-6 rounded-2xl text-center transition-all hover:bg-[#F8F8F9] hover:shadow-md">
                      <img src="@/Assets/IMG/p8.png" alt="Sara Alaoui" class="w-24 h-24 rounded-full mx-auto mb-6">
                      <h2 class="text-xl font-semibold text-[#5AE4A8] mb-2">Robert Pattinson</h2>
                      <p class="text-base mb-6">Marketing Head</p>
                      <div class="flex justify-center space-x-6">
                          <a href="#"><img src="@/Assets/IMG/X.png" alt="X" class="w-6 h-6"></a>
                          <a href="#"><img src="@/Assets/IMG/linkedin.png" alt="LinkedIn" class="w-6 h-6"></a>
                          <a href="#"><img src="@/Assets/IMG/mail.png" alt="Mail" class="w-6 h-6"></a>
                      </div>
                  </div>
  
                  <!-- Team Member 3 -->
                  <div class="p-6 rounded-2xl text-center transition-all hover:bg-[#F8F8F9] hover:shadow-md">
                      <img src="@/Assets/IMG/p9.png" alt="Frenk Lopez" class="w-24 h-24 rounded-full mx-auto mb-6">
                      <h2 class="text-xl font-semibold text-[#5AE4A8] mb-2">Frenk Lopez</h2>
                      <p class="text-base mb-6">Software Engineer</p>
                      <div class="flex justify-center space-x-6">
                          <a href="#"><img src="@/Assets/IMG/X.png" alt="X" class="w-6 h-6"></a>
                          <a href="#"><img src="@/Assets/IMG/linkedin.png" alt="LinkedIn" class="w-6 h-6"></a>
                          <a href="#"><img src="@/Assets/IMG/mail.png" alt="Mail" class="w-6 h-6"></a>
                      </div>
                  </div>
              </div>
          </div>
      </section>
  </PublicLayout>
  </template>
  
  <script setup>
  import { Head, Link } from '@inertiajs/vue3';
  import { onMounted } from 'vue';
  import PublicLayout from '@/Layouts/PublicLayout.vue';
  
  onMounted(() => {
      const slides = document.querySelectorAll('.testimonial-slide');
      const prevButton = document.getElementById('prev-testimonial');
      const nextButton = document.getElementById('next-testimonial');
      let currentSlide = 0;
  
      function showSlide(index) {
          slides.forEach((slide, i) => {
              slide.classList.toggle('hidden', i !== index);
              slide.classList.toggle('active', i === index);
          });
      }
  
      prevButton.addEventListener('click', () => {
          currentSlide = (currentSlide - 1 + slides.length) % slides.length;
          showSlide(currentSlide);
      });
  
      nextButton.addEventListener('click', () => {
          currentSlide = (currentSlide + 1) % slides.length;
          showSlide(currentSlide);
      });
  
      // Initialize the first slide
      showSlide(currentSlide);
  });
  </script>