<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="flex items-center justify-between">
                <Heading title="Edit Item" />
                <Link href="/imenus">
                    <Button>
                        <Icon name="circleArrowLeft" />
                        <span>Back</span>
                    </Button>
                </Link>
            </div>
            <div class="mx-auto  space-y-4 p-6">
                <form @submit.prevent="submit" class="flex flex-row gap-5">
                    <div class="w-full flex flex-col gap-1.5">
                        <Input v-model="form.name" label="Item Name" required />
                        <Textarea v-model="form.description" label="Description" />
                        <div class="flex flex-row gap-3">
                            <Input v-model="form.price" label="Price" type="number" step="0.01" />
                            <Input v-model="form.price" label="Offer Price" type="number" step="0.01" />
                            <Input v-model="form.price" label="Tax" type="number" step="0.01" />
                        </div>
                        <div class="mt-4 flex justify-between">
                            <ToggleSwitch class="w-fit" v-model="form.is_active" active-label="Active" inactive-label="Inactive" />
                            <Button type="submit">Update</Button>
                        </div>
                    </div>
                    <div class="w-full">
                        <ImageUploader v-model="form.image" label="Image Upload" video />
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script lang="ts" setup>
import Heading from '@/components/Heading.vue';
import Icon from '@/components/Icon.vue';
import ImageUploader from '@/components/ImageUploader.vue';
import ToggleSwitch from '@/components/ToggleSwitch.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Textarea from '@/components/ui/textarea/textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    menu: Object,
    category: Object,
    item: Object,
});

const breadcrumbs = [
    { title: 'Menus', href: '/imenus' },
    { title: props.menu.name, href: `/imenus/${props.menu.id}` },
    { title: props.category.name, href: `/imenus/${props.menu.id}/categories/${props.category.id}/edit` },
    { title: props.item.name, href: `/imenus/${props.menu.id}/categories/${props.category.id}/items/${props.item.id}` },
];

const form = reactive({
    name: props.item.name,
    description: props.item.description,
    image: props.item.image,
    price: props.item.price,
    is_active: !!props.item.is_active,
});

const submit = () => {
    router.put(`/imenus/${props.menu.id}/categories/${props.category.id}/items/${props.item.id}`, form);
};
</script>
