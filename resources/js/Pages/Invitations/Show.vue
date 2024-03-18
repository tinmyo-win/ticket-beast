<template>
    <div class="w-full flex min-h-screen items-center">
        <form @submit.prevent="submit" class=" border p-5 max-w-md mx-auto flex-1">
            <h2 class="text-xl text-center py-5">Join TicketBeast</h2>
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                    email</label>
                <input v-model="form.email" type="email" id="email"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    placeholder="name@flowbite.com" required />
                <div v-if="form.errors.email" v-text="form.errors.email" class="text-xs text-red-700" />
            </div>
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                    password</label>
                <input v-model="form.password" type="password" id="password"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    required />
                <div v-if="form.errors.password" v-text="form.errors.password" class="text-xs text-red-700" />
            </div>
            <button type="submit"
                class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create
                Account</button>
        </form>
    </div>

</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const form = useForm({
    email: '',
    password: '',
})
const submit = () => {
    form.post(route('promoters.register', { invitation_code: invitationId.value}));
}

const invitationId = ref('');

const extractInvitationId = () => {
    const path = window.location.pathname;
    const parts = path.split('/');
    if (parts.length >= 3) {
        invitationId.value = parts[2]; // Assuming the invitation ID is the third part of the path
    } else {
        console.error('Invalid URL format');
    }
};

onMounted(extractInvitationId);
</script>