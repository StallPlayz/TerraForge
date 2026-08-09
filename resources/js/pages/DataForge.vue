<template>
    <SimulatorLayout>
        
        <div class="mb-8 flex items-center justify-between border-b border-gray-800 pb-4">
            <div>
                <h1 class="text-3xl font-bold text-teal-400">The Data Forge</h1>
                <p class="text-sm text-gray-500">Inject new entities directly into the TerraForge database.</p>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-6 flex space-x-2 border-b border-gray-800 pb-px">
            <button 
                v-for="tab in tabs" 
                :key="tab.id"
                @click="activeTab = tab.id"
                class="rounded-t-lg px-4 py-2 text-sm font-medium transition-colors focus:outline-none"
                :class="activeTab === tab.id ? 'bg-[#1a1a1a] text-teal-400 border-t border-l border-r border-gray-800' : 'text-gray-500 hover:text-gray-300 hover:bg-gray-800/50'"
            >
                {{ tab.name }}
            </button>
        </div>

        <!-- TAB CONTENT: WEAPONS -->
        <div v-if="activeTab === 'weapons'" class="rounded-b-lg rounded-tr-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl">
            <form @submit.prevent="submitWeapon" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                
                <!-- String/Enum Inputs -->
                <div class="col-span-1 md:col-span-2 lg:col-span-3"><h3 class="border-b border-gray-800 pb-2 text-lg font-semibold text-gray-300">Identity</h3></div>
                
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-400">Weapon Name</label>
                    <input v-model="weaponForm.name" type="text" class="w-full rounded border border-gray-700 bg-gray-900 px-3 py-2 text-white focus:border-teal-500 focus:ring-1 focus:ring-teal-500" required>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-400">Damage Type</label>
                    <select v-model="weaponForm.damage_type" class="w-full rounded border border-gray-700 bg-gray-900 px-3 py-2 text-white">
                        <option>Melee</option><option>Ranged</option><option>Magic</option><option>Summon</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-400">Use Ammo Type (Optional)</label>
                    <input v-model="weaponForm.use_ammo_type" type="text" placeholder="e.g., Bullet, Arrow" class="w-full rounded border border-gray-700 bg-gray-900 px-3 py-2 text-white placeholder-gray-600">
                </div>

                <!-- Combat Stats -->
                <div class="col-span-1 mt-4 md:col-span-2 lg:col-span-3"><h3 class="border-b border-gray-800 pb-2 text-lg font-semibold text-gray-300">Combat Stats</h3></div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-400">Base Damage</label>
                    <input v-model="weaponForm.base_damage" type="number" class="w-full rounded border border-gray-700 bg-gray-900 px-3 py-2 text-white" required>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-400">Crit Chance (%)</label>
                    <input v-model="weaponForm.base_crit_chance" type="number" class="w-full rounded border border-gray-700 bg-gray-900 px-3 py-2 text-white" required>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-400">Armor Penetration</label>
                    <input v-model="weaponForm.armor_penetration" type="number" class="w-full rounded border border-gray-700 bg-gray-900 px-3 py-2 text-white" required>
                </div>

                <!-- Timing & Mechanics -->
                <div class="col-span-1 mt-4 md:col-span-2 lg:col-span-3"><h3 class="border-b border-gray-800 pb-2 text-lg font-semibold text-gray-300">Timing & Engine Mechanics</h3></div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-400">Use Time (Ticks)</label>
                    <input v-model="weaponForm.use_time" type="number" class="w-full rounded border border-gray-700 bg-gray-900 px-3 py-2 text-white" required>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-400">Use Animation (Ticks)</label>
                    <input v-model="weaponForm.use_animation" type="number" class="w-full rounded border border-gray-700 bg-gray-900 px-3 py-2 text-white" required>
                </div>
                <div class="flex items-center gap-3 pt-6">
                    <input v-model="weaponForm.auto_reuse" type="checkbox" id="autoReuse" class="h-5 w-5 rounded border-gray-700 bg-gray-900 text-teal-500 focus:ring-teal-500 focus:ring-offset-gray-900">
                    <label for="autoReuse" class="text-sm font-medium text-gray-400">Enable Auto-Reuse</label>
                </div>
                <div class="flex items-center gap-3 pt-6">
                    <input v-model="weaponForm.is_true_melee" type="checkbox" id="trueMelee" class="h-5 w-5 rounded border-gray-700 bg-gray-900 text-teal-500 focus:ring-teal-500 focus:ring-offset-gray-900">
                    <label for="trueMelee" class="text-sm font-medium text-gray-400">Is True Melee</label>
                </div>

                <!-- Hidden/Default Values for the DB -->
                <input v-model="weaponForm.projectile_count" type="hidden">
                <input v-model="weaponForm.mana_cost" type="hidden">
                <input v-model="weaponForm.reuse_delay" type="hidden">
                <input v-model="weaponForm.melee_speed_application" type="hidden">

                <!-- Submit -->
                <div class="col-span-1 mt-6 md:col-span-2 lg:col-span-3">
                    <button type="submit" :disabled="weaponForm.processing" class="w-full rounded bg-teal-600 py-3 font-bold text-white transition hover:bg-teal-500 disabled:opacity-50">
                        {{ weaponForm.processing ? 'Forging Weapon...' : 'Forge Weapon ->' }}
                    </button>
                    <div v-if="weaponForm.recentlySuccessful" class="mt-2 text-center text-sm text-green-400">Weapon successfully forged!</div>
                </div>
            </form>
        </div>

        <!-- TAB CONTENT: ENEMIES -->
        <div v-if="activeTab === 'enemies'" class="rounded-b-lg rounded-tr-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl">
            <form @submit.prevent="submitEnemy" class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-400">Enemy Name</label>
                    <input v-model="enemyForm.name" type="text" class="w-full rounded border border-gray-700 bg-gray-900 px-3 py-2 text-white" required>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-400">Base Defense</label>
                    <input v-model="enemyForm.base_defense" type="number" class="w-full rounded border border-gray-700 bg-gray-900 px-3 py-2 text-white" required>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <button type="submit" :disabled="enemyForm.processing" class="w-full rounded bg-red-600 py-3 font-bold text-white transition hover:bg-red-500 disabled:opacity-50">
                        {{ enemyForm.processing ? 'Spawning Enemy...' : 'Forge Enemy ->' }}
                    </button>
                    <div v-if="enemyForm.recentlySuccessful" class="mt-2 text-center text-sm text-green-400">Enemy successfully forged!</div>
                </div>
            </form>
        </div>

        <!-- TAB CONTENT: ARMOR & ACCESSORIES (LOCKED) -->
        <div v-if="activeTab === 'armor' || activeTab === 'accessories'" class="flex min-h-[300px] flex-col items-center justify-center rounded-b-lg rounded-tr-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl">
            <svg class="mb-4 h-12 w-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            <h2 class="text-xl font-bold text-gray-400">Blueprint Locked</h2>
            <p class="mt-2 text-sm text-gray-500">The database migrations for {{ activeTab }} have not been built yet.</p>
        </div>

    </SimulatorLayout>
