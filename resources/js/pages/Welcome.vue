<template>
    <SimulatorLayout>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            <!-- Left Panel: The Loadout -->
            <div class="flex flex-col gap-6">
                <div class="rounded-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl">
                    <div class="mb-4 flex items-center justify-between border-b border-gray-800 pb-2">
                        <h2 class="text-xl font-semibold text-teal-400">The Loadout</h2>
                        <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>

                    <WeaponSelector :weapons="weapons" v-model="currentWeapon" />

                </div>
            </div>

            <!-- Right Panel: The Target -->
            <div class="flex flex-col gap-6">
                <div class="rounded-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl">
                    <div class="mb-4 flex items-center justify-between border-b border-gray-800 pb-2">
                        <h2 class="text-xl font-semibold text-red-400">The Target</h2>
                        <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>

                    <EnemySelector :enemies="enemies" v-model="currentEnemy" />

                </div>
            </div>

        </div>

        <!-- Bottom Panel: The Arena -->
        <div class="mt-6 rounded-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between border-b border-gray-800 pb-2">
                <h2 class="text-xl font-semibold text-yellow-500">The Arena</h2>

                <span v-if="currentWeapon && currentEnemy"
                    class="rounded bg-teal-900/50 px-2 py-1 text-xs text-teal-400 uppercase tracking-widest border border-teal-800">Target
                    Acquired</span>
                <span v-else
                    class="rounded bg-gray-800 px-2 py-1 text-xs text-gray-400 uppercase tracking-widest">Awaiting
                    Simulation</span>
            </div>

            <CombatArena :weapon="currentWeapon" :enemy="currentEnemy" />

        </div>

        <!-- Debug Data Dump -->
        <div class="mt-8 rounded-lg border border-gray-800 bg-[#121212] p-4 text-xs">
            <details>
                <summary class="cursor-pointer text-gray-500 hover:text-teal-400 font-mono">View Raw Database Connection
                    Test</summary>
                <pre v-if="weapons.length" class="mt-4 overflow-x-auto text-teal-300">{{ weapons }}</pre>
            </details>
        </div>

    </SimulatorLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import SimulatorLayout from '@/layouts/SimulatorLayout.vue';
import WeaponSelector from '@/components/WeaponSelector.vue';
import EnemySelector from '@/components/EnemySelector.vue';
import CombatArena from '@/components/CombatArena.vue';

const weapons = ref([]);
const enemies = ref([]);

const currentWeapon = ref(null);
const currentEnemy = ref(null);

onMounted(async () => {
    try {
        const [weaponRes, enemyRes] = await Promise.all([
            fetch('/api/weapons'),
            fetch('/api/enemies')
        ]);

        weapons.value = await weaponRes.json();
        enemies.value = await enemyRes.json();
    } catch (error) {
        console.error("Failed to fetch data:", error);
    }
});
</script>
