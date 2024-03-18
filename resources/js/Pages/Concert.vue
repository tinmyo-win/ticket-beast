<template>
    <div class="flex justify-center">
        <div class="flex border border-gray-200 m-6">
            <img :src="'/storage/' + concert.poster_image_path" class="" style="width: 500px;" alt="">

            <div
                class=" w-full max-w-lg p-4 bg-white  rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                <div class="px-10 space-y-6 pb-3 border-b">
                    <h1 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ concert.title }}
                    </h1>
                    <h2 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ concert.subtitle
                        }}
                    </h2>
                    <p>
                        <font-awesome-icon :icon="faCalendarDays" class="mr-2" />
                        {{ concert.formatted_date }}
                    </p>
                    <p>
                        <font-awesome-icon :icon="faClock" class="mr-2" />
                        Doors at {{ concert.formatted_start_time }}
                    </p>
                    <p>
                        <font-awesome-icon :icon="faDollar" class="mr-2" />
                        {{ concert.ticket_price_in_dollars }}
                    </p>
                    <p>
                        <font-awesome-icon :icon="faLocation" class="mr-2" />
                        {{ concert.venue }}
                    <p>
                        {{ concert.venue_address }}
                    </p>
                    <p> {{ concert.city }}, {{ concert.state }} {{ concert.zip }} </p>
                    </p>
                    <p>
                        <font-awesome-icon :icon="faInfoCircle" class="mr-2" />
                        {{ concert.additional_information }}
                    </p>
                </div>

                <TicketCheckout class="px-8" :concertId="concert.id" :concertTitle="concert.title"
                    :price="concert.ticket_price" />

            </div>
        </div>
    </div>

</template>
<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCalendarDays, faClock } from '@fortawesome/free-regular-svg-icons';
import { faDollar, faLocation, faInfoCircle } from '@fortawesome/free-solid-svg-icons';
import TicketCheckout from '../Components/TicketCheckout.vue';
export default {
    data() {
        return {
            faCalendarDays: faCalendarDays,
            faClock: faClock,
            faDollar: faDollar,
            faLocation: faLocation,
            faInfoCircle: faInfoCircle,
        }
    },
    props: {
        'concert': Object,
    },
    components: {
        TicketCheckout,
        FontAwesomeIcon,
    },
}
</script>