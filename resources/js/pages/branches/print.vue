<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { onMounted } from 'vue';
import { useAccess } from '@/composables/useAccess'
import Button from '@/components/ui/button/Button.vue';
import Icon from '@/components/Icon.vue';
import { Head, Link } from '@inertiajs/vue3';
const { can } = useAccess('branches')
const props = defineProps({ branches: Array });
const breadcrumbs = [
  { title: 'Dashboard', href: '/' },
  { title: 'Branches', href: '/branches' },
  { title: 'Print', href: '/branches/print' },
]
const printPage = () => {
  window.print(); // automatically open print dialog
};
onMounted(() => {
  printPage()
})
</script>

<template>
  <Head title="Print All Branches" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <div class="flex justify-between items-center flex-row mb-4 ">
        <Heading title="All Branches" />

        <Link href="/branches">
        <Button class="h-full no-print">
          <Icon name="CircleArrowLeft" />
          Back
        </Button>
        </Link>
      </div>
      <table class="w-full border-collapse border">
        <thead>
          <tr class="bg-gray-100">
            <th class="border p-2">ID</th>
            <th class="border p-2">Name</th>
            <th class="border p-2">Location</th>
            <th class="border p-2">Phone</th>
            <th class="border p-2">Description</th>
            <th class="border p-2">Image</th>
            <th class="border p-2">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="branch in branches" :key="branch.id">
            <td class="border p-2">{{ branch.id }}</td>
            <td class="border p-2">{{ branch.name }}</td>
            <td class="border p-2">{{ branch.location }}</td>
            <td class="border p-2">{{ branch.phone }}</td>
            <td class="border p-2">{{ branch.description }}</td>
            <td class="border p-2">
              <img v-if="branch.image" :src="branch.image" class="w-20 h-20 object-cover" />
              <span v-else>-</span>
            </td>
            <td class="border p-2">{{ branch.status ? 'Active' : 'Inactive' }}</td>
          </tr>
        </tbody>
      </table>
      <div class="my-4 text-center">
        <Button v-if="can('print')" @click="printPage" class="h-full no-print">
          <Icon name="printer" />
          Print Now
        </Button>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
@media print {
  .no-print {
    display: none !important;
  }

  body {
    -webkit-print-color-adjust: exact;
  }

  #printArea {
    page-break-inside: avoid;
  }
}
</style>
