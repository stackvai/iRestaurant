<script setup lang="ts">
import type { CheckboxRootEmits, CheckboxRootProps } from 'reka-ui'
import { cn } from '@/lib/utils'
import { CheckboxIndicator, CheckboxRoot, useForwardPropsEmits } from 'reka-ui'
import { computed, type HTMLAttributes } from 'vue'
import Icon from '@/components/Icon.vue'

// Accept a label prop for showing label text
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
  <label class="inline-flex items-center gap-2">
    <CheckboxRoot data-slot="checkbox" v-bind="forwarded" :class="cn(
      'peer border-input data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground data-[state=checked]:border-primary focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive size-4 shrink-0 rounded-[4px] border shadow-xs transition-shadow outline-none focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50',
      props.class
    )">
      <CheckboxIndicator data-slot="checkbox-indicator"
        class="flex items-center justify-center text-current transition-none">
        <slot v-pre>
          <Icon name="check" />
        </slot>
      </CheckboxIndicator>
    </CheckboxRoot>

    <!-- Label Text -->
    <span v-if="props.label" class="text-sm">{{ props.label }}</span>
  </label>
</template>
