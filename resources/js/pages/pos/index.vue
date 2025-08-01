<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import {
    Dialog,
    DialogOverlay,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
} from '@/components/ui/dialog'
import Button from '@/components/ui/button/Button.vue'
import Icon from '@/components/Icon.vue'

const props = defineProps({
    items: Array,
    categories: Array,
    branch: Object,
    options: Array
})
const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Pos', href: '/pos' },
]
const cart = ref([])
const selectedCategory = ref(null)
const search = ref('')
const showOptionModal = ref(false)
const selectedItem = ref(null)
const selectedOptions = ref({})

// 💡 Open modal and reset selected options
function openOptionSelector(item) {
    selectedItem.value = item
    selectedOptions.value = {}
    showOptionModal.value = true
}

// ✅ Confirm options with validations
function confirmOptions() {
    for (const opt of selectedItem.value.options) {
        const val = selectedOptions.value[opt.id] || (opt.pivot.multiple_selection ? [] : null)
        const count = Array.isArray(val) ? val.length : val ? 1 : 0

        if (opt.pivot.is_required && count < opt.pivot.min_selection) {
            alert(`You must select at least ${opt.pivot.min_selection} for ${opt.name}`)
            return
        }

        if (opt.pivot.max_selection && count > opt.pivot.max_selection) {
            alert(`You can select maximum ${opt.pivot.max_selection} for ${opt.name}`)
            return
        }
    }

    const itemToAdd = {
        ...selectedItem.value,
        quantity: 1,
        selected_options: JSON.parse(JSON.stringify(selectedOptions.value)),
        base_price: Number(selectedItem.value.price),
        price: 0,
        extra_price: 0,
    }

    updateCartItemPrice(itemToAdd)
    cart.value.push(itemToAdd)
    showOptionModal.value = false
}

// ✅ Dynamically compute the item price
function updateCartItemPrice(item) {
    const base = Number(item.base_price || item.price || 0)
    let extra = 0

    if (item.selected_options) {
        for (const [optId, optItemIds] of Object.entries(item.selected_options)) {
            const opt = item.options.find(o => o.id == optId)
            if (!opt) continue

            const ids = Array.isArray(optItemIds) ? optItemIds : [optItemIds]
            for (const id of ids) {
                const optionItem = opt.option_items.find(oi => oi.id === id)
                if (optionItem) {
                    extra += Number(optionItem.extra_price || 0)
                }
            }
        }
    }

    item.extra_price = extra
    item.price = base + extra
}

// ✅ Toggle checkbox with validation
function toggleCheckbox(optId, itemId, opt) {
    const selected = selectedOptions.value[optId] || []

    if (selected.includes(itemId)) {
        if (selected.length <= opt.pivot.min_selection) {
            alert(`You must select at least ${opt.pivot.min_selection} for ${opt.name}`)
            return
        }
        selectedOptions.value[optId] = selected.filter(i => i !== itemId)
    } else {
        if (selected.length >= opt.pivot.max_selection) {
            alert(`Maximum ${opt.pivot.max_selection} options allowed for ${opt.name}`)
            return
        }
        selectedOptions.value[optId] = [...selected, itemId]
    }
}

// ✅ Utility to get option item label
function getOptionLabel(item, optId, itemId, qty = 1) {
    const opt = item.options.find(o => o.id == optId)
    const oi = opt?.option_items.find(oi => oi.id == itemId)
    if (!oi) return ''
    return `${oi.name} ×${qty}`
}

function getOptionPrice(item, optId, itemId, qty = 1) {
    const opt = item.options.find(o => o.id == optId)
    const oi = opt?.option_items.find(oi => oi.id == itemId)
    if (!oi) return '0.00'
    const extra = Number(oi.extra_price || 0) * qty
    return extra.toFixed(2)
}


// ✅ Remove item from cart
function removeFromCart(id) {
    cart.value = cart.value.filter(i => i.id !== id)
}

// ✅ Computed total
const total = computed(() =>
    cart.value.reduce((sum, i) => sum + Number(i.price) * i.quantity, 0).toFixed(2)
)

// ✅ Filtered items by search and category
const filteredItems = computed(() =>
    props.items.filter(i =>
        (!selectedCategory.value || i.category_id === selectedCategory.value) &&
        i.name.toLowerCase().includes(search.value.toLowerCase())
    )
)

