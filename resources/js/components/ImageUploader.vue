<template>
  <div
    class="relative w-full aspect-video border-2 border-dashed border-gray-500 rounded-lg overflow-hidden group cursor-pointer transition duration-300"
    @dragover.prevent
    @drop.prevent="handleDrop"
    @click="triggerFileInput"
  >
    <!-- File input (fully hidden) -->
    <input
      ref="fileInput"
      type="file"
      accept="image/*"
      class="hidden"
      @change="handleFileSelect"
    />

    <!-- Image or Placeholder -->
    <div class="absolute inset-0 flex items-center justify-center transition-all">
      <img
        v-if="preview"
        :src="preview"
        class="w-full h-full object-cover transition duration-300 group-hover:blur-sm"
      />
      <div v-else class=" text-sm">
        Drag & drop image or click to upload
      </div>
    </div>

    <!-- Hover overlay -->
    <div
      class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-50 transition duration-300 z-10"
    >
      <svg class="w-10 h-10 text-gray-600 animate-bounce" fill="none" stroke="currentColor" stroke-width="1.5"
        viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M4.5 12.75l7.5-7.5 7.5 7.5M12 5.25v13.5" />
      </svg>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onBeforeUnmount } from 'vue'

const props = defineProps({
  modelValue: File,
  existingUrl: String,
})
const emit = defineEmits(['update:modelValue'])

const fileInput = ref(null)
const preview = ref(null)

watch(
  () => props.modelValue,
  (newFile) => {
    if (newFile instanceof File) {
      preview.value = URL.createObjectURL(newFile)
    }
  },
  { immediate: true }
)

if (props.existingUrl && !preview.value) {
  preview.value = props.existingUrl
}

function handleFileSelect(e) {
  const file = e.target.files[0]
  if (file) emit('update:modelValue', file)
}

function handleDrop(e) {
  const file = e.dataTransfer.files[0]
  if (file) emit('update:modelValue', file)
}

function triggerFileInput() {
  if (fileInput.value) fileInput.value.click()
}

onBeforeUnmount(() => {
  if (preview.value) URL.revokeObjectURL(preview.value)
})
</script>
