import { ref } from 'vue'

interface Toast {
  id: number
  title: string
  description: string
  type: 'success' | 'error' | 'info'
}

const toasts = ref<Toast[]>([])

let idCounter = 0

export function useToast() {
  function showToast(title: string, description: string, type: Toast['type']) {
    const id = ++idCounter
    toasts.value.push({ id, title, description, type })

    // Auto-remove after 3s
    setTimeout(() => {
      toasts.value = toasts.value.filter((t) => t.id !== id)
    }, 3000)
  }

  return { toasts, showToast }
}
