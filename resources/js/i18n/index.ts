import { createI18n } from 'vue-i18n';
import en from './en';
import pl from './pl';

function detectLocale(): string {
  const saved = localStorage.getItem('locale');
  if (saved === 'pl' || saved === 'en') return saved;
  return navigator.language.startsWith('pl') ? 'pl' : 'en';
}

export const i18n = createI18n({
  legacy: false,
  locale: detectLocale(),
  fallbackLocale: 'en',
  messages: { pl, en },
});
