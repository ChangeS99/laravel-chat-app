<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    name: '',
});

const signup = () => form.post(route('signup.store'));
</script>

<template>
    <div class="min-w-full sm:min-w-[70%] md:min-w-[30rem] ">
        <form class="flex flex-col gap-y-2" @submit.prevent="signup">
            <div class="flex flex-col gap-1">
                <label class="top-label" for="name">
                    Username
                </label>
                <div class="px-2 w-full">
                    <input v-model="form.name" type="text" id="name" class="rounded-full-input" />
                </div>
                <div class="input-error" v-if="form.errors.name">
                    {{ form.errors.name }}
                </div>
            </div>
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
                <div class="input-error" v-if="form.errors.password">
                    {{ form.errors.password }}
                </div>
            </div>
            <div class="flex flex-col gap-1">
                <label class="top-label" for="password_confirmation">
                    Password Confirmation
                </label>
                <div class="px-2 w-full">
                    <input v-model="form.password_confirmation" type="password" id="password_confirmation"
                        class="rounded-full-input" />
                </div>
                <div class="input-error" v-if="form.errors.password_confirmation">
                    {{ form.errors.password_confirmation }}
                </div>
            </div>
            <div class="w-full px-2">
                <button type="submit" class="w-full bg-indigo-600 text-white px-2 py-3 rounded-full">
                    Sign Up
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped></style>