<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const tenants = usePage().props.tenants;
const token = usePage().props.token;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                    <div class="p-6 text-gray-900 grid grid-cols-3 gap-4">
                        <span v-for="(tenant, key) in tenants" :key="key">
                            <div class="bg-white rounded-xl shadow-lg px-2 py-4 space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6">
                                <img class="block mx-auto h-24 rounded-full sm:mx-0 sm:shrink-0" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQpCq6ndIfyFkc5kaDd6JxUydI0XeYgmVQx2g&usqp=CAU" alt="Woman's Face" />
                                <div class="text-center space-y-2 sm:text-left">
                                    <div class="space-y-0.5">
                                    <p class="text-lg text-black font-semibold">
                                        {{tenant.id}}
                                    </p>
                                    <p class="text-slate-500 font-medium">
                                        {{tenant.tenancy_db_name}}
                                    </p>
                                    </div>
                                    <a
                                        :href="`http://${tenant.redirect_link}/access?token=${token}`"
                                        class="px-4 py-1 text-sm text-purple-600 font-semibold rounded-full border border-purple-200 hover:text-white hover:bg-purple-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2">
                                        Access
                                    </a>
                                </div>
                            </div>
                        </span>

                    </div>
                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>
