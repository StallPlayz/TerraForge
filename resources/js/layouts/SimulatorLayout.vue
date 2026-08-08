<template>
    <div class="min-h-screen bg-[#121212] text-gray-100 font-sans selection:bg-teal-500 selection:text-white">

        <header class="border-b border-gray-800 bg-[#1a1a1a] px-6 py-4">
            <h1 class="text-xl font-bold tracking-wider text-teal-400">
                TERRA<span class="text-white">FORGE</span>
            </h1>
        </header>

        <main class="mx-auto max-w-7xl p-6">
            <slot />
        </main>

    </div>
</template>

<script setup>
</script>
