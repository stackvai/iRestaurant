<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 max-w-xl mx-auto space-y-4">
      <h1 class="text-2xl font-bold">Edit Item</h1>
      <form @submit.prevent="submit">
        <Input v-model="form.name" label="Item Name" required />
        <Input v-model="form.description" label="Description" />
        <Input v-model="form.image" label="Image URL" />
        <Input v-model="form.price" label="Price" type="number" required />
        <label class="flex items-center gap-2 mt-2">
          <input type="checkbox" v-model="form.is_active" />
          <span>Active</span>
        </label>

        <div class="mt-4 flex justify-end">
          <Button type="submit">Update</Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { reactive } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Input from '@/components/ui/input/Input.vue'
import Button from '@/components/ui/button/Button.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  menu: Object,
  category: Object,
  item: Object
})

const breadcrumbs = [
  { title: 'Menus', href: '/imenus' },
  { title: props.menu.name, href: `/imenus/${props.menu.id}` },
  { title: props.category.name, href: `/imenus/${props.menu.id}/categories/${props.category.id}/edit` },
  { title: props.item.name, href: `/imenus/${props.menu.id}/categories/${props.category.id}/items/${props.item.id}` },
]

const form = reactive({
  name: props.item.name,
  description: props.item.description,
  image: props.item.image,
  price: props.item.price,
  is_active: !!props.item.is_active,
})

const submit = () => {
  router.put(`/imenus/${props.menu.id}/categories/${props.category.id}/items/${props.item.id}`, form)
}
</script>
