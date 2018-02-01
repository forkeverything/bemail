<template>
    <span class="field-error help-block">
        <strong>{{ error }}</strong>
    </span>
</template>
<script>
export default {
    props: ['error'],
}
</script>