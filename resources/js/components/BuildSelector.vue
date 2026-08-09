<template>
    <div class="rounded-lg border border-gray-800 bg-[#1a1a1a] p-4 shadow-lg">
        <div class="mb-4 flex items-center justify-between border-b border-gray-800 pb-2">
            <h2 class="text-lg font-bold text-teal-400">The Loadout</h2>
        </div>

        <div class="space-y-4">
            <!-- Ammo Slot -->
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-400">Ammunition</label>
                <select v-model="selected.ammo" class="w-full rounded border border-gray-700 bg-gray-900 px-3 py-2 text-sm text-white">
                    <option :value="null">None (No Ammo)</option>
                    <option v-for="ammo in ammos" :key="ammo.id" :value="ammo">
                        {{ ammo.name }} ({{ ammo.damage }} DMG)
                    </option>
                </select>
            </div>

            <!-- Armor Slots -->
            <div class="grid grid-cols-1 gap-3 md:grid-cols-3 pt-2 border-t border-gray-800">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-400">Head</label>
                    <select v-model="selected.head" class="w-full rounded border border-gray-700 bg-gray-900 px-2 py-1.5 text-sm text-white">
                        <option :value="null">Empty</option>
                        <option v-for="piece in armorPieces.filter(p => p.slot === 'Head')" :key="piece.id" :value="piece">{{ piece.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-400">Body</label>
                    <select v-model="selected.body" class="w-full rounded border border-gray-700 bg-gray-900 px-2 py-1.5 text-sm text-white">
                        <option :value="null">Empty</option>
                        <option v-for="piece in armorPieces.filter(p => p.slot === 'Body')" :key="piece.id" :value="piece">{{ piece.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-400">Legs</label>
                    <select v-model="selected.legs" class="w-full rounded border border-gray-700 bg-gray-900 px-2 py-1.5 text-sm text-white">
                        <option :value="null">Empty</option>
                        <option v-for="piece in armorPieces.filter(p => p.slot === 'Legs')" :key="piece.id" :value="piece">{{ piece.name }}</option>
                    </select>
                </div>
            </div>

            <!-- Accessory Slots -->
            <div class="pt-2 border-t border-gray-800">
                <label class="mb-2 block text-xs font-medium text-gray-400">Accessories</label>
                <div class="space-y-2">
                    <select v-for="index in 5" :key="index" v-model="selected.accessories[index - 1]" class="w-full rounded border border-gray-700 bg-gray-900 px-3 py-1.5 text-sm text-white">
                        <option :value="null">Accessory Slot {{ index }}</option>
                        <option v-for="acc in getAvailableAccessories(index - 1)" :key="acc.id" :value="acc">
                            {{ acc.name }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
    armorPieces: { type: Array, default: () => [] },
    accessories: { type: Array, default: () => [] },
    ammos: { type: Array, default: () => [] },
});

const emit = defineEmits(['update:build']);

const selected = reactive({
    ammo: null,
    head: null,
    body: null,
    legs: null,
    accessories: [null, null, null, null, null]
});

// Helper function: filters out accessories already selected in other slots
const getAvailableAccessories = (currentIndex) => {
    const selectedIds = selected.accessories
        .map((acc, idx) => (idx !== currentIndex && acc ? acc.id : null))
        .filter(Boolean);
    
    return props.accessories.filter(acc => !selectedIds.includes(acc.id));
};

watch(selected, (newBuild) => {
    emit('update:build', newBuild);
}, { deep: true });
</script>