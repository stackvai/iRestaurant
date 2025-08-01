<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="flex justify-between items-center">
                <Heading title="Create New Menu" />
                <Link href="/imenus">
                <Button>
                    <Icon name="circleArrowLeft" />
                    <span>Back</span>
                </Button>
                </Link>
            </div>
            <div class="max-w-5xl mx-auto h-auto overflow-clip
">
                <form @submit.prevent="submit" class="h-full flex items-start justify-center gap-4">
                    <div
                        class="flex items-start flex-col justify-between h-full w-md gap-2 p-4">
                        <Input v-model="form.name" label="Name" :isRequired="true" placeholder="Menu Name"
                            class="border border-gray-600 rounded px-2 py-1.5 outline-none w-full" />
                        <Textarea v-model="form.description" placeholder="Description" label="Description"
                            class="w-full border border-gray-600 rounded px-2 py-1.5 outline-none mb-2" />
                        <ImageUploader v-model="form.image_file" />
                        <div class="flex items-center justify-between flex-row w-full gap-2 mt-2">
                            <ToggleSwitch class="w-fit" v-model="form.is_active" active-label="Active"
                                inactive-label="Inactive" />
                            <Button class="w-auto" type="submit">
                                <Icon name="plus" />
                                <span>Create New Menu</span>
                            </Button>
                        </div>
                    </div>
                    <div class="p-4 overflow-y-auto max-h-[500px]">
                        <h2 class="text-lg font-semibold mb-2">Weekly Availability</h2>
                        <div v-for="(slots, day) in form.availability" :key="day" class="space-y-1">
                            <div class="flex items-center gap-2"><span class=" font-medium">{{ day }}</span> <Button
                                    variant="outline" size="xs" type="button"
                                    @click="form.availability[day].push({ from: '', to: '' })">
                                    <Icon name="plus" />
                                </Button></div>

                            <div v-for="(slot, index) in slots" :key="index" class="flex items-center gap-2">
                                <input type="time" v-model="form.availability[day][index].from"
                                    class="border rounded px-2 py-1" />
                                <span>-</span>
                                <input type="time" v-model="form.availability[day][index].to"
                                    class="border rounded px-2 py-1" />
                                <Button size="sm" variant="outline" type="button"
                                    @click="form.availability[day].splice(index, 1)" class="">
                                    <Icon name="trash" />
                                </Button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/button/Button.vue';
import ImageUploader from '@/components/ImageUploader.vue'
import Input from '@/components/ui/input/Input.vue';
import Textarea from '@/components/ui/textarea/textarea.vue';
import Heading from '@/components/Heading.vue';
import Icon from '@/components/Icon.vue';
import ToggleSwitch from '@/components/ToggleSwitch.vue';
const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Menus', href: '/imenus' },
    { title: 'Create', href: '/imenus/create' }
]

const form = useForm({
    name: '',
    description: '',
    image: null,
    is_active: true,
    availability: {
        Monday: [{ from: '', to: '' }],
        Tuesday: [{ from: '', to: '' }],
        Wednesday: [{ from: '', to: '' }],
        Thursday: [{ from: '', to: '' }],
        Friday: [{ from: '', to: '' }],
        Saturday: [{ from: '', to: '' }],
        Sunday: [{ from: '', to: '' }],
    },
})

const submit = () => {
    form.post(route('imenus.store'))
}
</script>