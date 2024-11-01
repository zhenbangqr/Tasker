<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';

defineProps(['tasks', 'users', 'current_user', 'due']);

const form = useForm({
    task: '',
    taskdescription: '',
    assigned_to: [],
    due:'',
});
</script>

<template>
    <Head title="Add New Task" />

    <AuthenticatedLayout>

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Tasks
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:p-4 lg:p-6">

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="pt-1 pb-4 font-semibold text-xl text-gray-800 leading-tight">
                        Create New Task
                    </h2>

                    <form @submit.prevent="form.post(route('tasks.store'), { onSuccess: () => form.reset() })">
                            <textarea
                                v-model="form.task"
                                placeholder="What task today?"
                                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            ></textarea>

                        <textarea
                            v-model="form.taskdescription"
                            placeholder="Description"
                            class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                        ></textarea>

                        <div class="mt-4">
                            <label class="block font-medium text-sm text-gray-700">Join task with:</label>
                            <div class="mt-2" v-for="user in users" :key="user.id">
                                <input v-if="user.id !== current_user" type="checkbox" :id="`user-${user.id}`" :value="user.id" v-model="form.assigned_to">
                                <label v-if="user.id !== current_user" :for="`user-${user.id}`" class="ml-2">{{ user.name }}</label>
                            </div>
                        </div>


                        <div class="mt-4">
                            <label class="block font-medium text-sm text-gray-700">Due Date:</label>
                            <input
                                type="date"
                                v-model="form.due"
                                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            />
                        </div>

                        <InputError :message="form.errors.task" class="mt-2" />
                        <PrimaryButton class="mt-4">Add Task</PrimaryButton>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>

</script>
