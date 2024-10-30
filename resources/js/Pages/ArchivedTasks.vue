<script setup>
import { Head } from '@inertiajs/vue3';
import Task from '@/Components/Task.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps(['tasks', 'users', 'current_user']);

</script>

<template>
    <Head title="Archived Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Archived Tasks
            </h2>
        </template>

        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-4 lg:px-6">

                <div class="mt-2 grid grid-cols-2 gap-4">

                    <template v-if="archivedTasks.length > 0">
                        <div
                            v-for="(task, index) in archivedTasks"
                            :key="task.id"
                            class="mt-2 bg-white shadow-sm rounded-lg divide-y"
                            :class="{ 'col-start-2': index % 2 === 1 }"
                        >
                            <Task
                                :task="task"
                                :users="users"
                                :current_user="current_user"
                            />
                        </div>
                    </template>
                    <template v-else>
                        <div class="py-4 text-center text-gray-500 col-span-2">
                            No archived tasks
                        </div>
                    </template>

                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script>
export default {
    computed: {
        archivedTasks() {
            return this.tasks.filter(task => {
                return task.user_id === this.current_user.id || task.user.some(user => user.id === this.current_user.id);
            }).filter(task => task.status === 'Archived');
        }
    }
};
</script>
