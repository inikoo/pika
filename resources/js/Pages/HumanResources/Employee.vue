<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 08 Sept 2022 00:38:38 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import {library} from '@fortawesome/fontawesome-svg-core';
import {faIdCard,faUser} from '@/../private/pro-light-svg-icons';
import {faCheckCircle} from '@/../private/pro-solid-svg-icons';

import PageHeading from '@/Components/Headings/PageHeading.vue';

library.add(faIdCard,faUser,faCheckCircle)
import { trans } from 'laravel-vue-i18n';
import {Inertia} from '@inertiajs/inertia';

const props=defineProps(["title","pageHead","employee"])

const createEmployeeUser = () =>{
    Inertia.post(route('hr.employees.show.user.store',props['employee'].data.id), {})
}



</script>


<template layout="App">
    <Head :title="title" />
    <PageHeading :data="pageHead"></PageHeading>

        <div v-if="!employee.data.user || ( $page.props.flash.notification && $page.props.flash.notification.type==='newUser')"   class="m-4 bg-white shadow sm:rounded-lg max-w-2xl">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ trans('System user')}}  <span v-if="$page.props.flash.notification" class="text-green-600 ml-2 text-sm"><font-awesome-icon aria-hidden="true" icon="fa-solid fa-check-circle" size="lg" /> {{$page.props.flash.notification.message}}</span></h3>
                <div v-if="!employee.data.user"  class="mt-2 sm:flex sm:items-start sm:justify-between">
                    <div class="max-w-xl text-sm text-gray-500">
                        <p class="text-red-500">{{ trans('This employee is not an user') }}.</p>
                    </div>


                    <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex sm:flex-shrink-0 sm:items-center">
                        <button @click="createEmployeeUser" type="button" class="mr-5 inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-sm">
                            {{ trans('Add employee to system users') }}
                        </button>

                    </div>
                </div>
            </div>
            <div v-if="$page.props.flash.notification && $page.props.flash.notification.type==='newUser'" class="border-t border-gray-200 px-4 py-5 sm:p-0">

                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 capitalize">{{$page.props.flash.notification.fields.username.label}}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{$page.props.flash.notification.fields.username.value}}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 capitalize">{{$page.props.flash.notification.fields.password.label}}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{$page.props.flash.notification.fields.password.value}}</dd>
                    </div>


                </dl>
            </div>

        </div>




</template>

