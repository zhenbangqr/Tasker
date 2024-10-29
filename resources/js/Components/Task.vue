<script setup>
defineProps(['task', 'users']);
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
                    <small class="ml-2 text-sm text-gray-600">{{ new Date(task.created_at).toLocaleString() }}</small>
                </div>
            </div>

            <div>
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

        </div>

    </div>
</template>