// ✅ Inertia form for order submission
const form = useForm({
    items: [],
    payment_method: 'cash',
    branch_id: props.branch?.id || null
})

// ✅ Submit order
function submitOrder() {
    form.items = cart.value.map(i => ({
        id: i.id,
        quantity: i.quantity,
        price: i.price,
        selected_options: i.selected_options || {},
    }))

    form.post('/pos/order', {
        preserveScroll: true,
        onSuccess: () => {
            cart.value = []
        }
    })
}
</script>


<template>
    <!-- Option Selection Modal -->
    <Dialog v-model:open="showOptionModal">
        <DialogOverlay class="fixed inset-0 bg-gray-900/10 z-40" />

        <DialogContent
            class="fixed z-50 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] max-h-[80vh] bg-white p-6 rounded shadow-lg overflow-y-auto">
            <DialogHeader>
                <DialogTitle class="text-xl font-bold">Choose Options for {{ selectedItem?.name }}</DialogTitle>
                <DialogDescription class="text-sm text-gray-500 mb-4">
                    Select required and optional additions.
                </DialogDescription>
            </DialogHeader>

            <div v-if="selectedItem">
                <div v-for="opt in selectedItem.options" :key="opt.id" class="mb-4">
                    <label class="font-semibold block mb-2">
                        {{ opt.name }}
                        <span class="text-xs text-gray-500">(min: {{ opt.pivot.min_selection }}, max: {{
                            opt.pivot.max_selection }})</span>
                    </label>

                    <!-- Multiple selection -->
                    <div v-if="opt.pivot.multiple_selection">
                        <div v-for="optItem in opt.option_items" :key="optItem.id" class="flex items-center gap-2 mb-1">
                            <label><input type="checkbox"
                                    :checked="(selectedOptions[opt.id] || []).includes(optItem.id)" :disabled="!selectedOptions[opt.id]?.includes(optItem.id) &&
                                        (selectedOptions[opt.id]?.length || 0) >= opt.pivot.max_selection"
                                    @change="toggleCheckbox(opt.id, optItem.id, opt)" class="" />
                                {{ optItem.name }} <span class="text-gray-500">+₹{{ optItem.extra_price
                                }}</span></label>
                        </div>
                    </div>

                    <!-- Single selection -->
                    <div v-else>
                        <div v-for="optItem in opt.option_items" :key="optItem.id" class="flex items-center gap-2 mb-1">
                            <label :for="`opt-${opt.id}-${optItem.id}`">
                                <input type="radio" :id="`opt-${opt.id}-${optItem.id}`" :name="`opt-${opt.id}`"
                                    :value="optItem.id" v-model="selectedOptions[opt.id]" class="" />

                                {{ optItem.name }} <span class="text-gray-500">+₹{{ optItem.extra_price }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <DialogFooter class="mt-6 flex justify-end gap-2">
                <Button @click="showOptionModal = false" class="">Cancel</Button>
                <Button @click="confirmOptions" class="">Add
                    to Cart</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>


    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 py-0 h-full">
            <div class="grid grid-cols-12 gap-2 h-full">
                <!-- Categories -->
                <div class="col-span-2 p-2 pr-4 pl-0 border-r h-full">
                    <h2 class="text-lg font-semibold mb-2">Categories</h2>
                    <hr class="my-2">
                    <ul class="space-y-1">
                        <ul class="space-y-1">
                            <!-- All -->
                            <li @click="selectedCategory = null" :class="[
                                'cursor-pointer p-1 rounded text-sm',
                                selectedCategory === null ? 'bg-gray-100 font-medium' : ''
                            ]">
                                All
                            </li>

                            <!-- Categories -->
                            <li v-for="cat in props.categories" :key="cat.id" @click="selectedCategory = cat.id" :class="[
                                'cursor-pointer hover:bg-gray-100 p-1 rounded text-sm',
                                selectedCategory === cat.id ? 'bg-gray-100 font-medium' : ''
                            ]">
                                {{ cat.name }}
                            </li>
                        </ul>

                    </ul>
                </div>

                <!-- Items -->
                <div class="col-span-7 p-2">
                    <!-- Search -->
                    <div class="w-full mb-2">
                        <input v-model="search" placeholder="Search items..."
                            class="w-full px-4 py-2 border rounded shadow-sm" />
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div v-for="item in filteredItems" :key="item.id"
                            class="p-4 border rounded shadow-sm hover:bg-gray-100 cursor-pointer"
                            @click="openOptionSelector(item)">
                            <h3 class="font-bold">{{ item.name }}</h3>
                            <p>₹{{ item.price }}</p>
                        </div>
                    </div>
                </div>

                <!-- Cart Section -->
                <div class="col-span-3 pl-4 py-4 space-y-4 border-l flex flex-col justify-between">
                    <div>
                        <!-- Branch details -->
                        <div>
                            <h3 class="font-semibold text-lg">Branch</h3>
                            <div class="bg-gray-100 p-2 pl-4 rounded-xl">
                                <p class="text-sm">{{ props.branch.name }}</p>
                                <p class="text-xs">{{ props.branch.location }}</p>
                            </div>
                        </div>

                        <!-- Cart Items -->
                        <div>
                            <h3 class="font-semibold text-lg">Selected Items</h3>
                            <ul class="space-y-2">
                                <li v-for="i in cart" :key="i.id" class="p-2 border rounded bg-gray-50">
                                    <!-- Top: Item name, qty, price, remove -->
                                    <div class="flex justify-between items-center">
                                        <div class="w-full flex justify-end items-center">
                                            <Button size="xs" variant="" @click="removeFromCart(i.id)" class="">
                                                <Icon name="x" />
                                            </Button>
                                        </div>
                                    </div>
                                    <div class="font-semibold text-sm flex justify-between">
                                        <span>
                                            {{ i.name }} x{{ i.quantity }}
                                        </span>
                                        <span>{{ (i.base_price * i.quantity).toFixed(2) }}৳</span>
                                    </div>

                                    <!-- Options -->
                                    <div v-if="i.selected_options" class="text-sm text-gray-700 pl-2 space-y-1 pb-1">
                                        <div v-for="(optItemIds, optId) in i.selected_options" :key="optId">
                                            <div v-if="Array.isArray(optItemIds)">
                                                <div v-for="id in optItemIds" :key="id">
                                                    <div class="flex justify-between text-xs">
                                                        <span class="">+ {{ getOptionLabel(i, optId, id,
                                                            i.quantity) }}</span>
                                                        <span class="">{{ getOptionPrice(i, optId, id,
                                                            i.quantity) }}৳</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center text-xs pt-1 border-t">
                                        <div class="flex items-center justify-start gap-2">
                                            <Button size="xs" variant="ghost"
                                                @click="() => { i.quantity > 1 ? i.quantity-- : removeFromCart(i.id); updateCartItemPrice(i) }">
                                                <Icon name="minus" />
                                            </Button>
                                            <span>{{ i.quantity }}</span>
                                            <Button size="xs" variant="ghost"
                                                @click="() => { i.quantity++; updateCartItemPrice(i) }">
                                                <Icon name="plus" />
                                            </Button>
                                        </div>
                                        <!-- Subtotal -->
                                        <div class="text-right font-medium">
                                            Subtotal: {{ (i.price * i.quantity).toFixed(2) }}৳
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>


                    <div class="flex justify-between gap-2 flex-col h-fit">
                        <!-- Total Calculation -->
                        <div class="text-right">
                            <h4 class="text-xl font-bold">Total: {{ total }}৳
                            </h4>
                        </div>

                        <!-- Payment -->
                        <div>
                            <label class="block mb-1 font-medium">Payment Method</label>
                            <div class="flex gap-2 mt-2">
                                <Button v-for="method in [
                                    { value: 'cash', label: 'Cash' },
                                    { value: 'card', label: 'Card' },
                                    { value: 'mobile_banking', label: 'Mobile Bank' }
                                ]" :key="method.value" @click="form.payment_method = method.value" :class="[
                                    'rounded border text-sm',
                                    form.payment_method === method.value
                                        ? 'bg-gray-950 text-white'
                                        : 'bg-white text-gray-800 border-gray-300 hover:bg-gray-100'
                                ]">
                                    {{ method.label }}
                                </Button>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div>
                            <Button @click="submitOrder" class="w-full">
                                Make an Order
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
