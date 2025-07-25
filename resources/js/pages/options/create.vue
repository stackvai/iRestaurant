<script setup>
import { ref, computed, onMounted } from 'vue'
import Icon from '@/components/Icon.vue'
import Button from '@/components/ui/button/Button.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, router, Link } from '@inertiajs/vue3'
import { AccordionContent, AccordionHeader, AccordionItem, AccordionRoot, AccordionTrigger } from 'reka-ui'
import Checkbox from '@/components/ui/checkbox/Checkbox.vue'
const { categories } = defineProps({
    categories: Array
})

const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Options', href: '/options' },
    { title: 'Create', href: '/options/create' }
]

const form = useForm({
    name: '',
    description: '',
    is_active: true,
    // Option Items
    option_items: [
        {
            name: '',
            description: '',
            extra_price: 0,
            is_default: true,
            position: 1
        }
    ],
    // Item settings
    items: [
        // example:
        // { item_id: 1, is_required: false, min_selection: 1, max_selection: 1, multiple_selection: false }
    ]
})




function createOption() {
    // Validate min <= max
    const invalidItem = form.items.find(i => Number(i.min_selection || 0) > Number(i.max_selection || 0))
    if (invalidItem) {
        alert('Minimum selection cannot be greater than maximum selection.')
        return
    }

    // Normalize booleans and compute is_required
    form.is_active = !!form.is_active
    form.multiple_selection = !!form.multiple_selection

    form.option_items = form.option_items.map((item, index) => ({
        ...item,
        is_default: !!item.is_default,
        extra_price: Number(item.extra_price || 0),
        position: index + 1,
    }))

    form.items = form.items.map(item => {
        const min = Number(form.min_selection || 0)
        const max = Number(form.max_selection || 1)

        return {
            item_id: Number(item.item_id),
            is_required: min > 0 && min === max,
            min_selection: min,
            max_selection: max,
            multiple_selection: !!form.multiple_selection,
        }
    })


    form.post('/options', {
        onSuccess: () => {
            form.reset()
            form.option_items = [{
                name: '', description: '', extra_price: 0, is_default: false, position: 1,
            }]
            router.reload({ only: ['options'] })
        },
    })
}

