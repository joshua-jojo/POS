<template lang="">
    <div class="form-control">
        <label class="label" v-if="title">
            <span class="label-text"
                >{{ title }} : {{ rupiah(inputValue) }}</span
            >
        </label>
        <div class="flex gap-2">
            <input
                type="number"
                class="input input-bordered input-sm w-full"
                :value="inputValue"
                @input="$emit('update:inputValue', $event.target.value)"
                :placeholder="`Masukkan ${title}`"
            />
            <input-keypad
                v-model:keypadValue="keypad"
                :key="inputValue"
                :id="Math.random().toString(36)"
                harga="true"
                :location="location ? location : false"
            ></input-keypad>
        </div>
        <transition name="list">
            <label class="label" v-if="error">
                <span class="label-text-alt text-error">{{ error }}</span>
            </label>
        </transition>
    </div>
</template>
<script>
export default {
    props: ["inputValue", "error", "title", "location"],
    data() {
        return {
            keypad: this.inputValue,
        };
    },
    watch: {
        keypad(newData) {
            this.$emit("update:inputValue", newData);
        },
        inputValue(newData) {
            this.keypad = newData;
        },
    },
};
</script>
<style lang=""></style>
