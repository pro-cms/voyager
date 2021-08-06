<template>
    <div v-if="action == 'view-options'">
        <Alert v-if="relationship === undefined" color="red" class="mt-2">
            {{ __('voyager::formfields.relationship.not_resolved') }}
        </Alert>

        <template v-else>
            <template v-if="relationship && relationship.bread !== null">
                <div class="input-group mt-2">
                    <label class="label">{{ __('voyager::formfields.relationship.browse_list') }}</label>
                    <select class="input w-full" v-model="options.list">
                        <option :value="null">{{ __('voyager::generic.none') }}</option>
                        <option v-for="(list, i) in relationship.bread.layouts.where('type', 'list')" :key="`list-${i}`" :value="list.uuid">
                            {{ list.name }}
                        </option>
                    </select>
                </div>
                <div class="input-group mt-2">
                    <label class="label">{{ __('voyager::formfields.relationship.add_view') }}</label>
                    <select class="input w-full" v-model="options.view">
                        <option :value="null">{{ __('voyager::generic.none') }}</option>
                        <option v-for="(view, i) in relationship.bread.layouts.where('type', 'view')" :key="`view-${i}`" :value="view.uuid">
                            {{ view.name }}
                        </option>
                    </select>
                </div>
                <div class="input-group mt-2">
                    <label class="label">{{ __('voyager::formfields.relationship.display_column') }}</label>
                    <select class="input w-full" v-model="options.display_column" :disabled="!options.list || !relatedList">
                        <option :value="null">
                            {{ __('voyager::generic.none') }}
                        </option>
                        <template v-if="options.list && relatedList">
                            <option v-for="column in relatedList.formfields" :value="column.column.column">
                                {{ translate(column.title, true) }}
                            </option>
                        </template>
                    </select>
                </div>
                <div class="input-group mt-2">
                    <label class="label mt-4">{{ __('voyager::formfields.relationship.show_actions') }}</label>
                    <input type="checkbox" v-model="options.show_actions" :disabled="!options.list || !relatedList">
                </div>
            </template>
            <template v-else>
                <Alert color="yellow" class="mt-2">
                    {{ __('voyager::formfields.relationship.no_bread') }}
                </Alert>

                <div class="input-group mt-2">
                    <label class="label">{{ __('voyager::formfields.relationship.display_column') }}</label>
                    <select class="input w-full" v-model="options.display_column">
                        <option :value="null">
                            {{ __('voyager::generic.none') }}
                        </option>
                        <option v-for="column in relationship.columns" :key="column">{{ column }}</option>
                    </select>
                </div>

                <div class="input-group mt-2">
                    <label class="label">{{ __('voyager::formfields.relationship.order_column') }}</label>
                    <select class="input w-full" v-model="options.order_column">
                        <option :value="null">
                            {{ __('voyager::generic.none') }}
                        </option>
                        <option v-for="column in relationship.columns" :key="column">{{ column }}</option>
                    </select>
                </div>
            </template>

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
                        <th>
                            {{ translate(options.display_name, true) }}
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
                display_column: null,
                display_name: '',
                order_column: null,
                show_actions: true,
                view: null,
                list: null,
            };
        },
        relationship() {
            if (this.column.hasOwnProperty('column') && this.column.hasOwnProperty('type') && this.column.type == 'relationship') {
                return this.relationships.where('method', this.column.column).first();
            }

            return undefined;
        },
        relatedList() {
            return this.relationship.bread.layouts.where('type', 'list').where('uuid', this.options.list).first();
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