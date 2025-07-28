<script setup>
import { ref } from 'vue'
import { useForm, router, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Button from '@/components/ui/button/Button.vue'
import Icon from '@/components/Icon.vue'

const props = defineProps({
  roles: Object,
  filters: Object,
})

const breadcrumbs = [
  { title: 'Dashboard', href: '/' },
  { title: 'Roles', href: '/roles' },
]

const form = useForm({
  id: null,
  name: '',
  status: 1,
})

const editing = ref(false)

function editRole(role) {
  editing.value = true
  form.id = role.id
  form.name = role.name
  form.status = role.status
}

function resetForm() {
  form.reset()
  editing.value = false
}

function submit() {
  if (editing.value) {
    form.put(`/roles/${form.id}`, {
      onSuccess: resetForm,
    })
  } else {
    form.post('/roles', {
      onSuccess: resetForm,
    })
  }
}

function destroy(id) {
  if (confirm('Are you sure?')) {
    router.delete(`/roles/${id}`)
  }
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Roles</h1>

      <!-- Create/Edit Role Form -->
      <form @submit.prevent="submit" class="mb-6 p-2 border rounded flex items-center justify-between gap-2">
        <div class=" w-full">
          <label class="block font-medium">Name</label>
          <input v-model="form.name" class="w-full border rounded px-2 py-1" />
        </div>
        <div class=" w-full">
          <label class="block font-medium">Status</label>
          <select v-model="form.status" class="w-full border rounded px-2 py-1">
            <option :value="1">Active</option>
            <option :value="0">Inactive</option>
          </select>
        </div>
        <div class=" w-fit flex flex-row gap-2">
          <Button class=" text-white px-4 py-1 rounded" type="submit">
            {{ editing ? 'Update' : 'Create' }}
          </Button>
          <Button variant='secondary' type="button" @click="resetForm" v-if="editing"
            class="ml-2 text-gray-600 underline">Cancel</BUtton>
        </div>
      </form>

      <!-- Role Table -->
      <table class="w-full border">
        <thead>
          <tr class="bg-gray-100 flex flex-row justify-between items-center px-2">
            <th class="text-left p-2">#</th>
            <th class="text-left p-2">Name</th>
            <th class="text-left p-2">Status</th>
            <th class="text-left p-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="role in props.roles.data" :key="role.id"
            class="border-t flex flex-row justify-between items-center">
            <td class="p-2">{{ role.id }}</td>
            <td class="p-2">{{ role.name }}</td>
            <td class="p-2">
              <span :class="role.status ? 'text-green-600' : 'text-red-600'">
                {{ role.status ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="p-2 space-x-2">
              <Button @click="editRole(role)" class="">
                <Icon name="edit" />
              </Button>
              <Button variant="destructive" @click="destroy(role.id)" class="">
                <Icon name="trash" />
              </Button>
              <Link :href="`/roles/${role.id}/permissions`">
              <Button variant="destructive" class="">
                <Icon name="eye" />
              </Button>
              </Link>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="roles?.meta?.links?.length > 3" class="mt-4 space-x-2">
        <button v-for="link in roles.meta.links" :key="link.label" :disabled="!link.url" @click="router.visit(link.url)"
          v-html="link.label" class="px-2 py-1 border rounded" :class="{ 'bg-gray-300': link.active }" />
      </div>
    </div>
  </AppLayout>
</template>
