<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 15 Sept 2022 16:07:20 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<template layout="App">
    <Head :title="title" />
    <PageHeading :data="pageHead"></PageHeading>




</template>

<script setup lang="ts">
import { Head } from '@inertiajs/inertia-vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';

defineProps(["title","pageHead","warehouse"])

import {library} from '@fortawesome/fontawesome-svg-core';
import {faInventory,faWarehouse,faMapSigns} from '@/../private/pro-light-svg-icons';
library.add(faInventory,faWarehouse,faMapSigns);

</script>
