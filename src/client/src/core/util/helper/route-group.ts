import type {RouteRecordRaw} from 'vue-router';

export const createRouteGroup = (
  groupName: string,
  routes: RouteRecordRaw[],
) => {
  return routes.map((route) => {
    return {
      ...route,
      meta: {...route.meta, group: groupName},
      path: `/${groupName}/${route.path}`,
    };
  });
};
