<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <Heading :title="`Add Item to ${category.name}`" />
                <Link href="/imenus">
                    <Button>
                        <Icon name="circleArrowLeft" />
                        <span>Back</span>
                    </Button>
                </Link>
            </div>
            <div class="mx-auto max-w-xl space-y-4 p-6 pt-2">
                <h1 class="text-2xl font-bold"></h1>
                <form @submit.prevent="submit" class="flex flex-col justify-items-start gap-1.5">
                    <Input v-model="form.name" label="Name" required />
                    <Textarea v-model="form.description" label="Description" />

                    <div class="flex flex-row gap-3">
                        <Input v-model="form.price" label="Price" type="number" step="0.01" />
                        <Input v-model="form.offer_price" label="Offer Price" type="number" step="0.01" />
                        <Input v-model="form.tax" label="Tax" type="number" step="0.01" />
                    </div>
                    <ImageUploader v-model="form.image" label="Upload Image" type="video"/>

                    <div class="mt-4 flex justify-between">
                        <ToggleSwitch class="w-fit" v-model="form.is_active" active-label="Active" inactive-label="Inactive" />
                        <Button type="submit">
                            <div class="flex items-center gap-2"><Icon name="download" /> Save</div>
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
import { onMounted, reactive } from 'vue';
import { useToast } from '@/composables/useToast';
const { showToast } = useToast();
const props = defineProps({
    menu: Object,
    category: Object,
    duplicate: Object,
});

const breadcrumbs = [
    { title: 'Menus', href: '/imenus' },
    { title: props.menu.name, href: `/imenus/${props.menu.id}` },
    { title: 'Add Item', href: null },
];

const form = reactive({
    name: '',
    description: '',
    image: '',
    price: '',
    offer_price:'',
    tax:'',
    serial_no:'',
    is_active: true,
});


onMounted(() => {
  if (props.duplicate) {
    form.name = props.duplicate.name + ' (Copy)'
    form.description = props.duplicate.description
    form.image = props.duplicate.image
    form.price = props.duplicate.price
    form.offer_price = props.duplicate.offer_price
    form.tax = props.duplicate.tax
    form.is_active = props.duplicate.is_active
  }
});


const submit = () => {
    router.post(
        `/imenus/${props.menu.id}/categories/${props.category.id}/items`,
        form,
        {
            preserveScroll: true,
            onSuccess: () => {
                showToast('Item added successfully!', '', 'success'); // ✅ Show toast
            },
            onError: (errors) => {
                showToast('Failed to add item.', '', 'error'); // optional error toast
            },
        }
    );
};

</script>
