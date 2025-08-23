<script setup>
import Icon from '@/components/Icon.vue';
import Button from '@/components/ui/button/Button.vue';
import Tooltip from '@/components/ui/tooltip/Tooltip.vue';
import TooltipContent from '@/components/ui/tooltip/TooltipContent.vue';
import TooltipProvider from '@/components/ui/tooltip/TooltipProvider.vue';
import TooltipTrigger from '@/components/ui/tooltip/TooltipTrigger.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Options', href: '/options' },
]

defineProps({ options: Array })

const deleteOption = (id) => {
    if (confirm('Are you sure you want to delete this option?')) {
        router.delete(`/options/${id}`);
    }
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex flex-row items-center justify-between">
                <h1 class="text-2xl font-bold">All Options</h1>
                <Link href="/options/create">
                <Button>
                    <Icon name="plus" /> Create New Options
                </Button>
                </Link>
            </div>
            <hr class="my-6">
            <!-- Option List -->
            <div v-for="option in options" :key="option.id" class="rounded shadow p-4 mb-4">
                <!--Options list header-->
                <div class="flex justify-between items-center relative">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl font-semibold">{{ option.name }}</h2>
                            <span class="relative flex size-3">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-75"
                                    :class="{ 'bg-teal-400': option.is_active, 'bg-rose-400': !option.is_active }"></span>
                                <span class="relative inline-flex size-3 rounded-full"
                                    :class="{ 'bg-teal-500': option.is_active, 'bg-rose-500': !option.is_active }"></span>
                            </span>
                        </div>
                        <p class="text-sm">{{ option.description }}</p>
                    </div>
                    <div class="flex flex-row items-center justify-end gap-2">
                        <Link :href="`/options/${option.id}/edit`">
                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Button>
                                        <Icon name="edit" class="w-6 h-6" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    Edit Options Details
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                        </Link>

                        <TooltipProvider :delay-duration="0">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Button @click="deleteOption(option.id)">
                                        <Icon name="trash" class="w-6 h-6" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    Delete Options
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>

                    </div>
                </div>
                <div class="flex flex-row">
                    <!-- Option Items -->
                    <div v-if="option.option_items.length" class="mt-4 w-full">
                        <h3 class="text-sm font-semibold mb-2">Option Items:</h3>
                        <ul class="list-disc ml-5 text-xs  grid grid-cols-3">
                            <li v-for="item in option.option_items" :key="item.id">
                                {{ item.name }} - +${{ item.extra_price }}
                                <span class="text-green-600" v-if="item.is_default">[Default]</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Assigned Items -->
                    <div v-if="option.items.length" class="mt-4 w-full">
                        <h3 class="text-sm font-semibold mb-2">Used In Items:</h3>
                        <ul class="list-disc ml-5 text-xs  grid grid-cols-3">
                            <li v-for="item in option.items" :key="item.id">
                                {{ item.name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
