/// <reference types="vite/client" />

declare module '*.vue' {
    import type { DefineComponent } from 'vue';
    const component: DefineComponent;
    export default component;
}

declare const route: typeof import('../vendor/tightenco/ziggy/src/js').route;

interface Window {
    axios: import('axios').AxiosStatic;
}
