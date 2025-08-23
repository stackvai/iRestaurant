<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8 p-6">
            <!--Top Menu Section-->
            <div class="relative mb-4 flex flex-col space-y-4 rounded-lg bg-white p-4 shadow-sm">
                <!-- Menu Info -->
                <div class="flex items-start gap-4">
                    <div class="h-[160px] w-[200px] overflow-hidden rounded">
                        <img :src="menu.image || 'https://via.placeholder.com/200x160?text=No+Image'" alt=""
                            class="h-full w-full rounded-lg object-cover" />
                    </div>
                    <div class="relative flex-1">
                        <Link :href="`/imenus/${menu.id}/edit`" class="absolute top-2 right-2">
                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Button variant="outline">
                                        <Icon name="edit" class="h-6 w-6" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent>Edit Menu Details</TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                        </Link>

                        <h1 class="text-2xl font-bold">{{ menu.name }}</h1>
                        <p class="text-gray-500">{{ menu.description || 'No description' }}</p>
                        <!-- Availability Grid -->
                        <div class="grid grid-cols-7 gap-2 text-center">
                            <div v-for="(slots, day) in menu.availability" :key="day"
                                class="rounded border bg-gray-50 p-2">
                                <h3 class="text-sm font-semibold">{{ day }}</h3>
                                <div class="mt-1 text-xs text-gray-700">
                                    <template v-if="slots.length">
                                        <div v-for="slot in slots" :key="slot.from + slot.to">
                                            {{ slot.from || '00:00' }} - {{ slot.to || '00:00' }}
                                        </div>
                                    </template>
                                    <template v-else>
                                        <span class="font-medium text-red-500">Off day</span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-6" />
            <!--Categories Section-->
            <div v-for="category in menu.categories" :key="category.id"
                class="flex flex-col gap-1 rounded-xl border p-2 shadow">
                <!--Catgory Heading Section-->
                <div class="flex items-center justify-between p-2">
                    <div class="flex items-center justify-start gap-2">
                        <div class="h-full w-14 overflow-hidden rounded">
                            <img :src="category.image" alt="" class="h-14 w-full rounded-lg object-cover" />
                        </div>
                        <div class="">
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-semibold">{{ category.name }}</h2>
                                <span class="relative flex size-3">
                                    <span
                                        class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-75"
                                        :class="{ 'bg-teal-400': category.is_active, 'bg-rose-400': !category.is_active }"></span>
                                    <span class="relative inline-flex size-3 rounded-full"
                                        :class="{ 'bg-teal-500': category.is_active, 'bg-rose-500': !category.is_active }"></span>
                                </span>
                            </div>
                            <p class="text-sm text-gray-500">{{ category.description }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <Link :href="`/imenus/${menu.id}/categories/${category.id}/add-item`">
                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Button class="h-8 w-8">
                                        <Icon name="plus" class="h-6 w-6" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent> Add Item </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                        </Link>
                        <Link :href="`/imenus/${menu.id}/categories/${category.id}/edit`">
                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Button size="icon" class="h-8 w-8">
                                        <Icon name="edit" class="h-6 w-6" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent> Edit Category </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                        </Link>

                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Button @click="deleteCategory(category.id)" class="h-8 w-8">
                                        <Icon name="trash" class="h-6 w-6" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent> Delete Category </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </div>
                </div>
                <!--Items Heading Section-->
                <div class="flex items-center justify-around border-y px-12 py-1.5">
                    <span class="w-[50%] text-sm font-semibold">{{ category.items.length }} items</span>
                    <span class="w-[20%] text-center text-sm font-semibold">Price</span>
                    <span class="w-[30%] text-right text-sm font-semibold">Actions</span>
                </div>
                <!--Items Section-->
                <Draggable v-model="category.items" :group="{ name: 'items', pull: false, put: false }" item-key="id"
                    @end="onDragEnd(category)">
                    <template #item="{ element: item, index }">
                        <div class="flex items-center justify-between p-2">
                            <!-- Items Title -->
                            <div class="flex w-[50%] items-center justify-start gap-2">
                                <button variant="ghost"
                                    class="flex h-10 w-10 cursor-move items-center justify-center rounded-xl bg-transparent hover:bg-gray-100">
                                    <Icon name="alignJustify" class="h-6 w-6 opacity-60" />
                                </button>
                                <div class="h-full w-12 overflow-hidden rounded">
                                    <img :src="item.image" alt="" class="h-12 w-full rounded-lg object-cover" />
                                </div>
                                <div class="">
                                    <div class="flex items-center gap-2">
                                        <h2 class="text-sm font-semibold">{{ item.name }}</h2>
                                        <span class="relative flex size-2">
                                            <span
                                                class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-75"
                                                :class="{ 'bg-teal-400': item.is_active, 'bg-rose-400': !item.is_active }"></span>
                                            <span class="relative inline-flex size-2 rounded-full"
                                                :class="{ 'bg-teal-500': item.is_active, 'bg-rose-500': !item.is_active }"></span>
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ item.description }}</p>
                                </div>
                            </div>
                            <!-- Items Price -->
                            <p class="w-[15%] text-center text-sm font-normal">
                                {{ item.price }}
                            </p>
                            <!-- Items Actions -->
                            <div class="flex w-[35%] items-center justify-end gap-2">
                                <TooltipProvider :delay-duration="0">
                                    <Tooltip>
                                        <TooltipTrigger>
                                            <Button @click="duplicateItem(category.id, item)" size="icon"
                                                class="h-7 w-7">
                                                <Icon name="copy" class="h-5 w-5" />
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent> Duplicate Item </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                                <Link :href="`/imenus/${menu.id}/categories/${category.id}/items/${item.id}/edit`">
                                <TooltipProvider :delay-duration="0">
                                    <Tooltip>
                                        <TooltipTrigger>
                                            <Button size="icon" class="h-7 w-7">
                                                <Icon name="edit" class="h-5 w-5" />
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent> Edit Item </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                                </Link>
                                <TooltipProvider :delay-duration="0">
                                    <Tooltip>
                                        <TooltipTrigger>
                                            <Button @click="deleteItem(category.id, item.id)" size="icon"
                                                class="h-7 w-7">
                                                <Icon name="trash" class="h-5 w-5" />
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent> Delete Item </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                            </div>
                        </div>
                    </template>
                </Draggable>
                <!-- Show if no items -->
                <div v-if="!category.items || category.items.length === 0" class="flex justify-center py-4">
                    <Link :href="`/imenus/${menu.id}/categories/${category.id}/add-item`">
                    <Button variant="" class="px-6">
                        <Icon name="plus" class="   h-4 w-4" />
                        Add New Item
                    </Button>
                    </Link>
                </div>
            </div>
            <!--Add New Category Section-->
            <div class="flex flex-col items-center gap-4 rounded-lg border border-dashed border-gray-500 p-4 shadow">
                <p v-if="menu.categories.length">Create New Category</p>
                <p v-else>No items found in this category.</p>
                <Link :href="`/imenus/${menu.id}/add-category`" class="text-blue-500 hover:underline">
                <Button>
                    <Icon name="plus" class="h-6 w-6" /> Add Category
                </Button>
                </Link>
            </div>
        </div>
        <!-- Add the toast manager anywhere globally -->
        <ToastManager />
    </AppLayout>
