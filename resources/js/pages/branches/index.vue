<script lang="ts" setup>
import Heading from '@/components/Heading.vue';
import Icon from '@/components/Icon.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue';
import { useAccess } from '@/composables/useAccess'
const { can } = useAccess('branches')
import DeleteModal from '@/components/DeleteModal.vue'
import { useToast } from '@/composables/useToast'
import Dropdown from '@/components/Dropdown.vue';
const { showToast } = useToast()


const props = defineProps({ branches: Object, filters: Object })
const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Branches', href: '/branches' },
]

const showDeleteModal = ref(false)
const deleteUrl = ref('')
const deleteName = ref('')
function openDeleteModal(branch) {
    deleteUrl.value = `/branches/${branch.id}`
    deleteName.value = branch.name
    showDeleteModal.value = true
}

function handleDeleted() {
    showToast('Success', 'Branch deleted successfully!', 'success')
}
function handleError() {
    showToast('Failed', 'Failed to delete branch. Please try again.', 'error')
}

const search = ref(props.filters.search || '')
const perPage = ref(props.filters.per_page || 10)

// Watch search and perPage together
watch([search, perPage], ([newSearch, newPerPage]) => {
    router.get('/branches', { search: newSearch, per_page: newPerPage }, { preserveState: true })
})

function applySearch() {
    router.get('/branches', { search: search.value, per_page: perPage.value }, { preserveState: true })
}



async function downloadCsv() {
    try {
        const response = await fetch('/branches/export');

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'branches.csv';
        a.click();
        window.URL.revokeObjectURL(url);

        showToast('Success', 'Branches exported successfully!', 'success');
    } catch (error) {
        console.error(error);
        showToast('Failed', 'Failed to export branches. Please try again.', 'error');
    }
}

</script>

<template>

    <Head title="All Branches" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="flex justify-between items-center flex-row mb-4">
                <Heading title="Branches" />
                <Link v-if="can('create')" href="/branches/create" class="">
                <Button class="h-full">
                    <Icon name="plus" />
                    Add Branch
                </Button>
                </Link>
            </div>
            <div class="flex justify-between items-center">
                <div class="flex justify-start items-center gap-2">
                    <Input v-model="search" type="text" placeholder="Search customers..."
                        class="border rounded px-3 w-64" />
                    <Button @click="applySearch">
                        <Icon name="search" />
                    </Button>
                </div>
                <div class="flex justify-end gap-2">
                    <Dropdown v-model="perPage" :options="[
                        { label: '10 per page', value: 10 },
                        { label: '25 per page', value: 25 },
                        { label: '50 per page', value: 50 },
                        { label: '100 per page', value: 100 }
                    ]" />
                    <Link href="/branches/print">
                    <Button v-if="can('print')" @click="printBranches">
                        <Icon name="printer" /> Print All Data
                    </Button>
                    </Link>
                    <Button v-if="can('export')" @click="downloadCsv">
                        <Icon name="sheet" /> Export as Excel
                    </Button>
                </div>
            </div>

            <table class="w-full mt-4 border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-start">ID</th>
                        <th class="p-2 text-start">Image</th>
                        <th class="p-2 text-start">Name</th>
                        <th class="p-2 text-start">Location</th>
                        <th class="p-2 text-start">Phone</th>
                        <th class="p-2 text-start">Status</th>
                        <th class="p-2 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(branch, index) in branches.data" :key="branch.id" class="border-t">
                        <td class="p-2"> {{ (branches.current_page - 1) * branches.per_page + (index + 1) }}</td>
                        <td class="p-2">
                            <img :src="branch.image" alt="" class="w-10 h-10 rounded-lg aspect-square object-cover">
                        </td>
                        <td class="p-2">{{ branch.name }}</td>
                        <td class="p-2">{{ branch.location }}</td>
                        <td class="p-2">{{ branch.phone }}</td>
                        <td class="p-2">{{ branch.status ? 'Active' : 'Inactive' }}</td>
                        <td class="p-2 gap-2 flex items-center justify-end">
                            <Link v-if="can('edit')" :href="`/branches/${branch.id}/edit`" class="">
                            <Button size="sm">
                                <Icon name="edit" />
                            </Button>
                            </Link>
                            <Button v-if="can('delete')" variant="destructive" size="sm"
                                @click="openDeleteModal(branch)">
                                <Icon name="trash" />
                            </Button>
                            <p class="text-sm" v-if="!can('edit') && !can('delete')">No Actions</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Pagination -->
            <div class="p-4 flex justify-center">
                <Component :is="branches.links.length ? 'nav' : 'div'" class="flex gap-1">
                    <template v-for="(link, key) in branches.links" :key="key">
                        <Button v-if="link.url" @click="router.visit(link.url)" v-html="link.label" :class="[
                            'px-3 py-1 border rounded',
                            link.active ? 'text-white' : 'bg-white text-gray-700 hover:bg-gray-100'
                        ]" />
                        <span v-else v-html="link.label" class="px-3 py-1 text-gray-400" />
                    </template>
                </Component>
            </div>
            <!-- Delete Modal -->
            <DeleteModal v-model="showDeleteModal" :delete-url="deleteUrl" :item-name="deleteName"
                @deleted="handleDeleted" @error="handleError" />
        </div>
    </AppLayout>
</template>
