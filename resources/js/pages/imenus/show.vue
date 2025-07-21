<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-8">
            <!--Top Menu Section-->
            <div
                class="flex flex-row items-start justify-start mb-4 shadow-sm h-44 p-1 gap-2 rounded-lg bg-white relative">
                <div class="w-[200px] h-full rounded overflow-hidden">
                    <img :src="menu.image" alt="" class="w-full h-full object-cover rounded-lg mb-4" />
                </div>
                <div class="p-2">
                    <Link :href="`/imenus/${menu.id}/edit`" class="absolute top-2 right-2">
                    <TooltipProvider :delay-duration="0">
                        <Tooltip>
                            <TooltipTrigger>
                                <Button variant="outline">
                                    <Icon name="edit" class="w-6 h-6" />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>
                                Edit Menu Details
                            </TooltipContent>
                        </Tooltip>
                    </TooltipProvider>
                    </Link>
                    <h1 class="text-2xl font-bold">{{ menu.name }}</h1>
                    <p class="text-gray-500">{{ menu.description }}</p>
                </div>
            </div>
            <hr class="my-6">
            <!--Categories Section-->
            <div v-for="category in menu.categories" :key="category.id"
                class="border p-2 rounded-xl shadow flex flex-col gap-4">
                <!--Catgory Heading Section-->
                <div class="flex items-center justify-between p-2">
                    <div class="flex items-center gap-4 justify-start">
                        <div class="w-16 h-full rounded overflow-hidden">
                            <img :src="category.image" alt="" class="w-full h-16 object-cover rounded-lg" />
                        </div>
                        <div class="">
                            <div class="flex items-center gap-2">
                                <h2 class="text-2xl font-semibold">{{ category.name }}</h2>
                                <span class="relative flex size-3">
                                    <span
                                        class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-75"
                                        :class="{ 'bg-teal-400': category.is_active, 'bg-rose-400': !category.is_active }"></span>
                                    <span class="relative inline-flex size-3 rounded-full"
                                        :class="{ 'bg-teal-500': category.is_active, 'bg-rose-500': !category.is_active }"></span>
                                </span>
                            </div>
                            <p class="text-gray-500 text-xl">{{ category.description }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <Link :href="`/imenus/${menu.id}/categories/${category.id}/add-item`">
                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Button>
                                        <Icon name="plus" class="w-6 h-6" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    Add Item
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                        </Link>
                        <Link :href="`/imenus/${menu.id}/categories/${category.id}/edit`">
                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Button size="icon" class="h-9 w-9">
                                        <Icon name="edit" class="w-6 h-6" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    Edit Category
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                        </Link>

                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Button @click="deleteCategory(category.id)">
                                        <Icon name="trash" class="w-6 h-6" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    Delete Category
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </div>
                </div>
                <!--Items Heading Section-->
                <div class="flex items-center justify-around border-y py-1.5 px-12 mt-2">
                    <span class="w-[50%]">{{ category.items.length }} items</span>
                    <span class="w-[15%] text-center">Price</span>
                    <span class="w-[35%] text-right">Actions</span>
                </div>
                <!--Items Section-->
                <div v-if="category.items.length" v-for="item in category.items" :key="item.id"
                    class="flex items-center justify-between ml-10 p-2">
                    <!-- Items Title -->
                    <div class="w-[50%] flex items-center gap-4 justify-start">
                        <div class="w-12 h-full rounded overflow-hidden">
                            <img :src="item.image" alt="" class="w-full h-12 object-cover rounded-lg" />
                        </div>
                        <div class="">
                            <div class="flex items-center gap-2">
                                <h2 class="text-xl font-semibold">{{ item.name }}</h2>
                                <span class="relative flex size-2">
                                    <span
                                        class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-75"
                                        :class="{ 'bg-teal-400': item.is_active, 'bg-rose-400': !item.is_active }"></span>
                                    <span class="relative inline-flex size-2 rounded-full"
                                        :class="{ 'bg-teal-500': item.is_active, 'bg-rose-500': !item.is_active }"></span>
                                </span>
                            </div>
                            <p class="text-gray-500">{{ item.description }}</p>
                        </div>
                    </div>
                    <!-- Items Title -->
                    <p class="w-[15%] text-center text-lg font-normal">
                        {{ item.price }}
                    </p>
                    <!-- Items Actions -->
                    <div class="w-[35%] flex items-center justify-end gap-2">
                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Button @click="duplicateItem(category.id, item)" size="icon" class="h-9 w-9">
                                        <Icon name="copy" class="w-6 h-6" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    Duplicate Item
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                        <Link :href="`/imenus/${menu.id}/categories/${category.id}/items/${item.id}/edit`">
                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Button size="icon" class="h-9 w-9">
                                        <Icon name="edit" class="w-6 h-6" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    Edit Item
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                        </Link>
                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Button @click="deleteItem(category.id, item.id)" size="icon" class="h-9 w-9">
                                        <Icon name="trash" class="w-6 h-6" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    Delete Item
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </div>
                </div>
                <div v-else class="text-center text-gray-500 py-4 flex flex-col items-center">
                    No items found in this category.
                    <Link :href="`/imenus/${menu.id}/categories/${category.id}/add-item`">
                    <Button class="mt-2">
                        <Icon name="plus" class="w-6 h-6" />
                        Add Item
                    </Button>
                    </Link>
                </div>
            </div>
            <!--Add New Category Section-->
            <div class="border p-4 rounded-lg border-dashed border-gray-500 shadow flex flex-col items-center gap-4">
                <p v-if="menu.categories.length">Create New Category</p>
                <p v-else>No items found in this category.</p>
                <Link :href="`/imenus/${menu.id}/add-category`" class="text-blue-500 hover:underline">
                <Button>
                    <Icon name="plus" class="w-6 h-6" /> Add Category
                </Button>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import { router } from '@inertiajs/vue3'
import Icon from '@/components/Icon.vue';
import { TooltipProvider, Tooltip, TooltipTrigger, TooltipContent } from '@/components/ui/tooltip';

const props = defineProps({
    menu: Object
})
const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Menus', href: '/imenus' },
    { title: props.menu.name, href: `/imenus/${props.menu.id}` }
]

const deleteCategory = (categoryId) => {
    if (confirm('Are you sure you want to delete this category?')) {
        router.delete(`/imenus/${props.menu.id}/categories/${categoryId}`)
    }
}

const deleteItem = (categoryId, itemId) => {
    if (confirm('Are you sure you want to delete this item?')) {
        router.delete(`/imenus/${props.menu.id}/categories/${categoryId}/items/${itemId}`);
    }
}

const duplicateItem = (categoryId, item) => {
    router.get(`/imenus/${props.menu.id}/categories/${categoryId}/add-item`, {
        duplicate: JSON.stringify(item), // Pass the full item as query param
    })
}

</script>
