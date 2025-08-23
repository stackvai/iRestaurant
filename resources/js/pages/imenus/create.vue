<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="flex items-center justify-between">
                <Heading title="Create New Menu" />
                <Link href="/imenus">
                    <Button>
                        <MyIcon name="circleArrowLeft" />
                        <span>Back</span>
                    </Button>
                </Link>
            </div>
            <div class="mx-auto h-auto max-w-5xl overflow-clip">
                <form @submit.prevent="submit" class="flex h-full items-start justify-center gap-4">
                    <div class="flex h-full w-md flex-col items-start justify-between gap-2 p-4">
                        <Input
                            v-model="form.name"
                            label="Name"
                            :isRequired="true"
                            placeholder="Menu Name"
                            class="w-full rounded border border-gray-600 px-2 py-1.5 outline-none"
                        />
                        <Textarea
                            v-model="form.description"
                            placeholder="Description"
                            label="Description"
                            class="w-full rounded border border-gray-600 px-2 py-1.5 outline-none"
                        />

                        <ComboboxRoot v-model="form.branch_id" class="relative mb-2 w-full">
                            <label for="branch">Branch</label>
                            <ComboboxAnchor
                                class="inline-flex h-[35px] min-w-full items-center justify-between gap-[5px] rounded-lg border border-gray-600 px-[15px] text-sm"
                            >
                                <ComboboxInput
                                    class="selection:bg-grass5 h-full w-full outline-none"
                                    placeholder="Select Branch ... "
                                    :display-value="
                                        (id) => {
                                            const branch = props.branches.find((b) => b.id === id);
                                            return branch ? branch.name : '';
                                        }
                                    "
                                />
                                <ComboboxTrigger>
                                    <Icon icon="radix-icons:chevron-down" class="text-grass11 h-4 w-4" />
                                </ComboboxTrigger>
                            </ComboboxAnchor>

                            <ComboboxContent
                                class="data-[side=top]:animate-slideDownAndFade data-[side=right]:animate-slideLeftAndFade data-[side=bottom]:animate-slideUpAndFade data-[side=left]:animate-slideRightAndFade absolute z-100 mt-1 w-full min-w-[160px] overflow-hidden rounded-lg border bg-white shadow-sm will-change-[opacity,transform]"
                            >
                                <ComboboxViewport class="p-[5px]">
                                    <ComboboxEmpty class="text-mauve8 py-2 text-center text-xs font-medium" />

                                    <template v-for="(group, index) in options" :key="group.name">
                                        <ComboboxGroup>
                                            <ComboboxSeparator v-if="index !== 0" class="bg-grass6 m-[5px] h-[1px]" />

                                            <ComboboxLabel class="text-mauve11 px-[25px] text-xs leading-[25px]">
                                                {{ group.name }}
                                            </ComboboxLabel>

                                            <ComboboxItem
                                                v-for="option in group.children"
                                                :key="option.id"
                                                :value="option.id"
                                                class="text-grass11 data-[disabled]:text-mauve8 data-[highlighted]:bg-grass9 data-[highlighted]:text-grass1 relative flex h-[25px] items-center rounded-[3px] pr-[35px] pl-[25px] text-xs leading-none select-none data-[disabled]:pointer-events-none data-[highlighted]:outline-none"
                                            >
                                                <ComboboxItemIndicator class="absolute left-0 inline-flex w-[25px] items-center justify-center">
                                                    <Icon icon="radix-icons:check" />
                                                </ComboboxItemIndicator>
                                                <span>
                                                    {{ option.name }}
                                                </span>
                                            </ComboboxItem>
                                        </ComboboxGroup>
                                    </template>
                                </ComboboxViewport>
                            </ComboboxContent>
                        </ComboboxRoot>
                        <div class="w-full">
                            <ImageUploader v-model="form.image" type="video" label="Upload Image" />
                        </div>
                        <div class="mt-2 flex w-full flex-row items-center justify-between gap-2">
                            <ToggleSwitch class="w-fit" v-model="form.is_active" active-label="Active" inactive-label="Inactive" />
                            <Button class="w-auto" type="submit">
                                <MyIcon name="plus" />
                                <span>Create New Menu</span>
                            </Button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h2 class="mb-2 text-lg font-semibold">Weekly Availability</h2>
                        <div v-for="(slots, day) in form.availability" :key="day" class="space-y-1">
                            <div class="mt-2 flex items-center gap-1">
                                <span class="font-medium">{{ day }}</span>
                                <Button variant="outline" size="xs" type="button" @click="form.availability[day].push({ from: '', to: '' })">
                                    <MyIcon name="plus" />
                                </Button>
                            </div>

                            <div v-for="(slot, index) in slots" :key="index" class="flex items-center gap-2">
                                <input type="time" v-model="form.availability[day][index].from" class="rounded border px-2 py-1" />
                                <span>-</span>
                                <input type="time" v-model="form.availability[day][index].to" class="rounded border px-2 py-1" />
                                <Button size="sm" variant="outline" type="button" @click="form.availability[day].splice(index, 1)" class="">
                                    <MyIcon name="trash" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script lang="ts" setup>
import Heading from '@/components/Heading.vue';
import MyIcon from '@/components/Icon.vue';
import ImageUploader from '@/components/ImageUploader.vue';
import ToggleSwitch from '@/components/ToggleSwitch.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Textarea from '@/components/ui/textarea/textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { Link, useForm } from '@inertiajs/vue3';
import {
    ComboboxAnchor,
    ComboboxContent,
    ComboboxEmpty,
    ComboboxGroup,
    ComboboxInput,
    ComboboxItem,
    ComboboxItemIndicator,
    ComboboxLabel,
    ComboboxRoot,
    ComboboxSeparator,
    ComboboxTrigger,
    ComboboxViewport,
} from 'reka-ui';

const props = defineProps<{
    branches: { id: number; name: string }[];
}>();

const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Menus', href: '/imenus' },
    { title: 'Create', href: '/imenus/create' },
];

const form = useForm({
    name: '',
    description: '',
    image: null,
    is_active: true,
    branch_id: '',
    availability: {
        Monday: [{ from: '', to: '' }],
        Tuesday: [{ from: '', to: '' }],
        Wednesday: [{ from: '', to: '' }],
        Thursday: [{ from: '', to: '' }],
        Friday: [{ from: '', to: '' }],
        Saturday: [{ from: '', to: '' }],
        Sunday: [{ from: '', to: '' }],
    },
});

const submit = () => {
    form.post(route('imenus.store'));
};

const options = [
    {
        name: 'Branches',
        children: props.branches.map((branch) => ({
            id: branch.id,
            name: branch.name,
        })),
    },
];
</script>
