<script setup></script>

<template>
    <div>
        <slot name="parent"> </slot>
        
        <div class="pl-4 text-sm">
            <slot name="children"> </slot>
        </div>
    </div>
</template>
