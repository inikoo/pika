<template layout="App">
    <Head :title="title" />
    <PageHeading :data="pageHead"></PageHeading>
    <Table :resource="employees" class="mt-5">


        <template #cell(slug)="{ item: employee }">
            <Link :href="route('hr.employees.show',employee.slug)">
                {{ employee.slug }}
            </Link>
        </template>
        <template #cell(job_positions)="{ item: employee }">
            <job-position-badges :job_positions="employee['job_positions']"/>
        </template>
    </Table>
</template>

<script setup>
import {Head, Link} from '@inertiajs/inertia-vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import JobPositionBadges from '@/Components/Elements/Badges/JobPositionBadges.vue';
import Table from '@/Components/Table/Table.vue';

defineProps(['employees', 'title','pageHead']);

</script>
