import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                "error": "#ba1a1a",
                "on-secondary": "#ffffff",
                "danger-red": "#E02424",
                "surface-variant": "#e2e1ed",
                "on-tertiary-container": "#ffd4c5",
                "border-light": "#E5E7EB",
                "tertiary": "#852b00",
                "surface-dim": "#d9d9e4",
                "inverse-on-surface": "#f0f0fb",
                "secondary-fixed-dim": "#adc8f5",
                "on-secondary-container": "#3e5980",
                "on-secondary-fixed-variant": "#2d486d",
                "surface-container-high": "#e7e7f3",
                "info-cyan": "#3ABFF8",
                "tertiary-fixed": "#ffdbcf",
                "inverse-primary": "#b5c4ff",
                "secondary": "#455f87",
                "tertiary-fixed-dim": "#ffb59a",
                "on-primary-fixed": "#00174d",
                "primary-fixed": "#dbe1ff",
                "outline": "#737686",
                "on-primary-fixed-variant": "#003dab",
                "surface-tint": "#1353d8",
                "on-primary-container": "#d4dcff",
                "on-background": "#191b23",
                "success-green": "#0E9F6E",
                "background": "#faf8ff",
                "primary-fixed-dim": "#b5c4ff",
                "primary": "#003fb1",
                "tertiary-container": "#ad3b00",
                "primary-container": "#1a56db",
                "on-surface-variant": "#434654",
                "surface": "#faf8ff",
                "surface-container": "#ededf8",
                "on-secondary-fixed": "#001c3b",
                "inverse-surface": "#2e3039",
                "surface-bright": "#faf8ff",
                "error-container": "#ffdad6",
                "secondary-container": "#b5d0fd",
                "warning-orange": "#FF5A1F",
                "on-tertiary-fixed": "#380d00",
                "on-error-container": "#93000a",
                "surface-container-lowest": "#ffffff",
                "on-primary": "#ffffff",
                "on-error": "#ffffff",
                "on-surface": "#191b23",
                "outline-variant": "#c3c5d7",
                "bg-subtle": "#F9FAFB",
                "on-tertiary": "#ffffff",
                "on-tertiary-fixed-variant": "#802a00",
                "surface-container-highest": "#e2e1ed",
                "secondary-fixed": "#d5e3ff",
                "surface-container-low": "#f3f3fe"
            },
            borderRadius: {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "full": "9999px"
            },
            spacing: {
                "gutter": "1.5rem",
                "stack-sm": "0.5rem",
                "stack-lg": "1.5rem",
                "margin-page": "2rem",
                "sidebar-width": "240px",
                "stack-md": "1rem",
                "container-max": "1280px"
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                "body-lg": ["Inter"],
                "headline-lg": ["Inter"],
                "headline-md": ["Inter"],
                "body-sm": ["Inter"],
                "label-md": ["Inter"],
                "label-sm": ["Inter"],
                "headline-lg-mobile": ["Inter"],
                "headline-sm": ["Inter"],
                "body-md": ["Inter"]
            },
            fontSize: {
                "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600"}],
                "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                "headline-sm": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}]
            }
        },
    },

    plugins: [forms, require('@tailwindcss/container-queries')],
};
