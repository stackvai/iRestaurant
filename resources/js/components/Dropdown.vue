<script lang="ts" setup>
import { ref } from 'vue'
import Icon from './Icon.vue';

const props = defineProps<{
    modelValue: string | number
    options: Array<{ label: string | number, value: string | number }>
    placeholder?: string
}>()

const emit = defineEmits(['update:modelValue'])

const show = ref(false)

function selectOption(option: { label: string | number, value: string | number }) {
    emit('update:modelValue', option.value)
    show.value = false
}

// close dropdown if clicked outside
const dropdownRef = ref<HTMLElement | null>(null)
document.addEventListener('click', (e: Event) => {
    if (!dropdownRef.value?.contains(e.target as Node)) {
        show.value = false
    }
})
</script>

<template>
    <div class="relative w-40" ref="dropdownRef">
        <button type="button" class="w-full flex justify-between items-center px-3 py-2 border rounded bg-white text-sm"
            @click="show = !show">
            <span>
                {{options.find(o => o.value === modelValue)?.label || placeholder || 'Select...'}}
            </span>
            <Icon name="chevronDown" />
        </button>

        <Transition name="dropdown" enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95">
            <ul v-show="show"
                class="absolute z-50 mt-1 w-full border rounded bg-white shadow-md max-h-48 overflow-auto">
                <li v-for="option in options" :key="option.value" @click="selectOption(option)"
                    class="cursor-pointer px-3 py-2 hover:bg-gray-200">
                    {{ option.label }}
                </li>
            </ul>
        </Transition>
    </div>
</template>
