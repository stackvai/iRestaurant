<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 max-w-xl mx-auto space-y-4">
            <h1 class="text-2xl font-bold">Add Category</h1>
            <form @submit.prevent="submit">
                <Input v-model="form.name" label="Name" required />
                <Input v-model="form.description" label="Description" />
                <Input v-model="form.image" label="Image URL" />
                <label class="flex items-center gap-2 mt-2">
                    <input type="checkbox" v-model="form.is_active" />
                    <span>Active</span>
                </label>

                <div class="mt-4 flex justify-end">
                    <Button type="submit">Save</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { reactive } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Input from '@/components/ui/input/Input.vue'
import Button from '@/components/ui/button/Button.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({ menu: Object })

const breadcrumbs = [
    { title: 'Menus', href: '/imenus' },
    { title: props.menu.name, href: `/imenus/${props.menu.id}` },
    { title: 'Add Category', href: null },
]

const form = reactive({
    name: '',
    description: '',
    image: '',
    is_active: true,
})

const submit = () => {
    router.post(`/imenus/${props.menu.id}/categories`, form)
}
</script>
