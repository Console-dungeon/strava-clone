declare module 'vue' {
  interface ComponentCustomProperties {
    route: typeof import('../../vendor/tightenco/ziggy/src/js').route;
  }
}

export {};
