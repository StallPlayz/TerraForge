<template>
    <div class="w-full">
        <div v-if="!weapon || !enemy"
            class="flex min-h-[150px] items-center justify-center rounded-lg border-2 border-dashed border-gray-700 text-sm text-gray-500">
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

                <button @mousedown="startAttack" @mouseup="stopAttack" @mouseleave="stopAttack"
                    @touchstart.prevent="startAttack" @touchend.prevent="stopAttack"
                    class="mt-6 w-full select-none rounded px-4 py-2 font-bold text-white transition"
                    :class="isCooldown && !weapon.auto_reuse ? 'bg-gray-600 cursor-not-allowed' : 'bg-red-600 hover:bg-red-500 active:bg-red-700'">
                    {{ isCooldown && !weapon.auto_reuse ? 'Recharging...' : (weapon.auto_reuse ? 'Hold to Fire' :
                        'Strike Target') }}
                </button>

                <button @click="clearLog"
                    class="mt-2 w-full rounded border border-gray-600 px-4 py-1 text-xs text-gray-400 hover:text-white">
                    Clear Combat
                </button>
            </div>

            <!-- MIDDLE PANEL: Live Combat Log (Cols 2 & 3) -->
            <div
                class="col-span-1 lg:col-span-2 flex flex-col rounded border border-gray-700 bg-black p-4 font-mono text-sm shadow-inner overflow-hidden">
                <div class="mb-2 flex items-center justify-between">
                    <h3 class="text-gray-500">Combat Log:</h3>
                    <span class="text-xs text-gray-600">Tick Rate: 60Hz</span>
                </div>

                <div class="flex-1 overflow-y-auto max-h-[250px] flex flex-col-reverse">
                    <div v-if="combatLog.length === 0" class="text-gray-600 italic">Waiting for combat...</div>
                    <div v-for="(log, index) in combatLog" :key="index" class="mb-1">
                        <span class="text-gray-500">[{{ log.time }}]</span>

                        <span v-if="log.isNoAmmo" class="font-bold italic text-yellow-500">
                            No ammo! Ranged weapon requires ammunition to fire.
                        </span>

                        <span v-else-if="log.isIframeMiss" class="italic text-gray-600">
                            Swing missed! <span class="text-red-400">{{ enemy.name }}</span> was invincible.
                        </span>

                        <span v-else>
                            You hit <span class="text-red-400">{{ enemy.name }}</span> for
                            <span :class="log.isCrit ? 'text-base font-bold text-yellow-400' : 'text-white'">
                                {{ log.damage }} damage
                            </span>
                            <span v-if="log.isCrit" class="italic text-yellow-500"> (Critical Strike!)</span>
                        </span>
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
import { ref, computed, onUnmounted, watch } from 'vue';

const props = defineProps({
    weapon: Object,
    enemy: Object,
    stats: Object
});

const combatLog = ref([]);
const hitHistory = ref([]);
const totalDamage = ref(0);
const totalHits = ref(0);
const realDPS = ref("0.0");
const isCooldown = ref(false);

let animationFrameId = null;
let isAutoAttacking = false;
let lastStrikeTime = 0;
let lastEnemyHitTime = 0;
let lastDpsUpdateTime = 0;

const DPS_WINDOW_MS = 2000;
const ENEMY_IFRAME_TICKS = 10;
const iframeMs = (ENEMY_IFRAME_TICKS / 60) * 1000;

const cooldownMs = computed(() => {
    if (!props.weapon) return 0;
    return (props.weapon.use_time / 60) * 1000;
});

const effectiveDefense = computed(() => {
    if (!props.weapon || !props.enemy || !props.stats) return 0;
    // Uses Total Armor Pen
    return Math.max(0, props.enemy.base_defense - props.stats.armorPen);
});

const baseHitDamage = computed(() => {
    if (!props.weapon || !props.enemy || !props.stats) return 0;
    const defenseReduction = Math.floor(effectiveDefense.value / 2);
    // Uses Total Damage
    return Math.max(1, props.stats.damage - defenseReduction);
});

const attacksPerSecond = computed(() => {
    if (!props.weapon) return 0;
    return (60 / props.weapon.use_time).toFixed(2);
});

