<script lang="ts" setup>
import Heading from '@/components/Heading.vue'
import Icon from '@/components/Icon.vue'
import ImageUploader from '@/components/ImageUploader.vue'
import ToggleSwitch from '@/components/ToggleSwitch.vue'
import Button from '@/components/ui/button/Button.vue'
import Input from '@/components/ui/input/Input.vue'
import Textarea from '@/components/ui/textarea/textarea.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { useAccess } from '@/composables/useAccess'
const { can } = useAccess('branches')
import { useToast } from '@/composables/useToast'
import ToastManager from '@/components/ToastManager.vue'
const { showToast } = useToast();
const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Branches', href: '/branches' },
    { title: 'Create', href: '/branches/create' },
]
const props = defineProps({
    branch: {
        type: Object,
        default: () => ({
            name: '',
            location: '',
            phone: '',
            description: '',
            image: '',
            status: true,
        }),
    },
})

const form = useForm({ ...props.branch })

function submit() {
    if (props.branch.id) {
        form.put(`/branches/${props.branch.id}`, {
            onSuccess: () => {
                showToast('Success', 'Branch updated successfully', 'success')
            },
            onError: (errors) => {
                Object.values(errors).forEach((msg) => {
                    showToast('Validation Error', String(msg), 'error')
                })

            },
            onFailure: () => {
                showToast('Error', 'Something went wrong', 'error')
            },
        })
    } else {
        form.post('/branches', {
            onSuccess: () => {
                showToast('Success', 'Branch created successfully.', 'success')
            },
            onError: (errors) => {
                Object.values(errors).forEach((msg) => {
                    showToast('Validation Error', String(msg), 'error')
                })
            },

            onFailure: () => {
                showToast('Error', 'Something went wrong.', 'error')
            },
        })
    }
}
</script>

<template>
    <Head title="Add New Branch" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <ToastManager/>
        <div class="p-4">
            <div class="flex items-center justify-between">
                <Heading title="Add New Branch" />
                <Link href="/branches">
                <Button>
                    <Icon name="circleArrowLeft" />
                    <span>Back</span>
                </Button>
                </Link>
            </div>
            <div class="mx-auto h-auto max-w-2xl overflow-clip p-4">
                <form @submit.prevent="submit" class="space-y-4">
                    <Input v-model="form.name" label="Name" :isRequired="true" placeholder="Menu Name"
                        class="w-full rounded border border-gray-600 px-2 py-1.5 outline-none" />
                    <div class="flex flex-row gap-4">
                        <Input v-model="form.location" label="Location" :isRequired="true" placeholder="Location"
                            class="w-full rounded border border-gray-600 px-2 py-1.5 outline-none" />

                        <Input v-model="form.phone" label="Phone" :isRequired="true" placeholder="Phone Number"
                            class="w-full rounded border border-gray-600 px-2 py-1.5 outline-none" />
                    </div>

                    <Textarea v-model="form.description" placeholder="Description" label="Description"
                        class="w-full rounded border border-gray-600 px-2 py-1.5 outline-none" />
                    <div class="w-full">
                        <ImageUploader v-model="form.image" type="video" label="Branch Image" />
                    </div>
                    <div class="flex justify-between items-center">
                        <ToggleSwitch class="w-fit" v-model="form.status" active-label="Active"
                            inactive-label="Inactive" />
                        <Button v-if="can('print')" class="">
                            <Icon name="upload" />
                            Save New Branch
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
