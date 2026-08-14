<template>
    <Head title="TerraForge Simulator" />

    <SimulatorLayout>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            <!-- Left Panel: The Loadout -->
            <div class="flex flex-col gap-6 lg:col-span-1">
                <!-- Weapon Box -->
                <div class="rounded-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl">
                    <div class="mb-4 flex items-center justify-between border-b border-gray-800 pb-2">
                        <h2 class="text-xl font-semibold text-teal-400">The Weapon</h2>
                    </div>
                    <WeaponSelector :weapons="weapons" v-model="selectedWeapon" />
                </div>

                <!-- Build Box -->
                <BuildSelector :armorPieces="armorPieces" :accessories="accessories" :ammos="ammos"
                    @update:build="currentBuild = $event" />
            </div>

            <!-- Right Panel: The Target & Live Stats -->
            <div class="flex h-full flex-col gap-6 lg:col-span-1 min-h-0">

                <!-- Enemy Box -->
                <div class="shrink-0 rounded-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl">
                    <div class="mb-4 flex items-center justify-between border-b border-gray-800 pb-2">
                        <h2 class="text-xl font-semibold text-red-400">The Target</h2>
                    </div>
                    <EnemySelector :enemies="enemies" v-model="selectedEnemy" />
                </div>

                <!-- Live Player Stats Panel -->
                <div class="flex flex-col rounded-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl transition-all duration-300 min-h-0"
                     :class="showDetailedStats ? 'flex-1' : ''">

                    <div class="shrink-0 mb-4 flex items-center justify-between border-b border-gray-800 pb-2">
                        <h2 class="text-xl font-semibold text-purple-400">Live Player Stats</h2>

                        <button @click="showDetailedStats = !showDetailedStats"
                                class="rounded p-1 text-gray-500 hover:bg-gray-800 hover:text-purple-400 focus:outline-none"
                                title="Toggle Source Breakdown">
                            <svg v-if="!showDetailedStats" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Core Stats -->
                    <div v-if="playerStats" class="shrink-0 grid grid-cols-2 gap-y-3 text-sm">
                        <div class="text-gray-400">Total Damage:</div>
                        <div class="text-right font-bold text-white">{{ playerStats.damage }}</div>

                        <div class="text-gray-400">Crit Chance:</div>
                        <div class="text-right font-bold text-white">{{ playerStats.crit }}%</div>

                        <div class="text-gray-400">Armor Penetration:</div>
                        <div class="text-right font-bold text-white">{{ playerStats.armorPen }}</div>
                    </div>

                    <!-- Stat Sources -->
                    <div v-if="showDetailedStats" class="mt-4 flex flex-col border-t border-gray-800 pt-4">
                        <h3 class="mb-3 shrink-0 text-xs font-bold uppercase tracking-wider text-gray-500">Stat Sources</h3>

                        <div class="space-y-3 overflow-y-auto pr-2 custom-scrollbar max-h-57">

                            <div v-for="source in statSources" :key="source.name" class="rounded border border-gray-800 bg-gray-900/50 p-2">
                                <div class="mb-1 text-xs font-semibold text-teal-400">
                                    {{ source.name }} <span class="font-normal text-gray-500">({{ source.type }})</span>
                                </div>
                                <ul class="list-inside list-disc text-xs text-gray-300">
                                    <li v-for="(stat, idx) in source.stats" :key="idx">{{ stat }}</li>
                                </ul>
                            </div>

                            <div v-if="statSources.length === 0" class="text-xs italic text-gray-600">
                                No gear equipped.
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bottom Panel: The Arena -->
        <div class="mt-6 rounded-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between border-b border-gray-800 pb-2">
                <h2 class="text-xl font-semibold text-yellow-500">The Arena</h2>
                <span v-if="selectedWeapon && selectedEnemy"
                    class="rounded border border-teal-800 bg-teal-900/50 px-2 py-1 text-xs tracking-widest text-teal-400 uppercase">Target Acquired</span>
                <span v-else
                    class="rounded bg-gray-800 px-2 py-1 text-xs tracking-widest text-gray-400 uppercase">Awaiting Simulation</span>
            </div>

            <CombatArena :weapon="selectedWeapon" :enemy="selectedEnemy" :stats="playerStats" />
        </div>
    </SimulatorLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import SimulatorLayout from '@/layouts/SimulatorLayout.vue';
import WeaponSelector from '@/components/WeaponSelector.vue';
import EnemySelector from '@/components/EnemySelector.vue';
import CombatArena from '@/components/CombatArena.vue';
import BuildSelector from '@/components/BuildSelector.vue';

const props = defineProps({
    weapons: { type: Array, default: () => [] },
    enemies: { type: Array, default: () => [] },
    armorPieces: { type: Array, default: () => [] },
    accessories: { type: Array, default: () => [] },
    ammos: { type: Array, default: () => [] },
});

const selectedWeapon = ref(null);
const selectedEnemy = ref(null);
const showDetailedStats = ref(false);

const currentBuild = ref({
    ammo: null, head: null, body: null, legs: null, accessories: [null, null, null, null, null]
});

