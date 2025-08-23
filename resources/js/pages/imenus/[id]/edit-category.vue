<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="flex items-center justify-between">
                <Heading title="Edit Category" />
                <Link href="/imenus">
                    <Button>
                        <Icon name="circleArrowLeft" />
                        <span>Back</span>
                    </Button>
                </Link>
            </div>
            <div class="mx-auto max-w-xl space-y-2 p-6 pt-2">
                <form @submit.prevent="submit" class="flex flex-col justify-items-start gap-1.5">
                    <Input v-model="form.name" label="Name" required />
                    <Textarea v-model="form.description" label="Description" />
                    <ImageUploader v-model="form.image" label="Category Image" type="video" />

                    <div class="mt-4 flex justify-between">
                        <ToggleSwitch class="w-fit" v-model="form.is_active" active-label="Active" inactive-label="Inactive" />
                        <Button type="submit">Update</Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
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
});

const breadcrumbs = [
    { title: 'Menus', href: '/imenus' },
    { title: props.menu.name, href: `/imenus/${props.menu.id}` },
    { title: 'Edit Category', href: null },
];

const form = reactive({
    name: props.category.name,
    description: props.category.description,
    image: props.category.image,
    is_active: !!props.category.is_active,
});

const submit = () => {
    router.put(`/imenus/${props.menu.id}/categories/${props.category.id}`, form);
};
</script>
