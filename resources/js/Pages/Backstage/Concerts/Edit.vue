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
        <main class="py-8">
            <div class="pl-10 py-6 text-xl border-b">
                Add a concert
            </div>
            <form @submit.prevent="submit">
                <div class="pl-10 pt-10 flex gap-4 bg-gray-50 border-b">
                    <div class="basis-1/3">
                        <div class="">
                            <h2 class="text-base wt-medium mb-4">Concert Details</h2>
                            <p class="text-gray-600 text-sm mb-4">Tell us who's playing! <em>(Please be
                                    Slayer!)</em></p>
                            <p class="text-gray-600 text-sm">Include the headliner in the concert name,
                                use the
                                subtitle section to list any opening bands, and add any important information to the
                                description.</p>
                        </div>
                    </div>
                    <div class="m-5 px-5 basis-2/3 border bg-white rounded">
                        <div class="mt-5 mb-5">
                            <label for="title"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                            <input v-model="form.title" type="text" id="title"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="The headliners" required />

                            <div v-if="form.errors.title">{{ form.errors.title }}</div>

                        </div>
                        <div class="mt-5 mb-5">
                            <label for="subtitle"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subtitle</label>
                            <input type="text" v-model="form.subtitle" id="subtitle"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="With the openers (Optionals)" />

                            <div v-if="form.errors.subtitle">{{ form.errors.subtitle }}</div>

                        </div>
                        <div class="mt-5 mb-5">
                            <label for="additional_information"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Additional
                                Information</label>
                            <textarea v-model="form.additional_information" id="additional_information" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="This concert is 19+ (optional)"></textarea>

                            <div v-if="form.errors.additional_information">{{ form.errors.additional_information }}
                            </div>


                        </div>
                    </div>
                </div>
                <div class="pl-10 pt-10 flex gap-4 bg-gray-50 border-b">
                    <div class="basis-1/3">
                        <div class="">
                            <h2 class="text-base wt-medium mb-4">Date & time</h2>
                            <p class="text-gray-600 text-sm">We are pleased to announce date and time</p>
                        </div>
                    </div>
                    <div class="m-5 px-5 basis-2/3 border bg-white rounded">
                        <div class="flex gap-5">
                            <div class="mt-5 mb-5 flex-1">
                                <label for="Date"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                                <input v-model="form.date" type="date" id="date"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    required />

                                <div v-if="form.errors.date">{{ form.errors.date }}</div>

                            </div>
                            <div class="mt-5 mb-5 flex-1">
                                <label for="time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
                                    Time</label>
                                <input type="text" v-model="form.time" id="time"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="7:00pm" />

                                <div v-if="form.errors.time">{{ form.errors.time }}</div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="pl-10 pt-10 flex gap-4 bg-gray-50 border-b">
                    <div class="basis-1/3">
                        <div class="">
                            <h2 class="text-base wt-medium mb-4">Venue Information</h2>
                            <p class="text-gray-600 text-sm">Where's the show?. Let's attendances know then
                                venue name and adress so they can bring the mosh
                            </p>
                        </div>
                    </div>
                    <div class="m-5 px-5 basis-2/3 border bg-white rounded">
                        <div class="mt-5 mb-5 flex-1">
                            <label for="venue"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Venue Name</label>
                            <input type="text" id="venue" v-model="form.venue"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="The King" required />

                            <div v-if="form.errors.venue">{{ form.errors.venue }}</div>

                        </div>
                        <div class="mt-5 mb-5 flex-1">
                            <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Street Address
                            </label>
                            <input v-model="form.venue_address" type="text" id="address"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="500 Street Example" />

                            <div v-if="form.errors.venue_address">{{ form.errors.venue_address }}</div>

                        </div>
                        <div class="flex gap-3">
                            <div class="mt-5 mb-5 flex-1">
                                <label for="city" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    City
                                </label>
                                <input type="text" id="city" v-model="form.city"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Laraville" required />

                                <div v-if="form.errors.city">{{ form.errors.city }}</div>

                            </div>
                            <div class="mt-5 mb-5 flex-1">
                                <label for="state" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    State
                                </label>
                                <input type="text" id="state" v-model="form.state"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="ON" />

                                <div v-if="form.errors.state">{{ form.errors.state }}</div>

                            </div>
                            <div class="mt-5 mb-5 flex-1">
                                <label for="zip" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    ZIP
                                </label>
                                <input type="text" id="zip" v-model="form.zip"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="500600" />

                                <div v-if="form.errors.zip">{{ form.errors.zip }}</div>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="pl-10 pt-10 flex gap-4 bg-gray-50 border-b">
                    <div class="basis-1/3">
                        <div class="">
                            <h2 class="text-base wt-medium mb-4">Tickets & Pricing</h2>
                            <p class="text-gray-600 text-sm mb-4">
                                Set your ticket price and availability
                            </p>
                            <p class="text-gray-600 text-sm">
                                But don't forget , metalheads are cheap so keep it reasonable
                            </p>
                        </div>
                    </div>

                    <div class="m-5 px-5 basis-2/3 border bg-white rounded">
                        <div class="flex gap-4">
                            <div class="mt-5 mb-5 flex-1">
                                <label for="tickets_quantity"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tickets
                                    Pricing</label>
                                <input v-model="form.ticket_price" type="text" id="tickets_quantity"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="The headliners" required />

                                <div v-if="form.errors.ticket_price">{{ form.errors.ticket_price }}</div>

                            </div>
                            <div class="mt-5 mb-5 flex-1">
                                <label for="pricing"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity</label>
                                <input v-model="form.ticket_quantity" type="text" id="pricing"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="With the openers (Optionals)" />

                                <div v-if="form.errors.ticket_quantity">{{ form.errors.ticket_quantity }}</div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <button type="submit"
                        class="text-white bg-gradient-to-r from-purple-500 to-pink-500 hover:bg-gradient-to-l focus:ring-4 focus:outline-none focus:ring-purple-200 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                        Save Changes
                    </button>

                </div>
            </form>


        </main>
        <footer class="flex py-10 justify-between bg-black text-white">
            <p class="w-full text-center">@Ticket Beast</p>
        </footer>

    </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3';
import moment from 'moment';

const props = defineProps({
    concert: Object
})

const form = useForm({
    title: props.concert.title,
    subtitle: props.concert.subtitle,
    additional_information: props.concert.additional_information,
    date: '2024-02-02',
    time: '9:00pm',
    venue: props.concert.venue,
    venue_address: props.concert.venue_address,
    city: props.concert.city,
    state: props.concert.state,
    zip: props.concert.zip,
    ticket_price: props.concert.ticket_price,
    ticket_quantity: props.concert.ticket_quantity

})

const submit = () => {
    form.patch(route('backstage.concerts.update', props.concert.id));
}
</script>