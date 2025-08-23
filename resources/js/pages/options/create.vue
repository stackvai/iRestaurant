<script setup>
import Icon from '@/components/Icon.vue';
import ImageUploader from '@/components/ImageUploader.vue';
import Button from '@/components/ui/button/Button.vue';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { AccordionContent, AccordionHeader, AccordionItem, AccordionRoot, AccordionTrigger } from 'reka-ui';
import { computed, onMounted, ref } from 'vue';

const { categories } = defineProps({
    categories: Array,
});

const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Options', href: '/options' },
    { title: 'Create', href: '/options/create' },
];

const form = useForm({
    name: '',
    description: '',
    is_active: true,
    option_items: [
        {
            name: '',
            description: '',
            extra_price: 0,
            tax: 0,
            image: '',
            is_default: true,
            position: 1,
        },
    ],
    items: [],
});

function createOption() {
    const invalidItem = form.items.find((i) => Number(i.min_selection || 0) > Number(i.max_selection || 0));
    if (invalidItem) {
        alert('Minimum selection cannot be greater than maximum selection.');
        return;
    }

    form.is_active = !!form.is_active;
    form.multiple_selection = !!form.multiple_selection;

    form.option_items = form.option_items.map((item, index) => ({
        ...item,
        is_default: !!item.is_default,
        extra_price: Number(item.extra_price || 0),
        tax: Number(item.tax || 0),
        position: index + 1,
    }));

    form.post('/options', {
        onSuccess: () => {
            form.reset();
            form.option_items = [
                {
                    name: '',
                    description: '',
                    extra_price: 0,
                    tax: 0,
                    image: '',
                    is_default: true,
                    position: 1,
                },
            ];
            router.reload({ only: ['options'] });
        },
    });
}

function addOptionItem() {
    const index = form.option_items.length;
    form.option_items.push({
        name: '',
        description: '',
        extra_price: 0,
        tax: 0,
        image: '',
        is_default: index === 0,
        position: index + 1,
    });
}

function removeOptionItem(index) {
    form.option_items.splice(index, 1);
    form.option_items.forEach((item, i) => {
        item.position = i + 1;
        item.is_default = i === 0;
    });
}

const search = ref('');
const openCategories = ref([]);
const filteredItems = computed(() => {
    if (!search.value.trim()) return categories;
    const lowerSearch = search.value.toLowerCase();
    return categories
        .map((category) => ({
            ...category,
            items: category.items.filter((item) => item.name.toLowerCase().includes(lowerSearch)),
        }))
        .filter((cat) => cat.items.length > 0);
});

function toggleItem(itemId) {
    const exists = form.items.find((i) => i.item_id === itemId);
    if (exists) {
        form.items = form.items.filter((i) => i.item_id !== itemId);
    } else {
        form.items.push({
            item_id: itemId,
            is_required: false,
            min_selection: 0,
            max_selection: 1,
            multiple_selection: false,
        });
    }
}

function isItemSelected(itemId) {
    return form.items.some((i) => i.item_id === itemId);
}

onMounted(() => {
    if (categories.length > 0) {
        openCategories.value = [categories[0].id];
    }
});
</script>


