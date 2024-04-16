<script setup>
import MessageInput from "./MessageInput.vue";
import Message from "./Message.vue";
import { ref, watchEffect } from "vue";

const scroll = ref(null);

import { chat_types } from "./chat_types";
const props = defineProps({
    user: Object,
    group: {
        type: Object,
        optional: true
    },
    from: {
        type: String
    },
    friend: {
        type: Object,
        nullable: true
    },
    conversation: {
        type: Object,
        nullable: true
    }
});

const scrollToBottom = () => {

    scroll.value.scrollIntoView({ behavior: "smooth" });
};


let webSocketChannel = props.group && `group-${props.group.id}`;
if (props.conversation) {
    webSocketChannel = props.conversation && `conversation-${props.conversation.id}`;
}


const messages = ref([]);
const rootUrl = "http://127.0.0.1:8000";

const connectWebSocket = () => {
    try {
        if (props.from === chat_types.group) {
            window.Echo.private(webSocketChannel).listen(".got-message", async (e) => {
                // if (e.message) {
                //     const newMessages = [...messages.value, e.message]
                //     messages.value = newMessages
                // }

                await getMessages();
            });
        }

        if (props.from === chat_types.chat) {
            window.Echo.private(webSocketChannel).listen(".got-conversation-message", async (e) => {
                // if (e.message) {
                //     const newMessages = [...messages.value, e.message]
                //     messages.value = newMessages
                // }

                await getMessages();
            });
        }
    } catch (error) {
        // console.log(error);
    }


};

const getMessages = async () => {
    try {
        let result = null;
        if (props.group && chat_types.group === props.from) {

            result = await axios.get(`${rootUrl}/message/${props.group.name}/show`);
        } else {
            result = null
        }

        if (props.from === chat_types.chat) {
            result = await axios.get(`${rootUrl}/chat/show/${props.friend.id}`);
        }

        if (props.from === chat_types.group) {

            if (result.data.group) {
                messages.value = [...result.data.group.messages];
            }
            setTimeout(scrollToBottom, 0);

        }

        if (props.from === chat_types.chat) {
            messages.value = [...result.data.messages];
            setTimeout(scrollToBottom, 0);
        }

        // setTimeout(scrollToBottom, 0);
    } catch (err) {
        console.log(err);
    }
};



watchEffect(() => {
    getMessages();
    connectWebSocket();
    setTimeout(scrollToBottom, 0);
    return () => {

        window.Echo.leave(webSocketChannel);
    };
}, []);
</script>
<template>
    <div class="relative flex flex-col flex-1 mb-2 rounded-lg shadow-lg bg-slate-200">

        <div class="relative flex flex-col flex-1">
            <div class="absolute top-0 bottom-0 left-0 right-0 mb-[2rem] overflow-y-auto rounded-lg">

                <div class="flex flex-col flex-1">
                    <div class="card-body" style="overflow-y: auto">
                        <div v-for="message in messages">
                            <Message :from="props.from" :message="message" :userId="props.user.id" />
                        </div>

                        <span ref="scroll"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="min-w-full ">
            <div class="flex max-w-full ">
                <MessageInput :friend="props.friend" rootUrl="{rootUrl}" :group="props.group" :from="props.from" />
            </div>
        </div>
    </div>
</template>
