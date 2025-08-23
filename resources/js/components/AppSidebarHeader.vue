<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import { type NavItem } from '@/types';
import { SidebarFooter } from '@/components/ui/sidebar';
import NavFooter from '@/components/NavFooter.vue';
import NavUser from '@/components/NavUser.vue';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
const footerNavItems: NavItem[] = [

];
</script>

<template>
    <header
        class="w-full flex h-16 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
        <div class="w-full flex flex-row justify-between items-center">
            <div class="flex items-center justify-start">
                <SidebarTrigger class="-ml-1" />
                <template v-if="breadcrumbs && breadcrumbs.length > 0">
                    <Breadcrumbs :breadcrumbs="breadcrumbs" />
                </template>
            </div>



            <div>
                <NavUser />
            </div>


        </div>
    </header>
</template>