<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex flex-row items-center justify-between">
                <h1 class="text-2xl font-bold">Create Options</h1>
                <Link href="/options">
                    <Button> <Icon name="circleArrowLeft" /> Back </Button>
                </Link>
            </div>
            <hr class="my-6" />
            <!-- Create Form -->
            <form @submit.prevent="createOption" class="relative">
                <div class="flex flex-row gap-4">
                    <div class="w-[55%] rounded-xl p-2 shadow">
                        <!--Options Settings-->
                        <div class="p-2">
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Name</label>
                                <input v-model="form.name" class="w-full rounded border p-2" />
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Description</label>
                                <textarea v-model="form.description" class="w-full rounded border p-2"></textarea>
                            </div>

                            <div class="mb-3 flex flex-col gap-2">
                                <div class="flex flex-row items-center justify-between">
                                    <div>
                                        <h1 class="text-md font-semibold">Minimum quantity</h1>
                                        <p class="text-xs">What’s the minimum number of options a customer must select?</p>
                                    </div>
                                    <input type="number" v-model="form.min_selection" class="w-16 rounded border p-1" placeholder="-" />
                                </div>
                                <div class="flex flex-row items-center justify-between">
                                    <div>
                                        <h1 class="text-md font-semibold">Maximum quantity</h1>
                                        <p class="text-xs">What’s the maximum number of options a customer can select?</p>
                                    </div>
                                    <input type="number" v-model="form.max_selection" class="w-16 rounded border p-1" placeholder="-" />
                                </div>
                            </div>
                            <div class="mb-3 flex flex-row justify-between">
                                <Checkbox :model-value="form.is_active" @update:model-value="form.is_active = $event" label="Active" />
                                <Checkbox
                                    :model-value="form.multiple_selection"
                                    @update:model-value="form.multiple_selection = $event"
                                    label="Can User Select One Item Multiple time?"
                                />
                            </div>
                        </div>
                        <!-- Option Items -->
                        <div class="">
                            <h3 class="text-lg font-bold">Option Items</h3>

                            <div
                                v-for="(item, index) in form.option_items"
                                :key="index"
                                class="my-2 flex flex-row items-center justify-between gap-2 rounded-lg border p-2"
                            >
                                <div class="flex flex-row items-center justify-start gap-2">
                                    <div class="w-[45%]">
                                        <ImageUploader v-model="item.image" type="square" />
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="w-full flex flex-row gap-1">
                                            <div class="">
                                                <label class="block text-sm font-medium">Name</label>
                                                <Input v-model="item.name" class="w-full rounded border p-1.5" />
                                            </div>
                                            <div class="">
                                                <label class="block text-sm font-medium">Extra Price</label>
                                                <Input type="number" v-model="item.extra_price" class="w-full rounded border p-1.5" />
                                            </div>
                                            <div class="">
                                                <label class="block text-sm font-medium">Tax Rate</label>
                                                <Input type="number" v-model="item.tax" class="w-full rounded border p-1.5" />
                                            </div>
                                        </div>
                                        <div class="w-full">
                                            <p class="text-sm font-medium">Description</p>
                                            <Input v-model="item.description" rows="1" class="w-full rounded border p-1.5"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <Button type="button" size="sm" @click="addOptionItem">
                                        <Icon name="copy" />
                                    </Button>

                                    <Button type="button" size="sm" @click="removeOptionItem(index)">
                                        <Icon name="trash" />
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-[45%] rounded-xl p-4 shadow">
                        <div class="flex flex-row items-center justify-between">
                            <div>
                                <h3 class="text-md font-bold">Add to items</h3>
                                <p class="text-xs">Select which items these Options will be added to.</p>
                            </div>

                            <Button type="submit" class="rounded px-4 py-2"> <Icon name="hardDriveDownload" /> Save </Button>
                        </div>
                        <hr class="my-3" />
                        <div>
                            <input type="text" v-model="search" placeholder="Search items..." class="mb-4 w-full rounded border px-3 py-2" />

                            <AccordionRoot type="multiple" v-model="openCategories" class="w-full rounded-lg border">
                                <AccordionItem v-for="category in filteredItems" :key="category.id" :value="category.id">
                                    <AccordionHeader>
                                        <AccordionTrigger
                                            class="flex w-full items-center justify-between rounded border-b px-4 py-2 text-left font-medium"
                                        >
                                            <span>{{ category.name }}</span>
                                            <Icon
                                                name="arrowDown"
                                                :class="[
                                                    'transition-transform duration-400',
                                                    openCategories.includes(category.id) ? 'rotate-180' : '',
                                                ]"
                                            />
                                        </AccordionTrigger>
                                    </AccordionHeader>
                                    <AccordionContent class="rounded-b-lg border-b px-4 py-2">
                                        <div class="grid grid-cols-2 gap-2">
                                            <label v-for="item in category.items" :key="item.id" class="inline-flex items-center gap-2 text-xs">
                                                <input
                                                    type="checkbox"
                                                    :checked="isItemSelected(item.id)"
                                                    @change="toggleItem(item.id)"
                                                    class="form-checkbox"
                                                />
                                                {{ item.name }}
                                            </label>
                                        </div>
                                    </AccordionContent>
                                </AccordionItem>
                            </AccordionRoot>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
