<template>
    <span class="field-error text-danger">
        <small>{{ error }}</small>
    </span>
</template>
<script>
export default {
    props: ['error'],
}
</script>