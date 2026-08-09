<template>
    <div class="w-full">
        <div class="mb-4">
            <label for="enemy-select" class="mb-2 block text-sm font-medium text-gray-400">Target Enemy</label>
            <select
                id="enemy-select"
                v-model="selected"
                @change="emitSelection"
                class="w-full rounded-md border border-gray-700 bg-gray-900 px-3 py-2 text-white focus:border-transparent focus:outline-none focus:ring-2 focus:ring-red-500"
            >
                <option disabled value="">-- Select a Target --</option>
                <option v-for="enemy in enemies" :key="enemy.id" :value="enemy">
                    {{ enemy.name }}
                </option>
            </select>
        </div>

        <!-- The Stat Card -->
        <div v-if="selected" class="rounded border border-gray-700 bg-gray-800/50 p-4 shadow-inner">
            <h3 class="mb-3 text-lg font-bold text-red-400 border-b border-gray-700 pb-1">{{ selected.name }}</h3>

            <div class="grid grid-cols-2 gap-y-2 text-sm">
                <div class="text-gray-400">Base Defense:</div>
                <div class="text-right font-medium text-white">{{ selected.base_defense }}</div>

                <div class="text-gray-400 col-span-2 mt-2">Immunities:</div>
                <div class="col-span-2 flex flex-wrap gap-1">
                    <span
                        v-if="selected.debuff_immunities && selected.debuff_immunities.length"
                        v-for="immunity in selected.debuff_immunities"
                        :key="immunity"
                        class="rounded bg-gray-700 px-2 py-0.5 text-xs text-gray-300"
                    >
                        {{ immunity }}
                    </span>
                    <span v-else class="text-xs text-gray-500 italic">None</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    enemies: {
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
