<script setup>
import { useForm, Link } from "@inertiajs/vue3";

import { ref } from "vue";
import CustomModal from "../UI/CustomModal.vue";
const modalOpen = ref(false);
const groups = ref([]);

const groupName = ref("");
const form = useForm({
    name: "",
});

const addClickHandler = () => {
    modalOpen.value = true;
};

const closeModalHandler = () => {
    modalOpen.value = false;
};

const createGroup = async () => {
    const result = await window.axios.post(route("group.store"), {
        name: form.name,
    });


    if (result.data) {
        if (result.data.success) {
            const newGroups = [...groups.value, result.data.group];
            groups.value = [...newGroups];
            closeModalHandler();
        }
    }
};

const props = defineProps({
    groups: Array,
});

if (props.groups) {
    groups.value = props.groups;
}
</script>

<template>
    <div class="flex flex-col items-center w-full min-h-full bg-gray-200 rounded-lg shadow-lg">
        <!-- add group button -->
        <div class="flex justify-center p-4">
            <button @click.prevent="addClickHandler"
                class="border-2 rounded-[100%] w-[3rem] h-[3rem] flex justify-center items-center bg-indigo-700 hover:bg-indigo-500 text-3xl text-white">
                <p class="pb-1 m-0">+</p>
            </button>
            <CustomModal v-if="modalOpen" @model-close-event="() => closeModalHandler()">
                <template v-slot:content>
                    <form @submit.prevent="createGroup" class="p-4">
                        <!-- group name -->
                        <div class="flex flex-col p-4">
                            <label class="text-sm font-medium">Name</label>
                            <div class="px-2">
                                <input v-model="form.name" type="text" class="rounded-md" />
                            </div>
                        </div>
                        <!-- end group name -->
                        <!-- group member add -->
                        <div class="p-4">
                            <!-- <h3>Add Members</h3>
                            <div> -->
                            <!-- search -->
                            <!-- <div>
                                    <h2>Search</h2>
                                </div> -->
                            <!-- end search -->
                            <!-- all friend list -->
                            <!-- <div>

                                </div> -->
                            <!-- end all friend list -->
                            <!-- </div> -->
                        </div>
                        <!-- end group member add -->

                        <div class="p-4">
                            <button type="submit"
                                class="px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-500">
                                Create
                            </button>
                        </div>
                    </form>
                </template>
            </CustomModal>
        </div>
        <!-- end add button -->
        <!-- created groups -->
        <div class="relative flex flex-col flex-1  gap-2  min-w-[7rem]">
            <div
                class="absolute top-0 bottom-0 left-0 right-0 flex flex-col items-center flex-1 min-h-full gap-2 pt-1 pb-2 overflow-y-scroll ">
                <div class="ml-3 flex items-center justify-center min-w-[4rem] min-h-[4rem] max-w-[4rem] max-h-[4rem] bg-gray-300 rounded-full"
                    v-for="(group, index) in groups" :key="index">
                    <h3 class="">
                        <Link :href="route('group.show', group.name)">
                        {{ group.name }}
                        </Link>
                    </h3>
                </div>
            </div>
        </div>
        <!-- end created groups -->
    </div>
</template>
