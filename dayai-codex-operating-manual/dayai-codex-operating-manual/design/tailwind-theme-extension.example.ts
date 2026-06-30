// Example Tailwind theme extension for DAYAI.
// Adapt this to the existing repo configuration instead of forcing a new setup.

export const dayaiThemeExtension = {
  colors: {
    dayai: {
      primary: '#1E6BFF',
      secondary: '#7B61FF',
      accent: '#06B6D4',
      success: '#22C55E',
      warning: '#F59E0B',
      danger: '#EF4444',
      bg: 'var(--dayai-bg)',
      bgSubtle: 'var(--dayai-bg-subtle)',
      surface: 'var(--dayai-surface)',
      surfaceMuted: 'var(--dayai-surface-muted)',
      border: 'var(--dayai-border)',
      borderStrong: 'var(--dayai-border-strong)',
      text: 'var(--dayai-text)',
      textMuted: 'var(--dayai-text-muted)',
      textSubtle: 'var(--dayai-text-subtle)',
    },
  },
  borderRadius: {
    dayaiSm: '8px',
    dayaiMd: '12px',
    dayaiLg: '16px',
    dayaiXl: '20px',
    dayai2xl: '24px',
  },
  boxShadow: {
    dayaiXs: 'var(--dayai-shadow-xs)',
    dayaiSm: 'var(--dayai-shadow-sm)',
    dayaiMd: 'var(--dayai-shadow-md)',
  },
};
