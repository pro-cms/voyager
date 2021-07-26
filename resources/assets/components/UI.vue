<template>
    <Card title="UI Elements">
        <div>
            <div class="flex flex-wrap space-x-1 w-full">
                <button class="button accent my-2" v-scroll-to="'ui-headings'">Headings</button>
                <button class="button accent my-2" v-scroll-to="'ui-icons'">Icons</button>
                <button class="button accent my-2" v-scroll-to="'ui-buttons'">Buttons</button>
                <button class="button accent my-2" v-scroll-to="'ui-inputs'">Inputs</button>
                <button class="button accent my-2" v-scroll-to="'ui-color-picker'">Color picker</button>
                <button class="button accent my-2" v-scroll-to="'ui-datetime'">Date/Time picker</button>
                <button class="button accent my-2" v-scroll-to="'ui-tags'">Tag input</button>
                <button class="button accent my-2" v-scroll-to="'ui-sliders'">Sliders</button>
                <button class="button accent my-2" v-scroll-to="'ui-badges'">Badges</button>
                <button class="button accent my-2" v-scroll-to="'ui-alerts'">Alerts</button>
                <button class="button accent my-2" v-scroll-to="'ui-tooltips'">Tooltips</button>
                <button class="button accent my-2" v-scroll-to="'ui-notifications'">Notifications</button>
                <button class="button accent my-2" v-scroll-to="'ui-pagination'">Pagination</button>
                <button
                    class="button accent my-2"
                    v-for="el in $store.ui"
                    v-scroll-to="`ui-${slugify(el.title, { lower: true })}`"
                    :key="`ui-${el.title}`"
                >{{ el.title }}</button>
            </div>
        </div>
    </Card>

    <Card no-header>
        <div class="w-full flex">
            <div class="w-6/12">
                <Collapsible title="Headings" id="ui-headings" class="h-full">
                    <h1>H1 Heading</h1>
                    <h2>H2 Heading</h2>
                    <h3>H3 Heading</h3>
                    <h4>H4 Heading</h4>
                    <h5>H5 Heading</h5>
                    <h6>H6 Heading</h6>
                </Collapsible>
            </div>
            <div class="w-6/12">
                <Collapsible title="Icons" id="ui-icons" class="h-full">
                    <IconPicker />
                </Collapsible>
            </div>
        </div>
    </Card>

    <Collapsible title="Buttons" id="ui-buttons">
        <div class="w-full flex space-x-1">
            <div class="w-4/12">
                <Collapsible title="Default" :title-size="5">
                    <div class="flex flex-wrap space-x-1 justify-center">
                        <button class="button mb-1">Default</button>
                        <button
                            v-for="color in colors"
                            :key="'button-' + color"
                            :class="['button', color, 'mb-1']"
                        >{{ __('voyager::generic.color_names.' + color) }}</button>
                    </div>
                </Collapsible>
            </div>
            <div class="w-4/12">
                <Collapsible title="Active" :title-size="5">
                    <div class="flex flex-wrap space-x-1 justify-center">
                        <button class="button active mb-1">Default</button>
                        <button
                            v-for="color in colors"
                            :key="'button-' + color"
                            :class="['button', color, 'mb-1', 'active']"
                        >{{ __('voyager::generic.color_names.' + color) }}</button>
                    </div>
                </Collapsible>
            </div>
            <div class="w-4/12">
                <Collapsible title="Disabled" :title-size="5">
                    <div class="flex flex-wrap space-x-1 justify-center">
                        <button class="button mb-1" disabled>Default</button>
                        <button
                            v-for="color in colors"
                            :key="'button-' + color"
                            :class="['button', color, 'mb-1']"
                            disabled
                        >{{ __('voyager::generic.color_names.' + color) }}</button>
                    </div>
                </Collapsible>
            </div>
        </div>
        <div class="w-full flex">
            <div class="w-4/12">
                <Collapsible title="With Icon" :title-size="5">
                    <div class="flex flex-wrap space-x-1 justify-center">
                        <button class="button small mb-1">
                            <Icon icon="information-circle" class="mr-1" :size="4" />Default
                        </button>
                        <button
                            v-for="color in colors"
                            :key="'button-' + color"
                            :class="['button', 'small', color, 'mb-1']"
                        >
                            <Icon icon="information-circle" class="mr-1" :size="4" />
                            {{ __('voyager::generic.color_names.' + color) }}
                        </button>
                    </div>
                </Collapsible>
            </div>
            <div class="w-4/12">
                <Collapsible title="Responsive" :title-size="5">
                    <div class="flex flex-wrap space-x-1 justify-center">
                        <button :class="['button', 'small', 'mb-1']">
                            <Icon icon="information-circle" :size="4" />
                            <span>Default</span>
                        </button>
                        <button
                            v-for="color in colors"
                            :key="'button-' + color"
                            :class="['button', 'small', color, 'mb-1']"
                        >
                            <Icon icon="information-circle" :size="4" />
                            <span>{{ __('voyager::generic.color_names.' + color) }}</span>
                        </button>
                    </div>
                </Collapsible>
            </div>
            <div class="w-4/12">
                <Collapsible title="Button group" :title-size="5">
                    <div class="flex flex-wrap space-x-1 justify-center">
                        <div class="button-group">
                            <button
                                v-for="color in colors"
                                :key="'button-' + color"
                                :class="['button', color, 'mb-1']"
                            >{{ __('voyager::generic.color_names.' + color) }}</button>
                        </div>
                    </div>
                </Collapsible>
            </div>
        </div>
    </Collapsible>

    <Collapsible title="Inputs" id="ui-inputs">
        <div class="flex w-full">
            <Collapsible title="Default" :title-size="5" class="w-1/3">
                <input type="text" class="input w-full" placeholder="Placeholder" />
            </Collapsible>
            <Collapsible title="Disabled" :title-size="5" class="w-1/3">
                <input type="text" class="input w-full" disabled placeholder="Placeholder" />
            </Collapsible>
            <Collapsible title="Small" :title-size="5" class="w-1/3">
                <input type="text" class="input w-full small" placeholder="Placeholder" />
            </Collapsible>
        </div>
    </Collapsible>

    <Collapsible title="Color picker" id="ui-color-picker">
        <template #actions>
            <div class="space-x-1">
                <button class="button" @click="colorSizePlus">
                    <Icon icon="plus" />
                </button>
                <button class="button" @click="colorSizeMinus">
                    <Icon icon="minus" />
                </button>
            </div>
        </template>
        <Collapsible title="Colors">
            <ColorPicker :size="colorSize" v-model="color"></ColorPicker>
        </Collapsible>
        <Collapsible title="Colors (allow none)">
            <ColorPicker :size="colorSize" v-model="color" add-none></ColorPicker>
        </Collapsible>
    </Collapsible>

    <Collapsible title="Date/Time picker" id="ui-datetime">
        <Card title="Single" :title-size="6">
            <DateTime
                v-model="dtData.from"
                v-bind="datetime"
            />
        </Card>
        <Card title="Range" :title-size="6">
            <DateTime-range
                v-model:from="dtData.from"
                v-model:to="dtData.to"
                v-bind="datetime"
            />
        </Card>
        <Card title="Settings">
            <div class="w-full inline-flex space-x-2 mb-2">
                <div class="input-group w-auto">
                    <label class="label">Inline</label>
                    <input type="checkbox" class="input" v-model="datetime.inline">
                </div>
                <div class="input-group w-auto">
                    <label class="label">Sunday first</label>
                    <input type="checkbox" class="input" v-model="datetime.sundayFirst">
                </div>
                <div class="input-group w-auto">
                    <label class="label">Close on select</label>
                    <input type="checkbox" class="input" v-model="datetime.closeOnSelect" :disabled="datetime.inline">
                </div>
                <div class="input-group w-full">
                    <label class="label">Mode</label>
                    <select class="input w-full" :value="datetime.type" @change="datetime.displayFormat = dtFormats[$event.target.value]; datetime.type = $event.target.value">
                        <option v-for="(format, type) in dtFormats" :key="type" :value="type">
                            {{ titleCase(type) }}
                        </option>
                    </select>
                </div>
                <div class="input-group w-full">
                    <label class="label">Display format</label>
                    <input type="text" class="input w-full" v-model="datetime.displayFormat">
                </div>
                <div class="input-group w-full">
                    <label class="label">Distance</label>
                    <input type="number" class="input w-full" v-model.number="datetime.distance" min="0">
                </div>
            </div>
            <div class="w-full inline-flex space-x-2 mb-2">
                <div class="input-group w-full">
                    <label class="label">Model value (From)</label>
                    <input type="text" class="input w-full" v-model="dtData.from">
                </div>
                <div class="input-group w-full">
                    <label class="label">Model value (To)</label>
                    <input type="text" class="input w-full" v-model="dtData.to">
                </div>
            </div>
            <div class="w-full inline-flex space-x-2 mb-2">
                <div class="input-group w-full">
                    <label class="label">Dropdown placement</label>
                    <select class="input w-full" :value="datetime.placement" @change="datetime.placement = $event.target.value" :disabled="datetime.inline">
                        <option v-for="placement in placements" :key="placement" :value="placement">
                            {{ titleCase(placement) }}
                        </option>
                    </select>
                </div>
            </div>
        </Card>
    </Collapsible>

    <Collapsible title="Tag input" id="ui-tags">
        <TagInput v-model="tags" />
    </Collapsible>

    <Collapsible title="Sliders" id="ui-sliders">
        <Card title="From 1 to 100">
            <Slider v-model:lower="range.lower" :range="false" :min="1" class="mt-2" />
        </Card>
        <Card title="Range from 1 to 100">
            <Slider v-model:lower="range.lower" v-model:upper="range.upper" :min="1" class="mt-2" />
        </Card>
        <Card title="No inputs">
            <Slider v-model:lower="range.lower" v-model:upper="range.upper" :min="1" :inputs="false" class="mt-2" />
        </Card>
        <Card title="With distance 10">
            <Slider v-model:lower="range.lower" v-model:upper="range.upper" :min="1" :distance="10" class="mt-2" />
        </Card>
        <Card title="Different color">
            <Slider v-model:lower="range.lower" v-model:upper="range.upper" :min="1" class="mt-2" color="red" />
        </Card>
    </Collapsible>

    <Collapsible title="Badges" id="ui-badges">
        <div class="w-full flex">
            <Collapsible title="Default" :title-size="5" class="w-1/4">
                <div class="flex flex-wrap space-x-1 w-full">
                    <Badge
                        v-for="color in colors"
                        :color="color"
                        class="my-1"
                        :key="'badge-' + color"
                    >{{ __('voyager::generic.color_names.' + color) }}</Badge>
                </div>
            </Collapsible>
            <Collapsible title="Large" :title-size="5" class="w-1/4">
                <div class="flex flex-wrap space-x-1 w-full">
                    <Badge
                        v-for="color in colors"
                        :color="color"
                        class="my-1"
                        :key="'badge-' + color"
                        large
                    >{{ __('voyager::generic.color_names.' + color) }}</Badge>
                </div>
            </Collapsible>
            <Collapsible title="With icon" :title-size="5" class="w-1/4">
                <div class="flex flex-wrap space-x-1 w-full">
                    <Badge
                        v-for="color in colors"
                        :color="color"
                        class="my-1"
                        :key="'badge-' + color"
                        icon="x"
                    >{{ __('voyager::generic.color_names.' + color) }}</Badge>
                </div>
            </Collapsible>
            <Collapsible title="Large with icon" :title-size="5" class="w-1/4">
                <div class="flex flex-wrap space-x-1 w-full">
                    <Badge
                        v-for="color in colors"
                        :color="color"
                        class="my-1"
                        :key="'badge-' + color"
                        icon="information-circle"
                        large
                    >{{ __('voyager::generic.color_names.' + color) }}</Badge>
                </div>
            </Collapsible>
        </div>
    </Collapsible>

    <Collapsible title="Alerts" id="ui-alerts">
        <Alert v-for="color in colors" :color="color" :key="'alert-' + color" class="mb-3">
            <template #title>{{ __('voyager::generic.color_names.' + color) }}</template>
            <p>{{ lorem }}</p>
        </Alert>
    </Collapsible>

    <Collapsible title="Tooltips" id="ui-tooltips">
        <div class="w-full flex justify-center">
            <button class="button" v-tooltip:top="'Tooltip on top'">Top</button>
        </div>
        <div class="w-full inline-flex space-x-1 justify-center my-2">
            <button class="button" v-tooltip:left="'Tooltip on the left'">Left</button>
            <button class="button" v-tooltip:right="'Tooltip on the right'">Right</button>
        </div>
        <div class="w-full flex justify-center">
            <button class="button" v-tooltip:bottom="'Tooltip on the bottom'">Bottom</button>
        </div>
        <div class="w-full flex justify-center mt-2">
            <button class="button" v-tooltip:bottom.show="'Permanent tooltip on the bottom'">Permanent</button>
        </div>
    </Collapsible>

    <Collapsible title="Notifications" id="ui-notifications">
        <Collapsible
            v-for="color in colors"
            :key="'notification_' + color"
            :title="__('voyager::generic.color_names.' + color)"
            :title-size="5"
        >
            <div class="flex flex-wrap space-x-1">
                <button
                    @click="new $notification(lorem).title(ucFirst(color)).color(color).show()"
                    class="button mb-1"
                    :class="color"
                >Message and title</button>
                <button
                    @click="new $notification(lorem).color(color).show()"
                    class="button mb-1"
                    :class="color"
                >Message only</button>
                <button
                    @click="new $notification(lorem).title(ucFirst(color)).color(color).indeterminate().show()"
                    class="button mb-1"
                    :class="color"
                >Indeterminate</button>
                <button
                    @click="new $notification(lorem).title(ucFirst(color)).color(color).timeout().show()"
                    class="button mb-1"
                    :class="color"
                >With timeout</button>
            </div>
        </Collapsible>
        <Collapsible title="Confirm" :title-size="5">
            <div class="flex flex-wrap space-x-1">
                <button
                    @click="new $notification('Are you sure?').confirm().show().then((r) => { })"
                    class="button blue mb-1"
                >Simple</button>
                <button
                    @click="new $notification('Are you sure?').confirm().indeterminate().show()"
                    class="button blue mb-1"
                >Indeterminate</button>
                <button
                    @click="new $notification('Are you sure?').confirm().timeout().show()"
                    class="button blue mb-1"
                >With timeout</button>
                <button
                    @click="new $notification('Are you sure?').confirm().addButton({ key: true, value: 'Yup', color: 'green' }).addButton({ key: false, value: 'Nah', color: 'red' }).show()"
                    class="button blue mb-1"
                >Custom buttons</button>
            </div>
        </Collapsible>
        <Collapsible title="Prompt" :title-size="5">
            <div class="flex flex-wrap space-x-1">
                <button
                    @click="new $notification('Enter your name').prompt('').show()"
                    class="button blue mb-1"
                >Simple</button>
                <button
                    @click="new $notification('Enter your name').prompt('').timeout().show()"
                    class="button blue mb-1"
                >With timeout</button>
                <button
                    @click="new $notification('Enter your name').prompt('').addButton({ key: true, value: 'Safe', color: 'green' }).addButton({ key: false, value: 'Abort', color: 'red' }).show()"
                    class="button blue mb-1"
                >Custom buttons</button>
                <button
                    @click="new $notification('Enter your name').prompt(name).show().then((r) => { if (r !== false) { name = r; } })"
                    class="button blue mb-1"
                >Value: {{ name }}</button>
            </div>
        </Collapsible>
    </Collapsible>

    <Collapsible title="Pagination" id="ui-pagination">
        <Collapsible title="Default" :title-size="5">
            <Pagination :page-count="100" v-model="page"></Pagination>
        </Collapsible>

        <Collapsible title="No previous/next button" :title-size="5">
            <Pagination :page-count="100" v-model="page" :prev-next-buttons="false"></Pagination>
        </Collapsible>

        <Collapsible title="No first/last button" :title-size="5">
            <Pagination :page-count="100" v-model="page" :first-last-buttons="false"></Pagination>
        </Collapsible>

        <Collapsible title="Only page-buttons" :title-size="5">
            <Pagination
                :page-count="100"
                v-model="page"
                :first-last-buttons="false"
                :prev-next-buttons="false"
            ></Pagination>
        </Collapsible>

        <Collapsible title="Different color (Works with all other colors as well)" :title-size="5">
            <Pagination :page-count="100" v-model="page" color="red"></Pagination>
        </Collapsible>
    </Collapsible>

    <Collapsible
        v-for="el in $store.ui"
        :id="`ui-${slugify(el.title, { lower: true })}`"
        :title="el.title"
        :key="`ui-${el.title}`"
    >
        <component :is="el.component" />
    </Collapsible>
