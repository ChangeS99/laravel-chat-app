<script setup>
import { ref } from "vue";
import ChatBox from "../../../Components/Chat/ChatBox.vue";
import { chat_types } from "../../../Components/Chat/chat_types";
import { GroupListTypes } from "./group_list_types";
import axios from "axios";

const rootUrl = "http://127.0.0.1:8000";

// get acccess to the group and user data
const props = defineProps({
    group: Object,
    user: Object,
    groups: Object,
    friends: Object,
    members: Object
});

const friends_client = ref(props.friends);
const members_client = ref(props.members);

const listToShow = ref(GroupListTypes.friends);

const showAddMembers = ref(false);
const showMembers = ref(false);

// since the friends list from server contains all the freinds of users
// modify the list to contain only the friends that are not in the group
// create a hashmap with the users id from members list
const friendsMap = new ref({});

members_client.value.forEach(m => {
    friendsMap.value[m.id] = m
})

// then go through the friends list
// and if member id containes in friendsMap then 
// remove it from the friends_client list
const filteredFriends = friends_client.value.filter(f => {
    // check if friend id exists in friendsMap
    return !friendsMap.value[f.id];
})

friends_client.value = [...filteredFriends];

const handleShowAddMembers = () => {
    if (showMembers.value) {
        listToShow.value = GroupListTypes.friends;
        showMembers.value = !showMembers.value;
    }
    showAddMembers.value = !showAddMembers.value;
}

const handleShowMembers = () => {

    if (showAddMembers.value) {
        listToShow.value = GroupListTypes.members;
        showAddMembers.value = !showAddMembers.value;
    }
    showMembers.value = !showMembers.value;
}

const addMemberHandler = async (id) => {
    try {
        const result = await axios.post(`${rootUrl}/group/add-member`, {
            group_name: props.group.name,
            user_id: id
        });

        // if success update the freinds list
        if (result.data.success) {
            const friend = friends_client.value.find((f) => {
                return f.id === id;
            })
            const newFriendList = friends_client.value.filter((f) => {
                return f.id !== id;
            })

            const newMemberList = [...members_client.value, friend];
            members_client.value = [...newMemberList]

            friends_client.value = [...newFriendList];
        }
    } catch (error) {
        // console.log(error);
    }
}

</script>

<template>

    <section class="relative flex flex-1 min-h-full">
        <div class="flex flex-col flex-1 pt-2">
            <section class="flex pb-2">
                <div class="flex flex-1">
                    <h1 class="text-3xl font-bold">{{ props.group.name }}</h1>
                </div>
                <div class="flex">
                    <div class="pr-2">
                        <button @click.prevent="handleShowMembers"
                            class="px-4 py-2 text-gray-100 bg-gray-600 rounded-lg">
                            Members
                        </button>
                    </div>
                    <div class="pr-2">
                        <button @click.prevent="handleShowAddMembers"
                            class="px-4 py-2 text-gray-100 bg-gray-600 rounded-lg">
                            Add Members
                        </button>

                    </div>
                </div>
            </section>
            <ChatBox :user="props.user" :group="props.group" :from="chat_types.group" />
        </div>

        <!--  adding member section -->
        <section class="absolute top-0 right-0 z-20 min-h-full px-2 bg-white min-w-[20rem]" v-if="
            showAddMembers">
            <button @click.prevent="handleShowAddMembers">
                <v-icon name="io-close-circle-outline" class="w-[2rem] h-[2rem]" />
            </button>
            <h3 class="font-medium">Friends</h3>
            <div class="flex flex-col gap-2 pt-4">
                <div class="flex flex-1 px-2 py-2 text-gray-200 bg-gray-400 rounded-lg"
                    v-for="friend in friends_client">
                    <div class="flex items-center flex-1">
                        <h3>{{ friend.name }}</h3>
                    </div>
                    <div class="flex items-center pl-4">
                        <button @click.prevent="() => addMemberHandler(friend.id)">
                            <v-icon name="io-add-circle-outline" class="w-[2rem] h-[2rem]" />
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <!-- end adding member section -->

        <!-- show members section -->
        <section class="absolute top-0 right-0 z-20 min-h-full px-2 bg-white
        min-w-[20rem]
        " v-if="
            showMembers">
            <button @click.prevent="handleShowMembers">
                <v-icon name="io-close-circle-outline" class="w-[2rem] h-[2rem]" />
            </button>
            <h3 class="font-medium">Members</h3>
            <div class="flex flex-col gap-2 pt-4">
                <div class="flex flex-1 px-2 py-2 text-gray-200 bg-gray-400 rounded-lg"
                    v-for="member in members_client">
                    <div class="flex items-center flex-1">

                        <h3>{{ member.name }}</h3>
                    </div>
                    <!-- <div v-if="member.id !== props.user.id" class="flex items-center pl-4">
                        <v-icon name="io-add-circle-outline" class="w-[2rem] h-[2rem]" />
                    </div> -->
                </div>
            </div>
        </section>
        <!-- end show members section -->
    </section>

</template>