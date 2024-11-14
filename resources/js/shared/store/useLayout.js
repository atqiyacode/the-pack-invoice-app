import { defineStore } from 'pinia';
import { computed, ref, readonly } from 'vue';

export const useLayout = defineStore(
    'layout',
    () => {
        const layoutConfig = ref({
            preset: 'Aura',
            primary: 'emerald',
            surface: null,
            darkTheme: false,
            menuMode: 'static'
        });

        const layoutState = ref({
            staticMenuDesktopInactive: false,
            overlayMenuActive: false,
            profileSidebarVisible: false,
            configSidebarVisible: false,
            staticMenuMobileActive: false,
            menuHoverActive: false,
            activeMenuItem: null
        });

        const setPrimary = (value) => {
            layoutConfig.value.primary = value;
        };

        const setSurface = (value) => {
            layoutConfig.value.surface = value;
        };

        const setPreset = (value) => {
            layoutConfig.value.preset = value;
        };

        const setActiveMenuItem = (item) => {
            layoutState.value.activeMenuItem = item.value || item;
        };

        const setMenuMode = (mode) => {
            layoutConfig.value.menuMode = mode;
        };

        const toggleDarkMode = () => {
            if (!document.startViewTransition) {
                executeDarkModeToggle();
                return;
            }

            document.startViewTransition(() => executeDarkModeToggle(event));
        };

        const executeDarkModeToggle = () => {
            layoutConfig.value.darkTheme = !layoutConfig.value.darkTheme;
            document.documentElement.classList.toggle('app-dark');
        };

        const onMenuToggle = () => {
            if (layoutConfig.value.menuMode === 'overlay') {
                layoutState.value.overlayMenuActive = !layoutState.value.overlayMenuActive;
            }

            if (window.innerWidth > 991) {
                layoutState.value.staticMenuDesktopInactive = !layoutState.value.staticMenuDesktopInactive;
            } else {
                layoutState.value.staticMenuMobileActive = !layoutState.value.staticMenuMobileActive;
            }
        };

        const resetMenu = () => {
            layoutState.value.overlayMenuActive = false;
            layoutState.value.staticMenuMobileActive = false;
            layoutState.value.menuHoverActive = false;
        };

        const isSidebarActive = computed(() => layoutState.value.overlayMenuActive || layoutState.value.staticMenuMobileActive);

        const isDarkTheme = computed(() => layoutConfig.value.darkTheme);

        const getPrimary = computed(() => layoutConfig.value.primary);

        const getSurface = computed(() => layoutConfig.value.surface);

        return {
            layoutConfig: layoutConfig,
            layoutState: layoutState,
            // layoutConfig: readonly(layoutConfig),
            // layoutState: readonly(layoutState),
            onMenuToggle,
            isSidebarActive,
            isDarkTheme,
            getPrimary,
            getSurface,
            setActiveMenuItem,
            toggleDarkMode,
            setPrimary,
            setSurface,
            setPreset,
            resetMenu,
            setMenuMode
        };
    },
    {
        persist: true
    }
);
