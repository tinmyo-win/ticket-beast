<template>
    <div>
        <header class="flex justify-between bg-black text-white items-center">
            <div class="ml-10">
                <img src="/img/checkout-icon.png" style="height: 5rem;" />
            </div>
            <div class="mr-10">
                Log out
            </div>
        </header>
        <main class="pb-8 mx-10">
            <div class="flex justify-between px-10 py-6 border-b">
                <h1 class="text-xl">Your Concerts</h1>
                <Link :href="route('backstage.concerts.new')"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                Add
                Concert</Link>
            </div>

            <div>
                <div class="mb-10">
                    <h2 class="py-5 text-xl font-bold text-gray-600">
                        Published
                    </h2>
                    <div class="flex flex-wrap gap-10">
                        <div v-for="concert in publishedConcerts">
                            <div class="max-w-md p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800
                                dark:border-gray-700">
                                <p href="#" class="py-1">
                                <h3 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ concert.title }}</h3>
                                <h5 class="mb-2 text-lg tracking-tight text-gray-600 dark:text-white">
                                    {{ concert.subtitle }}</h5>
                                </p>
                                <p class="py-1">
                                    <font-awesome-icon :icon="faLocation" class="mr-2" />
                                    {{ concert.venue }}
                                    -
                                    {{ concert.venue_address }}

                                    {{ concert.city }}, {{ concert.state }} {{ concert.zip }}
                                </p>
                                <p class="py-2">
                                    <font-awesome-icon :icon="faCalendarDays" class="mr-2" />
                                    {{ concert.formatted_date }} @ {{ concert.formatted_start_time }}
                                </p>
                                <div class="flex gap-3">
                                    <Link
                                        :href="route('backstage.published-concert-orders.index', concert.id)"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-black bg-slate-100 rounded-lg hover:bg-slate-400 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Manage
                                    </Link>
                                    <Link :href="route('concerts.show', concert.id) "
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-blue-700">
                                    Public Link
                                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                    </svg>
                                    </Link>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div>
                    <h2 class="py-5 text-xl font-bold text-gray-600">
                        Unpublished
                    </h2>
                    <div class="flex flex-wrap gap-10">
                        <div v-for="concert in unPublishedConcerts">
                            <div class="max-w-md p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800
                                dark:border-gray-700">
                                <p href="#" class="py-1">
                                <h3 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ concert.title }}</h3>
                                <h5 class="mb-2 text-lg tracking-tight text-gray-600 dark:text-white">
                                    {{ concert.subtitle }}</h5>
                                </p>
                                <p class="py-1">
                                    <font-awesome-icon :icon="faLocation" class="mr-2" />
                                    {{ concert.venue }}
                                    -
                                    {{ concert.venue_address }}

                                    {{ concert.city }}, {{ concert.state }} {{ concert.zip }}
                                </p>
                                <p class="py-2">
                                    <font-awesome-icon :icon="faCalendarDays" class="mr-2" />
                                    {{ concert.formatted_date }} @ {{ concert.formatted_start_time }}
                                </p>
                                <div class="flex gap-2">
                                    <Link :href="route('backstage.concerts.edit', concert.id)"
                                        class="inline-flex items-center px-5 py-2 text-sm font-medium text-center text-black bg-slate-100 rounded-lg hover:bg-slate-400 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Edit
                                    </Link>
                                    <Link :href="route('backstage.concerts.publish')" method="post"
                                        :data="{ concert_id: concert.id }"
                                        class="text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Publish
                                    </Link>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
        <footer class="flex py-10 justify-between bg-black text-white">
            <p class="w-full text-center">@Ticket Beast</p>
        </footer>

    </div>
</template>
<script setup>
import { faLocation } from '@fortawesome/free-solid-svg-icons';
import { computed } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCalendarDays } from '@fortawesome/free-regular-svg-icons';
import { Link } from '@inertiajs/vue3';


const props = defineProps({
    publishedConcerts: Array,
    unPublishedConcerts: Array,
})
</script>