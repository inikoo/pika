<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sat, 17 Sept 2022 02:06:31 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<template layout="App">
    <Head :title="title" />
    <PageHeading :data="pageHead"></PageHeading>




</template>

<script setup lang="ts">
import { Head } from '@inertiajs/inertia-vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';

defineProps(["title","pageHead","location"])

import {library} from '@fortawesome/fontawesome-svg-core';
import {faInventory} from '@/../private/pro-light-svg-icons';
library.add(faInventory);

</script>
