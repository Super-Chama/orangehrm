<template>
  <oxd-layout v-bind="$attrs" :sidepanel-menu-items="sidepanelMenuItems">
    <template v-for="(_, name) in $slots" #[name]="slotData">
      <slot :name="name" v-bind="slotData" />
    </template>
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
        <router-link
          class="oxd-userdropdown-link"
          :to="{name: 'updatePassword'}"
        >
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
import {useRoute} from 'vue-router';
import {OxdLayout} from '@ohrm/oxd';
import {computed, provide, readonly, shallowRef} from 'vue';
import {RouterLink, RouterView} from 'vue-router';
import {dateFormatKey} from '@/core/util/composable/useDateFormat';

export default {
  inheritAttrs: false,
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
    const route = useRoute();
    provide('permissions', readonly(props.permissions));
    provide(dateFormatKey, readonly(props.dateFormat));

    const onClickSupport = () => {
      if (props.helpUrl) window.open(props.helpUrl, '_blank');
    };

    const sidepanelItems = shallowRef([
      {
        name: 'Admin',
        url: '#/admin',
        icon: 'admin',
        active: false,
      },
      {
        name: 'PIM',
        url: '#/pim',
        icon: 'pim',
        active: false,
      },
      {
        name: 'Leave',
        url: '#/leave',
        icon: 'leave',
        active: false,
      },
    ]);

    const sidepanelMenuItems = computed(() =>
      sidepanelItems.value.map((item) => {
        return {
          ...item,
          active: item.url.substring(2) === route.meta?.group,
        };
      }),
    );

    return {
      onClickSupport,
      sidepanelMenuItems,
    };
  },
};
</script>