const estimatedDPS = computed(() => {
    if (!props.weapon || !props.enemy || !props.stats) return 0;
    // Uses Total Crit
    const critChance = props.stats.crit / 100;
    const avgDamagePerHit = (baseHitDamage.value * (1 - critChance)) + ((baseHitDamage.value * 2) * critChance);
    return (avgDamagePerHit * attacksPerSecond.value).toFixed(1);
});

const gameLoop = (timestamp) => {
    if (isAutoAttacking && props.weapon?.auto_reuse) {
        strike(timestamp);
    }
    if (timestamp - lastDpsUpdateTime >= 100) {
        updateRealDPS(timestamp);
        lastDpsUpdateTime = timestamp;
    }
    animationFrameId = requestAnimationFrame(gameLoop);
};

const startAttack = () => {
    if (!props.weapon || !props.enemy) return;
    isAutoAttacking = true;

    const now = performance.now();
    strike(now); // RESTORED: This makes manual weapons swing!

    if (!animationFrameId) {
        animationFrameId = requestAnimationFrame(gameLoop);
    }
};

const stopAttack = () => {
    isAutoAttacking = false;
};

const updateRealDPS = (now) => {
    hitHistory.value = hitHistory.value.filter(hit => now - hit.timestamp <= DPS_WINDOW_MS);

    if (hitHistory.value.length === 0) {
        realDPS.value = "0.0";
        if (!isAutoAttacking && animationFrameId) {
            cancelAnimationFrame(animationFrameId);
            animationFrameId = null;
        }
        return;
    }

    const windowDamage = hitHistory.value.reduce((sum, hit) => sum + hit.damage, 0);
    const oldestHit = hitHistory.value[0].timestamp;
    let activeTime = (now - oldestHit) / 1000;
    if (activeTime < 0.5) activeTime = 0.5;

    realDPS.value = (windowDamage / activeTime).toFixed(1);
};

const strike = (now) => {
    if (!now) now = performance.now();
    if (now - lastStrikeTime < cooldownMs.value) return;

    lastStrikeTime = now;
    isCooldown.value = true;
    setTimeout(() => { isCooldown.value = false; }, cooldownMs.value);

    const dateNow = new Date();
    const timestampStr = dateNow.toLocaleTimeString([], { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' }) + '.' + Math.floor(dateNow.getMilliseconds() / 100);

    if (props.stats && !props.stats.canFire) {
        combatLog.value.unshift({
            time: timestampStr,
            damage: 0,
            isCrit: false,
            isIframeMiss: false,
            isNoAmmo: true
        });
        return;
    }

    if (now - lastEnemyHitTime < iframeMs) {
        combatLog.value.unshift({ time: timestampStr, damage: 0, isCrit: false, isIframeMiss: true });
        return;
    }

    lastEnemyHitTime = now;

    const damageVariance = 0.85 + (Math.random() * 0.30);
    const randomizedDamage = Math.round(baseHitDamage.value * damageVariance);
    const roll = Math.floor(Math.random() * 100) + 1;
    const isCrit = roll <= props.stats.crit;
    const finalDamage = Math.max(1, isCrit ? randomizedDamage * 2 : randomizedDamage);

    totalDamage.value += finalDamage;
    totalHits.value += 1;
    hitHistory.value.push({ damage: finalDamage, timestamp: now });

    combatLog.value.unshift({ time: timestampStr, damage: finalDamage, isCrit: isCrit, isIframeMiss: false });
};

const clearLog = () => {
    combatLog.value = [];
    hitHistory.value = [];
    totalDamage.value = 0;
    totalHits.value = 0;
    realDPS.value = "0.0";
    lastStrikeTime = 0;
    lastEnemyHitTime = 0;
    isAutoAttacking = false;

    if (animationFrameId) {
        cancelAnimationFrame(animationFrameId);
        animationFrameId = null;
    }
};

onUnmounted(() => {
    if (animationFrameId) cancelAnimationFrame(animationFrameId);
});

watch([() => props.weapon, () => props.enemy], () => {
    clearLog();
});
</script>