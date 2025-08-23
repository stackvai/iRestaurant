<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import PagesLayout from '@/layouts/pages/Layout.vue'

const props = defineProps({
    page: Object,
    slug: String,
})
const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Pages', href: '/about-us' },
      { title: `${props.page.title}`, href: `/roles/${props.page.slug}` },
]
const form = useForm({
    title: props.page?.title || '',
    content: props.page?.content || '',
})

const updated = ref(false)

function updatePage() {
    form.put(`/pages/${props.slug}`, {
        preserveScroll: true,
        onSuccess: () => {
            updated.value = true
            setTimeout(() => (updated.value = false), 2000)
        },
    })
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <PagesLayout>
            <div class="max-w-3xl mx-auto p-6">
                <h1 class="text-2xl font-bold mb-4 capitalize">Edit Page: {{ props.slug }}</h1>

                <div v-if="updated" class="mb-4 text-green-600">✅ Page updated!</div>

                <form @submit.prevent="updatePage" class="space-y-4">
                    <div>
                        <label class="block font-semibold mb-1">Title</label>
                        <input v-model="form.title" class="w-full border rounded px-3 py-2" required />
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Content</label>
                        <textarea v-model="form.content" class="w-full border rounded px-3 py-2" rows="10" />
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Update Page
                    </button>
                </form>
            </div>
        </PagesLayout>
    </AppLayout>
</template>
