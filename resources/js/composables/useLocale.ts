import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

export function useLocale() {
  const { locale } = useI18n();

  const currentLocale = computed(() => locale.value as 'pl' | 'en');

  function setLocale(lang: 'pl' | 'en') {
    locale.value = lang;
    localStorage.setItem('locale', lang);
  }

  function toggleLocale() {
    setLocale(currentLocale.value === 'pl' ? 'en' : 'pl');
  }

  return { currentLocale, setLocale, toggleLocale };
}
