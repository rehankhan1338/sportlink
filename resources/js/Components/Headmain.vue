<script setup>
defineProps({
    title: {
        type: String,
        required: true
    }
});
</script>

<template>
 <head>
  <title>{{ title }}</title>
  <meta name="description" content="This is a short description of my website for SEO and social sharing.">
  <meta name="keywords" content="keyword1, keyword2, keyword3">
  <meta name="author" content="Your Name">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

</template>
