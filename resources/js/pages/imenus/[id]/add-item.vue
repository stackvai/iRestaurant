<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 max-w-xl mx-auto space-y-4">
            <h1 class="text-2xl font-bold">Add Item to {{ category.name }}</h1>
            <form @submit.prevent="submit">
                <Input v-model="form.name" label="Item Name" required />
                <Input v-model="form.description" label="Description" />
                <Input v-model="form.image" label="Image URL" />
                <Input v-model="form.price" label="Price" type="number" step="0.01" />
                <label class="flex items-center gap-2 mt-2">
                    <input type="checkbox" v-model="form.is_active" />
                    <span>Active</span>
                </label>
                <div class="mt-4 flex justify-end">
                    <Button type="submit">
                        <div class="flex items-center gap-2"> <Icon name="download" /> Save</div>
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { reactive, onMounted } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Input from '@/components/ui/input/Input.vue'
import Button from '@/components/ui/button/Button.vue'
import { router } from '@inertiajs/vue3'
import Icon from '@/components/Icon.vue'

const props = defineProps({
    menu: Object,
    category: Object,
    duplicate: Object
})

const breadcrumbs = [
    { title: 'Menus', href: '/imenus' },
    { title: props.menu.name, href: `/imenus/${props.menu.id}` },
    { title: 'Add Item', href: null },
]

const form = reactive({
    name: '',
    description: '',
    image: '',
    price: '',
    is_active: true,
})

onMounted(() => {
    if (props.duplicate) {
        form.name = props.duplicate.name
        form.description = props.duplicate.description
        form.price = props.duplicate.price
        form.image = props.duplicate.image
        form.is_active = props.duplicate.is_active
    }
})

const submit = () => {
    router.post(`/imenus/${props.menu.id}/categories/${props.category.id}/items`, form)
}
</script>
