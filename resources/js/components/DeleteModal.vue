<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from '@/components/ui/dialog'
import Button from '@/components/ui/button/Button.vue'
import Icon from '@/components/Icon.vue'

const props = defineProps<{
  modelValue: boolean,
  deleteUrl: string,
  itemName?: string
}>()

const emit = defineEmits(['update:modelValue', 'deleted', 'error'])

const loading = ref(false)

function close() {
  emit('update:modelValue', false)
}

function confirmDelete() {
  loading.value = true
  router.delete(props.deleteUrl, {
    onSuccess: () => {
      emit('deleted')
      close()
      loading.value = false
    },
    onError: () => {
      emit('error')
      loading.value = false
    },
    onFinish: () => {
      loading.value = false
    },
  })
}
</script>

<template>
  <Dialog :open="modelValue" @update:open="emit('update:modelValue', $event)">
    <DialogContent class="max-w-md">
      <DialogHeader>
        <DialogTitle>Delete Confirmation</DialogTitle>
      </DialogHeader>
      <p class="mt-2 text-sm text-gray-600">
        Are you sure you want to delete
        <span v-if="itemName" class="font-semibold">{{ itemName }}</span>?
        This action cannot be undone.
      </p>
      <DialogFooter class="mt-4 flex justify-end gap-2">
        <Button variant="outline" @click="close">Cancel</Button>
        <Button
          variant="destructive"
          :disabled="loading"
          @click="confirmDelete"
        >
          <Icon name="trash" class="mr-1" /> Delete
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
