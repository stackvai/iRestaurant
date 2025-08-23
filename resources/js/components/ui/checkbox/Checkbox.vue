<script setup lang="ts">
import type { CheckboxRootEmits, CheckboxRootProps } from 'reka-ui'
import { cn } from '@/lib/utils'
import { CheckboxIndicator, CheckboxRoot, useForwardPropsEmits } from 'reka-ui'
import { computed, type HTMLAttributes } from 'vue'
import Icon from '@/components/Icon.vue'

const props = defineProps<CheckboxRootProps & {
  class?: HTMLAttributes['class'],
  label?: string
}>()

const emits = defineEmits<CheckboxRootEmits>()

const delegatedProps = computed(() => {
  const { class: _, label: __, ...delegated } = props
  return delegated
})

const forwarded = useForwardPropsEmits(delegatedProps, emits)
</script>

<template>
  <label class="inline-flex items-center gap-2 cursor-pointer">
    <CheckboxRoot
      data-slot="checkbox"
      v-bind="forwarded"
      :class="cn(
        'peer border-input size-4 shrink-0 rounded-[4px] border shadow-xs outline-none transition-shadow',
        'focus-visible:ring-[3px] focus-visible:border-ring focus-visible:ring-ring/50',
        'data-[state=checked]:bg-primary data-[state=checked]:border-primary data-[state=checked]:text-primary-foreground',
        'disabled:cursor-not-allowed disabled:opacity-50',
        props.class
      )"
    >
      <CheckboxIndicator
        data-slot="checkbox-indicator"
        class="flex h-full w-full items-center justify-center text-current"
      >
        <Icon name="check" class="h-3.5 w-3.5" preload /> 
      </CheckboxIndicator>
    </CheckboxRoot>

    <!-- Label Text -->
    <span v-if="props.label" class="text-sm select-none">{{ props.label }}</span>
  </label>
</template>
