<template>
  <oxd-layout :icon="icon" :context-title="contextTitle" :user="user">
    <template v-for="(_, name) in $slots" v-slot:[name]="slotData">
      <slot :name="name" v-bind="slotData" />
    </template>
  </oxd-layout>
</template>

<script>
import Layout from '@orangehrm/oxd/src/core/components/Layout/Layout.vue';

export default {
  name: 'orangehrm-layout',
  components: {
    'oxd-layout': Layout,
  },
  props: {
    icon: {
      type: String,
      default: '',
    },
    contextTitle: {
      type: String,
      required: true,
    },
    user: {
      type: Object,
      required: true,
    },
  },
  created() {
    this.$store.setState('icon', this.icon);
    this.$store.setState('contextTitle', this.contextTitle);
    this.$store.setState('user', this.user);
  },
};
</script>
