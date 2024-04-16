<script setup>
import { Link } from "@inertiajs/vue3";
import SigninForm from "../../Components/Authentication/SigninForm.vue";
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
            <h1 class="text-4xl text-gray-700">Sign In</h1>
        </div>
        <SigninForm />
        <div class="pt-4">

            <Link :href="route('signup')" class="parent text-md text-gray-500
            ">
            Don't have an account? <span class="child">Sign Up.</span>
            </Link>

        </div>
    </section>
</template>

<style scoped>
.parent:hover .child {
    @apply text-blue-500;
}
</style>