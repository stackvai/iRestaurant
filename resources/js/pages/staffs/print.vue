<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { onMounted } from 'vue';
import { useAccess } from '@/composables/useAccess'
import Button from '@/components/ui/button/Button.vue';
import Icon from '@/components/Icon.vue';
import { Head, Link } from '@inertiajs/vue3';

const { can } = useAccess('staffs')   // ✅ use correct resource
const props = defineProps<{ staffs: Array<any> }>();

const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Staffs', href: '/staffs' },
    { title: 'Print', href: '/staffs/print' },
]

const printPage = () => {
    window.print(); // automatically open print dialog
};

onMounted(() => {
    printPage()
})
</script>

<template>

    <Head title="Print All Staffs" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="flex justify-between items-center mb-4 no-print">
                <Heading title="All Staffs" />
                <Link href="/staffs">
                <Button class="h-full">
                    <Icon name="CircleArrowLeft" />
                    Back
                </Button>
                </Link>
            </div>

            <table class="w-full border-collapse border text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">ID</th>
                        <th class="border p-2">Image</th>
                        <th class="border p-2">Name</th>
                        <th class="border p-2">Email</th>
                        <th class="border p-2">Mobile</th>
                        <th class="border p-2">Location</th>
                        <th class="border p-2">Role</th>
                        <th class="border p-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="staff in staffs" :key="staff.id">
                        <td class="border p-2">{{ staff.id }}</td>
                        <td class="border p-2 text-center">
                            <img v-if="staff.image"
                                :src="staff.image"
                                class="w-12 h-12 object-cover rounded" />
                            <span v-else>-</span>
                        </td>
                        <td class="border p-2">{{ staff.name }}</td>
                        <td class="border p-2">{{ staff.email }}</td>
                        <td class="border p-2">{{ staff.mobile || '-' }}</td>
                        <td class="border p-2">{{ staff.location || '-' }}</td>
                        <td class="border p-2">{{ staff.role?.name || '-' }}</td>
                        <td class="border p-2">{{ staff.status ? 'Active' : 'Blocked' }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="my-4 text-center no-print">
                <Button v-if="can('print')" @click="printPage">
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
}
</style>
