<script setup>
import { ref } from "vue";
import {useBuildQuery} from '@/Composables/useBuildQuery.js'
import AppLayout from '@/layouts/AppLayout.vue';
import {router } from '@inertiajs/vue3';
const props  = defineProps({
    menus: Object,
    title:String,
    request:Object,
    is_admin:Boolean,
    trash:Boolean,
    locations:Array,
});
</script>
<template>
    <Head :title="title" />
    <AppLayout>
       <div class="flex flex-wrap mt-4">
         <div class="w-full mb-12 px-4">
            <div class="relative flex flex-col min-w-0 break-words w-full mb-6 ">
                <div class="rounded-t mb-0 px-3 py-4 border-0">
                    <div class="flex flex-wrap items-center">
                        <div class="relative flex">
                            <div class="flex items-center">
                                <h3 class="font-bold text-lg">
                                    {{title}} 
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto">
                <table class="items-center w-full bg-transparent border-collapse">
                    <thead>
                    <tr class="hidden lg:table-row">
                        <Th>Location</Th>
                        <Th></Th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(menu,index) in locations" :key="index" class="cursor-pointer relative py-3 block lg:py-0 lg:table-row border-t lg:border-0">
                        <Td>
                            <strong class="block lg:hidden">Title</strong>
                            <span>
                            {{ menu }}</span>
                        </Td>
                        <Td >
                            <div>
                                <SecondaryLink  class="px-3 py-2 bg-indigo-500 rounded-md" :href="route('menu.create',{location : menu})">
                                    <i class="fas fa-pencil"></i>
                                </SecondaryLink>
                            </div>
                        </Td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
         </div>
       </div>
   </AppLayout>
</template>
