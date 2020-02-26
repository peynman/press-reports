<template>
    <div class="d-flex flex-column">
        <div class="d-flex flex-row align-center mb-1">
            <label :class="inputProps.error ? 'red--text me-1':'me-1'">{{ inputProps.label }}</label>
            <v-btn text icon :color="options.mode === 'code' ? 'primary':'secondary'" @click="setMode('code')">
                <v-icon>code</v-icon>
            </v-btn>
            <v-btn text icon :color="options.mode === 'tree' ? 'primary':'secondary'" @click="setMode('tree')">
                <v-icon>edit</v-icon>
            </v-btn>
            <v-btn text icon :color="options.mode === 'form' ? 'primary':'secondary'" @click="setMode('form')">
                <v-icon>remove_red_eye</v-icon>
            </v-btn>
        </div>
        <div ref="editor" style="width: 100%; height: 400px;"></div>
        <v-divider></v-divider>
        <div class="d-flex flex-column" v-if="inputProps.error">
            <div 
                v-for="(err,i) in inputProps['error-messages']" 
                :key="`error-message-${i}`"
            class="red--text">
                {{ err }}
            </div>
        </div>
    </div>
</template>

<script>
    import JSONEditor from 'jsoneditor'

    export default {
        name: "lpd-form-json",
        props: {
            inputProps: Object,
            group: String,
            initValue: Object|Array|String,
        },
        computed: {
        },
        data: () => ({
            options: {
                mode: 'code'
            },
            editor: null,
        }),
        mounted: function() {
            const component = this;
            const container = this.$refs.editor;
            const options = {
                ...this.options,
                templates: this.inputProps.templates,
                onChange: function() {
                    try {
                        const json = component.editor.get();
                        component.$emit('input', JSON.stringify(json));
                    } catch (ex) {
                        // console.error(ex);
                    }
                },
            };
            if (container) {
                this.editor = new JSONEditor(container, options);
                if (this.initValue) {
                    if (typeof this.initValue === 'string') {
                        this.editor.set(JSON.parse(this.initValue));
                    } else {
                        this.editor.set(this.initValue);
                    }
                } else {
                    this.editor.set({});
                }
            }
        },
        methods: {
            setMode: function(mode) {
                this.options.mode = mode;
                this.editor.setMode(mode);
            }
        },
    }
</script>

<style scoped>

</style>