</template>

<script>
export default {
    layout: null,
}
</script>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import SimulatorLayout from '@/layouts/SimulatorLayout.vue';

// Tab State Management
const tabs = [
    { id: 'weapons', name: 'Weapons' },
    { id: 'enemies', name: 'Enemies' },
    { id: 'armor', name: 'Armor Pieces' },
    { id: 'accessories', name: 'Accessories' }
];
const activeTab = ref('weapons');

// Inertia Form for Weapons (Mapped exactly to UML/DB constraints)
const weaponForm = useForm({
    name: '',
    damage_type: 'Melee',
    use_ammo_type: null,
    base_damage: 0,
    base_crit_chance: 4,
    armor_penetration: 0,
    use_time: 20,
    use_animation: 20,
    auto_reuse: false,
    is_true_melee: true,
    // Hidden defaults
    projectile_count: 1,
    mana_cost: 0,
    reuse_delay: 0,
    melee_speed_application: 'UseTime'
});

// Inertia Form for Enemies
const enemyForm = useForm({
    name: '',
    base_defense: 0,
});

// Submit Handlers
const submitWeapon = () => {
    weaponForm.post('/weapons', {
        preserveScroll: true,
        onSuccess: () => weaponForm.reset(),
    });
};

const submitEnemy = () => {
    enemyForm.post('/enemies', {
        preserveScroll: true,
        onSuccess: () => enemyForm.reset(),
    });
};
</script>