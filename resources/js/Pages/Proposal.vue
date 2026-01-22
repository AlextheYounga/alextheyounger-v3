<template>
    <Head title="Proposal" />
    <div id="proposal" class="min-h-screen py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg">
                <div class="px-6">
                    <div class="no-print mb-4 flex justify-end">
                        <button
                            @click="printPage"
                            aria-label="Print proposal"
                            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <PrinterIcon class="mr-2 h-5 w-5" />
                            Save as PDF
                        </button>
                    </div>

                    <!-- Header -->
                    <div class="mb-16">
                        <h1 class="text-5xl font-bold text-gray-900">{{ proposal.title }}</h1>
                        <p class="mt-1 text-sm text-gray-500">Created on {{ formatDate(proposal.created_at) }}</p>
                        <p class="mt-3 text-sm text-gray-600"><b>Prepared for</b>: {{ proposal.client }}</p>
                        <p class="mt-1 text-sm text-gray-600"><b>Prepared by</b>: Alex Younger</p>
                    </div>

                    <!-- Description -->
                    <div v-if="hasContent(description)" class="my-16">
                        <h2 class="mb-4 text-2xl font-medium text-gray-700">PROJECT DESCRIPTION</h2>
                        <div class="prose max-w-none" v-html="description"></div>
                    </div>

                    <!-- Scope -->
                    <div v-if="hasContent(scope)" class="my-16">
                        <h2 class="mb-4 text-2xl font-medium text-gray-700">PROJECT SCOPE</h2>
                        <div class="prose max-w-none" v-html="scope"></div>
                    </div>

                    <!-- Tech Stack -->
                    <div v-if="hasContent(technology)" class="my-16">
                        <h2 class="mb-4 text-2xl font-medium text-gray-700">TECHNOLOGY STACK</h2>
                        <div class="prose max-w-none" v-html="technology"></div>
                    </div>

                    <!-- Line Items -->
                    <div v-if="proposal.line_items && Object.entries(proposal.line_items).length" class="my-16">
                        <div class="my-12">
                            <h2 class="text-gray-700mb-4 text-2xl font-medium">LINE ITEMS</h2>
                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Description
                                            </th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Price
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr v-for="(item, index) in proposal.line_items" :key="index">
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                                {{ item.description }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-700">
                                                ${{ formatPrice(item.price) }}
                                            </td>
                                        </tr>
                                        <tr class="font-bold">
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">Total</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-700">
                                                ${{ formatPrice(proposal.total) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Schedule -->
                    <div v-if="paymentSchedule && Object.entries(paymentSchedule).length" class="mt-16">
                        <div class="my-12">
                            <h2 class="mb-4 text-2xl font-medium text-gray-700">PAYMENT SCHEDULE</h2>
                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Milestone
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Description
                                            </th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Amount Due
                                            </th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                                            >
                                                Date
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr v-for="item in paymentSchedule" :key="item.date">
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                                {{ item?.milestone }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ item?.description }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-900">
                                                ${{ formatPrice(item?.amount_due) }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-900">
                                                {{ item?.date }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Completion Date -->
                    <div v-if="proposal.completion_date" class="-mt-6 mb-16">
                        <div>
                            <h3 class="mr-4 text-lg font-medium text-gray-700">ESTIMATED COMPLETION DATE:</h3>
                            <p class="text-lg text-gray-600">{{ formatDate(proposal.completion_date) }}</p>
                        </div>
                    </div>

                    <!-- Disclaimer -->
                    <div v-if="hasContent(disclaimer)" class="my-16">
                        <div class="mb-16">
                            <h2 class="mb-4 text-2xl font-medium text-gray-700">DISCLAIMER</h2>
                            <div class="prose max-w-none" v-html="disclaimer"></div>
                        </div>
                    </div>

                    <!-- Client Agreement -->
                    <div
                        v-if="proposal.properties?.use_client_agreement"
                        id="client-agreement"
                        class="mb-16 rounded border p-8 shadow"
                    >
                        <div class="no-print" v-if="!proposal.client_sign_date">
                            <h2 class="mb-4 text-2xl font-medium text-gray-700">Client Agreement</h2>
                            <form @submit.prevent="submitAgreement" class="space-y-6">
                                <div>
                                    <label class="flex items-center">
                                        <input
                                            type="checkbox"
                                            v-model="agreement"
                                            class="form-checkbox h-5 w-5 text-indigo-600"
                                        />
                                        <span class="ml-2 text-gray-700"
                                            >I agree to the terms and conditions outlined in this proposal</span
                                        >
                                    </label>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Digital Signature</label>
                                    <div class="mt-1">
                                        <input
                                            type="text"
                                            v-model="signature"
                                            class="yesteryear block w-full rounded-md border-0 text-4xl"
                                            placeholder="Your Name"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <button
                                        type="submit"
                                        :disabled="!canSubmit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                                    >
                                        Sign and Agree
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div v-if="proposal.client_sign_date">
                            <h2 class="mb-4 text-2xl font-medium text-gray-700">Client Agreement</h2>
                            <div class="mt-4 flex items-center justify-between">
                                <p class="text-lg text-gray-600">
                                    <b class="pr-4">Signed by:</b>
                                    <span class="yesteryear signature text-5xl">{{ proposal.client_signature }}</span>
                                </p>
                                <p class="mt-2 text-lg text-gray-600">
                                    <b class="pr-4">Signed on:</b>
                                    <span class="underline">{{ formatDate(proposal.client_sign_date) }}</span>
                                </p>
                            </div>

                            <div class="no-print">
                                <button
                                    @click="printPage"
                                    class="mt-6 inline-flex items-center rounded-md border border-transparent bg-green-500 px-4 py-2 text-sm font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
                                >
                                    <PrinterIcon class="mr-2 h-5 w-5" />
                                    Save Document
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, defineProps } from "vue";
import { Head, router } from "@inertiajs/vue3";
import { PrinterIcon } from "@heroicons/vue/24/outline";

document.body.classList.remove("bg-black");

const { proposal } = defineProps({
    proposal: Object,
});
const agreement = ref(false);
const signature = ref("");

const { description, scope, technology, disclaimer, payment_schedule: paymentSchedule } = proposal.content;

const canSubmit = computed(() => {
    return agreement.value && signature.value.trim().length > 0;
});

const hasContent = (content) => {
    return content && content.replace(/<[^>]*>/g, "").trim().length > 0;
};

const submitAgreement = () => {
    router.post(
        `/proposals/${proposal.hash}/sign`,
        {
            signature: signature.value,
            agreement: agreement.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                // Update the local proposal data
                proposal.client_signature = signature.value;
                proposal.client_agreement = true;
                proposal.client_sign_date = new Date().toISOString();

                // Clear the form
                signature.value = "";
                agreement.value = false;
            },
        },
    );
};

const printPage = () => {
    window.print();
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const formatPrice = (price) => {
    return parseFloat(price).toFixed(2);
};
</script>

<style scoped>
.yesteryear {
    font-family: "Yesteryear", cursive;
    font-weight: 400;
    font-style: normal;
}

body {
    background: #f9fafb;
}

input:focus {
    outline: none !important;
    box-shadow: none !important;
}

@media print {
    /* Hide elements not needed on print */
    .no-print {
        display: none !important;
    }

    body {
        background: #fff !important;
    }

    #client-agreement {
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
