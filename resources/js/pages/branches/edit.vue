<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    branch: {
        type: Object,
        default: () => ({
            name: '',
            location: '',
            phone: '',
            status: true,
        }),
    },
})

const form = useForm({ ...props.branch })

function submit() {
    if (props.branch.id) {
        form.put(`/branches/${props.branch.id}`)
    } else {
        form.post('/branches')
    }
}
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">{{ props.branch.id ? 'Edit Branch' : 'Create Branch' }}</h1>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label>Name</label>
                    <input v-model="form.name" class="border w-full p-1" />
                </div>
                <div>
                    <label>Location</label>
                    <input v-model="form.location" class="border w-full p-1" />
                </div>
                <div>
                    <label>Phone</label>
                    <input v-model="form.phone" class="border w-full p-1" />
                </div>
                <div>
                    <label>Status</label>
                    <select v-model="form.status" class="border w-full p-1">
                        <option :value="true">Active</option>
                        <option :value="false">Inactive</option>
                    </select>
                </div>
                <button class="bg-green-600 text-white px-4 py-2 rounded">
                    {{ props.branch.id ? 'Update' : 'Save' }}
                </button>
            </form>
        </div>
    </AppLayout>
</template>