</template>
<script>
import { placements } from '@popperjs/core/lib/enums';
import scrollTo from '@directives/scroll-to';
import draggable from 'vuedraggable';
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';

dayjs.extend(utc);
dayjs.extend(timezone);

import { FORMATS } from './UI/DateTime.vue';

export default {
    directives: { scrollTo: scrollTo },
    components: {
        draggable
    },
    data() {
        return {
            name: 'Voyager',
            lorem: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid pariatur, ipsum similique veniam quo totam eius aperiam dolorum.',
            tags: ['Lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipisicing', 'elit'],
            color: this.colors[0],
            colorSize: 4,
            page: 1,
            range: {
                lower: 1,
                upper: 100
            },
            datetime: {
                type: 'date',
                inline: false,
                displayFormat: 'YYYY-MM-DD',
                closeOnSelect: false,
                distance: 0,
                sundayFirst: false,
                closeOnSelect: false,
                dayNames: this.__('voyager::datetime.day_names'),
                monthNames: this.__('voyager::datetime.month_names'),
                placement: 'bottom-start',
            },
            dtData: {
                from: dayjs().tz(dayjs.tz.guess()).toISOString(),
                to: dayjs().add(3, 'day').tz(dayjs.tz.guess()).toISOString(),
            },
            placements,
        };
    },
    computed: {
        dtFormats() {
            return FORMATS;
        }
    },
    methods: {
        colorSizePlus() {
            if (this.colorSize < 10) {
                this.colorSize += 1;
            }
        },
        colorSizeMinus() {
            if (this.colorSize > 1) {
                this.colorSize -= 1;
            }
        },
    },
    mounted() {
        this.$watch(() => this.range.minprice, (min) => {
            this.range.minthumb = ((this.range.minprice - this.range.min) / (this.range.max - this.range.min)) * 100;
        }, { immediate: true });

        this.$watch(() => this.range.maxprice, (min) => {
            this.range.maxthumb = 100 - (((this.range.maxprice - this.range.min) / (this.range.max - this.range.min)) * 100); 
        }, { immediate: true });
    }
}
</script>

