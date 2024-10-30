<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps(['task', 'users', 'current_user']);

const form = useForm({
    task: props.task.task,
    taskdescription: props.task.taskdescription,
    assigned_to: props.task.user.map(user => user.id),
});

const editing = ref(false);
</script>

<template>
    <div class="p-6 flex space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4M12 2a10 10 0 100 20 10 10 0 000-20z"/>
        </svg>
        <div class="flex-1">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-gray-800">
                        Added by
                        <span v-if="task.user && task.user.length > 0">
                            {{ task.user[0].name }}
                        </span>
                        <span v-else>
                            Unknown
                        </span>
                    </span>
                    <small v-if="task.created_at !== task.updated_at" class="text-sm text-gray-600"> &middot; edited</small>
                    <small class="ml-2 text-sm text-gray-600">{{ new Date(task.created_at).toLocaleString() }}</small>
                </div>

                <Dropdown>
                    <template #trigger>
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </button>
                    </template>
                    <template #content>
                        <button class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out" @click="editing = true">
                            Edit
                        </button>
                        <button class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out" @click="form.delete(route('tasks.destroy', task.id))">
                            Delete
                        </button>
                    </template>
                </Dropdown>
            </div>

            <form v-if="editing" @submit.prevent="form.put(route('tasks.update', task.id), { onSuccess: () => editing = false })">
                <div>
                    <label for="task" class="block text-sm font-medium text-gray-700">Title:</label>
                    <input type="text" id="task" v-model="form.task" class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                    <InputError :message="form.errors.task" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label for="taskdescription" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea id="taskdescription" v-model="form.taskdescription" class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                    <InputError :message="form.errors.taskdescription" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label class="block font-medium text-sm text-gray-700">Collaborators:</label>
                    <div class="mt-2" v-for="user in users" :key="user.id">
                        <input v-if="user.id !== current_user" type="checkbox" :id="`user-${user.id}`" :value="user.id" v-model="form.assigned_to">
                        <label v-if="user.id !== current_user" :for="`user-${user.id}`" class="ml-2">{{ user.name }}</label>
                    </div>
                </div>

                <div class="space-x-2">
                    <PrimaryButton class="mt-4">Save</PrimaryButton>
                    <button class="mt-4" @click="editing = false; form.reset(); form.clearErrors()">Cancel</button>
                </div>
            </form>

            <div v-else>
                <p class="mt-4 text-lg text-gray-900 font-bold">Title:</p>
                <p class="mt-4 text-base text-gray-900">{{ task.task }}</p>

                <p class="mt-4 text-lg text-gray-900 font-bold">Description:</p>
                <p class="mt-4 text-base text-gray-900">{{ task.taskdescription }}</p>

                <p class="mt-4 text-lg text-gray-900 font-bold">Collaborators:</p>
                <ul v-if="task.user.length > 0" class="mt-2">
                    <li v-for="collaborator in task.user" :key="collaborator.id">
                        {{ collaborator.name }}
                    </li>
                </ul>
                <p v-else>No collaborators</p>
            </div>

            <div class="mt-4">
                <p class="text-lg text-gray-900 font-bold">Status:</p>
                <p class="text-base text-gray-900">{{ task.status }}</p>
            </div>

            <div class="mt-4">
                <PrimaryButton @click="form.put(route('tasks.updateStatus', task.id))">
                    {{ task.status === 'Pending' ? 'Mark as Done' : 'Mark as Pending' }}
                </PrimaryButton>
            </div>

        </div>
    </div>
</template>
