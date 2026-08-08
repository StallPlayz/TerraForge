<template>
    <div class="w-full">
        <div v-if="!weapon || !enemy" class="flex min-h-[150px] items-center justify-center rounded-lg border-2 border-dashed border-gray-700 text-sm text-gray-500">
            Select a Weapon and a Target to begin simulation.
        </div>

        <!-- The Simulation Arena -->
        <div v-else class="grid grid-cols-1 gap-6 lg:grid-cols-4">
            
            <!-- LEFT PANEL: Dynamic Live Combat (Col 1) -->
            <div class="col-span-1 flex flex-col rounded border border-gray-700 bg-gray-800/50 p-4 shadow-inner">
                <h3 class="mb-3 text-lg font-bold text-red-500 border-b border-gray-700 pb-1">Live Combat</h3>
                
                <div class="grid flex-1 grid-cols-2 gap-y-2 text-sm">
                    <div class="text-gray-400">Total Hits:</div>
                    <div class="text-right font-medium text-white">{{ totalHits }}</div>

                    <div class="text-gray-400">Total Dmg:</div>
                    <div class="text-right font-medium text-white">{{ totalDamage }}</div>
                    
                    <div class="col-span-2 my-2 border-t border-gray-700"></div>

                    <div class="font-bold text-teal-400 text-gray-400">Real DPS:</div>
                    <div class="text-right font-bold text-teal-400">{{ realDPS }}</div>
                </div>

                <button 
                    @click="strike"
                    :disabled="isCooldown"
                    class="mt-6 w-full rounded px-4 py-2 font-bold text-white transition"
                    :class="isCooldown ? 'bg-gray-600 cursor-not-allowed' : 'bg-red-600 hover:bg-red-500 active:bg-red-700'"
                >
                    {{ isCooldown ? 'Recharging...' : 'Strike Target' }}
                </button>
                
                <button 
                    @click="clearLog"
                    class="mt-2 w-full rounded border border-gray-600 px-4 py-1 text-xs text-gray-400 hover:text-white"
                >
                    Clear Combat
                </button>
            </div>

            <!-- MIDDLE PANEL: Live Combat Log (Cols 2 & 3) -->
            <div class="col-span-1 lg:col-span-2 flex flex-col rounded border border-gray-700 bg-black p-4 font-mono text-sm shadow-inner overflow-hidden">
                <div class="mb-2 flex items-center justify-between">
                    <h3 class="text-gray-500">Combat Log:</h3>
                    <span class="text-xs text-gray-600">Tick Rate: 60Hz</span>
                </div>
                
                <div class="flex-1 overflow-y-auto max-h-[250px] flex flex-col-reverse">
                    <div v-if="combatLog.length === 0" class="text-gray-600 italic">Waiting for combat...</div>
                    <div 
                        v-for="(log, index) in combatLog" 
                        :key="index"
                        class="mb-1"
                    >
                        <span class="text-gray-500">[{{ log.time }}]</span>
                        You hit <span class="text-red-400">{{ enemy.name }}</span> for 
                        <span :class="log.isCrit ? 'text-yellow-400 font-bold text-base' : 'text-white'">
                            {{ log.damage }} damage
                        </span>
                        <span v-if="log.isCrit" class="text-yellow-500 italic"> (Critical Strike!)</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL: Static DPS Math (Col 4) -->
            <div class="col-span-1 rounded border border-gray-700 bg-gray-800/50 p-4 shadow-inner">
                <h3 class="mb-3 text-lg font-bold text-yellow-500 border-b border-gray-700 pb-1">Static Math</h3>
                <div class="grid grid-cols-2 gap-y-2 text-sm">
                    <div class="text-gray-400">Effective Def:</div>
                    <div class="text-right font-medium text-white">{{ effectiveDefense }}</div>
                    
                    <div class="text-gray-400">Avg Hit:</div>
                    <div class="text-right font-medium text-white">{{ baseHitDamage }}</div>
                    
                    <div class="text-gray-400">Atk / Sec:</div>
                    <div class="text-right font-medium text-white">{{ attacksPerSecond }}</div>

                    <div class="col-span-2 my-2 border-t border-gray-700"></div>

                    <div class="text-gray-400 font-bold text-gray-400">Est. DPS:</div>
                    <div class="text-right font-bold text-gray-300">{{ estimatedDPS }}</div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, computed, onUnmounted } from 'vue';

const props = defineProps({
    weapon: Object,
    enemy: Object
});

// --- STATE ---
const combatLog = ref([]);
const totalDamage = ref(0);
const totalHits = ref(0);
const realDPS = ref("0.0");
const isCooldown = ref(false);

let combatStartTime = null;
let lastStrikeTime = 0;
let dpsInterval = null;

// --- TERRARIA MATH COMPUTATIONS --- //

const cooldownMs = computed(() => {
    if (!props.weapon) return 0;
    return (props.weapon.use_time / 60) * 1000;
});

const effectiveDefense = computed(() => {
    if (!props.weapon || !props.enemy) return 0;
    return Math.max(0, props.enemy.base_defense - props.weapon.armor_penetration);
});

const baseHitDamage = computed(() => {
    if (!props.weapon || !props.enemy) return 0;
    const defenseReduction = Math.floor(effectiveDefense.value / 2);
    return Math.max(1, props.weapon.base_damage - defenseReduction);
});

const attacksPerSecond = computed(() => {
    if (!props.weapon) return 0;
    return (60 / props.weapon.use_time).toFixed(2);
});

const estimatedDPS = computed(() => {
    if (!props.weapon || !props.enemy) return 0;
    const critChance = props.weapon.base_crit_chance / 100;
    const avgDamagePerHit = (baseHitDamage.value * (1 - critChance)) + ((baseHitDamage.value * 2) * critChance);
    return (avgDamagePerHit * attacksPerSecond.value).toFixed(1);
});

// --- DYNAMIC COMBAT ENGINE --- //

const updateRealDPS = () => {
    if (!combatStartTime) return;
    const elapsedSeconds = (Date.now() - combatStartTime) / 1000;
    if (elapsedSeconds > 0) {
        realDPS.value = (totalDamage.value / elapsedSeconds).toFixed(1);
    }
};

const strike = () => {
    const now = Date.now();
    
    if (now - lastStrikeTime < cooldownMs.value) {
        return; 
    }

    lastStrikeTime = now;
    isCooldown.value = true;
    setTimeout(() => {
        isCooldown.value = false;
    }, cooldownMs.value);

    if (!combatStartTime) {
        combatStartTime = now;
        dpsInterval = setInterval(updateRealDPS, 100); // Update DPS UI every 100ms
    }

    const roll = Math.floor(Math.random() * 100) + 1;
    const isCrit = roll <= props.weapon.base_crit_chance;
    
    const finalDamage = isCrit ? baseHitDamage.value * 2 : baseHitDamage.value;
    
    totalDamage.value += finalDamage;
    totalHits.value += 1;
    
    combatLog.value.unshift({
        time: new Date().toLocaleTimeString([], { hour12: false, hour: '2-digit', minute: '2-digit', second:'2-digit' }) + '.' + Math.floor(now % 1000 / 100),
        damage: finalDamage,
        isCrit: isCrit
    });
};

const clearLog = () => {
    combatLog.value = [];
    totalDamage.value = 0;
    totalHits.value = 0;
    realDPS.value = "0.0";
    combatStartTime = null;
    lastStrikeTime = 0;
    
    if (dpsInterval) {
        clearInterval(dpsInterval);
        dpsInterval = null;
    }
};

onUnmounted(() => {
    if (dpsInterval) clearInterval(dpsInterval);
});
</script>