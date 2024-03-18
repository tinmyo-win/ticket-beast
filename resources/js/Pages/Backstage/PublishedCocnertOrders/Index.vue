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
                        }}</span></h1>
                <div class="flex">
                    <h1 class="text-xl font-bold">Orders</h1>
                    <Link :href="route('backstage.concert-messages.new', concert?.id)" class="text-xl pl-5">Message
                    Attendees</Link>
                </div>
            </div>
            <div class="px-10 py-6">
                <div>
                    <h1 class="m-2 text-xl">Overviews</h1>
                    <div class="w-full m-2 bg-white border border-gray-200 rounded-lg">
                        <div class="border-b p-5">
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                                This show is {{ salePercent }}% sold out
                            </p>

                            <div class="w-full bg-gray-200 rounded-full">
                                <div class="bg-green-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                    :style="{ width: salePercent + '%' }"> {{ salePercent }}%</div>
                            </div>
                        </div>

                        <div class="flex">
                            <section class="border-green-800 flex-1 p-5">
                                Total Tickets Remaining
                                <div class="mt-2 text-4xl font-bold">{{ concert.tickets_remaining }}</div>
                            </section>
                            <section class="flex-1 border-x p-5">
                                Total Tickets Sold
                                <div class="mt-2 text-4xl font-bold">{{ concert.tickets_sold }}</div>
                            </section>
                            <section class="flex-1 p-5">
                                Total Revenue
                                <div class="mt-2 text-4xl font-bold">${{ concert?.revenue_in_dollars }}</div>
                            </section>
                        </div>

                    </div>
                </div>

                <div class="mt-10">
                    <div class="flex justify-between">
                        <h1 class="m-2 text-xl">Recent Orders</h1>
                        <button @click="exportOrders"
                            class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 hover:text-blue-700">Export
                            Orders</button>
                    </div>
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Tickets
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Card
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Purchased
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr :key="order.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                    v-for="order in orders">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ order.email }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ order.tickets.length }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ order?.amount }}
                                    </td>
                                    <td class="px-6 py-4">
                                        **** {{ order?.card_last_four }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ moment(order.created_at).format('MMMM Do YYYY @ h:mm a') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
import { Link } from '@inertiajs/vue3';
import moment from 'moment';
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    concert: Object,
    orders: Array,
})

const salePercent = computed(() => ((props.concert?.tickets_sold / props?.concert?.total_tickets) * 100).toFixed(2))

const exportOrders = () => {
    console.log('download');
    window.open(route('backstage.concert-orders.download', { id: props.concert.id }));
    // router.get(route('backstage.concert-orders.download', {id: props.concert.id}))
}

</script>