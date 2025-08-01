<script setup>
import { ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Button from '@/components/ui/button/Button.vue'

const props = defineProps({
  customers: Object,
  filters: Object
})
const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Customers', href: '/Customers' },
]
const search = ref(props.filters.search || '')

watch(search, (value) => {
  router.get('/customers', { search: value }, { preserveState: true })
})
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Customers</h1>
        <input
          v-model="search"
          type="text"
          placeholder="Search customers..."
          class="border rounded px-3 py-2 w-64"
        />
      </div>

      <div class="rounded">
        <table class="min-w-full table-auto">
          <thead>
            <tr class="bg-gray-100 text-left text-sm font-medium">
              <th class="px-4 py-2">#</th>
              <th class="px-4 py-2">Name</th>
              <th class="px-4 py-2">Email</th>
              <th class="px-4 py-2">Mobile</th>
              <th class="px-4 py-2">Role</th>
              <th class="px-4 py-2">Location</th>
              <th class="px-4 py-2">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in customers.data" :key="user.id" class="border-t">
              <td class="px-4 py-2">{{ index + 1 + (customers.current_page - 1) * customers.per_page }}</td>
              <td class="px-4 py-2">{{ user.name }}</td>
              <td class="px-4 py-2">{{ user.email }}</td>
              <td class="px-4 py-2">{{ user.mobile || '-' }}</td>
              <td class="px-4 py-2">{{ user.role?.name || '-' }}</td>
              <td class="px-4 py-2">{{ user.location || '-' }}</td>
              <td class="px-4 py-2">{{ user.status? "Active" : "Blocked" || '-' }}</td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div class="p-4 flex justify-center">
          <Component :is="customers.links.length ? 'nav' : 'div'" class="flex gap-1">
            <template v-for="(link, key) in customers.links" :key="key">
              <Button
                v-if="link.url"
                @click="router.visit(link.url)"
                v-html="link.label"
                :class="[
                  'px-3 py-1 border rounded',
                  link.active ? 'text-white' : 'bg-white text-gray-700 hover:bg-gray-100'
                ]"
              />
              <span v-else v-html="link.label" class="px-3 py-1 text-gray-400" />
            </template>
          </Component>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