</template>

<script lang="ts" setup>
import Icon from '@/components/Icon.vue';
import ToastManager from '@/components/ToastManager.vue';
import Button from '@/components/ui/button/Button.vue';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import Draggable from 'vuedraggable';
const { showToast } = useToast();
const props = defineProps({
    menu: Object,
});
const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Menus', href: '/imenus' },
    { title: props.menu.name, href: `/imenus/${props.menu.id}` },
];
onMounted(() => {
    props.menu.categories.forEach((category) => {
        category.items.sort((a, b) => a.serial_no - b.serial_no);
    });
    console.log(props.menu);
});


const onDragEnd = async (category) => {
    category.items.forEach((item, index) => {
        item.serial_no = index + 1;
    });

    category.items = [...category.items]; // trigger reactivity

    try {
        await router.post(
            `/imenus/${props.menu.id}/categories/${category.id}/items/reorder`,
            {
                items: category.items.map((item) => ({ id: item.id, serial_no: item.serial_no })),
            },
            {
                preserveScroll: true,
                onError: () => {
                    showToast('Failed to reorder items', '', 'error');
                },
            }
        );

        // Show toast immediately after success
        showToast('Items reordered successfully try more for test', '', 'success');

    } catch (error) {
        showToast('Failed to reorder items', '', 'error');
    }
};



const deleteCategory = (categoryId) => {
    if (confirm('Are you sure you want to delete this category?')) {
        router.delete(`/imenus/${props.menu.id}/categories/${categoryId}`);
    }
};

const deleteItem = (categoryId, itemId) => {
    if (confirm('Are you sure you want to delete this item?')) {
        router.delete(`/imenus/${props.menu.id}/categories/${categoryId}/items/${itemId}`);
    }
};

const duplicateItem = (categoryId, item) => {
    router.get(
        `/imenus/${props.menu.id}/categories/${categoryId}/add-item`,
        { duplicate: item.id }, // only send the ID
    );
};
</script>
