<template>
    <div class="bg-soft mt-12 px-5 full-height">
        <div class="container max-w-4xl mx-auto">
            <div class="">
                <div class="flex justify-between items-center border-b mb-5">
                    <h1 class="text-xl py-5">Order Summary</h1>
                    <Link href="#">
                    {{ order.confirmation_number }}
                    </Link>
                </div>
                <div class="p-xs-y-4 border-b pb-5 mb-5">
                    <p>
                        <strong>Order Total: $ {{ order.amount /100, 2 }}</strong>
                    </p>
                    <p class="text-dark-soft">Billed to Card #: **** **** **** {{ order.card_last_four }}</p>
                </div>

                <div class="mt-10">
                    <h2 class="text-2xl mb-3">Your Tickets</h2>
                    <div v-for="ticket in order.tickets" class=" rounded overflow-hidden shadow-lg border mb-10">
                        <div class="flex justify-between py-6 px-6 bg-gray-700 text-white">
                            <div class="">
                                <h1 class="text-xl">{{ order?.concert?.title }}</h1>
                                <h2>{{ order?.concert?.subtitle }}</h2>
                            </div>
                            <div>
                                <h2>General Admission</h2>
                                <h2 class="text-sm">Admit One</h2>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-b flex justify-between">
                            <div class="flex">
                                <font-awesome-icon :icon="faCalendarDays" class="text-2xl text-blue-500 mr-2" />
                                <span>
                                    {{ moment(order.concert.date).format('MMMM Do YYYY') }}
                                    <!-- {{ $moment(order.concert.date).format('dddd MMMM YYYY') }} -->
                                    <p class="text-gray-500">
                                        Doors at {{ moment(order.concert.date).format('h:mm a') }}
                                    </p>
                                </span>
                            </div>

                            <div class="flex">
                                <font-awesome-icon :icon="faLocation" class="text-2xl text-blue-500 mr-2" />
                                <span>
                                    {{ order.concert.venue }}
                                    <p class="text-gray-500">
                                        {{ order.concert.venue_address }}
                                        <br />
                                        {{ order.concert.city }}, Myanmar
                                    </p>
                                </span>
                            </div>
                        </div>
                        <div class="px-6 pt-4 pb-3 flex justify-between">
                            <span>{{ ticket.code }}</span>
                            <span>{{ order?.email }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCalendar, faCalendarDays } from '@fortawesome/free-regular-svg-icons';
import { faLocation } from '@fortawesome/free-solid-svg-icons';
import moment from 'moment';
defineProps({
    order: Object,
})
</script>