const search = ref('')
const filteredItems = computed(() => {
    if (!search.value.trim()) return categories

    const lowerSearch = search.value.toLowerCase()

    const results = categories
        .map(category => ({
            ...category,
            items: category.items.filter(item =>
                item.name.toLowerCase().includes(lowerSearch)
            )
        }))
        .filter(cat => cat.items.length > 0)

    // Always ensure first category is expanded if it's still in the list
    if (results.length && !openCategories.value.includes(results[0].id)) {
        openCategories.value = [results[0].id]
    }

    return results
})
function toggleItem(itemId) {
    const exists = form.items.find(i => i.item_id === itemId)
    if (exists) {
        form.items = form.items.filter(i => i.item_id !== itemId)
    } else {
        form.items.push({
            item_id: itemId,
            is_required: false,
            min_selection: 0,
            max_selection: 1,
            multiple_selection: false,
        })
    }
}
function isItemSelected(itemId) {
    return form.items.some(i => i.item_id === itemId)
}
function addOptionItem() {
    const index = form.option_items.length
    form.option_items.push({
        name: '',
        description: '',
        extra_price: 0,
        is_default: index === 0, // only first one is default
        position: index + 1,
    })
}
function removeOptionItem(index) {
    form.option_items.splice(index, 1)

    // Recalculate positions
    form.option_items.forEach((item, i) => {
        item.position = i + 1
        item.is_default = i === 0 // First becomes default
    })
}
const openCategories = ref([])
onMounted(() => {
    if (categories.length > 0) {
        openCategories.value = [categories[0].id]
    }
})
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex flex-row items-center justify-between">
                <h1 class="text-2xl font-bold">Create Options</h1>
                <Link href="/options">
                <Button>
                    <Icon name="circleArrowLeft" /> Back
                </Button>
                </Link>
            </div>
            <hr class="my-6">
            <!-- Create Form -->
            <form @submit.prevent="createOption" class="relative">
                <div class="flex flex-row gap-4">
                    <div class="shadow rounded-xl p-2 w-[55%]">
                        <!--Options Settings-->
                        <div class="p-2">
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Name</label>
                                <input v-model="form.name" class="w-full border p-2 rounded" />
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Description</label>
                                <textarea v-model="form.description" class="w-full border p-2 rounded"></textarea>
                            </div>

                            <div class="mb-3 flex flex-col gap-2">
                                <div class="flex flex-row justify-between items-center">
                                    <div>
                                        <h1 class="text-md font-semibold">Minimum quantity</h1>
                                        <p class="text-xs">What’s the minimum number of options a customer must select?
                                        </p>
                                    </div>
                                    <input type="number" v-model="form.min_selection" class="border p-1 w-16 rounded"
                                        placeholder="-" />
                                </div>
                                <div class="flex flex-row justify-between items-center">
                                    <div>
                                        <h1 class="text-md font-semibold">Maximum quantity</h1>
                                        <p class="text-xs">
                                            What’s the maximum number of options a customer can select?
                                        </p>
                                    </div>
                                    <input type="number" v-model="form.max_selection" class="border p-1 w-16 rounded"
                                        placeholder="-" />
                                </div>
                            </div>
                            <div class="flex flex-row justify-between mb-3">

                                <Checkbox :model-value="form.is_active"
                                    @update:model-value="form.is_active = $event" label="Active" />
                                <Checkbox :model-value="form.multiple_selection"
                                    @update:model-value="form.multiple_selection = $event"
                                    label="Can User Select One Item Multiple time?" />

                            </div>
                        </div>
                        <!-- Option Items -->
                        <div class="">
                            <h3 class="text-lg font-bold">Option Items</h3>

                            <div v-for="(item, index) in form.option_items" :key="index"
                                class="p-2 my-2 border rounded-lg flex flex-row gap-2 items-center justify-between">
                                <div class="flex flex-row gap-2 items-center justify-start">
                                    <div class="w-[40%]">
                                        <label class="block text-sm font-medium">Name</label>
                                        <input v-model="item.name" class="w-full border p-1.5 rounded" />
                                    </div>
                                    <div class="w-[20%]">
                                        <label class="block text-sm font-medium">Extra Price</label>
                                        <input type="number" v-model="item.extra_price"
                                            class="w-full border p-1.5 rounded" />
                                    </div>
                                    <div class="w-[40%] flex  flex-col">
                                        <p class="text-sm font-medium">Description</p>
                                        <textarea v-model="item.description" rows="1"
                                            class="w-full border p-1.5 rounded"></textarea>
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

                    <div class=" w-[45%] shadow rounded-xl p-4">
                        <div class="flex flex-row items-center justify-between">
                            <div>
                                <h3 class="text-md font-bold">Add to items</h3>
                                <p class="text-xs">Select which items these Options will be added to.</p>
                            </div>

                            <Button type="submit" class=" px-4 py-2 rounded ">
                                <Icon name="hardDriveDownload" /> Save
                            </Button>

                        </div>
                        <hr class="my-3">
                        <div>
                            <input type="text" v-model="search" placeholder="Search items..."
                                class="w-full border px-3 py-2 rounded mb-4" />

                            <AccordionRoot type="multiple" v-model="openCategories" class="w-full rounded-lg border">
                                <AccordionItem v-for="category in filteredItems" :key="category.id"
                                    :value="category.id">
                                    <AccordionHeader>
                                        <AccordionTrigger
                                            class="text-left w-full py-2 px-4 rounded border-b font-medium flex items-center justify-between">
                                            <span>{{ category.name }}</span>
                                            <Icon name="arrowDown" :class="[
                                                'transition-transform duration-400',
                                                openCategories.includes(category.id) ? 'rotate-180' : ''
                                            ]" />
                                        </AccordionTrigger>

                                    </AccordionHeader>
                                    <AccordionContent class="px-4 py-2 border-b rounded-b-lg">
                                        <div class="grid grid-cols-2 gap-2">
                                            <label v-for="item in category.items" :key="item.id"
                                                class="inline-flex items-center gap-2 text-xs">
                                                <input type="checkbox" :checked="isItemSelected(item.id)"
                                                    @change="toggleItem(item.id)" class="form-checkbox " />
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
