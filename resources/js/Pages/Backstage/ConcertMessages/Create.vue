<template>
    <div class="min-h-screen flex flex-col">
        <header class="flex justify-between bg-black text-white items-center">
            <div class="ml-10">
                <img src="/img/checkout-icon.png" style="height: 5rem;" />
            </div>
            <div class="mr-10">
                Log out
            </div>
        </header>
        <main class="flex-grow">
            <div class="flex justify-between px-10 py-6 border-b">
                <h1 class="text-xl">{{ concert?.title }} <span class="text-gray-500 px-3"> / {{ concert?.formatted_date
                        }}</span>
                </h1>
                <div class="flex">
                    <h1 class="text-xl font-bold">Orders</h1>
                    <h1 class="text-xl pl-5">Message
                        Attendees</h1>
                </div>
            </div>

            <div>
                <h1 class="pt-5 text-center text-2xl">New Message</h1>

                <form @submit.prevent="submit" class="max-w-xl mx-auto border rounded-lg p-10 my-3">
                    <div v-if="$page.props.flash" class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                        role="alert">
                        {{ $page.props.flash }}
                    </div>


                    <div class="mb-5">
                        <label for="subject"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subject</label>
                        <input v-model="form.subject" type="text" id="subject"
                            class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div class="mb-5">
                        <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                            message</label>
                        <textarea v-model="form.message" id="message" rows="4"
                            class="block min-h-60 p-2.5 w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full text-white bg-gradient-to-r from-purple-500 to-pink-500 hover:bg-gradient-to-l focus:ring-4 focus:outline-none focus:ring-purple-200 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                        Send Now
                    </button>
                </form>
            </div>
        </main>
        <footer class="flex py-10 justify-between bg-black text-white">
            <p class="w-full text-center">@Ticket Beast</p>
        </footer>

    </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    concert: Object,
})

const form = useForm({
    subject: '',
    message: '',
})

const submit = () => {
    form.post(route('backstage.concert-messages.store', {
        id: props.concert.id
    }));
}
</script>