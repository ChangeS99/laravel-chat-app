<script setup>
import { Link } from "@inertiajs/vue3";

import SignupForm from "../../Components/Authentication/SignupForm.vue";
</script>

<script>
import AuthLayout from '@/Layouts/AuthLayout.vue';


export default {
    // Using a render function...
    // layout: (h, page) => h(Layout, [page]),

    // Using shorthand syntax...
    layout: AuthLayout,
}
</script>

<template>
    <section class="flex justify-center items-center flex-col">
        <div class="pt-5">
            <h1 class="text-4xl text-gray-700">Sign Up</h1>
        </div>
        <SignupForm />
        <div class="pt-4">
            <Link :href="route('signin')" class="parent text-md text-gray-500">
            Already have an account? <span class="child">Sign In.</span>
            </Link>
        </div>
    </section>
</template>

<style scoped>
.parent:hover .child {
    @apply text-blue-500;
}
</style>