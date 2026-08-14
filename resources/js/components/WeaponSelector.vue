<template>
    <div class="w-full">
        <div class="mb-4">
            <label for="weapon-select" class="mb-2 block text-sm font-medium text-gray-400">Equipped Weapon</label>
            <select
                id="weapon-select"
                v-model="selected"
                @change="emitSelection"
                class="w-full rounded-md border border-gray-700 bg-gray-900 px-3 py-2 text-white focus:border-transparent focus:outline-none focus:ring-2 focus:ring-teal-500"
            >
                <option disabled value="">-- Select a Weapon --</option>
                <option v-for="weapon in weapons" :key="weapon.id" :value="weapon">
                    {{ weapon.name }}
                </option>
            </select>
        </div>

        <!-- The Stat Card -->
        <div v-if="selected" class="rounded border border-gray-700 bg-gray-800/50 p-4 shadow-inner">
            <h3 class="mb-3 text-lg font-bold text-teal-300 border-b border-gray-700 pb-1">{{ selected.name }}</h3>

            <div class="grid grid-cols-2 gap-y-2 text-sm">
                <div class="text-gray-400">Damage:</div>
                <div class="text-right font-medium text-white">{{ selected.base_damage }} <span class="text-xs text-gray-500">({{ selected.damage_type }})</span></div>

                <div class="text-gray-400">Crit Chance:</div>
                <div class="text-right font-medium text-white">{{ selected.base_crit_chance }}%</div>

                <div class="text-gray-400">Use Time:</div>
                <div class="text-right font-medium text-white">{{ selected.use_time }} <span class="text-xs text-gray-500">ticks</span></div>

                <div class="text-gray-400">Armor Pen:</div>
                <div class="text-right font-medium text-white">{{ selected.armor_penetration }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    weapons: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['update:modelValue']);

const selected = ref('');

const emitSelection = () => {
    emit('update:modelValue', selected.value);
};
</script>
