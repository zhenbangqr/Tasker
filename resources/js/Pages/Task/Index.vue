<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';
import Task from '@/Components/Task.vue';

defineProps(['tasks', 'users', 'current_user']);

const form = useForm({
    task: '',
    taskdescription: '',
    assigned_to: []
});
</script>

<template>
    <Head title="Task" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
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

                <InputError :message="form.errors.task" class="mt-2" />
                <PrimaryButton class="mt-4">Add Task</PrimaryButton>
            </form>

            <div class="mt-6 grid grid-cols-2 gap-4">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Pending Tasks</h2>

                    <div class="mt-2 bg-white shadow-sm rounded-lg divide-y">
                        <template v-if="pendingTasks.length > 0">
                            <Task
                                v-for="task in pendingTasks"
                                :key="task.id"
                                :task="task"
                                :users="users"
                                :current_user="current_user"
                            />
                        </template>
                        <template v-else>
                            <div class="py-4 text-center text-gray-500">
                                No pending tasks
                            </div>
                        </template>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-medium text-gray-900">Done Tasks</h2>

                    <div class="mt-2 bg-white shadow-sm rounded-lg divide-y">
                        <template v-if="doneTasks.length > 0">
                            <Task
                                v-for="task in doneTasks"
                                :key="task.id"
                                :task="task"
                                :users="users"
                                :current_user="current_user"
                            />
                        </template>
                        <template v-else>
                            <div class="py-4 text-center text-gray-500">
                                No done tasks
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    computed: {
        filteredTasks() {
            return this.tasks.filter(task => {
                return task.user_id === this.current_user || task.user.some(user => user.id === this.current_user);
            });
        },
        pendingTasks() {
            return this.filteredTasks.filter(task => task.status !== 'Done');
        },
        doneTasks() {
            return this.filteredTasks.filter(task => task.status === 'Done');
        }
    }
};
</script>
