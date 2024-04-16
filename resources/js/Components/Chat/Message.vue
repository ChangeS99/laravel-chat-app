<script setup>
import { chat_types } from './chat_types';


const props = defineProps({
    message: Object,
    userId: Number,
    from: {
        type: String,
        nullable: true
    }
})


</script>

<template>

    <div v-if="props.from === chat_types.group" class="py-2 border-2"
        :class="props.message.user.id === props.userId ? '' : ''">
        <div class="flex px-5" :class="props.message.user.id === props.userId ? '' : ''">
            <div class=" p-4 flex flex-col max-w-[60%] min-w-[60%]"
                :class="props.message.user.id === props.userId ? 'self-start ml-auto bg-slate-400 shadow-lg rounded-full' : 'self-end rounded-full bg-slate-300 shadow-lg bg-opacity-55'">
                <small class="">
                    <span class="text-xs ">{{ props.message.user.name }} </span>
                </small>
                <small class="text-medium">
                    {{ props.message.time }}
                </small>
                <div class="text-sm font-medium">
                    {{ props.message.text }}
                </div>
            </div>
        </div>
    </div>

    <div v-if="props.from === chat_types.chat" class="py-2 border-2"
        :class="props.message.userId === props.userId ? '' : ''">
        <div class="flex px-5" :class="props.message.userId === props.userId ? '' : ''">
            <div class=" p-4 flex flex-col max-w-[60%] min-w-[60%]"
                :class="props.message.userId === props.userId ? 'self-start ml-auto bg-slate-400 shadow-lg rounded-full' : 'self-end rounded-full bg-slate-300 shadow-lg bg-opacity-55'">
                <small class="">
                    <span class="text-xs ">{{ props.message.name }} </span>
                </small>
                <small class="text-medium">
                    {{ props.message.time }}
                </small>
                <div class="text-sm font-medium">
                    {{ props.message.text }}
                </div>
            </div>
        </div>
    </div>
</template>