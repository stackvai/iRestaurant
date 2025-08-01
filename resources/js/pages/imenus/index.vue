<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="flex flex-row items-center justify-between">
                <Heading title="All Menus" />
                <Link href="/imenus/create">
                <Button> <Icon name="plus"/> Create New Menu</Button>
                </Link>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4 p-4" v-if="imenus.length">
                <div v-for="menu in imenus" :key="menu.id" class="border p-1 rounded-xl shadow">
                    <img :src="menu.image" alt="" class="w-full h-52 object-cover rounded-xl" />
                    <div class="p-3">
                        <div class="flex items-center  gap-2">
                            <h2 class="text-xl font-semibold">{{ menu.name }}</h2>
                            <span class="relative flex size-3">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-75"
                                    :class="{ 'bg-teal-400': menu.is_active, 'bg-rose-400': !menu.is_active }"></span>
                                <span class="relative inline-flex size-3 rounded-full"
                                    :class="{ 'bg-teal-500': menu.is_active, 'bg-rose-500': !menu.is_active }"></span>
                            </span>
                        </div>

                        <p class="text-gray-700">{{ menu.description }}</p>

                        <div class="flex flex-row items-center justify-center gap-2 mt-4">
                            <Button @click="deleteMenu(menu.id)" variant="destructive" class="w-[50%]">Delete</Button>
                            <Link :href="`/imenus/${menu.id}/edit`" class="w-[50%]">
                            <Button class="w-full">Edit</Button>
                            </Link>
                            <Link :href="`/imenus/${menu.id}`" class="w-auto">
                                <Button class="w-full">View</Button>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="flex flex-col items-center justify-center gap-4 h-[60dvh]">
                <p class="text-center text-6xl text-gray-500">No menus found.</p>
                <Link href="/imenus/create">
                <Button class="text-xl">Create New One</Button>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import Icon from '@/components/Icon.vue';
import Heading from '@/components/Heading.vue';
const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Menus', href: '/imenus' },
]

defineProps({
    imenus: {
        type: Array,
        default: () => ([])
    }
});

const deleteMenu = (id) => {
    if (confirm('Delete this menu?')) {
        router.delete(route('imenus.destroy', id))
    }
}
</script>

<style scoped></style>