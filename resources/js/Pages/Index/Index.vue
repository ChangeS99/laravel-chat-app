<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<script>
import IndexLayout from '@/Layouts/IndexLayout.vue';


export default {
    // Using a render function...
    // layout: (h, page) => h(Layout, [page]),

    // Using shorthand syntax...
    layout: IndexLayout,
}
</script>

<template>
    <div class="flex flex-col items-center justify-center gap-2">
        <h1 class="text-6xl font-medium">Chat App</h1>
        <h3 class="text-3xl">Created Using Laravel, Inertia</h3>
    </div>
    <div class="flex justify-center pt-4">
        <Link class="px-4 py-2 text-gray-100 bg-gray-700 rounded-lg" :href="route('home')">Get Started</Link>
    </div>
</template>
