<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({ branches: Object })

function destroy(id) {
    if (confirm('Delete this branch?')) {
        router.delete(`/branches/${id}`)
    }
}
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">Branches</h1>
            <Link href="/branches/create" class="bg-blue-600 text-white px-4 py-2 rounded">Add Branch</Link>

            <table class="w-full mt-4 border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2">Name</th>
                        <th class="p-2">Location</th>
                        <th class="p-2">Phone</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="branch in branches.data" :key="branch.id" class="border-t">
                        <td class="p-2">{{ branch.name }}</td>
                        <td class="p-2">{{ branch.location }}</td>
                        <td class="p-2">{{ branch.phone }}</td>
                        <td class="p-2">{{ branch.status ? 'Active' : 'Inactive' }}</td>
                        <td class="p-2 space-x-2">
                            <Link :href="`/branches/${branch.id}/edit`" class="text-blue-600 underline">Edit</Link>
                            <button @click="destroy(branch.id)" class="text-red-600 underline">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