const formatStatName = (key, value) => {
    const labels = {
        ranged_damage: 'Ranged Damage',
        melee_damage: 'Melee Damage',
        magic_damage: 'Magic Damage',
        ranged_crit: 'Ranged Crit Chance',
        melee_crit: 'Melee Crit Chance',
        magic_crit: 'Magic Crit Chance',
        armor_penetration: 'Armor Penetration',
        defense: 'Defense',
        max_mana: 'Max Mana'
    };

    const label = labels[key] || key;

    if (key.includes('damage')) {
        return `+${Math.round(value * 100)}% ${label}`;
    }
    if (key.includes('crit')) {
        return `+${value}% ${label}`;
    }
    return `+${value} ${label}`;
};

// === THE BREAKDOWN ENGINE ===

const statSources = computed(() => {
    const sources = [];
    if (!selectedWeapon.value) return sources;

    sources.push({
        name: selectedWeapon.value.name,
        type: 'Weapon',
        stats: [`${selectedWeapon.value.base_damage} Base Damage`, `${selectedWeapon.value.base_crit_chance}% Base Crit`, `${selectedWeapon.value.armor_penetration} Base Armor Pen`]
    });

    if (currentBuild.value.ammo && selectedWeapon.value.damage_type === 'Ranged') {
        sources.push({
            name: currentBuild.value.ammo.name,
            type: 'Ammo',
            stats: [`+${currentBuild.value.ammo.damage} Base Damage`, `+${currentBuild.value.ammo.armor_penetration} Base Armor Pen`]
        });
    }

    const parseBonuses = (item, type, bonuses) => {
        if (!bonuses) return;
        const formatted = Object.entries(bonuses).map(([k, v]) => formatStatName(k, v));
        if (formatted.length > 0) {
            sources.push({ name: item.name, type: type, stats: formatted });
        }
    };

    if (currentBuild.value.head) parseBonuses(currentBuild.value.head, 'Helmet', currentBuild.value.head.stat_bonuses);
    if (currentBuild.value.body) parseBonuses(currentBuild.value.body, 'Breastplate', currentBuild.value.body.stat_bonuses);
    if (currentBuild.value.legs) parseBonuses(currentBuild.value.legs, 'Greaves', currentBuild.value.legs.stat_bonuses);

    if (currentBuild.value.head && currentBuild.value.body && currentBuild.value.legs) {
        if (currentBuild.value.head.set_family === currentBuild.value.body.set_family &&
            currentBuild.value.body.set_family === currentBuild.value.legs.set_family) {
            parseBonuses({ name: `${currentBuild.value.head.set_family} Set Bonus` }, 'Set Bonus', currentBuild.value.head.set_bonus_stats);
        }
    }

    currentBuild.value.accessories.forEach(acc => {
        if (acc) parseBonuses(acc, 'Accessory', acc.stat_bonuses);
    });

    return sources;
});


// === THE MATH ENGINE ===

const playerStats = computed(() => {
    if (!selectedWeapon.value) return null;

    let dmgMult = 1.0;
    let crit = selectedWeapon.value.base_crit_chance;
    let armorPen = selectedWeapon.value.armor_penetration;
    let baseDmg = selectedWeapon.value.base_damage;
    let maxMana = 200;

    const isRanged = selectedWeapon.value.damage_type === 'Ranged';
    const isMagic = selectedWeapon.value.damage_type === 'Magic';
    const hasAmmoSelected = currentBuild.value.ammo !== null;
    const canFire = !isRanged || hasAmmoSelected;

    if (isRanged && hasAmmoSelected) {
        baseDmg += currentBuild.value.ammo.damage;
        armorPen += currentBuild.value.ammo.armor_penetration;
    }

    const applyStats = (bonuses) => {
        if (!bonuses) return;
        if (bonuses.ranged_damage && isRanged) dmgMult += bonuses.ranged_damage;
        if (bonuses.melee_damage && selectedWeapon.value.damage_type === 'Melee') dmgMult += bonuses.melee_damage;
        if (bonuses.magic_damage && isMagic) dmgMult += bonuses.magic_damage;

        if (bonuses.ranged_crit && isRanged) crit += bonuses.ranged_crit;
        if (bonuses.melee_crit && selectedWeapon.value.damage_type === 'Melee') crit += bonuses.melee_crit;
        if (bonuses.magic_crit && isMagic) crit += bonuses.magic_crit;

        if (bonuses.armor_penetration) armorPen += bonuses.armor_penetration;
        if (bonuses.max_mana) maxMana += bonuses.max_mana;
    };

    if (currentBuild.value.head) applyStats(currentBuild.value.head.stat_bonuses);
    if (currentBuild.value.body) applyStats(currentBuild.value.body.stat_bonuses);
    if (currentBuild.value.legs) applyStats(currentBuild.value.legs.stat_bonuses);

    if (currentBuild.value.head && currentBuild.value.body && currentBuild.value.legs) {
        if (currentBuild.value.head.set_family === currentBuild.value.body.set_family &&
            currentBuild.value.body.set_family === currentBuild.value.legs.set_family) {
            applyStats(currentBuild.value.head.set_bonus_stats);
        }
    }

    currentBuild.value.accessories.forEach(acc => {
        if (acc) applyStats(acc.stat_bonuses);
    });

    return {
        damage: Math.round(baseDmg * dmgMult),
        crit: crit,
        armorPen: armorPen,
        maxMana: maxMana,
        canFire: canFire
    };
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #374151;
    border-radius: 20px;
}
</style>
