<template>
  <PublicLayout>
    <!-- Set Dynamic Title -->
    <Head>
      <title>Fonctionnalités - BEZTAM</title>
    </Head>

    <!-- Features Section -->
    <section id="fonc" class="my-10 sm:my-16 md:my-20">
      <div class="container mx-auto px-4 sm:px-6 max-w-6xl flex flex-col-reverse md:flex-row items-center justify-between gap-4 sm:gap-6 md:gap-8">
        <div class="w-full md:w-2/3 text-center md:text-left">
          <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold">Nos <span class="text-[#5AE4A8]">Fonctionnalités</span> : </h1>
          <p class="mt-4 sm:mt-6 text-sm sm:text-base font-medium leading-7 sm:leading-8 w-full">Profitez de toutes les fonctionnalités conçues pour vous aider à gérer vos finances efficacement, en vous offrant une expérience intuitive, fluide et personnalisée.</p>
          <p class="mt-3 sm:mt-4 mb-6 sm:mb-8 text-sm sm:text-base font-medium leading-7 sm:leading-8 w-full"><span class="text-[#5AE4A8] font-bold">BEZTAM</span> vous accompagne à chaque étape pour vous permettre de prendre des décisions éclairées et de garder le contrôle total de votre budget, en toute simplicité.</p>
          <Link :href="route('register')" class="inline-block rounded-xl py-2 px-6 sm:px-8 bg-green-300 border-green-300 border-2 hover:bg-green-400 hover:border-black text-black font-semibold text-base sm:text-lg transition-all">Commencez gratuitement !</Link>
        </div>
        <div class="w-full md:w-1/3 mb-6 md:mb-0">
          <img src="@/Assets/IMG/Fon.png" alt="Fonctionnalités" class="w-full h-auto max-w-[250px] sm:max-w-[300px] md:max-w-[350px] mx-auto">
        </div>
      </div>
    </section>

    <!-- Feature Cards -->
    <section class="my-10 sm:my-16 md:my-20">
      <div class="container mx-auto px-4 sm:px-6 max-w-6xl">
        <!-- Feature 1 -->
        <div class="flex flex-col items-center mb-10 sm:mb-16 p-4 sm:p-6 hover:bg-[#F8F8F9] hover:rounded-lg hover:shadow-md transition-all">
          <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 border-4 border-[#5AE4A8] rounded-full flex items-center justify-center">
            <img src="@/Assets/IMG/process.png" alt="Suivi des Revenus & Dépenses" class="w-10 h-10 sm:w-12 sm:h-12">
          </div>
          <h2 class="text-lg sm:text-xl font-semibold text-[#5AE4A8] mt-6 sm:mt-8">Suivi des Revenus & Dépenses</h2>
          <p class="mt-3 sm:mt-4 text-sm sm:text-base font-medium text-center w-full leading-7 sm:leading-8">Gardez une vision claire et détaillée de vos finances en enregistrant vos revenus et dépenses en quelques clics. Catégorisez vos transactions, analysez vos habitudes de consommation et identifiez où va votre argent pour mieux ajuster votre budget et optimiser vos dépenses quotidiennes.</p>
        </div>

        <!-- Feature 2 -->
        <div class="flex flex-col items-center mb-10 sm:mb-16 p-4 sm:p-6 hover:bg-[#F8F8F9] hover:rounded-lg hover:shadow-md transition-all">
          <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 border-4 border-[#5AE4A8] rounded-full flex items-center justify-center">
            <img src="@/Assets/IMG/sh.png" alt="Budgets Personnalisés" class="w-10 h-10 sm:w-12 sm:h-12">
          </div>
          <h2 class="text-lg sm:text-xl font-semibold text-[#5AE4A8] mt-6 sm:mt-8">Budgets Personnalisés</h2>
          <p class="mt-3 sm:mt-4 text-sm sm:text-base font-medium text-center w-full leading-7 sm:leading-8">Fixez des budgets adaptés à votre style de vie et suivez leur évolution en temps réel. Recevez des alertes en cas de dépassement et ajustez vos dépenses pour mieux respecter vos objectifs financiers. BEZTAM vous aide à structurer vos finances sans effort et à mieux planifier l'avenir.</p>
        </div>

        <!-- Feature 3 -->
        <div class="flex flex-col items-center mb-10 sm:mb-16 p-4 sm:p-6 hover:bg-[#F8F8F9] hover:rounded-lg hover:shadow-md transition-all">
          <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 border-4 border-[#5AE4A8] rounded-full flex items-center justify-center">
            <img src="@/Assets/IMG/notification.png" alt="Rappels et Notifications Intelligents" class="w-10 h-10 sm:w-12 sm:h-12">
          </div>
          <h2 class="text-lg sm:text-xl font-semibold text-[#5AE4A8] mt-6 sm:mt-8">Rappels et Notifications Intelligents</h2>
          <p class="mt-3 sm:mt-4 text-sm sm:text-base font-medium text-center w-full leading-7 sm:leading-8">Ne ratez plus aucune échéance ! Planifiez vos paiements, recevez des rappels pour vos factures et soyez alerté lorsque vous approchez de vos limites budgétaires. Grâce aux notifications personnalisables, BEZTAM vous accompagne au quotidien pour éviter les oublis et garder un contrôle total sur votre argent.</p>
        </div>

        <!-- Feature 4 -->
        <div class="flex flex-col items-center mb-10 sm:mb-16 p-4 sm:p-6 hover:bg-[#F8F8F9] hover:rounded-lg hover:shadow-md transition-all">
          <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 border-4 border-[#5AE4A8] rounded-full flex items-center justify-center">
            <img src="@/Assets/IMG/gra.png" alt="Graphiques et Statistiques" class="w-10 h-10 sm:w-12 sm:h-12">
          </div>
          <h2 class="text-lg sm:text-xl font-semibold text-[#5AE4A8] mt-6 sm:mt-8">Graphiques et Statistiques</h2>
          <p class="mt-3 sm:mt-4 text-sm sm:text-base font-medium text-center w-full leading-7 sm:leading-8">Analysez vos finances en un coup d'œil avec des graphiques interactifs et des rapports détaillés. Identifiez vos tendances de dépenses, suivez vos progrès et prenez des décisions éclairées pour mieux gérer votre budget. Une visualisation claire et intuitive pour comprendre et améliorer votre gestion financière.</p>
        </div>

        <!-- Feature 5 -->
        <div class="flex flex-col items-center mb-10 sm:mb-16 p-4 sm:p-6 hover:bg-[#F8F8F9] hover:rounded-lg hover:shadow-md transition-all">
          <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 border-4 border-[#5AE4A8] rounded-full flex items-center justify-center">
            <img src="@/Assets/IMG/sync.png" alt="Synchronisation Multi-Appareils" class="w-12 h-12 sm:w-16 sm:h-16">
          </div>
          <h2 class="text-lg sm:text-xl font-semibold text-[#5AE4A8] mt-6 sm:mt-8">Synchronisation Multi-Appareils</h2>
          <p class="mt-3 sm:mt-4 text-sm sm:text-base font-medium text-center w-full leading-7 sm:leading-8">Accédez à votre compte BEZTAM partout, sur mobile, tablette et ordinateur. Vos données sont automatiquement synchronisées pour que vous puissiez suivre et gérer vos finances en toute fluidité, quel que soit l'appareil utilisé. Un accès sécurisé et optimisé pour une gestion financière sans interruption.</p>
        </div>

        <!-- Feature 6 -->
        <div class="flex flex-col items-center mb-10 sm:mb-16 p-4 sm:p-6 hover:bg-[#F8F8F9] hover:rounded-lg hover:shadow-md transition-all">
          <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 border-4 border-[#5AE4A8] rounded-full flex items-center justify-center">
            <img src="@/Assets/IMG/cyber-security.png" alt="Sécurité et Confidentialité Avancées" class="w-12 h-12 sm:w-16 sm:h-16">
          </div>
          <h2 class="text-lg sm:text-xl font-semibold text-[#5AE4A8] mt-6 sm:mt-8">Sécurité et Confidentialité Avancées</h2>
          <p class="mt-3 sm:mt-4 text-sm sm:text-base font-medium text-center w-full leading-7 sm:leading-8">Protégez vos informations financières avec un chiffrement de pointe et des protocoles de sécurité rigoureux. Vos données restent privées et accessibles uniquement par vous. BEZTAM garantit une protection optimale pour vous offrir une expérience sécurisée et sereine dans la gestion de votre budget.</p>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
</script>