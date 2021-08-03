<template>
    <div v-if="action == 'view-options'">
        <Alert v-if="relationship === undefined" color="red" class="mt-2">
            {{ __('voyager::formfields.relationship.not_resolved') }}
        </Alert>

        <template v-else>
            <template v-if="true || relationship.bread === null">
                <Alert color="yellow" class="mt-2">
                    {{ __('voyager::formfields.relationship.no_bread') }}
                </Alert>

                <div class="input-group mt-2">
                    <label class="label">{{ __('voyager::formfields.relationship.display_column') }}</label>
                    <select class="input w-full" v-model="options.display_column">
                        <option v-for="column in relationship.columns" :key="column">{{ column }}</option>
                    </select>
                </div>
            </template>
            <template v-else>

            </template>

            <div class="input-group mt-2">
                <label class="label mt-4">{{ __('voyager::formfields.relationship.allow_none') }}</label>
                <input type="checkbox" v-model="options.allow_none">
            </div>

            <div class="input-group mt-2">
                <label class="label mt-4">{{ __('voyager::formfields.relationship.display_name') }}</label>
                <LanguageInput type="text" class="input w-full" v-model="options.display_name" :placeholder="__('voyager::formfields.relationship.display_name')" />
            </div>
        </template>
    </div>
    <template v-else-if="action == 'view'">
        <div class="voyager-table">
            <table>
                <thead>
                    <tr>
                        <th class="w-2"></th>
                        <th class="flex">
                            <div class="flex-none self-center">
                                {{ translate(options.display_name, true) }}
                            </div>
                            <div class="flex flex-grow justify-end">
                                <button class="button" v-if="options.allow_none" disabled>
                                    {{ __('voyager::generic.none') }}
                                </button>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th class="w-2"></th>
                        <th>
                            <input class="input w-full" disabled :placeholder="__('voyager::bread.search_type', {type: translate(options.display_name, true)})">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="result in dummyResults" :key="result.key">
                        <td class="w-2">
                            <input type="checkbox" class="input" disabled>
                        </td>
                        <td>{{ result.value }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </template>
</template>

<script>
import formfieldBuilder from '@mixins/formfield-builder';

export default {
    mixins: [formfieldBuilder],
    computed: {
        defaultViewOptions() {
            return {
                display_column: '',
                display_name: '',
                view: null,
                list: null,
                allow_none: true,
            };
        },
        relationship() {
            if (this.column.hasOwnProperty('column') && this.column.hasOwnProperty('type') && this.column.type == 'relationship') {
                return this.relationships.where('method', this.column.column).first();
            }

            return undefined;
        }
    },
    data() {
        return {
            dummyResults: [
                { key: 1, value: 'Lorem ipsum dolor sit amet' },
                { key: 2, value: 'Lorem ipsum dolor sit amet' },
                { key: 3, value: 'Lorem ipsum dolor sit amet' },
                { key: 4, value: 'Lorem ipsum dolor sit amet' },
                { key: 5, value: 'Lorem ipsum dolor sit amet' },
            ]
        };
    }
}
</script>