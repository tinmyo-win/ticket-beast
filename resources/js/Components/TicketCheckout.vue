<template>
    <div class="flex flex-col">
        <div class="flex justify-between">
            <div class="col col-xs-6">
                <div class="form-group mb-1 p-2.5">
                    <label class="form-label">
                        Price
                    </label>
                    <span class="fbg-gray-50 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        ${{ priceInDollars }}
                    </span>
                </div>
            </div>
            <div class="col col-xs-6">
                <div class="form-group mb-1 p-2.5">
                    <label class="form-label">
                        Qty
                    </label>
                    <input v-model="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                </div>
            </div>
        </div>
        <div class="text-center">
            <button class="w-full text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" @click="openStripe" :class="{ 'btn-loading': processing }"
                :disabled="processing">
                Buy Tickets
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'price',
        'concertTitle',
        'concertId',
    ],
    data() {
        return {
            quantity: 1,
            stripeHandler: null,
            processing: false,
        }
    },
    computed: {
        description() {
            if (this.quantity > 1) {
                return `${this.quantity} tickets to ${this.concertTitle}`
            }
            return `One ticket to ${this.concertTitle}`
        },
        totalPrice() {
            return this.quantity * this.price
        },
        priceInDollars() {
            return (this.price / 100).toFixed(2)
        },
        totalPriceInDollars() {
            return (this.totalPrice / 100).toFixed(2)
        },
    },
    methods: {
        initStripe() {
            const handler = StripeCheckout.configure({
                key: App.stripePublicKey
            })

            window.addEventListener('popstate', () => {
                handler.close()
            })

            return handler
        },
        openStripe(callback) {
            this.stripeHandler.open({
                name: 'TicketBeast',
                description: this.description,
                currency: "usd",
                allowRememberMe: false,
                panelLabel: 'Pay {{amount}}',
                amount: this.totalPrice,
                image: '/img/checkout-icon.png',
                token: this.purchaseTickets,
            })
        },
        purchaseTickets(token) {
            this.processing = true

            axios.post(`/concerts/${this.concertId}/orders`, {
                email: token.email,
                ticket_quantity: this.quantity,
                payment_token: token.id,
            }).then(response => {
                window.location = `/orders/${response.data.confirmation_number}`
            }).catch(response => {
                this.processing = false
            })
        }
    },
    created() {
        this.stripeHandler = this.initStripe()
    }
}
</script>
