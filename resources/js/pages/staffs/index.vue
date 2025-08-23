<script lang="ts" setup>
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Button from '@/components/ui/button/Button.vue'
import Icon from '@/components/Icon.vue'
import Input from '@/components/ui/input/Input.vue'
import Heading from '@/components/Heading.vue'
import { useAccess } from '@/composables/useAccess'
const { can } = useAccess('staffs')
import { useToast } from '@/composables/useToast'
const { showToast } = useToast()
import {
  ComboboxRoot,
  ComboboxInput,
  ComboboxTrigger,
  ComboboxContent,
  ComboboxViewport,
  ComboboxItem,
  ComboboxAnchor
} from 'reka-ui'
import Dropdown from '@/components/Dropdown.vue'
const props = defineProps({
  staff: Object,
  filters: Object
})

const breadcrumbs = [
  { title: 'Dashboard', href: '/' },
  { title: 'Staffs', href: '/Staffs' },
]
const search = ref(props.filters.search || '')
const perPage = ref(props.filters.per_page || 10)

watch(perPage, (value) => {
  router.get('/staffs', { search: search.value, per_page: value }, { preserveState: true })
})

function applySearch() {
  router.get('/staffs', { search: search.value }, { preserveState: true })
}

async function downloadCsv() {
  try {
    const response = await fetch('/staffs/export');

    if (!response.ok) throw new Error('Failed to export');

    const blob = await response.blob();
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'staffs.csv';
    a.click();
    window.URL.revokeObjectURL(url);

    showToast('Success', 'Staffs exported successfully!', 'success');
  } catch (error) {
    console.error(error);
    showToast('Failed', 'Failed to export staffs. Please try again.', 'error');
  }
}
</script>

<template>

  <Head title="All Staffs" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <div class="flex items-center justify-between mb-4">
        <Heading title="Staff Members" />
        <Link href="/dashboard">
        <Button>
          <Icon name="CircleArrowLeft" />
          <span>Back</span>
        </Button>
        </Link>
      </div>
      <div class="flex justify-between items-center gap-2 mb-4">
        <div class="flex justify-start items-center gap-2">
          <Input v-model="search" type="text" placeholder="Search staff..." class="border rounded px-3 w-64" />
          <Button @click="applySearch">
            <Icon name="search" />
          </Button>
        </div>
        <div class="flex justify-end gap-2">
          <Dropdown v-model="perPage" :options="[
            { label: '10 per page', value: 10 },
            { label: '25 per page', value: 25 },
            { label: '50 per page', value: 50 },
            { label: '100 per page', value: 100 }
          ]" />

          <Link href="staffs/print" v-if="can('print')">
          <Button>
            <Icon name="printer" /> Print All Data
          </Button>
          </Link>
          <Button v-if="can('export')" @click="downloadCsv">
            <Icon name="sheet" /> Export as Excel
          </Button>
        </div>
      </div>

      <div class="rounded">
        <table class="min-w-full table-auto">
          <thead>
            <tr class="bg-gray-100 text-left text-sm font-medium">
              <th class="border px-4 py-2">ID</th>
              <th class="border px-4 py-2">Image</th>
              <th class="border px-4 py-2">Name</th>
              <th class="border px-4 py-2">Email</th>
              <th class="border px-4 py-2">Mobile</th>
              <th class="border px-4 py-2">Role</th>
              <th class="border px-4 py-2">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in staff.data" :key="user.id" class="border-t">
              <td class="border px-4 py-2">{{ index + 1 + (staff.current_page - 1) * staff.per_page }}</td>
              <td class="border p-2 text-center">
                <img v-if="user.image" :src="user.image" class="w-12 h-12 object-cover rounded" />
                <span v-else>-</span>
              </td>
              <td class="border px-4 py-2">{{ user.name }}</td>
              <td class="border px-4 py-2">{{ user.email }}</td>
              <td class="border px-4 py-2">{{ user.mobile || '-' }}</td>
              <td class="border px-4 py-2">{{ user.role?.name || '-' }}</td>
              <td class="border px-4 py-2">{{ user.status ? "Active" : "Blocked" || '-' }}</td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div class="p-4 flex justify-center">
          <Component :is="staff.links.length ? 'nav' : 'div'" class="flex gap-1">
            <template v-for="(link, key) in staff.links" :key="key">
              <Button v-if="link.url" @click="router.visit(link.url)" v-html="link.label" :class="[
                'px-3 py-1 border rounded',
                link.active ? ' text-white' : 'bg-white text-gray-700 hover:bg-gray-100'
              ]" />
              <span v-else v-html="link.label" class="px-3 py-1 text-gray-400" />
            </template>
          </Component>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
