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
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard

            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">

                    <div class="p-6 text-gray-900">
                        Hello {{ current_user.name }}!
                    </div>

                    <div class="p-6 text-gray-900">
                        It is currently {{ currentDate }}. {{ currentTime }}.
                    </div>

                    <div class="p-6 text-gray-900">
                        You currently have {{ pendingTasks.length }} pending tasks and {{ doneTasks.length }} done tasks.
                    </div>

                    <div class="p-6 text-gray-900">
                        There are {{ due_tasks.length }} tasks due in 7 days and {{ overdue_tasks.length }} tasks overdue.
                    </div>
                </div>
            </div>
        </div>

        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Pending Tasks</h2>

                        <div class="mt-2 grid grid-cols-2 gap-4">
                            <template v-if="pendingTasks.length > 0">
                                <div
                                    v-for="(task, index) in pendingTasks"
                                    :key="task.id"
                                    class="bg-white shadow-sm rounded-lg divide-y"
                                    :class="{ 'col-start-2': index % 2 === 1 }"
                                >
                                    <Task
                                        :task="task"
                                        :users="users"
                                        :current_user="current_user"
                                        :due="task.due"
                                    />
                                </div>
                            </template>

                            <template v-else>
                                <div class="py-4 text-center text-gray-500 col-span-2">
                                    No pending tasks
                                </div>
                            </template>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Done Tasks</h2>

                        <div class="mt-2 grid grid-cols-2 gap-4">
                            <template v-if="doneTasks.length > 0">
                                <div
                                    v-for="(task, index) in doneTasks"
                                    :key="task.id"
                                    class="bg-white shadow-sm rounded-lg divide-y"
                                    :class="{ 'col-start-2': index % 2 === 1 }"
                                >
                                    <Task
                                        :task="task"
                                        :users="users"
                                        :current_user="current_user"
                                        :due="task.due"
                                    />
                                </div>
                            </template>
                            <template v-else>
                                <div class="py-4 text-center text-gray-500 col-span-2">
                                    No done tasks
                                </div>
                            </template>
                        </div>
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
                return (task.user_id === this.current_user.id || task.user.some(user => user.id === this.current_user.id))
                    && task.status !== 'Archived'; // Exclude archived tasks
            });
        },
        pendingTasks() {
            return this.filteredTasks.filter(task => task.status === 'Pending');
        },
        doneTasks() {
            return this.filteredTasks.filter(task => task.status === 'Done');
        },
        due_tasks() {
            const today = new Date();
            const fiveDaysFromNow = new Date();
            fiveDaysFromNow.setDate(today.getDate() + 7);

            return this.pendingTasks.filter(task => {
                const taskDueDate = new Date(task.due);
                return taskDueDate >= today && taskDueDate <= fiveDaysFromNow;
            });
        },

        overdue_tasks() {
            const today = new Date();

            return this.pendingTasks.filter(task => {
                const taskDueDate = new Date(task.due);
                return taskDueDate < today;
            });
        }
    },

    data() {
        return {
            currentDate: new Date().toLocaleDateString(),
            currentTime: new Date().toLocaleTimeString()
        };
    },
    mounted() {
        this.updateTime();
        this.timer = setInterval(this.updateTime, 1000);
    },
    beforeUnmount() {
        clearInterval(this.timer);
    },
    methods: {
        updateTime() {
            this.currentDate = new Date().toLocaleDateString();
            this.currentTime = new Date().toLocaleTimeString();
        }
    }
};
</script>
