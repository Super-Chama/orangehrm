<template>
  <oxd-layout>
    <router-view></router-view>
    <template #user-actions>
      <li>
        <router-link to="about" class="oxd-userdropdown-link">
          {{ $t('general.about') }}
        </router-link>
      </li>
      <li>
        <router-link to="support" class="oxd-userdropdown-link">
          {{ $t('general.support') }}
        </router-link>
      </li>
      <li>
        <router-link to="updatePassword" class="oxd-userdropdown-link">
          {{ $t('general.change_password') }}
        </router-link>
      </li>
      <li>
        <a :href="logoutUrl" role="menuitem" class="oxd-userdropdown-link">
          {{ $t('general.logout') }}
        </a>
      </li>
    </template>
    <template #nav-actions>
      <oxd-icon-button name="question-lg" @click="onClickSupport" />
    </template>
  </oxd-layout>
</template>

<script>
import {OxdLayout} from '@ohrm/oxd';
import {provide, readonly} from 'vue';
import {RouterLink, RouterView} from 'vue-router';
import {dateFormatKey} from '@/core/util/composable/useDateFormat';

export default {
  components: {
    'oxd-layout': OxdLayout,
    'router-link': RouterLink,
    'router-view': RouterView,
  },
  props: {
    helpUrl: {
      type: String,
      default: null,
    },
    logoutUrl: {
      type: String,
      default: '#',
    },
    dateFormat: {
      type: Object,
      default: null,
    },
    permissions: {
      type: Object,
      default: () => ({}),
    },
  },
  setup(props) {
    provide('permissions', readonly(props.permissions));
    provide(dateFormatKey, readonly(props.dateFormat));

    const onClickSupport = () => {
      if (props.helpUrl) window.open(props.helpUrl, '_blank');
    };

    return {
      onClickSupport,
    };
  },
};
</script>
