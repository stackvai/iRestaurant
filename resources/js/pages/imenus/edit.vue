<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 w-full min-h-[80dvh] max-w-md mx-auto flex items-center justify-center">
            <form @submit.prevent="submit" class="w-[500px] rounded-2xl bg-white p-6 shadow-md flex flex-col gap-4">
                <h1 class="text-2xl font-bold text-center">Edit Menu</h1>
                <input v-model="form.name" placeholder="Name"
                    class="border border-gray-600 rounded px-2 py-1.5 outline-none" />
                <textarea v-model="form.description" placeholder="Description"
                    class="border border-gray-600 rounded px-2 py-1.5 outline-none"></textarea>
                <input v-model="form.image" placeholder="Image URL"
                    class="border border-gray-600 rounded px-2 py-1.5 outline-none" />
                <label><input type="checkbox" v-model="form.is_active" /> Active</label>
                <Button type="submit">Update</Button>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/button/Button.vue';
const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Menus', href: '/imenus' },
    { title: 'Edit', href: '/imenus/edit' }
]
const props = defineProps({ menu: Object })

const form = useForm({
    name: props.menu.name,
    description: props.menu.description,
    image: props.menu.image,
    is_active: !!props.menu.is_active
})

const submit = () => {
    form.put(route('imenus.update', props.menu.id))
}
</script>
