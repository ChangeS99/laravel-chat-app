<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
});

const signin = () => form.post(route('signin.store'));
</script>

<template>
    <div class="min-w-full sm:min-w-[70%] md:min-w-[30rem] ">
        <form class="flex flex-col gap-y-2" @submit.prevent="signin">
            <div class="flex flex-col gap-1">
                <label class="top-label" for="email">
                    Email
                </label>
                <div class="px-2 w-full">
                    <input v-model="form.email" type="email" id="email" class="rounded-full-input" />
                </div>
                <div class="input-error" v-if="form.errors.email">
                    {{ form.errors.email }}
                </div>
            </div>
            <div class="flex flex-col w-full gap-1">
                <label for="password" class="top-label">
                    Password
                </label>
                <div class="px-2 w-full">
                    <input v-model="form.password" id="password" type="password" class="rounded-full-input" />
                </div>
                <div class="input-error" v-if="form.errors.email">
                    {{ form.errors.password }}
                </div>
            </div>
            <div class="w-full px-2">
                <button type="submit" class="w-full bg-indigo-600 text-white px-2 py-3 rounded-full">
                    Sign In
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped></style>