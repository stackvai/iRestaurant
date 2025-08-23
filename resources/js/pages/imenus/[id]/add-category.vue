<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="flex items-center justify-between">
                <Heading title="Create New Category" />
                <Link href="/imenus">
                    <Button>
                        <Icon name="circleArrowLeft" />
                        <span>Back</span>
                    </Button>
                </Link>
            </div>
            <div class="mx-auto max-w-xl space-y-4 p-6 pt-2">
                <form @submit.prevent="submit" class="flex flex-col justify-items-start gap-1.5">
                    <Input v-model="form.name" label="Name" required />
                    <Textarea v-model="form.description" label="Description" />
                    <ImageUploader v-model="form.image" label="Category Image" type="video"/>

                    <div class="mt-4 flex justify-between">
                        <ToggleSwitch class="w-fit" v-model="form.is_active" active-label="Active" inactive-label="Inactive" />
                        <Button type="submit">
                            <Icon name="save"/> Save
                        </Button>
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
import { useToast } from '@/composables/useToast';
const { showToast } = useToast();
const props = defineProps({ menu: Object });

const breadcrumbs = [
    { title: 'Menus', href: '/imenus' },
    { title: props.menu.name, href: `/imenus/${props.menu.id}` },
    { title: 'Add Category', href: null },
];

const form = reactive({
    name: '',
    description: '',
    image: '',
    is_active: true,
});

const submit = () => {
    router.post(`/imenus/${props.menu.id}/categories`, form,        {
            preserveScroll: true,
            onSuccess: () => {
                showToast('Category added successfully!', '', 'success'); // ✅ Show toast
            },
            onError: (errors) => {
                showToast('Failed to add Category.', '', 'error'); // optional error toast
            },
        });
};
</script>
