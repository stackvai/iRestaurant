<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Button from '@/components/ui/button/Button.vue'

const props = defineProps({
  role: Object,
  menus: Array,
  actions: Array,
  accessMap: Object
})

const permissions = ref({})

// Initialize permissions from accessMap
for (const [menuId, actionIds] of Object.entries(props.accessMap)) {
  permissions.value[menuId] = actionIds
}

const togglePermission = (menuId, actionId) => {
  const current = permissions.value[menuId] || []
  if (current.includes(actionId)) {
    permissions.value[menuId] = current.filter(id => id !== actionId)
  } else {
    permissions.value[menuId] = [...current, actionId]
  }
}

const isChecked = (menuId, actionId) => {
  return permissions.value[menuId]?.includes(actionId)
}

const submit = () => {
  router.post(`/roles/${props.role.id}/access`, {
    permissions: permissions.value,
  }, { preserveScroll: true })
}
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-4">
      <h1 class="text-2xl font-bold">Manage Access for Role: {{ role.name }}</h1>

      <table class="w-full table-auto border">
        <thead>
          <tr>
            <th class="border px-3 py-2">Menu</th>
            <th v-for="action in actions" :key="action.id" class="border px-3 py-2 text-center">
              {{ action.name }}
            </th>
          </tr>
        </thead>
        <tbody>
          <template v-for="menu in menus" :key="menu.id">
            <tr class="border">
              <td class="border px-3 py-2">{{ menu.name }}</td>
              <td v-for="action in actions" :key="action.id" class="text-center">
                <input
                  type="checkbox"
                  :checked="isChecked(menu.id, action.id)"
                  @change="togglePermission(menu.id, action.id)"
                />
              </td>
            </tr>

            <tr v-for="child in menu.children" :key="child.id">
              <td class="pl-6 border px-3 py-2">↳ {{ child.name }}</td>
              <td v-for="action in actions" :key="action.id" class="text-center">
                <input
                  type="checkbox"
                  :checked="isChecked(child.id, action.id)"
                  @change="togglePermission(child.id, action.id)"
                />
              </td>
            </tr>
          </template>
        </tbody>
      </table>

      <Button class="mt-4" @click="submit">Save Access</Button>
    </div>
  </AppLayout>
</template>
