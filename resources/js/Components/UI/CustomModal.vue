<script setup>
defineEmits(['model-close-event'])

const props = defineProps({
    width: {
        type: String,
        default: "50%"
    },
    height: {
        type: String,
        default: "50%"
    }
});
</script>

<template>
    <!-- background gray  -->
    <div @click="$emit('model-close-event')"
        class="fixed top-0 left-0 z-20 min-w-full min-h-full bg-gray-900 opacity-60">

    </div>
    <!-- end background -->

    <!-- modal content -->
    <div class="fixed top-1/2 left-1/2 translate-x-[-50%] translate-y-[-50%] z-30 min-w-fit min-h-fit">
        <section class="w-full h-full bg-white rounded-md shadow-lg">
            <!-- create input element -->
            <slot name="content">
                fallback
            </slot>
        </section>

    </div>
    <!-- end modal content -->
</template>