<template>
    <Head title="TerraForge Simulator" />

    <SimulatorLayout>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            <!-- Left Panel: The Loadout -->
            <div class="flex flex-col gap-6 lg:col-span-1">
                <div class="rounded-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl">
                    <div class="mb-4 flex items-center justify-between border-b border-gray-800 pb-2">
                        <h2 class="text-xl font-semibold text-teal-400">The Weapon</h2>
                    </div>
                    <WeaponSelector :weapons="weapons" v-model="selectedWeapon" />
                </div>

                <BuildSelector :armorPieces="armorPieces" :accessories="accessories" :ammos="ammos"
                    @update:build="currentBuild = $event" />
            </div>

            <!-- Right Panel: The Target & Live Stats -->
            <div class="flex flex-col gap-6 lg:col-span-1">
                
                <div class="rounded-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl">
                    <div class="mb-4 flex items-center justify-between border-b border-gray-800 pb-2">
                        <h2 class="text-xl font-semibold text-red-400">The Target</h2>
                    </div>
                    <EnemySelector :enemies="enemies" v-model="selectedEnemy" /> 
                </div>

                <!-- Live Player Stats Panel -->
                <div class="rounded-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl">
                    <div class="mb-4 flex items-center justify-between border-b border-gray-800 pb-2">
                        <h2 class="text-xl font-semibold text-purple-400">Live Player Stats</h2>
                    </div>
                    <div v-if="playerStats" class="grid grid-cols-2 gap-y-3 text-sm">
                        <div class="text-gray-400">Total Damage:</div>
                        <div class="text-right font-bold text-white">{{ playerStats.damage }}</div>

                        <div class="text-gray-400">Crit Chance:</div>
                        <div class="text-right font-bold text-white">{{ playerStats.crit }}%</div>

                        <div class="text-gray-400">Armor Penetration:</div>
                        <div class="text-right font-bold text-white">{{ playerStats.armorPen }}</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bottom Panel: The Arena -->
        <div class="mt-6 rounded-lg border border-gray-800 bg-[#1a1a1a] p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between border-b border-gray-800 pb-2">
                <h2 class="text-xl font-semibold text-yellow-500">The Arena</h2>
                <span v-if="selectedWeapon && selectedEnemy"
                    class="rounded bg-teal-900/50 px-2 py-1 text-xs text-teal-400 uppercase tracking-widest border border-teal-800">Target Acquired</span>
                <span v-else
                    class="rounded bg-gray-800 px-2 py-1 text-xs text-gray-400 uppercase tracking-widest">Awaiting Simulation</span>
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

const currentBuild = ref({
    ammo: null, head: null, body: null, legs: null, accessories: [null, null, null, null, null]
});

// THE MATH ENGINE:
const playerStats = computed(() => {
    if (!selectedWeapon.value) return null;
    
    let dmgMult = 1.0;
    let crit = selectedWeapon.value.base_crit_chance;
    let armorPen = selectedWeapon.value.armor_penetration;
    let baseDmg = selectedWeapon.value.base_damage;

    const isRanged = selectedWeapon.value.damage_type === 'Ranged';
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
        if (bonuses.ranged_crit && isRanged) crit += bonuses.ranged_crit;
        if (bonuses.armor_penetration) armorPen += bonuses.armor_penetration;
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
        canFire: canFire
    };
});
</script>