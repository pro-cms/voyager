<template>
    <div v-if="locales.length > 1">
        <Dropdown>
            <div>
                <a
                    v-for="l in locales"
                    v-bind:key="locale"
                    @click="locale = l"
                    class="link"
                    :class="locale == l ? 'active' : null"
                >
                    {{ languageForLocale(l) }}
                </a>
            </div>
            <template #opener>
                <button
                    class="button accent"
                >
                    <span>{{ languageForLocale(locale) }}</span>
                    <Icon icon="chevron-down" :size="4" />
                </button>
            </template>
        </Dropdown>
    </div>
</template>

<script>
import Store from '@/store';

export default {
    mounted() {
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && (e.key == 'ArrowDown' || e.key == 'ArrowRight')) {
                this.nextLocale();
                e.preventDefault();
            } else if (e.ctrlKey && (e.key == 'ArrowUp' || e.key == 'ArrowLeft')) {
                this.previousLocale();
                e.preventDefault();
            }
        });
    },
    methods: {
        languageForLocale(locale) {
            let key = `voyager::generic.languages.${locale}`;
            if (key !== this.__(key)) {
                return this.__(key);
            }

            return locale.toUpperCase();
        }
    },
    computed: {
        locales() {
            return Store.locales;
        },
        locale: {
            get() {
                return Store.locale;
            },
            set(locale) {
                Store.locale = locale;
            }
        },
    }
};
</script>